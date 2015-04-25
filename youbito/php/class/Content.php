

<?php

	class Content {		
	//singleton klasa Content
		
		private static $_instance = null;
		private  $_error = array(),
			     $_results = array();
					   

		private $_db = null;	
		public $count = 0;		   

	//privatni konstruktor, poziva se preko getInstance funkcije
		private function __construct () {

			$this->_db = DB::getInstance();
		}


	/*
		*funkcija koja provjerava postojanje objekta i ovisno o tome
		 vraca postojeci objekt ili stvara novi objekt i onda vraca njega
	*/
		public static function getInstance() {

			if(!isset(self::$_instance)) {
				self::$_instance = new Content();
			}

			return self::$_instance;
		}




/******Funkcije za vracanje podataka ****************/


		public function getCategories ($limit=10) {

		//reset array s rezultatima
			$this->_results = array();

			$kveri = "SELECT name, cat_id FROM categories LIMIT {$limit}";

			if($this->_db->upit("get", $kveri))
			{
				$this->_error = array();
				$allCats = $this->_db->getResults();
				$this->_results = $allCats;
				$this->count = sizeof($allCats);
				return true;
			}

			else
			 {
				$this->_results = array();
				$this->addError("unable to get the cats");
				return false;
			}
		}	

		public function getComments($video) {

			

			$kveri = "SELECT 
						   comments.user,
						   comments.likes,
						   comments.dislikes,
						   comments.submitted,
						   comments.content,
						   comments.comment_id,
						   users.usernick as nick					   
						   FROM comments
						   LEFT JOIN users
						   ON comments.user = users.user_id
						   WHERE video =? ORDER BY likes";

			$this->_db->upit("get", $kveri, array($video));	

	//odabiru se komentari koji odgovaraju zadanom videu				   
			if($this->_db->_count > 0) 	{ 

			
				$allComments = $this->_db->getResults();

				for($x=0; $x < sizeof($allComments); $x++)
				{

					$allComments[$x]['submitted'] 
					=
					$this->turn_to_past($allComments[$x]['submitted']);
				}
			 
				$kveri_sec = "SELECT sec_comments.user,
									 sec_comments.likes,
									 sec_comments.dislikes,
									 sec_comments.submitted,
									 sec_comments.content,
									 sec_comments.comment_id,
									 users.usernick as nick 
									 FROM sec_comments 
									 LEFT JOIN users
									 ON 
									 sec_comments.user = users.user_id
									 WHERE source_comment=?";



				for($x = 0; $x < sizeof($allComments); $x++)
				{
				//stvara se array za pohranu sekundarnih komentara
					$allComments[$x]['sec']	= array();

				//id komentara koji treba dobaviti
					$comment = $allComments[$x]['comment_id'];

				//dobavljanje sek. komentara koji odgovaraju trenutnom komentaru
					if($this->_db->upit("get", $kveri_sec, array($comment)) )
					{

						$subComments = $this->_db->getResults();

						for($y=0; $y < sizeof($subComments); $y++)
						{
						$subComments[$y]['submitted'] 
						=
						$this->turn_to_past($subComments[$y]['submitted']);
						}

					//svi sec. komentari na specificni komentar se spremaju u array
						$allComments[$x]['sec'] = $subComments;
					}
				}

				$this->_error = array(); //reset error arraya
				$this->_results = $allComments; // spremanje rezultata u results array objekta
				$this->count = sizeof($allComments);

				return true;
			}
			else {


				$this->_results = array();
				$this->addError("neuspjelo dobavljanje komentara");
				$this->count = 0;
				return false;
			}	

		}

		public function getSubs($user) {
		//array za spremanje svih pretplata	
			$subs = array();
			$kveri = "SELECT 
							user_subs.sub_id as sub_id,
							user_subs.owner  as owner,
							users.usernick   as sub_name 
							FROM user_subs 
							INNER JOIN users 
							ON 
							user_subs.owner = users.user_id 
							WHERE user_subs.user =?
					 		";


			if($this->_db->upit("get", $kveri, array($user))) {
				$subs = $this->_db->getResults();

				for($x=0; $x < sizeof($subs); $x++)
				{
					
				//dodavanje tipa pretplate	
					$subs[$x]['type'] = "user";
				}
			}

			$twoKveri = "SELECT 
							channel_subs.sub_id as sub_id,
							channel_subs.owner  as owner,
							chanels.name  		as sub_name  
							FROM channel_subs 
							INNER JOIN chanels 
							ON 
							channel_subs.channel = chanels.chanel_id 
							WHERE channel_subs.user =?
					 		";


			$this->_db->upit("get", $twoKveri, array($user));
			if($this->_db->_count)
			{
				$chanSubs = $this->_db->getResults();

				for($x=0; $x < sizeof($chanSubs); $x++)
				{

	 //pretplate iz channel_sub arraya spremaju se samo ako korisnik nije pretplacen na samog korisnika	
					$add = true;
					for($y=0; ($y < sizeof($subs)) && $add; $y++)
					{
				
						if($chanSubs[$x]['owner'] == $subs[$y]['owner'])
						{
							$add = false;								
						}
					}
					if($add)
					{	
						array_push($subs, $chanSubs[$x]);
					//dodavanje tipa pretplate	
						$subs[sizeof($subs) -1]['type'] = "channel";
					}

				}					
			}

			if(sizeof($subs)) {

				$this->_results = $subs;
				return true;
			}

			$this->_results = array();
			$this->addError("unable to get any subs");
			return false;

		}

	//jednostavna funkcija za odredivanje vremena proteklog od zadanog timestampa
		private function turn_to_past($old)
		{

				$old_time = strtotime($old);
				$new = time() - $old_time;

		   //$past varijabla se gradi pomocu podatak iz $new, dodavanjem stringova izmedu brojeva
				$past="";

				if(($days = floor($new/86400)) >= 1)
				{

					$past .= $days;
					$past .= ($days > 1) ? " days, ": " day, ";
					$new = $new - ($days*86400);
				}

				if(($hours = floor($new/3600)) >= 1)
				{
					$past .=  $hours;
					$past .= ($hours > 1) ? " hours, ": " hour, ";
					$new = $new - ($hours*3600); 
				}

				if(($minutes=floor($new/60)) >= 1)
				{

					$past .= $minutes;
					$past .= ($minutes > 1) ? " minutes ": " minute ";
					$new = $new - ($minutes * 60);
				}

				return $past;

		}

		public function getVideos($limit=15) {

	//kveri koristi left join na users i thumbs tablice kako be dobio put do thumb-a i ime vlasnika
			$kveri= "SELECT     videos.name, 
							videos.video_id,
							   videos.views, 
							    videos.made as made,
							videos.descript,
							    videos.like, 
							 videos.dislike,
							 videos.path as path, 
							thumbs.path as thumb,
							users.user_id as user_id, 
							users.usernick as nick 
							FROM videos 
							LEFT JOIN users ON 
							videos.owner = users.user_id  
							LEFT JOIN thumbs ON 
							videos.thumb = thumbs.thumb_id
							LIMIT {$limit}";

			if($this->_db->upit('get', $kveri, array()) ) 
			{

				$dbVids = $this->_db->getResults();

				$allVideos = array();

				foreach($dbVids as $vid)
				{
					
					$bestVid = array();//novi array za spremanje pojedinacnih vrijednosti
					foreach($vid as $stupac =>$value ) 
					{
						$bestVid[$stupac] = $value;
					}

			//pozivanje funkcije za izracun vremena proteklog od stvaranja videa
					$bestVid['made'] = $this->turn_to_past($bestVid['made']);

					if($bestVid['nick'] == "") $bestVid['nick'] = "noName";

					$allVideos[] = $bestVid;//spremanje trenutnog videa u array za sve videe	
				}
			
				$this->_results = $allVideos;
				$this->count = sizeof($allVideos);

				return true;
			}
			else {

				$this->_results = array();
				$this->addError("can't get the videos");
				return false;
			}
		}


		public function get_video_info ($video) {

			$kveri = "SELECT  
					  videos.video_id as id,
					  videos.owner as user,
					  videos.path, 
					  videos.name as name,
					  videos.category as cat_id, 
					  videos.duration, 
					  videos.like,
					  videos.dislike, 
					  videos.made,
					  videos.descript, 
					  videos.views, 
					  users.usernick as nick, 
					  categories.name as cat
					  FROM videos  
					  LEFT JOIN users ON  
					  videos.owner =  users.user_id 
					  INNER JOIN categories 
					  ON
					  videos.category = categories.cat_id  
					  WHERE video_id=?";

			if($this->_db->upit("get",$kveri, array($video)) ) {	

				$videoInfo = $this->_db->getResults();

				$this->_results = $videoInfo;
				$this->count    = sizeof($videoInfo);

				return true;
			}
			else {

				$this->_results = array();
				$this->addError("unable to get video info");
				return false;
			}
		}

		public function get_similar_videos($user, $cat, $vid) {

			$rez = array();//array za spremanje rezultata prvog query-ja
			if($user != NULL)
			{

				$kveri = "SELECT 
								 videos.video_id as id,
								 videos.name,
								 videos.views,
								 thumbs.path as thumb,
								 users.usernick as user,
								 users.user_id as user_id
								 FROM videos
								 LEFT JOIN users 
								 ON 
								 videos.owner = users.user_id 
								 LEFT JOIN thumbs
								 ON
								 videos.thumb = thumbs.thumb_id
								 WHERE videos.owner =? LIMIT 10";

				$this->_db->upit("get", $kveri, array($user));

				$rez = $this->_db->getResults();
			}	

			$kveri2 = "SELECT 							  
							  videos.name, 
							  videos.views,
							  videos.video_id as id,
							  thumbs.path     as thumb,
							  users.usernick  as user,
							  users.user_id   as user_id
							  FROM videos
							  LEFT JOIN users
							  ON 
							  videos.owner = users.user_id
							  LEFT JOIN thumbs 
							  ON 
							  videos.thumb = thumbs.thumb_id
							  INNER JOIN categories
							  ON
							  videos.category = categories.cat_id
							  LIMIT 15
							  ";


			$this->_db->upit("get", $kveri2 );

			$rez2 = $this->_db->getResults();
			
			$ResoGun = array_merge($rez, $rez2);	



		//petlja za uklanjanje videa koji su identicni sa zadanim ili su duplikati	
			$tryRes = array();
	
			for($x=0; $x < sizeof($ResoGun); $x++)
			{
				$add = true;


				if($ResoGun[$x]['name'] == $vid)
				{
					$add = false;
				}

				$y = $x + 1;
				while($y < sizeof($ResoGun) && $add)
				{

					if($ResoGun[$x]['name'] == $ResoGun[$y]['name'])
					{
					$add = false;				
					}

					$y++;
				}

				if($add)
				{
					array_push($tryRes, $ResoGun[$x]);
				}
				
			}

			$ResoGun = $tryRes;
			if(!empty($ResoGun))
			{
				$this->_error = array();
				$this->_results = $ResoGun;
				$this->count = sizeof($ResoGun);

				return true;
			}


			$this->addError("can't get similar videos, sorry.");
			return false;
		}


		public function getResults() {


			if(!empty($this->_results)) 
			{
			return $this->_results;
			}
			
			return false;
		}

		private function addError($err) {

			array_push($this->_error, $err);
		}


		public function getErrors ($divide = ", ") {

			$greske = implode($this->_error, $divide);	
			return $greske;
		}

	}
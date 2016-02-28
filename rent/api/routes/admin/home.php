<?php
	

	$boot->setGet('admin/home', 'admin.home', function($args=null) use($boot){

		checkZaposlenik($boot);
		
		$rule = (isset($args)) ? $args['rule'] : 'running';

		$db = $boot->db;
		$reservations = array();	
		$todayStamp   = strtotime('today');

		$reservations = getCurrentReservations($db);

		$showReservations = [];
		if($rule == 'today'){

			foreach($reservations as $reservation){

				$stamp_rezervacije = strtotime($reservation['datum_rezervacija']);
				

				if(!$reservation['u_tijeku'] && $stamp_rezervacije <= $todayStamp)
				{

					if($stamp_rezervacije < $todayStamp)
					{
						$reservation['kasni'] = true;
					}
					$showReservations[] = $reservation;
				}
			}		
		}
		elseif($rule == 'running'){

			foreach($reservations as $reservation)
			{
				if($reservation['u_tijeku'] == '1')
				{

					$datumPocetak = strtotime($reservation['datum_pocetak']);
					$maxTrajanje  = $reservation['max_trajanje'];
					
					$timeDiff  = $todayStamp - $datumPocetak;
					$daysSpent = floor($timeDiff/(3600*24));

					$daysLeft = $maxTrajanje - $daysSpent;

					$reservation['preostalo_dana'] = ($daysLeft > 0) ? $daysLeft : 0;
					$showReservations[] = $reservation;	
				}
			}
		}
		elseif($rule == 'future'){

			foreach($reservations as $reservation)
			{
				 
				$reserveStamp = strtotime($reservation['datum_rezervacija']);
				if($reserveStamp > $todayStamp)
				{
					$showReservations[] = $reservation;
				}
			}
		}
		
		$boot->render('admin/home.php',[
			'showReservations' => $showReservations,
			'reserveType'  	   => $rule
		]);
	});
		
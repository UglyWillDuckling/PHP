{% extends 'templates/adminTemplate.php' %}
{% block css %}
	<style>
		
		.img_wrapper{

			width: 280px;
			margin: 10px 0;
		}

		#offers_wrap{

			background: #F4F4F4;
			padding: 5px;
		}

		#offers_wrap > h3{

			
			margin-top: 2px;
			margin-left: 15px;
		}


		.offer
		{
		 margin: 15px 15px;
    	 padding: 12px;
    	 background-color: #FFFFFF;
    	 width: 94%;
		}

		.slide_wrapper
		{
		width: 55%;
		}

		.carousel-caption
		{
		top:auto;
		}


		#poruka{

			padding: 5px 5px;
			font-size: 1.4em;
		}

		.success{

			background-color: #349429;
			color: #fff;
		}

		.failed{

			background-color: #bb0066;
			color: #fff;
		}

		#offer_controls{

			//margin-bottom: 5px;
		}

		#show{

			position: fixed;
			top: 250px;
			background-color: #1B83F2;
			right: -200px;
			width: auto;
			color: #fff;
			padding: 15px 25px 15px 10px;
			font-size: 1.6em;
			border: none;
			-webkit-transition: width 3s linear;
		}

		#show:hover {

			right: -10px;
		}

		.args{

			background: #fff;
			padding: 10px;
			margin-bottom: 10px;
			border-radius: 5px;
		}
		.arg_title{

			text-decoration: underline;
		}

		.page{

			font-size: 1.1em;
		}

		.file{

			//margin-bottom: 10px;
		}

		.dot{

			font-size: 1.5em;
			color: red;
		}

		.btn-file {
		  position: relative;
		  overflow: hidden;
		}
		.btn-file input[type=file] {
		  position: absolute;
		  top: 0;
		  right: 0;
		  min-width: 100%;
		  min-height: 100%;
		  font-size: 100px;
		  text-align: right;
		  filter: alpha(opacity=0);
		  opacity: 0;
		  background: red;
		  cursor: inherit;
		  display: block;
		}

		.file_div{

			background: #fff;
			vertical-align:bottom;
			font-size: 1.1em;
			border: 1px solid #D8D8D8;
			border-radius: 5px;
			box-sizing: border-box;
		}

		.carImage{

			height: 350px !important;
		}

		#submit{
			position: fixed;
			top: 320px;
			width: 200px;
			right: -200px;

			color: #fff;
			padding: 15px 25px 15px 10px;
			font-size: 1.6em;
			border: none;

		}
		
		.fileMsg{
			color: #8B8B8B;
			padding: 5px;
		}

		.upute{

			padding: 5px;
			font-size: 1.55rem;
		}
	</style>
{% endblock %}

{% block col1 %}col-md-12{% endblock %}
{% block col2 %}col-md-12{% endblock %}

{% block title %}Main Page{% endblock %}

{% block main_content %}
	<h2>Posebne ponude</h2>

	<div id="poruka">
	
<!--CAROUSEL-->	
	</div>	
	<h3>Preview</h3>
	<div class="slide_wrapper">
		<div class="image_slider">
			<div id="myCarousel" class="carousel slide">

			  <!-- Wrapper for slides -->
			  <div class="carousel-inner main_carousel" role="listbox">
				  {% for offer in offers %}
					<div class="item{% if loop.index == 1 %} active {% endif %}">
					  <a 
					  href="{{ boot.baseUrl }}/{{ offer.link }}
					  		{% for argument in offer.arguments %}
					  			.{{ argument.trueName }}_{{ argument.value }}
					  		{% endfor %}">
						  <img class="carImage" src="{{boot.baseUrl }}/{{ offer.pic }}" alt="{{ offer.title }}">
						  <div class="carousel-caption">
							<p>{{ offer.title }}</p>
						  </div>
					  </a>
					</div> 
				  {% endfor %}					
			  </div>
			  <!-- Left and right controls -->
			  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			  </a>
			</div>
		</div>
	</div>
<!--\CAROUSEL-->

	<hr>

<!--PONUDE-->	
   <div id="offers_wrap">
	<h3>Ponude:</h3>
	<form id="mainPageForm" enctype="multipart/form-data" method="POST" action="{{ boot.baseUrl }}/admin/mainPage">
	<div id="offers">	
	{% for offer in offers %}
		<div class="offer">
			<label>Naslov</label>
			<input type="text" value="{{ offer.title }}" size="40" name="title">
			<div class="img_wrapper">
				<img class="img-responsive" src="{{ boot.baseUrl }}/{{ offer.pic }}" alt="{{ offer.title }}">
			</div>
			<div class="file_div">
				<span class="btn btn-default btn-file btn-primary">
					Browse...<input type="file" class="file" name="slika" onchange="showFilename();" />
				</span>
				<span class="fileMsg">Odaberite sliku</span>
			</div>
			
			<br>
			<label for="page">link na stranicu</label><br>
			<select class="page" name="page">
				<option>Odaberite stranicu</option>
				{% for page in pages %}
				<option 
					value="{{ page.pageId }}" 
					{% if page.pageId == offer.page %}selected{% endif %}
				>
					{{ page.pageTitle }}
				</option>
				{% endfor %}
			</select>

			<h4 class="arg_title">argumenti za stranicu</h4>
			<div class="args">		
				{% for argument in offer.arguments %}
				<div class="arg_div">
					<label>{{ argument.argName }}</label>
					<select class="arg {% if(argument.req == 1) %}req{% endif %}" data-argid='{{ argument.argId }}' name='{{ argument.argName }}'>
						{% for argDat in argument.argData %}
							<option value="{{ argDat['id'] }}">{{ argDat['refField'] }}</option>
						{% endfor %}
					</select>
					{% if(argument.req == 1) %}
					<span class="dot">*</span>
					{% endif %}
				</div>	
				{% endfor %}
			</div>

			<a href="#" class="btn btn-danger delete_btn">Delete offer</a>
		</div>
	{% endfor %}
	</div>
	<div id="offer_controls">
		<button id="add_btn" class="btn btn-primary">Dodaj ponudu</button>
		<button id="show" >Preview</button>
	</div>
	<br>
	<input class="btn btn-success" type="submit" id="submit" value="save changes" name="submit" id="submit">
	<hr>
	<input type="hidden" name="csrfToken" id="csrfToken" value="{{ boot.csrfToken }}">
	</form>
   </div>

   <p class="upute">
   	<b>How to use this page</b>:
   	In order to save the offers that you have made or updated you have to fill out(images) all 
   	the offers. if the argument is marked as required you will have to specify it before
   	you can save the offers.
   </p>
<!--\PONUDE-->	
	

	<script type="text/javascript">


		var ponude =(function(){
			
			var stranice;

			deleteOffer = function(e){

				e.preventDefault();
				for(var i = 0; i < deleteButtons.length; i++)
				{	

					if(e.target == deleteButtons[i])
					{						
						ponude.removeChild(offers[i]);
					}			
				}			
				return false;
			};

		//prikaz argumenata za odredenu stranicu
			var showArgs = function()
			{

				if(!isNaN(this.value))
				{

					var page   = this;
					var pageId = this.value;

				 	var req  = new XMLHttpRequest();	 	
				 	var form = new FormData();

				 //SLANJE OBRAZCA 	
				 	form.append("id", pageId);
				 	form.append("csrfToken", csrf.value);
				 	req.open('POST', 'http://localhost/projekt-rent-aCar/ajax/getArgs', true);
				 	req.send(form);

				 	req.onreadystatechange=function(){

				 		if(req.readyState==4 && req.status==200)
					    {
					    
					    	var args = JSON.parse(req.responseText); //argumenti za odredenu stranicu 
					    	                              
					    	var titl 	   = document.createElement('h4');
					    	titl.innerHTML = 'arguments:';

					    	var arguments = page.parentNode.getElementsByClassName('args')[0];
					    	arguments.innerHTML = "";

					    	if(args){

						    	for(var i = 0; i < args.length; i++)
						    	{

						    		var argDiv  = document.createElement('div');
										argDiv.className = 'argument';
						    		
						    		var label   = document.createElement('label');
						    			label.innerHTML = args[i].ime;
									
									var winning = document.createElement('select');
										winning.className = 'arg';			
										winning.setAttribute('data-argid', args[i].id);		
										winning.name = args[i].ime;
									
										var argData =args[i].argData; 
										for(var j=0; j<argData.length; j++)
										{

											var opt = document.createElement('option');
												opt.value     = argData[j].id;
												opt.innerHTML = argData[j].refField;

											winning.appendChild(opt);
										}
								


									 argDiv.appendChild(label);
									 argDiv.appendChild(winning);

								  //ako je argument obavezan postavljamo klasu req i zvjezdicu
									 if(args[i].req == 1)
									 {
											winning.className += " req";
											var dot = document.createElement('span');
												dot.innerHTML = '*';
												dot.className = 'dot';
									 			argDiv.appendChild(dot);
									} 
									else {
											winning.className += " noReq";
									}

								  	 arguments.appendChild(argDiv);
						    	 }
						      } else {

						      	arguments.innerHTML = "";
						      }
					     }
				 	 }
				  } 	
			 };

	//CACHE THE DOM

		//content
			var offers  	 = document.getElementsByClassName('offer');
			var form 		 = document.getElementById('mainPageForm');
			var ponude     	 = document.getElementById('offers');
			var pages 	     = document.getElementsByClassName('page');
			var image_slider = document.getElementsByClassName('carousel-inner main_carousel')[0];
			var submit     	 = document.getElementById('submit');
			var poruka     	 = document.getElementById('poruka');
			var picInputs	 = document.getElementsByName('slika');
			var csrf     	 = document.getElementById('csrfToken');



			for(var i=0; i < picInputs.length; i++)
			{ 
					picInputs[i].onchange = _showFilename;	
			}

		//event listeneri za select stranica	
			for(var i=0; i < pages.length; i++)
			{ 
				pages[i].onchange = showArgs;			
			}

		//buttons
			var prev_btn 	  = document.getElementById('show');
			var add_btn 	  = document.getElementById('add_btn');
			var deleteButtons = document.getElementsByClassName('delete_btn');
			
		//listeneri za gumbe
			prev_btn.onclick = function(e)
			{		
				e.preventDefault();	
				_render();
			};

			add_btn.onclick = function(e){
				e.preventDefault();
				addOffer();
			}

			submit.onclick = submitForm;


			for(var i=0; i < deleteButtons.length; i++){

				deleteButtons[i].onclick = deleteOffer;
			}


		/**
		 *  funkcija za dodavanje ponude
		 * postavlja novi div klase 'offer' u div sa id='offers'
		 * ponudi se daj default slika  
		 */
			function addOffer(){

				var offer = document.createElement('div');
					offer.className = "offer";

				var input_naslov = document.createElement('input');
				input_naslov.type = 'text';
				input_naslov.name = 'title';
				input_naslov.size = '40';

				var label = document.createElement('label');
					label.innerHTML = ' Naslov ';

				var img_wrapper = document.createElement('div');
					img_wrapper.className = 'img_wrapper';

				var img = document.createElement('img');
					img.className = 'img-responsive';
					img.src 	  = 'http://localhost/projekt-rent-aCar/public/resources/slike/start/felga.jpg';

					img_wrapper.appendChild(img);


				var file_div = document.createElement('div');
					file_div.className = 'file_div';

					var browse = document.createElement('span');
						browse.className = 'btn btn-default btn-file btn-primary';
						browse.innerHTML = 'Browse...';

						var file_input = document.createElement('input');
							file_input.type = 'file';
							file_input.name = 'slika';
							file_input.className = 'file';
							file_input.onchange = _showFilename;
						browse.appendChild(file_input);


					var fileMsg = document.createElement('span');
						fileMsg.className = 'fileMsg';
						fileMsg.innerHTML = 'Odaberite sliku';


					file_div.appendChild(browse);	
					file_div.appendChild(fileMsg);

				var br = document.createElement('br');	


				var pageLabel = document.createElement('label');
					pageLabel.innerHTML = 'link na stranicu:';


				var pages  = document.createElement('select');
					pages.className  = 'page';	
					pages.name 		 = 'page';	
					_put_in_pages(pages); //ispunjavamo select za stranicama
					pages.onchange =  showArgs;	

				var argText = document.createElement('h4');	
					argText.innerHTML = "argumenti stranice";

					var smallText = document.createElement('small');
						smallText.innerHTML = " obavezni argumenti označeni su crvenom zvjezdicom";
					argText.appendChild(smallText);


			//div za argumente odabrane stranice
				var argFields = document.createElement('div');
					argFields.className = 'args';

				var delete_btn = document.createElement('a');
					delete_btn.href 	 = "#";
					delete_btn.className = "btn btn-danger delete_btn";
					delete_btn.innerHTML = "delete";

				delete_btn.onclick   = function(e){

					e.preventDefault();
					ponude.removeChild(offer);
				};



				offer.appendChild(label);
				offer.appendChild(input_naslov);
				offer.appendChild(img_wrapper);
				offer.appendChild(file_div);
				offer.appendChild(br);
				offer.appendChild(pageLabel);
				offer.appendChild(br);
				offer.appendChild(pages);
				offer.appendChild(argText);						
				offer.appendChild(argFields);
				offer.appendChild(delete_btn);

				ponude.appendChild(offer);	
			}

			function _put_in_pages(selectEl){
				var option = document.createElement('option');
					option.innerHTML = 'odaberite stranicu';

				selectEl.appendChild(option);	

				for(var i=0; i<stranice.length; i++)
				{

					var opt = document.createElement('option');
			 			opt.value 	  = stranice[i].id;
			 			opt.innerHTML = stranice[i].title;

			 		selectEl.appendChild(opt);
				}	
			}

		//GLAVNA FUNKCIJA
			function submitForm(e){

				e.preventDefault();

				var xhr 	= new XMLHttpRequest();
				var obrazac = new FormData();

				for(var i=0; i<offers.length; i++)
				{

					var greske = "";

					var title = document.getElementsByName('title')[i];
					var slika = document.getElementsByName('slika')[i];

					var page  = pages[i];

					if(title.value){
						title = title.value
					}
					else{

						greske = 'pogresno unijet naslov';
						title = '';
					}

					if(slika.files[0])
					{
					// !!! potrebno doraditi !!!
						slika = slika.files[0];	
						obrazac.append('slika' + i, slika);				
					}
					else{
						greske = 'pogresno unijete slike';
					}

					if( !isNaN(page.value) )
					{
						page = page.value;
					}
					else{
						greske = 'niste unijeli stranicu';
						page   = '';
					}

					var args  = offers[i].getElementsByClassName('arg');

					var argumenti = [];
					for(var j=0; j<args.length; j++)
					{

						var potrebno = args[j].className.substring(args[j].className.indexOf(" ") +1);

						argumenti[j] =[
							args[j].name,
							args[j].value,
							potrebno,
							args[j].getAttribute('data-argid')
						];
					}

					var offer = {
						title 	  : title,
						argumenti : argumenti,
						page  	  : page
					};
					offer = JSON.stringify(offer);

					obrazac.append('ponuda' + i, offer);				
				}//for offers	

				if(greske.length < 1 || 1)
				{

					xhr.open('POST', 'http://localhost/projekt-rent-aCar/admin/mainPage', true);
					obrazac.append("csrfToken", csrf.value);					
					xhr.send(obrazac);

					xhr.onreadystatechange = function(){

						if(xhr.readyState == 4 && xhr.status == 200)
						{

							var content = JSON.parse(xhr.responseText);

							_render();
							poruka.innerHTML = content.poruka;

							if(content.uspjeh == '1')
							{
								poruka.className = 'success';
							}
							else 
							{
								poruka.className = 'failed';
							}			
						}	
					}
				}
				else{
					
					poruka.innerHTML = greske;
					poruka.className = 'failed';
				}
				return false;
			}


		//RENDER
			function _render(){

				image_slider.innerHTML = '';

				for(var i = 0; i < offers.length; i++){			

					var unos  = offers[i].getElementsByTagName('input')[0];
					var title = unos.value;
					var img   = offers[i].getElementsByTagName('img')[0];
					var image_file = offers[i].getElementsByClassName('file')[0];
					
					var image_link = image_file.value;

					if(image_link)
					{
					image_path = "http://localhost/projekt-rent-aCar/public/resources/slike/main_page/" + image_link.substring(image_link.lastIndexOf("\\")); 
					img.src = image_path; 		
					}

				//IMAGE SLIDER
					var item 		 = document.createElement('div');								
					var slika 		 = document.createElement('img');								
					var caption 	 = document.createElement('div');								
					var caption_text = document.createElement('p');					

					item.className = (i == 0) ? 'item active' : 'item';

					slika.src 		  = img.src;
					slika.className   = 'carImage';
					caption.className = 'carousel-caption';

					caption_text.innerHTML = title;

					item.appendChild(slika);

					caption.appendChild(caption_text);
					item.appendChild(caption);

					image_slider.appendChild(item);
				}
			}

		//funkcija za dobavljanje svih stranica za select izbornik/e
			function _getPages(){

			 	var req  	= new XMLHttpRequest();
			 	var obrazac = new FormData();

				req.open('POST', 'http://localhost/projekt-rent-aCar/ajax/getPages', true);

				obrazac.append("csrfToken", csrf.value);
				req.send(obrazac);

			    req.onreadystatechange=function()
			    {
			    	if(req.readyState == 4 && req.status==200){
			 			stranice = JSON.parse(req.responseText);
			 		}	
			 	};	 	
			}
			function _showFilename(){

				var fakeName = this.value;
				var name = fakeName.substring(fakeName.lastIndexOf("\\") +1);

				var parent = this.parentNode.parentNode;

				parent.getElementsByClassName('fileMsg')[0].innerHTML = name;
			}
 
			_getPages();
		})();



//STYLING	
	window.onload = function(){

			var submit  = document.getElementById('submit');
			var show    = document.getElementById('show');
			var inter;



			submit.addEventListener('mouseover', showButton);
			show.addEventListener('mouseover',   showButton);
			submit.addEventListener('mouseout',  hideButton);
			show.addEventListener('mouseout',    hideButton);


			var subStyle  = window.getComputedStyle(submit);
			var showStyle = window.getComputedStyle(show);

			var w = parseInt(subStyle.width);
			var r = -(w - 10);

			submit.style.left = r + "px";
			show.style.left   =  ( -(parseInt(showStyle.width) - 10) ) + "px";


			function showButton(){
				
				var button = this;
				var style  = window.getComputedStyle(this);

				var w = parseInt(style.width);
				var r = parseInt(style.right);

				inter = window.setInterval(moveRight, 3);
				function moveRight(){

					button.style.left = parseInt(button.style.left) + 3 + "px";
					if(parseInt(button.style.left) > (-10))
						clearInterval(inter);
				}		
			}

			function hideButton(){

				if(inter){
					clearInterval(inter);
				}

				var style  = window.getComputedStyle(this);

				w = parseInt(style.width);
				this.style.left = -(w - 10) + "px";
			}
	}
	</script>
	
{% endblock %}


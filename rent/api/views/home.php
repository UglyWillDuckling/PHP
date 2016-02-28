{% extends 'templates/default.php' %}

{% block css %}
	<link href="{{ boot.baseUrl }}/api/views/home.css" rel="stylesheet" />
{% endblock %}

{% block title %}home{% endblock %} 
{% block main_content %}
<p>{{ youIdiot }}</p>
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
						  <img src="{{boot.baseUrl }}/{{ offer.pic }}" alt="{{ offer.title }}">
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


{% endblock %} 
	
{% block side_content %}
	<div class="text-center">
		<a href="{{ boot.urlZa('location') }}"><h2>Lokacije</h2></a>
	</div>
	<div class="nova_ponuda text-center">
		<h3>novo u ponudi</h3>
		
		<div id="myCarousel2" class="carousel slide">
		
		  <!-- Wrapper for slides -->
		  <div class="carousel-inner side_carousel" role="listbox">
		   {% for car in newCars %}		   
			  <div class="item{% if loop.index == 1 %} active{% endif %}">
			  
				  <a href="{{ boot.urlZa('vozila.single') }}.id_{{ car.id }}">
					<img src="{{ boot.baseUrl }}/{{ car.slika }}" alt="{{ car.model }}" />
				  </a>

			 	 <div class="carousel-caption">
					<p style="text-align: center;">
					{{ car.model }},
					{{ car.obujam_motora|rtrim('0') }}l, 
					{{ car.snaga_motor*1.341|round(0, 'ceil') }}ks
					{% if car.klima %}, klima {% endif %}
					{% if not car.stanje %}, rabljen {% endif %}
					, {{ car.broj_vrata }} vrata
					</p>
			 	 </div>
			   </div> 	 		 
		   {% endfor %}
		  </div>
		  <!-- Left and right controls -->
		  <a class="left carousel-control side_carousel_control" href="#myCarousel2" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		  </a>
		  <a class="right carousel-control side_carousel_control" href="#myCarousel2" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		  </a>
		</div>
	</div>
{% endblock %}
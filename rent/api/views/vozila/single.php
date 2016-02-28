{% extends 'templates/default.php' %}

{% block css %}
	<link href="{{ boot.baseUrl }}/api/views/vozila/vozila.css" rel="stylesheet" />
{% endblock %}

{% block title %}iznajmi{% endblock %} 
	
{% block col1 %}col-md-11{% endblock %}
{% block col2 %}col-md-10{% endblock %}

{% block js_plugins %}
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
{% endblock %}	

{% block js %}
	<script type="text/javascript">

		window.onload = function(){

			$(function() {
			   $( "#datum" ).datepicker();
			});	
		};

	</script>
{% endblock %}

{% block main_content %}
<h3>{{ vozilo.model }}</h3>
	<div class="img_wrapper_right">
		<img class="img-responsive" src="{{ boot.baseUrl }}/{{ vozilo.slika }}" alt="{{ vozilo.model }}">
	</div>
	<ul>
		<li><b>Klasa</b>: {{ vozilo.klasa }}</li>
		<li><b>Marka</b>: {{ vozilo.marka }}</li>
		<li><b>Boja</b>: {{ vozilo.boja }}</li>
		<li><b>Snaga</b>: {{ vozilo.snaga }}kw/{{ (vozilo.snaga*1.341)|round(0, 'ceil') }}ks</li>
		<li><b>Tip motora</b>: {{ vozilo.boja }}</li>	
		<li><b>Broj vrata</b>: {{ vozilo.vrata }}</li>
	</ul>

	<!--POKAŽI CIJENU-->
	
	<div class="iznajmi">
		<form action="{{ boot.urlZa('vozila.single') }}" method="POST">
		<fieldset>
			<legend>Iznajmite vozilo</legend>		
			<label for="cijena">Cijena po danu:</label>		
			<span id="cijena">{{ cijena }}kn</span><br>
			<p>	
			<label for="#">informacije o rezerviranju vozila:</label>		
				Vozila se iznajmljuju u periodu od maksimalno <b>90</b> dana, u slučaju da rok
				 želite produžiti kontaktirajte nas putem telefona ili emaila.
			</p>
			<label for="datum">Datum rezervacije: </label>
			<input type="text" name="datum" id="datum">

			<button class="btn btn-primary" type="submit" value="submit">iznajmi</button>
			<input type="hidden" name="carId" value="{{ vozilo.vozilaId }}">
		</fieldset>
		</form>
	</div>
{% endblock %}
{% extends 'templates/default.php' %}

{% block css %}
	<link href="{{ boot.baseUrl }}/api/views/vozila/vozila.css" rel="stylesheet" />
	<link href="{{ boot.baseUrl }}/api/views/vozila/css/all.css" rel="stylesheet" />
{% endblock %}

{% block title %}vozila{% endblock %} 

{% block col1 %}col-md-2{% endblock %}
{% block col2 %}col-md-10{% endblock %}

{% block main_content %}
	<h3>Poredaj prema:</h3>

	<form action="{{ boot.urlZa('vozila.all')}}" method="post">
		<div class="form-group">
			<label for="klasa">Klasa</label>
			<select class="select" name="klasa" id="klasa">
				<option value=""></option>
			{% for klasa in klase %}
				<option 
				value="{{ klasa.id }}"
				{% if klasa.id == gdje.klasa_id %}selected{% endif %}
				>
				{{ klasa.naziv }}
				</option>
			{% endfor %}
			</select>
		</div>

		<div class="form-group">
			<label for="marka">Marka</label>
			<select class="select" name="marka" id="marka">
				<option value=""></option>
			{% for marka in marke %}
				<option 
				value="{{ marka.id }}"
				{% if marka.id == gdje.marka_id %}selected{% endif %}
				>
				{{ marka.naziv }}
				</option>
			{% endfor %}
			</select>
		</div>

		<label>tip motora</label>
		<div class="radio">
		  <label><input type="radio" name="motor" value="1" {% if gdje.tip_motora == 1 %}checked{% endif %} >dizel</label>
		</div>
		<div class="radio">
		  <label><input type="radio" name="motor" value="0" {% if  gdje.stanje is not null and gdje.tip_motora == 0 %}checked{% endif %} >benzin</label>
		</div>

		<label>stanje</label>
		<div class="radio">
		  <label><input type="radio" name="stanje" value="1" {% if gdje.stanje == 1 %}checked{% endif %}  >novo</label>
		</div>
		<div class="radio">
		  <label><input type="radio" name="stanje" value="0" {% if  gdje.stanje is not null and gdje.stanje == 0 %}checked{% endif %}  >rabljeno</label>
		</div>

		<button type="submit" class="btn btn-primary" name="submit" value="submit">razvrstaj</button>
	</form>
{% endblock %}

{% block side_content %}
	<!--<h2 class="text-center">iznajmite vozilo</h2>-->

	<ul class="list-group">
	{% for vozilo in vozila %}
		<li class="list-group-item list_car">
			<h4>{{ vozilo.model }}</h4>
			<div class="img_wrapper_iznajmi float_left">
				<a href="{{ boot.urlZa('vozila.single') }}.id_{{ vozilo.vozilaId }}">
					<img class="img-responsive" src="{{ boot.baseUrl }}/{{ vozilo.slika }}" alt="{{ vozilo.model }}">
				</a>
			</div>	
			<div class="carInfo">
				<p><b>Klasa</b>: {{ vozilo.klasa }}</p>
				<p><b>Marka</b>: {{ vozilo.marka }}</p>
				<p><b>Snaga</b>: {{ vozilo.snaga }}kw/{{ (vozilo.snaga * 1.341)|round('0', 'floor') }}ks</p>
				<p><b>Tip motora</b>:{% if vozilo.tipMotora == 1 %}Dizel{% else %}Benzin{% endif %}</p>
				<p><b>Boja</b>:{{ vozilo.boja }}</p>
			
			</div>
		</li>
	{% endfor %}
	</ul>
{% endblock %}

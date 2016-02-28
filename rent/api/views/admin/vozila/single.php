{% extends 'templates/adminTemplate.php' %}
{% block css %}
{% endblock %}
{% block js %}
{% endblock %}


{% block col1 %}col-md-10{% endblock %}
{% block col2 %}col-md-12{% endblock %}

{% block title %}vozilo{% endblock %}

{% block main_content %}
	<h2>Izmjena vozila</h2>
	<form role="form" method="post" action="{{ boot.baseUrl }}/admin/vozila/single" enctype="multipart/form-data">
		<div class="form-group">
			<label for="model">Model</label>
			<input class="form-control" id="model" name="model" type="text" value="{{ vozilo.model }}" />
			{% if errors.first('model') %}<span class="help-block red">{{ errors.first('model') }}</span>{% endif %}
		</div>

<!--KLASE-->
		<div class="form-group">
			<label for="klasa">Klasa:</label>
			<select class="form-control" id="klasa" name="klasa">
			{% for klasa in klase %}
				<option 
					value="{{ klasa.id }}" 
					{% if klasa.naziv == vozilo.klasa %}selected{% endif %}
				>
					{{ klasa.naziv }}
				</option>
			{% endfor %}
			</select>
			{% if errors.first('klasa') %}<span class="help-block red">{{ errors.first('klasa') }}</span>{% endif %}
		</div>
<!--KLASE-->
<!--MARKE-->
		<div class="form-group">
			<label for="marka">Marka</label>
			<select class="form-control" id="marka" name="marka">
			{% for marka in marke %}
				<option 
					value="{{ marka.id }}" 
					{% if marka.naziv == vozilo.marka %}selected{% endif %}
				>
					{{ marka.naziv }}
				</option>
			{% endfor %}
			</select>
			{% if errors.first('marka') %}<span class="help-block red">{{ errors.first('marka') }}</span>{% endif %}
		</div>
<!--MARKE-->


		<div class="form-group">
			<label for="snaga">Snaga(kilowatt)</label>
			<input class="form-control" type="number" id="snaga" name="snaga" value="{{ vozilo.snaga }}" />
		</div>

		<div class="form-group">
			<label for="obujam">Obujam motora</label>
			<input class="form-control" type="text" id="obujam" name="obujam" value="{{ vozilo.obujam }}" />
			{% if errors.first('obujam') %}<span class="help-block red">{{ errors.first('obujam') }}</span>{% endif %}			
		</div>

		<label>klima</label>
		<div class="radio">
		  <label><input type="radio" name="klima" value="1" {% if vozilo.klima %}checked="checked"{% endif %} >da</label>
		</div>
		<div class="radio">
		  <label><input type="radio" name="klima" value="0" {% if not vozilo.klima %}checked="checked"{% endif %} >ne</label>
		</div>
		{% if errors.first('klima') %}<span class="help-block red">{{ errors.first('klima') }}</span>{% endif %}

		
		<div class="form-group">
			<label for="vrata">Broj vrata</label>
			<input class="form-control" type="number" id="vrata" name="vrata" value="{{ vozilo.vrata }}" />
			{% if errors.first('brojVrata') %}<span class="help-block red">{{ errors.first('brojVrata') }}</span>{% endif %}		
		</div>

<!--BOJE-->
		<div class="form-group">
			<label for="boja">Boja:</label>
			<select class="form-control" id="boja" name="boja">
			{% for boja in boje %}
				<option 
					value="{{ boja.id }}" 
					{% if boja.naziv == vozilo.boja %}selected{% endif %}
				>
					{{ boja.naziv }}
				</option>
			{% endfor %}
			</select>
			{% if errors.first('boja') %}<span class="help-block red">{{ errors.first('boja') }}</span>{% endif %}		
		</div>
<!--BOJE-->

		<label>stanje</label>
		<div class="radio">
		  <label><input type="radio" name="stanje" value="1" {% if vozilo.stanje %}checked="checked"{% endif %} >novo</label>
		</div>
		<div class="radio">
		  <label><input type="radio" name="stanje" value="0" {% if not vozilo.stanje %}checked="checked"{% endif %} >rabljeno</label>
		</div>
		{% if errors.first('stanje') %}<span class="help-block red">{{ errors.first('stanje') }}</span>{% endif %}


		<div class="form-group">
			<label for="na_lageru">Broj vozila na lageru</label>
			<input class="form-control" type="number" id="na_lageru" name="na_lageru" value="{{ vozilo.naLageru }}" />
			{% if errors.first('na_lageru') %}<span class="help-block red">{{ errors.first('na_lageru') }}</span>{% endif %}	
		</div>

		<div class="img_wrapper">		
			<img class="img-responsive" src="{{ boot.baseUrl }}/{{ vozilo.slika }}" alt="slika vozila" />
		</div>

		<div class="form-group">
			<label for="slika">Slika vozila</label>
			<input class="form-control" type="file" id="slika" name="slika" />
			{% if errors.first('slika') %}<span class="help-block red">{{ errors.first('slika') }}</span>{% endif %}			
		</div>
		
		<input type="hidden" name="id" value="{{ vozilo.vozilaId }}">
		<button type="submit" class="btn btn-primary" name="submit" value="submit">izmjeni vozilo</button>	
	</form>	

{% endblock %}
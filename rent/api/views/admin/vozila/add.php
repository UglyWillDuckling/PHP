{% extends 'templates/adminTemplate.php' %}
{% block css %}
{% endblock %}

{% block col1 %}col-md-12{% endblock %}
{% block col2 %}col-md-12{% endblock %}

{% block title %}dodaj vozilo{% endblock %}

{% block main_content %}
	<h2>Dodajte vozilo</h2>
	<form role="form" method="post" action="{{ boot.baseUrl }}/admin/vozila/add" enctype="multipart/form-data">
		<div class="form-group">
			<label for="model">Model</label>
			<input class="form-control" id="model" name="model" type="text" />
			{% if errors.first('model') %}<span class="help-block red">{{ errors.first('model') }}</span>{% endif %}
		</div>

<!--KLASE-->
		<div class="form-group">
			<label for="klasa">Klasa:</label>
			<select class="form-control" id="klasa" name="klasa">
			{% for klasa in klase %}
				<option 
					value="{{ klasa.id }}" 				
				>
					{{ klasa.naziv }}
				</option>
			{% endfor %}
			</select>
			{% if errors.first('klasa') %}<span class="help-block red">{{ errors.first('klasa') }}</span>{% endif %}
		</div>
<!--KLASE-->

		<div class="form-group">
			<label for="razina">razina:</label>
			<select class="form-control" id="razina" name="razina">
			{% for razina in razine %}
				<option 
					value="{{ razina.id }}" 				
				>
					{{ razina.naziv }}
				</option>
			{% endfor %}
			</select>
			{% if errors.first('razina') %}<span class="help-block red">{{ errors.first('razina') }}</span>{% endif %}
		</div>

<!--MARKE-->
		<div class="form-group">
			<label for="marka">Marka</label>
			<select class="form-control" id="marka" name="marka">
			{% for marka in marke %}
				<option 
					value="{{ marka.id }}" 					
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
			<input class="form-control" type="text" id="obujam" name="obujam" />
			{% if errors.first('obujam') %}<span class="help-block red">{{ errors.first('obujam') }}</span>{% endif %}			
		</div>

		<label>klima</label>
		<div class="radio">
		  <label><input type="radio" name="klima" value="1">da</label>
		</div>
		<div class="radio">
		  <label><input type="radio" name="klima" value="0">ne</label>
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
				<option value="{{ boja.id }}">{{ boja.naziv }}</option>		
			{% endfor %}
			</select>
			{% if errors.first('boja') %}<span class="help-block red">{{ errors.first('boja') }}</span>{% endif %}		
		</div>
<!--BOJE-->

		<label>stanje</label>
		<div class="radio">
		  <label><input type="radio" name="stanje" value="1">novo</label>
		</div>
		<div class="radio">
		  <label><input type="radio" name="stanje" value="0">rabljeno</label>
		</div>
		{% if errors.first('stanje') %}<span class="help-block red">{{ errors.first('stanje') }}</span>{% endif %}


		<div class="form-group">
			<label for="na_lageru">Broj vozila na lageru</label>
			<input class="form-control" type="number" id="na_lageru" name="na_lageru"/>
			{% if errors.first('na_lageru') %}<span class="help-block red">{{ errors.first('na_lageru') }}</span>{% endif %}	
		</div>

		<div class="form-group">
			<label for="slika">Slika vozila</label>
			<input class="form-control" type="file" id="slika" name="slika" />
			{% if errors.first('slika') %}<span class="help-block red">{{ errors.first('slika') }}</span>{% endif %}			
		</div>
		
		<input type="hidden" name="id" value="{{ vozilo.vozila_id }}">
		<button type="submit" class="btn btn-primary" name="submit" value="submit">Dodaj vozilo</button>	
	</form>	

{% endblock %}


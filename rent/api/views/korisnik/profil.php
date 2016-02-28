{% extends 'templates/default.php' %}


{% block col1 %}col-md-10{% endblock %}
{% block col2 %}col-md-11{% endblock %}

{% block title %}profil{% endblock %} 


{% block main_content %}

<h2>Profil</h2>
	<form action="" role="form" method="POST">
		<div class="form-group {% if errors.first('username') %}has-error{% endif %}">
	 		<label for="username">Korisničko ime: </label>
	 		<input class="form-control" type="text" id="username" name="username" value="{{ clan.username }}" />
			{% if errors.first('username') %}
				<span class="help-block">{{ errors.first('username') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('ime') %}has-error{% endif %}">
	 		<label for="ime">Ime: </label>
	 		<input class="form-control" type="text" id="ime" name="ime" value="{{ clan.ime }}" />	
			{% if errors.first('ime') %}
				<span class="help-block">{{ errors.first('ime') }}</span>
			{% endif %}
	 	</div>
	 	<div class="form-group {% if errors.first('prezime') %}has-error{% endif %}">
	 		<label for="prezime">prezime:</label>
	 		<input class="form-control" type="text" id="prezime" name="prezime" value="{{ clan.prezime }}"  />
			{% if errors.first('prezime') %}
				<span class="help-block">{{ errors.first('prezime') }}</span>			
			{% endif %}	 		
	 	</div>

	 	<div class="form-group {% if errors.first('oib') %}has-error{% endif %}">
	 		<label for="oib">oib:</label>
	 		<input class="form-control " type="text" id="oib" name="oib" value="{{ clan.oib }}"  />
			{% if errors.first('oib') %}
				<span class="help-block">{{ errors.first('oib') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('email') %}has-error{% endif %}">
	 		<label for="email">email:</label>
	 		<input class="form-control" type="email" id="email" name="email" value="{{ clan.email }}"  />	
			{% if errors.first('email') %}
				<span class="help-block">{{ errors.first('email') }}</span>
			{% endif %}			
	 	</div>
	 	<div class="form-group {% if errors.first('zupanija') %}has-error{% endif %}">
	 		<label for="zupanija">zupanija:</label>
	 		<!--<input class="form-control" type="text" id="mjesto" name="mjesto" />-->
	 		<select id="zupanija" name="zupanija" class="form-control">
	 			<option value="">odaberite zupaniju</option>
				{% for zupanija in zupanije %}	 			
					<option 
					value="{{ zupanija.id }}"
					{{ zupanija.naziv }}
					{% if zupanija.id == clan.zupanija_id %}selected{% endif %}
					>
					{{ zupanija.naziv }}
					</option>
				{% endfor %}
	 		</select>
			{% if errors.first('zupanija') %}
				<span class="help-block">{{ errors.first('zupanija') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('telefon') %}has-error{% endif %}">
	 		<label for="telefon">telefon:</label>
	 		<input class="form-control" type="text" id="telefon" name="telefon" value="{{ clan.tel_broj }}" />
			{% if errors.first('zupanija') %}
		 	<span class="help-block">{{ errors.first('telefon') }}</span>
			{% endif %}	 		
	 	</div>	
	 	<button type="submit" class="btn btn-primary" name="submit" value="submit">Izmjeni profil</button>
	 </form>
	
{% endblock %}

{% block side_content %}
	<h2 class="text-center">Popis rezervacija</h2>

	<table class="table table-hover">
	<thead>
		<th>model</th>
		<th>cijena po danu</th>
		<th>datum rezervacije</th>
		<th>započeto</th>
	</thead>
		<tbody>
		<!--ispis rezervacija -->

		{% for rezervacija in rezervacije %}
		<tr>
			<td>{{ rezervacija.model }}</td>
			<td>{{ rezervacija.cijena_dan }}kn</td>
			<td>{{ rezervacija.datum_rezervacija }}</td>
			<td>
				{% if rezervacija.u_tijeku == 1 %}
					da
				{% else %}
					ne
				{% endif %}
			</td>
			{% if rezervacija.u_tijeku < 1 %}
				<td>
				<a onclick = "return confirm('sigurno želite poništiti rezervaciju?');"  href="{{ boot.baseUrl }}/korisnik/ponistiRezervaciju.id_{{ rezervacija.rezId }}"
				 class="btn btn-danger" 
				>
				poništi
				</a>
				</td>	
			{% else %}			
				<td></td>	
			{% endif %}
		</tr>
		{% endfor %}

		</tbody>
	</table>
{% endblock %}
{% extends 'templates/default.php' %}

{% block title %}register{% endblock %} 
{% block main_content %}
	<h2>Register</h2>
	<form action="{{ boot.baseUrl }}/auth/register" role="form" method="POST">
		<div class="form-group {% if errors.first('username') %}has-error{% endif %}">
	 		<label for="username">Korisničko ime: </label>
	 		<input class="form-control" type="text" id="username" name="username" />
			{% if errors.first('username') %}
				<span class="help-block">{{ errors.first('username') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('ime') %}has-error{% endif %}">
	 		<label for="ime">Ime: </label>
	 		<input class="form-control" type="text" id="ime" name="ime" />	
			{% if errors.first('ime') %}
				<span class="help-block">{{ errors.first('ime') }}</span>
			{% endif %}
	 	</div>
	 	<div class="form-group {% if errors.first('prezime') %}has-error{% endif %}">
	 		<label for="prezime">prezime:</label>
	 		<input class="form-control" type="text" id="prezime" name="prezime" />
			{% if errors.first('prezime') %}
				<span class="help-block">{{ errors.first('prezime') }}</span>			
			{% endif %}	 		
	 	</div>
		<div class="form-group {% if errors.first('password') %}has-error{% endif %}">
	 		<label for="password">password:</label>
	 		<input class="form-control" type="text" id="password" name="password" />
			{% if errors.first('password') %}
				<span class="help-block">{{ errors.first('password') }}</span>
			{% endif %}	 		
	 	</div>
		<div class="form-group {% if errors.first('password_confirm') %}has-error{% endif %}">
	 		<label for="password_confirm">ponovi password:</label>
	 		<input class="form-control" type="text" id="password_confirm" name="password_confirm" />	
			{% if errors.first('password_confirm') %}
				<span class="help-block">{{ errors.first('password_confirm') }}</span>
			{% endif %}
	 	</div>
	 	<div class="form-group {% if errors.first('oib') %}has-error{% endif %}">
	 		<label for="oib">oib:</label>
	 		<input class="form-control " type="text" id="oib" name="oib" />
			{% if errors.first('oib') %}
				<span class="help-block">{{ errors.first('oib') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('email') %}has-error{% endif %}">
	 		<label for="email">email:</label>
	 		<input class="form-control" type="email" id="email" name="email" />	
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
					<option value="{{ zupanija.id }}">{{ zupanija.naziv }}</option>
				{% endfor %}
	 		</select>
			{% if errors.first('zupanija') %}
				<span class="help-block">{{ errors.first('zupanija') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('telefon') %}has-error{% endif %}">
	 		<label for="telefon">telefon:</label>
	 		<input class="form-control" type="text" id="telefon" name="telefon" />
			{% if errors %}
			<span class="help-block">{{ errors.first('telefon') }}</span>
			{% endif %}	 		
	 	</div>	
	 	<input type="hidden" name="csrfToken" value="{{ boot.csrfToken }}">
	 	<button type="submit" class="btn btn-primary" name="submit" value="submit">registriraj se</button>
	 </form>
{% endblock %}
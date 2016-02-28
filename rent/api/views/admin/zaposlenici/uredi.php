{% extends 'templates/adminTemplate.php' %}

{% block css %}
    <link href="{{ boot.baseUrl }}/api/views/admin/zaposlenici/css/zaposlenici.css" rel="stylesheet" />
{% endblock %}

{% block col1 %}col-md-8{% endblock %}
{% block col2 %}col-md-4{% endblock %}

{% block title %}profil zaposlenika{% endblock %}

{% block main_content %}

<h2 class="text-center">Uređivanje podataka zaposlenika</h2>
<form action="{{ boot.baseUrl }}/admin/zaposlenici/uredi.id_{{ zaposlenik.id }}" role="form" method="POST">
		<div class="form-group">
	 		<label for="username">Korisničko ime: </label>
	 		<input 
		 		 class="form-control" 
		 		 type="text"
		 		 id="username"
		 		 name="username"
		 		 value = "{{ zaposlenik.username }}"
		 		 disabled
		 	/>		
	 	</div>
	 	<div class="form-group {% if errors.first('ime') %}has-error{% endif %}">
	 		<label for="ime">Ime: </label>
	 		<input 
	 		class="form-control" 
	 		type="text" 
	 		id="ime" 
	 		name="ime" 
			value="{{ zaposlenik.ime }}"
	 		/>	
			{% if errors.first('ime') %}
				<span class="help-block">{{ errors.first('ime') }}</span>
			{% endif %}
	 	</div>
	 	<div class="form-group {% if errors.first('prezime') %}has-error{% endif %}">
	 		<label for="prezime">prezime:</label>
	 		<input 
	 		 class="form-control"
	 		 type="text" 
	 		 id="prezime"
	 		 name="prezime"
			 value="{{ zaposlenik.prezime }}"			
	 		 />
			{% if errors.first('prezime') %}
				<span class="help-block">{{ errors.first('prezime') }}</span>			
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('oib') %}has-error{% endif %}">
	 		<label for="oib">oib:</label>
	 		<input 
	 		class="form-control " 
	 		type="text" 
	 		id="oib" 
	 		name="oib"
			value="{{ zaposlenik.oib }}"
	 		/>

			{% if errors.first('oib') %}
				<span class="help-block">{{ errors.first('oib') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('email') %}has-error{% endif %}">
	 		<label for="email">email:</label>
	 		<input 
	 		class="form-control" 
	 		type="email" 
	 		id="email" 
	 		name="email" 
			value="{{ zaposlenik.email }}"
	 		/>	
			{% if errors.first('email') %}
				<span class="help-block">{{ errors.first('email') }}</span>
			{% endif %}			
	 	</div>
	 	<div class="form-group {% if errors.first('adresa') %}has-error{% endif %}">
	 		<label for="adresa">adresa:</label>
	 		<input 
	 		class="form-control" 
	 		type ="text" 
	 		id   ="adresa" 
	 		name ="adresa" 
	 		value="{{ zaposlenik.adresa }}"
	 		/>
			{% if errors %}
			<span class="help-block">{{ errors.first('adresa') }}</span>
			{% endif %}	 		
	 	</div>	
	 	<div class="form-group {% if errors.first('mjesto') %}has-error{% endif %}">
	 		<label for="mjesto">mjesto:</label>
	 		<!--<input class="form-control" type="text" id="mjesto" name="mjesto" />-->
	 		<select id="mjesto" name="mjesto" class="form-control">
	 			<option value="">odaberite mjesto</option>
				{% for mjesto in mjesta %}	 			
					<option value="{{ mjesto.id }}"  {% if mjesto.id == zaposlenik.mjesto_id %}selected{% endif %} >{{ mjesto.naziv }}</option>
				{% endfor %}
	 		</select>
			{% if errors.first('mjesto') %}
				<span class="help-block">{{ errors.first('mjesto') }}</span>
			{% endif %}	 		
	 	</div>
	 	<div class="form-group {% if errors.first('telefon') %}has-error{% endif %}">
	 		<label for="telefon">telefon:</label>
	 		<input 
	 		class="form-control" 
	 		type="text" 
	 		id="telefon" 
	 		name="telefon" 
	 		value="{{ zaposlenik.telefon }}"
	 		/>
			{% if errors %}
			<span class="help-block">{{ errors.first('telefon') }}</span>
			{% endif %}	 		
	 	</div>	
	 	<button type="submit" class="btn btn-primary" name="submit" value="submit">uredi</button>
	 </form>

{% endblock %}
{% extends 'templates/adminTemplate.php' %}

{% block css %}
    <link href="{{ boot.baseUrl }}/api/views/admin/zaposlenici/css/zaposlenici.css" rel="stylesheet" />
{% endblock %}

{% block col1 %}col-md-7{% endblock %}
{% block col2 %}col-md-4{% endblock %}

{% block title %}zaposlenici{% endblock %}

	{% block main_content %}
		<h3 class="text-center">Zaposlenici</h3>
		<hr>
		<div class="zaposlenici">
		<ul class="list_zaposlenici">
		{% for zaposlenik in zaposlenici %}
			<div class="zaposlenik_wrapper">
				<div class="zaposlenik">
					<div class="employ_img">
						<img src="{{ zaposlenik.slika }}" alt="zaposlenik_img" />
					</div>

					<div class="employ_info">
						<div class="user_info">
							<span>{{ zaposlenik.username }}</span>	
						</div>	
						<div class="user_info">
							<span>{{ zaposlenik.ime }} {{ zaposlenik.prezime }}</span>	
						</div>	
						<div class="user_info">		
							<span >email: {{ zaposlenik.email }}</span>
						</div>
						<div class="user_info">		
							<span >oib: {{ zaposlenik.oib }}</span>
						</div>
						<div class="user_info">		
							<span >
								{% if zaposlenik.admin_level > 1 %}
									superAdmin
								{% else %}
									Admin
								{% endif %}
							</span>
						</div>	
						{% if zaposlenik.active < 1 %}				
							<span class="fired">otpušten</span>
						{% endif %}
						<hr>
						{% if zaposlenik.active > 0 and zaposlenik.admin_level < 2 %}
							<a onclick = "return confirm('potvrdite otpustanje');" href="{{ boot.baseUrl }}/admin/zaposlenici/otpusti.id_{{ zaposlenik.id }}" class="btn btn-danger">otpusti</a>
						{% elseif zaposlenik.active < 1 %}
							<a onclick = "return confirm('potvrdite zapošljavanje');" href="{{ boot.baseUrl }}/admin/zaposlenici/zaposli.id_{{ zaposlenik.id }}" class="btn btn-primary">zaposli ponovno</a>
						{% endif %}

						<a href="{{ boot.baseUrl }}/admin/zaposlenici/uredi.id_{{ zaposlenik.id }}" class="btn btn-primary">uredi</a>			
					</div>
				</div>
			</div>	
		{% endfor %}
		</ul>
	{% endblock %}

	{% block side_content %}
		<h3>Dodavanje člana</h3>
		<hr>

		<form class="side_form" action="{{ boot.baseUrl }}/admin/zaposlenici/add" role="form" method="POST" enctype="multipart/form-data">
		<div class="form-group {% if errors.first('ime') %}has-error{% endif %}">
	 		<label for="username">Korisničko ime: </label>
	 		<input 
		 		 class="form-control" 
		 		 type="text"
		 		 id="username"
		 		 name="username"
		 	/>	
		 	{% if errors.first('username') %}
				<span class="help-block">{{ errors.first('username') }}</span>
			{% endif %}	
	 	</div>
	 	<div class="form-group {% if errors.first('ime') %}has-error{% endif %}">
	 		<label for="ime">Ime: </label>
	 		<input 
	 		class="form-control" 
	 		type="text" 
	 		id="ime" 
	 		name="ime" 
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
	 		 />
			{% if errors.first('prezime') %}
				<span class="help-block">{{ errors.first('prezime') }}</span>			
			{% endif %}	 		
	 	</div>
		<div class="form-group {% if errors.first('password') %}has-error{% endif %}">
	 		<label for="password">password:</label>
	 		<input 
	 		class="form-control" 
	 		type="text" 
	 		id="password"
	 		name="password"  
	 		/>	
	 		{% if errors.first('password') %}
				<span class="help-block">{{ errors.first('password') }}</span>
			{% endif %}	 	
	 	</div>
	 	<div class="form-group {% if errors.first('oib') %}has-error{% endif %}">
	 		<label for="oib">oib:</label>
	 		<input 
	 		class="form-control " 
	 		type="text" 
	 		id="oib" 
	 		name="oib"
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
	<!--upload slike-->
	 	<div class="form-group {% if errors.first('adresa') %}has-error{% endif %}">
	 		<label for="slika">slika:</label>
	 		<input 
	 		type ="file" 
	 		id   ="slika" 
	 		name ="slika" 
	 		/>
			{% if errors %}
			<span class="help-block">{{ errors.first('slika') }}</span>
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
	 	<button type="submit" class="btn btn-primary" name="submit" value="submit">unesi korisnika</button>
	 </form>
	{% endblock %}
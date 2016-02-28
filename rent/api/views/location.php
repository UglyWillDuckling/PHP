{% extends 'templates/default.php' %}

{% block css %}
{% endblock %}

{% block col1 %}col-md-12{% endblock %}
{% block col2 %}col-md-11{% endblock %}

{% block title %}lokacije{% endblock %} 
{% block main_content %}
	<div id="lokacije">
	{% for lokacija in lokacije %}	
		<div class="lokacija_wrapper">
			<h3>{{ lokacija.grad }}</h3>
			<p>{{ lokacija.adresa }}</p>
			<label for="#">Telefon:</label>
			<a href="#">{{ lokacija.telefon }}</a>
		</div>
	{% endfor %}
	</div>
{% endblock %}
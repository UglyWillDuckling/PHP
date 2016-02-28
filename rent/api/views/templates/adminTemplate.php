<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="<?php echo author; ?>">
     <link rel="icon" href="bootstrap-3.3.4/favicon.ico">

    <title>rent-a-Fićo | {% block title %}{% endblock %}</title>

     <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!--Fonts-->

    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

<!--Fonts-->

    <!-- Custom styles -->
    <link href="{{ boot.baseUrl }}/public/style/basic.css" rel="stylesheet">
	
    {% block css %}      
    {% endblock %}

     <!-- css starter tenmplate -->
    <link href="{{ boot.baseUrl }}/api/views/inc/starter-template.css" rel="stylesheet">
  </head>	
  <body>
{% include 'templates/includes/adminNavigation.php' %}<!--dodavanje navigacije-->
	<div class="container">
		<div class="row">
			{% if flash %}
			<div class="alert alert-info">
				<span class="close" data-dismiss="alert">&times;</span>
				{{ boot.flash() }}
			</div>
			{% endif %}
			<!--glavni div md-8-->
			<article class="{% block col1 %}col-md-9{% endblock %}">
				<div id="main_wrapper">
					{% block main_content %}
					{% endblock %}
				</div>
			</article>
			<aside class='{% block col2 %}col-md-3{% endblock %}'>
				<div id="side_wrapper">
					{% block side_content %}
					{% endblock %}		
				</div>
			</aside>
		</div>
		<br>
		<footer class="row">    	
			<div class="col-md-12 text-center"> 
				<div class="footer">
				{% block footer %}			
					<p>Sva prava pridrzana. rent-a-car 2015</p>
				{% endblock footer %}
				</div>
			</div>
		</footer><!-- /footer-->
	</div><!--\.container-->
	
	 <!--Bootstrap core Javascript-->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>>	
 	{% block js %}

 	{% endblock %}
 </body>
 	}
</html>

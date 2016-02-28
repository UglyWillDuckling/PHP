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

    <!-- Bootstrap core CSS -->
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ boot.baseUrl }}/public/style/basic.css" rel="stylesheet">
	
	 <!-- Custom styles for this template -->
    <link href="{{ boot.baseUrl }}/api/views/inc/starter-template.css" rel="stylesheet">
    {% block css %}
      
    {% endblock %}
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="{{ boot.baseUrl }}/api/views/inc/bootstrap-3.3.4/assets/js/ie-emulation-modes-warning.js"></script>
     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>	
  <body>
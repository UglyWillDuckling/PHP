{% extends 'templates/emptyTemplate.php' %}

{% block title %}Admin_login{% endblock %}

	{% block main_content %}
		<h2>Admin login</h2>

		<form action="{{ boot.urlZa('admin.auth.login') }}" role="form" class="text-center nav_form" method="POST">
			<div class="form-group text-center">
				<label for="username">username: </label>
				<input class="form-control" type="text" id="username" name="username" />	 		
			</div>
			<div class="form-group">
				<label for="password">password:</label>
				<input class="form-control" type="text" id="password" name="password" />	 		
			</div>
			<button type="submit" class="btn btn-primary" name="submit" value="login">login</button>
		</form>
	{% endblock %}
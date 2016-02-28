<nav class="navbar navbar-inverse navbar-fixed-top navigacija">
  <div class="container">
	<div class="naslov">
		<a style="color: inherit;" href="{{ boot.urlZa('home') }}"><h3>rent-a-Fićo<small><i>svi vaši snovi na jednom mjestu</i></small></i></h3></a>
	</div>
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	</div>
	<div id="navbar" class="collapse navbar-collapse">
	  <ul class="nav navbar-nav">
		<li><a href="{{ boot.urlZa('home') }}">Home</a></li>
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Klase<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
		{% for klasa in klase %}
			<li><a href="{{ boot.urlZa('vozila.all') }}.klasa_{{ klasa.id }}">{{ klasa.naziv }}</a></li>
		{% endfor %}
          </ul>
        </li>
		<li><a href="{{ boot.urlZa('vozila.all') }}">unajmi automobil</a></li>
		<li><a href="{{ boot.urlZa('admin.home') }}">zaposlenici</a></li>
	  </ul>
	  <div class="nav navbar-nav navbar-right">
			<ul class="nav navbar-nav login_register">
			
	  {% if not boot.member %}	
				<li><a href="{{ boot.urlZa('auth.prijava') }}">register</a></li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					  Login 
				  </a>
				  <div class="dropdown-menu">
					<!--obrazac za prijavu korisnika-->
					<form action="{{ boot.baseUrl }}/auth/login" role="form" class="text-center nav_form" method="POST">
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
				  </div>
				</li>
			
		{% else %}
				<li><a href="{{ boot.baseUrl }}/korisnik/profil">profil</a></li>
				<li><a href="{{ boot.baseUrl }}/auth/logout">logout</a></li>
		{% endif %}
		{% if boot.zaposlenik %}
				<li><a href="{{ boot.urlZa('admin.auth.logout') }}">adminLogout</a></li>
		{% endif %}
			</ul>
		</div>
	</div><!--/.nav-collapse -->
  </div>
</nav>
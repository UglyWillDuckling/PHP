<nav class="navbar navbar-inverse navbar-fixed-top navigacija">
  <div class="container">
	<div class="naslov">
		<h3>rent-a-Fićo<small> <i>svi vaši snovi na jednom mjestu</small></i></h3>
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
		<li><a href="{{ boot.urlZa('admin.home') }}.rule_running">adminHome</a></li>
		{% if boot.zaposlenik.admin_level > 1 %}
		<li><a href="{{ boot.urlZa('admin.zaposlenici.all') }}">svi zaposlenici</a></li>
		{% endif %}
		<li><a href="{{ boot.urlZa('admin.vozila.all') }}">vozila</a></li>
		<li><a href="{{ boot.urlZa('admin.mainPage') }}">mainPage Content</a></li>
		<li><a href="{{ boot.urlZa('admin.pages') }}">Pages</a></li>
	  </ul>
	  <div class="nav navbar-nav navbar-right">
		<ul class="nav navbar-nav login_register">
			<li><a href="{{ boot.urlZa('admin.auth.logout') }}">adminLogout</a></li>
		</ul>
	  </div><!--/.nav-collapse -->
  </div>
</nav>
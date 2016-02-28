
{% extends 'templates/adminTemplate.php' %}

{% block css %}
	<link rel="stylesheet" href="{{ boot.baseUrl }}/api/views/admin/pages/css/pages.css">
{% endblock %}

{% block col1 %}col-md-12{% endblock %}
{% block col2 %}col-md-12{% endblock %}

{% block title %}pages{% endblock %}


{% block main_content %}
	<div id="greska">
		<p id="error"></p>
		<span id="makniGresku">x</span>
	</div>

	<div id="poruka">
		<p id="msg"></p>
		<span id="makniPoruku">x</span>
	</div>

	<h2 id="naslov">Pages</h2>

	<div id="pages">
		{% for page in pages %}	

			<div class="page col-md-4">
			  <div class="dropdown"> 
				<button class="save_btn btn btn-primary">sačuvaj</button>		
			  </div>
			  <div class="dropdown deleteWrap">
			 	 <button class="del_page_btn btn btn-danger">obrisi stranicu</button>
			  </div>
			  <div class="form-group">
				<label for="">Naslov</label>
				<input class="form-control" type="text" data-naslov="" size ="25" value="{{ page.title }}">
			  </div>

			  <div class="form-group">
				<label for="">Link</label>
				<input  class="form-control" type="text" data-link="" name ="link" size ="25" value="{{ page.link }}">
			  </div>

				<div class="img_wrap">
					<img class="img-responsive slika2" src="{{ page.img }}" alt="page">
				</div>
				<div class="img_file">
					<span class="btn btn-default btn-file btn-primary">
						Browse...<input type="file" data-slika="" name="slika" >
					</span>
					<span class="fileMsg">Odaberite sliku</span>
				</div>
				<div class="arguments">
					<h4>argumenti</h4>
					{% for arg in page.args %}
						<div class="arg">
							<span class="argTitle">{{ arg.ime }}</span>
							<button class="arg_btn btn btn-success">modify</button>
							<button class="del_arg_btn btn btn-danger">delete</button>				
							<span class="reqSpan">{% if arg.req %}!{% endif %}</span>
						
							<div class="argInfo">
								<h4 class="naslov">{{ arg.ime }}</h4>
								<label for="">Ime</label>
								<input type="text" name="ime" data-name="" class="ime" value="{{ arg.ime }}">
								<br>
								<label for="">trueName</label>
								<input type="text" name="trueName" data-trueName="" value="{{ arg.trueName }}">
								<br>
								<label for="">required</label>
								<select name="req" data-req="">
									<option value="1">yes</option>
									<option value="0" {% if not arg.req %}selected{% endif %}>no</option>
								</select>
								<br>
								<label for="">referentna tablica</label>
								<select data-refTable="">							
									{% for table in tables %}
										<option value="{{ table.id }}" {% if  arg.refTable == table.id %}selected{% endif %}>{{ table.naziv }}</option>
									{% endfor %}
								</select>
								<br>	
								<label for="">referentno polje u tablici</label>

								<select data-refField="">									
									{% for table in tables %}
										{% if arg.refTable == table.id %}
											{% for field in table.fields %}
												<option value="{{ field.id }}">{{ field.naziv }}</option>		
											{% endfor %}
										{% endif %}	
									{% endfor %}
								</select>
								<button class="closet btn">Close</button>																
							</div>
						</div>
					{% endfor %}																	
				</div>

				<button class="add_arg_btn btn btn-success">dodaj argument</button>	
				
				<input type="hidden" name="pageId" data-pageid="" value="{{ page.id }}">
				<input type="hidden" name="csrfToken" id="csrfToken" value="{{ boot.csrfToken }}">
			</div>	
		{% endfor %}
		<span id="plus">&#43;</span>	
	</div><!--\#pages-->

 <script>

 </script>

{% endblock %}


{% block js %}
	<script src="{{ boot.baseUrl }}/api/js/pages/pages.js"></script>
{% endblock %}


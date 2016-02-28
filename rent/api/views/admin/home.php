{% extends 'templates/adminTemplate.php' %}
{% block css %}
    <link href="{{ boot.baseUrl }}/api/views/admin/css/adminHome.css" rel="stylesheet" />
{% endblock %}

{% block col1 %}col-md-12{% endblock %}
{% block col2 %}col-md-12{% endblock %}

{% block title %}rezervacije{% endblock %}

	{% block main_content %}
		<div class="panel panel-default">
    		<div class="panel-heading">
	    		<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
				{% if reserveType == 'today' %}
					Nezapočete rezervacije
				{% elseif reserveType == 'running' %}
					Rezervacije u tijeku	
				{% elseif reserveType == 'future' %}
					Buduće rezervacije
				{% endif %}
				<span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
					<li role="presentation">
						<a role="menuitem" tabindex="-1" href="{{ boot.baseUrl }}/admin/home.rule_today">Nezapočete rezervacije</a>
					</li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ boot.baseUrl }}/admin/home.rule_future">Buduće rezervacije</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ boot.baseUrl }}/admin/home.rule_running">Rezervacije u tijeku</a></li>
			    </ul>
		    </div>
		</div>  


   			<table class="table table-hover">
				<thead>
					<th>Korisničko ime</th>
					<th>model</th>
					<th>cijena po danu</th>
					<th>datum rezervacije</th>

				{% if reserveType == 'today' %}
					<th>kasni</th>
				{% elseif reserveType == 'running' %}
					<th>datum početka</th>				
					<th>max trajanje</th>	
					<th>preostalo dana</th>	
				{% endif %}

				</thead>
   				<tbody>
   				<!--ispis rezervacija -->
					{% for reserve in showReservations %}
					<tr {% if reserve.preostalo_dana == '0' %}class="danger"{% endif %}>
						<td>{{ reserve.username 		 }}</td>
						<td>{{ reserve.model 			 }}</td>
						<td>{{ reserve.cijena_dan 		 }}kn</td>
						<td>{{ reserve.datum_rezervacija }}</td>				
						{% if reserveType == 'running' %}
							<td>{{ reserve.datum_pocetak  }}</td>				
							<td>{{ reserve.max_trajanje   }}</td>				
							<td>{{ reserve.preostalo_dana }}</td>				
							<td><a  onclick = "return confirm('potvrdite završetak rezervacije.');"   href="{{ boot.baseUrl }}/admin/rezervacije.uTijeku_1.action_zavrsi.id_{{ reserve.rezId  }}" class="btn btn-success">završi</a></td>	
							<td><a  onclick = "return confirm('potvrdite produžetak rezervacije.');"  href="{{ boot.baseUrl }}/admin/rezervacije.uTijeku_1.action_produzi.id_{{ reserve.rezId }}" class="btn btn-primary">produži 30 dana</a></td>	
						{% elseif reserveType == 'today' %}
							{% if reserve.kasni %}
							<td class="red">!</td>
							{% else %}
							<td class=""></td>
							{% endif %}
							<td>
								<a 
								onclick = "return confirm('potvrdite poništavanje rezervacije.');"
								href  = "{{ boot.baseUrl }}/admin/rezervacije.uTijeku_0.action_iznajmi.id_{{ reserve.rezId }}" 
								class = "btn btn-primary"
								>
								iznajmi
								</a>
							</td>				
							<td><a onclick = "return confirm('potvrdite poništavanje rezervacije.');"   href="{{ boot.baseUrl }}/admin/rezervacije.uTijeku_0.action_ponisti.id_{{ reserve.rezId }}" class="btn btn-danger" >poništi</a></td>				
						{% elseif reserveType == 'future' %}
							<td>
								<a  
								 onclick = "return confirm('potvrdite poništavanje rezervacije.');"  
								 href  = "{{ boot.baseUrl }}/admin/rezervacije.uTijeku_0.action_ponisti.id_{{ reserve.rezId }}"
								 class = "btn btn-danger" 
								>
								poništi
								</a>
							</td>	
						{% endif %}
					</tr>
					{% endfor %}
   				</tbody>
   			</table>
 		 </div>
	{% endblock %}

	{% block side_content %}
	{% endblock %}
{% extends 'templates/adminTemplate.php' %}
{% block css %}

{% endblock %}

{% block title %}
	vozila
{% endblock %}

{% block col1 %}col-md-12{% endblock %}
{% block col2 %}col-md-12{% endblock %}

	{% block main_content %}
		<h2 style="text-decoration:underline;" class="text-center">Sva vozila</h2>

		<a class="btn btn-primary" href="{{ boot.baseUrl }}/admin/vozila/add">Dodaj vozilo</a>
		<table class="table table-hover">
			<thead>
				<th>id</th>
				<th>model</th>
				<th><a href="{{ boot.urlZa('admin.vozila.all') }}.order_marka{% if order %}.poredak_{{ order }}{% endif %}">marka</a></th>
				<th><a href="{{ boot.urlZa('admin.vozila.all') }}.order_klasa{% if order %}.poredak_{{ order }}{% endif %}">klasa</a></th>
				<th>snaga</th>
				<th>obujam motora</th>				
				<th>klima</th>
				<th>broj vrata</th>
				<th>boja</th>
				<th><a href="{{ boot.urlZa('admin.vozila.all') }}.order_stanje{% if order %}.poredak_{{ order }}{% endif %}">stanje</a></th>
				<th><a href="{{ boot.urlZa('admin.vozila.all') }}.order_naLageru{% if order %}.poredak_{{ order }}{% endif %}">na lageru</a></th>
			</thead>
			<tbody>
			{% for vozilo in vozila %}
				<tr>
				<td>{{ vozilo.carId }}</td>
				<td>
				{{ vozilo.model }}
				</td>
				<td>{{ vozilo.marka  }}</td>
				<td>{{ vozilo.klasa  }}</td>
				<td><b>{{ (vozilo.snaga*1.341)|round(0, 'ceil') }}</b>ks</td>
				<td>{{ vozilo.obujam }}</td>

				{% if vozilo.klima %}
				<td>da</td>
				{% else %}
				<td>ne</td>
				{% endif %}

				<td>{{ vozilo.vrata }}</td>
				<td>{{ vozilo.boja }}</td>

				{% if vozilo.stanje %}
				<td>novo</td>
				{% else %}
				<td>rabljeno</td>
				{% endif %}

				<td>{{ vozilo.naLageru }}</td>
				<td><a href="{{ boot.baseUrl }}/admin/vozila/single.id_{{ vozilo.vozilaId }}"
					 class="btn btn-success" 
					>
					izmjeni
					</a></td>
				<td><a href="{{ boot.baseUrl }}/admin/vozila/remove.id_{{ vozilo.vozila_id }}"
					 class="btn btn-danger" 
					>
					makni iz prodaje
					</a>
				</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
	{% endblock %}

	{% block side_content %}
	{% endblock %}
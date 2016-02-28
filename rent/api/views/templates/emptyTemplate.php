{% include 'templates/includes/head.php' %}<!-- dodavanje headera -->

  <div class="container">
    <div class="row">
      {% if flash %}
      <div class="alert alert-info">
        <span class="close" data-dismiss="alert">&times;</span>
        {{ flash }}
      </div>
      {% endif %}
      <!--glavni div md-8-->
      <article class="col-md-12">
        <div id="main_wrapper">
          {% block main_content %}
            empty template(login, brisi ovo)
          {% endblock %}
        </div>
      </article>
    </div>
      <footer class="row">      
      <div class="co-md-12 text-center"> 
      <!--footer stranice ('moguce izmijeniti sam sadrzaj u bloku footer')-->
        <div class="footer">
        {% block footer %}      
          <p>Sva prava pridrzana. rent-a-car 2015</p>
        {% endblock footer %}
        </div>
      </div>
    </footer><!-- /footer-->
  </div><!--\.container-->

    <!--!!!!POTREBAN include footera!!!!!-->

     <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="{{ boot.baseUrl }}/api/views/inc/bootstrap-3.3.4/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{ boot.baseUrl }}/api/views/inc/bootstrap-3.3.4/assets/js/ie10-viewport-bug-workaround.js"></script>  
 </body>
</html>
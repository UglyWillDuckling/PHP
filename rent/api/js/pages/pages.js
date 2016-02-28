
	
	var greska = document.getElementById('greska');
	var error  = document.getElementById('error');

	var poruka = document.getElementById('poruka');
	var msg    = document.getElementById('msg');

	var makniGresku = document.getElementById('makniGresku');

	makniGresku.onclick = function(){

		greska.style.display = "none";	
	};

	var makniPoruku = document.getElementById('makniPoruku');

	makniPoruku.onclick = function(){

		poruka.style.display = "none";
	};



	(function(){

		var argVisible = false;
		var tableList;

	//CACHE content
		var pages  		  = document.getElementsByClassName('page');
		var titles 		  = document.getElementsByClassName('naslov');
		var args   		  = document.getElementsByClassName('arguments');
		var slike_files   = document.getElementsByName('slika');
		var csrf   		  = document.getElementById('csrfToken');

		var stranice   = document.getElementById("pages");
		var addPageBtn = document.getElementById("plus");
		

	//CACHE buttons
		var save_btns 	  = document.getElementsByClassName('save_btn');
		var add_arg_btns  = document.getElementsByClassName('add_arg_btn');
		var modButtons    = document.getElementsByClassName('arg_btn');
		var deleteButtons = document.getElementsByClassName('del_arg_btn');
		var closeButtons  = document.getElementsByClassName('closet');
		var deletePageButtons  = document.getElementsByClassName('del_page_btn');

	//listeneri za buttone
		for(var i=0; i<modButtons.length; i++){	

			modButtons[i].onclick    = showArgument;
			deleteButtons[i].onclick = deleteArgument;
			closeButtons[i].onclick  = hideArg;
		}
	
	//dodavanje listenera za gumbe pojedine stranice
		for(var i=0; i<pages.length; i++)
		{

			add_arg_btns[i].onclick		 = addArgs;
			save_btns[i].onclick    	 = savePage;
			slike_files[i].onchange  	 = updateFileName;
			deletePageButtons[i].onclick = deletePage;
		}

		addPageBtn.onclick = addPage;


		function updateFileName(e){

			var val = this.value;
			if(val){
				var file = this.parentNode.parentNode;

				var msg = file.getElementsByClassName('fileMsg')[0];

				msg.innerHTML = val.substring(val.lastIndexOf("\\")+1);
			}	
		}//updateFileName()


		function addArgs(){
						
				var page = this.parentNode;
				var arguments = page.getElementsByClassName('arguments')[0];
	
				var br = document.createElement('br');
				
			//create the 'arg' element	
				var arg = document.createElement('div');
					arg.className = 'arg';

					var title = document.createElement('span');
						title.className="argTitle";


					var modify_btn = document.createElement('button');
						modify_btn.className="arg_btn btn btn-success";
						modify_btn.innerHTML="modify";

					  //modify_btn.setAttribute('data-pageId', pageId);				  
					    modify_btn.onclick = showArgument;


					var delete_btn = document.createElement('button');
						delete_btn.className = 'del_arg_btn btn btn-danger';
						delete_btn.innerHTML = 'delete';

						delete_btn.onclick = deleteArgument;

					var reqSpan = document.createElement('span');
						reqSpan.className = 'reqSpan';


				//informacije o argumentu		
					var argInfo = document.createElement('div');
						argInfo.className="argInfo";

						var h4 = document.createElement('h4');


					//ime argumenta koje prikazujemo korisniku
						var nameLabel = document.createElement('label');
							nameLabel.innerHTML = 'ime';

						var ime = document.createElement('input');
							ime.type = "text";
							ime.name = "ime";
							ime.className = "ime";
							ime.setAttribute('data-name', "");

					//pravo ime argumenta koje se koristi u url-u stranice	
						var trueLabel = document.createElement('label');
							trueLabel.innerHTML = 'trueName';

						
						var trueName = document.createElement('input');
							trueName.type = "text";
							trueName.name = "trueName";
							trueName.setAttribute('data-trueName', "");

					//select za required polje
					
						var reqLabel = document.createElement('label');
							reqLabel.innerHTML = 'required';

						var required =	document.createElement('select');
							required.name = "req";
							required.setAttribute('data-req', "");

							var opt1 = document.createElement('option');
								opt1.value     ="1";
								opt1.innerHTML ="yes";
							var opt2 = document.createElement('option');
								opt2.value     ="0";
								opt2.innerHTML ="no";

						required.appendChild(opt1);
						required.appendChild(opt2);


					//referentna tablica i referentno polje	
						var tableLabel = document.createElement('label');
							tableLabel.innerHTML = "referentna tablica";	

						var refTable = document.createElement('select');
							refTable.name="refTable";
							refTable.setAttribute('data-refTable', "");
							refTable.onchange = changeArgs;

							var option1 = document.createElement('option');
								option1.innerHTML = "odaberite tablicu";

							refTable.appendChild(option1);												
							putInTables(refTable);


						var fieldLabel = document.createElement('label');
						    fieldLabel.innerHTML = 'referentno polje';

						var refField = document.createElement('select');
							refField.name="refField";
							refField.setAttribute('data-refField', "");

							var option2 = document.createElement('option');
								option2.innerHTML = "odaberite polje";

							refField.appendChild(option2);


					//gumb za zatvaranje argumenta		
						var closeButton = document.createElement('button');
							closeButton.className="closet btn";
							closeButton.innerHTML="close";
							closeButton.onclick  = hideArg;
						

						argInfo.appendChild(h4);	
						argInfo.appendChild(nameLabel);	
						argInfo.appendChild(ime);	
						argInfo.appendChild(br.cloneNode());
						argInfo.appendChild(trueLabel);	
						argInfo.appendChild(trueName);	
						argInfo.appendChild(br.cloneNode());
						argInfo.appendChild(reqLabel);						
						argInfo.appendChild(required);	
						argInfo.appendChild(br.cloneNode());
						argInfo.appendChild(tableLabel);	
						argInfo.appendChild(refTable);	
						argInfo.appendChild(br.cloneNode());
						argInfo.appendChild(fieldLabel);	
						argInfo.appendChild(refField);	
						argInfo.appendChild(closeButton);



					arg.appendChild(title);	
					arg.appendChild(modify_btn);	
					arg.appendChild(delete_btn);	
					arg.appendChild(reqSpan);	
					arg.appendChild(argInfo);	

				arguments.appendChild(arg);		
		}//addArgs()


		function showArgument(e){

			if(!argVisible)
			{
				argVisible = true;
				var info = this.parentNode.getElementsByClassName('argInfo')[0];

				info.className += ' show';	
			}
		}	


 		function hideArg(){

 				argVisible = false;

 				var arguments = this.parentNode;
 				var arg 	  = arguments.parentNode;

 				var title = arg.getElementsByClassName('argTitle')[0];
 				var ime   = arguments.getElementsByClassName('ime')[0];

 				title.innerHTML = ime.value;

				var klasa = this.parentNode.className;
				this.parentNode.className = klasa.replace('show', '');

				var req = arg.querySelector('[data-req]').value;
				if(req == '1'){
				//ako je argument nužan do njega prikazujemo stavljamo '!'
					var reqSpan = arg.getElementsByClassName('reqSpan')[0];
						reqSpan.innerHTML = "!";
				}
		}


		function deleteArgument(){

			var arg = this.parentNode;
			var args = arg.parentNode;

			args.removeChild(arg);
		}

	/*******BRISANJE STRANICE******/
		function deletePage(){

			var yes = confirm('zelite li stvarno obrisati ovu stranicu?');

			if(yes){

				var page    = this.parentNode.parentNode;
				var pageId  = page.querySelector('[data-pageid]').value;

			if(pageId){

					var xhr 	= new XMLHttpRequest();
					var obrazac = new FormData();

					obrazac.append('pageId', pageId);
					obrazac.append('csrfToken', csrf.value);

					xhr.open('POST', 'http://localhost/projekt-rent-aCar/admin/ajax/deletePage', true);
					xhr.send(obrazac);

					xhr.onload = function(){

						var rez = xhr.responseText;

						if(rez==1){

							stranice.removeChild(page);
						} else {


						}
					};

				}else{
					stranice.removeChild(page);
				}
			}
		}

		function addPage(){

			var pages = this.parentNode;

			var page = document.createElement('div');
				page.className = "page col-md-4";

				var deleteWrapper = document.createElement('div');
					deleteWrapper.className ="dropdown";

					var deleteButton = document.createElement('button');
						deleteButton.className= "del_page_btn btn btn-danger";
						deleteButton.innerHTML= "obrisi stranicu";
						deleteButton.onclick   = deletePage;

					deleteWrapper.appendChild(deleteButton);


				var saveWrapper = document.createElement('div');
					saveWrapper.className = "dropdown";	


					var saveButton = document.createElement('button');
						saveButton.className="save_btn btn btn-primary";
						saveButton.innerHTML = "sačuvaj";
						saveButton.onclick = savePage;

						saveWrapper.appendChild(saveButton);

				page.appendChild(saveWrapper);					
				page.appendChild(deleteWrapper);



				var group1 = document.createElement('div');
					group1.className="form-group";

						var titleLabel = document.createElement('label');
							titleLabel.innerHTML ="naslov";

						var titleInput = document.createElement('input');
							titleInput.type="text";
							titleInput.className="form-control";
							titleInput.setAttribute('data-naslov', "");	

					group1.appendChild(titleLabel);
					group1.appendChild(titleInput);

				page.appendChild(group1);	


				var group2 = group1.cloneNode(false);
					group2.className="form-group";

						var linkLabel = document.createElement('label');
							linkLabel.innerHTML ="link";

						var titleInput = document.createElement('input');
							titleInput.type="text";
							titleInput.className="form-control";
							titleInput.setAttribute('data-link', "");	

					group2.appendChild(linkLabel);		
					group2.appendChild(titleInput);		

				page.appendChild(group2);	


				var imageWrap = document.createElement('div');
					imageWrap.className = "img_wrap";

					var img = document.createElement('img');
						img.className="img-responsive";

					imageWrap.appendChild(img);
				page.appendChild(imageWrap);
	

				var imgFileDiv = document.createElement('div');
					imgFileDiv.className="img_file";


					var btnFile = document.createElement('span');
						btnFile.className = "btn btn-default btn-file btn-primary";
						btnFile.innerHTML = "Browse...";

						var inputImage = document.createElement('input');
							inputImage.type="file";
							inputImage.setAttribute('data-slika', "");
							inputImage.onchange =updateFileName;
						 ;

						btnFile.appendChild(inputImage);	

					var fileMsg = document.createElement('span');
						fileMsg.className = "fileMsg";
						fileMsg.innerHTML = "odaberite sliku";
												
					imgFileDiv.appendChild(btnFile);
					imgFileDiv.appendChild(fileMsg);

				page.appendChild(imgFileDiv);


					var args = document.createElement("div");
						args.className="arguments";

						var h4Title = document.createElement("h4");
						h4Title.innerHTML = "argumenti";

						args.appendChild(h4Title);

				page.appendChild(args);
					

					var addButton =	 document.createElement('button');
						addButton.className="add_arg_btn btn btn-success";
						addButton.innerHTML = "dodaj argument";
						addButton.onclick = addArgs;


					

				page.appendChild(addButton);
			

				var hide = document.createElement('input');
					hide.type="hidden";
					hide.setAttribute('data-pageid', ""); 

				page.appendChild(hide);


			stranice.appendChild(page);
		}

		function savePage(e){

			e.preventDefault();

			var page 	= this.parentNode.parentNode;

			var naslov  = page.querySelector('[data-naslov]').value;
			var link    = page.querySelector('[data-link]').value;
			var slika   = page.querySelector('[data-slika]').files[0];
			var pageId  = page.querySelector('[data-pageid]').value;

			var stranica = {};


			if(!naslov || !link || !slika)
			{
				showError('Molimo ispunite sva potrebna polja za odabranu stranicu.');
				return false;
			}

			stranica.title = naslov;
			stranica.link   = link;
			stranica.pageId = pageId;


			var args = page.getElementsByClassName('argInfo');
			
			var arguments = [];
			for(var i=0; i<args.length; i++){

				var arg = args[i];
				var argument = {};

				var name 	 =arg.querySelector("[data-name]").value;
				var trueName =arg.querySelector("[data-truename]").value;
				var req 	 =arg.querySelector("[data-req]").value;
				var refTable =arg.querySelector("[data-reftable]").value;
				var refField =arg.querySelector("[data-reffield]").value;


				if(!name || !trueName || !refTable || !refField){

					showError('argumenti nisu ispravno ispunjeni.');
					return false;
				}	

				argument.name 	  =name;
				argument.trueName =trueName;
				argument.req 	  =req;
				argument.refTable =refTable;
				argument.refField =refField;

				arguments[i] = argument;
			}
			stranica.arguments = arguments;
			
			var xhr 	= new XMLHttpRequest();
			var obrazac = new FormData();

			stranica = JSON.stringify(stranica);

			obrazac.append('page', stranica);
			obrazac.append('slika', slika);
			obrazac.append('csrfToken', csrf.value);


			xhr.open('POST', 'http://localhost/projekt-rent-aCar/admin/pages', true);
			xhr.send(obrazac);

			xhr.onload = function(){

				var rez = JSON.parse(xhr.responseText);

				if(rez['success'] == '1')
				{
					var imgLink = rez['img'];

					var slika = page.getElementsByTagName('img')[0];
					slika.src = imgLink;

					showMsg(rez['msg']);
				} else {

					showError(rez['msg']);
				}
			}					
		}

		function changeArgs(){

			var val = this.value;
			

			var info = this.parentNode;

			var fields = info.querySelector('[data-reffield]');
				fields.innerHTML = "";

			for(var i=0; i<tableList.length; i++)
			{
				var table = tableList[i];

				if(table.id == val){

					var polja  = table.fields;


					var option = document.createElement('option');
						option.innerHTML = 'izaberite polje tablice';
					fields.appendChild(option);

					for(var j=0; j<polja.length; j++)
					{

						var opt = document.createElement('option');
							opt.value 	  = polja[j].id;
							opt.innerHTML = polja[j].naziv;

						fields.appendChild(opt);
					}
				}
			}
		}

		function putInTables(select){

			for(var i=0; i<tableList.length; i++)
			{

				var opt = document.createElement('option');
					opt.value     = tableList[i].id;
					opt.innerHTML = tableList[i].naziv;

				select.appendChild(opt);
			}
		}

		(function getTables(){
		
			var xhr = new XMLHttpRequest;
			var form = new FormData();


			xhr.open('POST', 'http://localhost/projekt-rent-aCar/admin/ajax/getTables');

			form.append('csrfToken', csrf.value);
			xhr.send();

			xhr.onload = function(){

				tableList = JSON.parse(xhr.responseText);
			};
		})();

	 	return false;
	})();

	function showError(err){

		greska.style.display = "block";
		error.innerHTML = err;
	}

	function showMsg(message){

		poruka.style.display = "block";
		msg.innerHTML = message;
	}

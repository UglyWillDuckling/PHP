<?php


	$boot->setGet('admin/rezervacije', 'admin.rezervacije', function($args) use($boot){

		checkZaposlenik($boot);

		try{

			$prevPage =  $boot->prevPage;
			$rezervacija = new Rezervacija($boot->db, $args['id']);

			if($args['uTijeku'] == 1)
			{

				if($rezervacija->uTijeku()){

					switch($args['action'])
					{
						case 'zavrsi':	
							if($rezervacija->zavrsi())	
							{
								$boot->flash('rezervacija zatvorena.');
							}
							else
							{
								$boot->flash('zatvaranje rezervacije neuspjelo.');
							}				
							break;

						case 'produzi':
							if($rezervacija->produzi()){

								$boot->flash('rezervacija je produzena za 30 dana.');	
							}
							else{
								$boot->flash('dogodila se greška prilikom produživanja rezervacije.');
							}					
							break;

						default:
							$boot->flash('došlo je do greške prilikom odabira opcija.');
							break;
					}
				}
				else
				{
					$boot->flash('dogodila se pogreška, ova rezervacija nije u tijeku.');				
				}
			}			
			else
			{	
				
			    if(!$rezervacija->uTijeku())
			    {

					switch($args['action']){

						case 'ponisti':	
							if($rezervacija->ponisti())	{
								$boot->flash('rezervacija poništena.');
							}				

							break;
						case 'iznajmi':

							if($rezervacija->iznajmi()){
								$boot->flash('automobil iznajmljen');	
							}
							else{
								$boot->flash('dogodila se greška prilikom izdavanja automobila.');
							}					
							break;
						default:

							$boot->flash('došlo je do greške prilikom odabira opcija.');
							break;
					 }
				}
				else
				{
					$boot->flash('dogodila se pogreška, ova rezervacija je u tijeku.');				
				}				
			}//else	
		}//try
		catch(Exception $e){

			$boot->flash($e->getMessage());
		}
		finally
		{
			$boot->redirect($boot->prevPage);	
		}
	});

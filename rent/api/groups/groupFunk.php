<?php

        /***********CSRF**************/

    $checkCsrf = function() use($boot){

        $sessionToken = Request::getSession('csrfToken');
        $postToken    = Request::getPost('csrfToken');

        if(!$sessionToken || !$postToken || $sessionToken != $postToken)
        {   
            $boot->flash(' bugger off Mate!!! ', 'red');
            $boot->redirect('home');
        } else {
            unset($_POST['csrfToken']);
        }
    };

    /**
        *[$createCsrf stvara nasumično generirani hash na temelju stringa kojeg stvara klasa    RandomLib\Factory]
     * @var function
     */ 
    $createCsrf = function() use($boot){

    //treba doraditi    
        $rand  = $boot->random;
        $token = hash( 'sha256', $rand->generateString(128) );

        $boot->csrfToken       = $token; //ovu varijablu stavljamo u 'form'
        $_SESSION['csrfToken'] = $token; //token u sessionu nam služi za provjeru tokena danog u obrazcu
    };

    $adminCheck = function() use($boot){

        if(!$boot->zaposlenik)
            return $boot->redirect('home', 'niste administrator');
    };


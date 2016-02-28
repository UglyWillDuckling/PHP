<?php


//order by 'id' accending
 $comments;

//order by 'mainCommentId' accending
 $subCom;

 $mainNumber =0;
 foreach($subCom as $sub)
 {   
        while($subCom['mainCommentId'] !== $comments[$mainNumber]['id'])
        {
            $mainNumber++;
        } 

        $comments[$mainNumber]['subComments'][] = $subCom;
 }

//pocetak $subCom arraya
 $subNow = 0;
 foreach($comments as $comment)
 {
    $id = $comment.id;

        while($subCom[$subNow]['mainCommentId'] == $id){

            $comment['subComments'][] = $subCom[$subNow];
            $subNow++;
        }
    }
 }


<?php
    
    get 10 posts

    foreach($posts as &$post)
    {
        //search by content_id
        get comments for post array order by id

        get replies for post array  order by commentId

        $reply_now=0; //trenutni odgovor
        foreach($comments as &$comment){

            //sve dok vrijednost 'comment_id' varijable u reply-ju odgovara id-iju komentara
            //spremamo ga u replies array istog
            while($comment['id'] == $replies[$reply_now]['comment_id'])
            {
               //spremanje odgovora u array 'replies' pripadajuceg komentara
                $comment[replies][] = $replies[$reply_now]; 
                $reply_now++; 
            }         
        }
    }


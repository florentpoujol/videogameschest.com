<?php

return array(

// LOST PASSWORD

    'lostpassword_confirmation' => array( 
        'subject' => 'Confirm that you want a new password',
        
        'html' =>
        'Hello :username <br>
        <br>
        It seems you losted your password and asked to get a new one. If it\'s not the case, just ignore this email. <br>
        To generate a new password, click the link below : <br>
        <a href=":link" title="Follow the link to generate a new password">:link</a> <br>
        <br>
        You will receive the new password in another email. <br>
        <br>
        -- <br>
        Regards, <br>
        The VideoGamesChest team <br>
        ',
    ),

    'lostpassword_success' => array( 
        'subject' => 'Here is your new temporary password',

        'html' => 
        'Hello :username <br>
        <br>
        Here is your new password : <br>
        :password<br>
        <br>
        You can now <a href=":login_link" title="Go to the login form">log in</a>. <br>
        <br>
        -- <br>
        Regards, <br>
        The VideoGamesChest team <br>
        ',
    ),

);

<?php

return array(

    'developer_passed_submission_review' =>
    'Hi :name <br>
    <br>
    You receive this email because someone created a developer profile on <a href="http://videogameschest.com" title="">VideoGamesChest.com</a> <br>
    <br>
    Log in to your account with your name, email or id (:id) and your temporary password : :password. Don\'t forget to edit your user account to change the password.<br>
    <br>
    A developer profile is linked to your user account. The profile is now private and you may review it and <a href="http://videogameschest.com/admin/editdeveloper/:id">edit it</a>. <br>
    Once you are satisfied with the informations it contains, you may send your profile in the Publishing review. <br>
    <br>
    <br>
    Thanks,<br>
    The VideoGamesChest.com team
    ',
        
    'developer_passed_publishing_review' => 'developer_passed_publishing_review',
    'developer_failed_publishing_review' => 'developer_failed_publishing_review',

    'game_passed_submission_review' => 'game_passed_submission_review',
    'game_passed_publishing_review' => 'game_passed_publishing_review',
    'game_failed_publishing_review' => 'game_failed_publishing_review',


    'register_confirmation_subject' => 'Activate your new user account',
    'register_confirmation' =>
    'Hello :username <br>
    <br>
    It seems your created a user account on <a href="http://videogameschest.com" title="Go to VideoGamesChest.com">VideoGamesChest.com</a>. <br>
    To be able to log in, you need to activate your account by clicking on the link below : <br>
    <a href=":link" title="Follow the link to activate your user account">:link</a> <br>
    <br>
    Regards, <br>
    The VideoGamesChest team <br>
    ',

// LOST PASSWORD

    'lostpassword_confirmation_subject' => 'Confirm that you want a new password',
    'lostpassword_confirmation' =>
    'Hello :username <br>
    <br>
    It seems you losted your password and asked to get a new one. If it\'s not the case, just ignore this email. <br>
    To generated a new password, click the link below : <br>
    <a href=":link" title="Follow the link to generate a new password">:link</a> <br>
    <br>
    You will receive the new password in another email. <br>
    <br>
    Regards, <br>
    The VideoGamesChest team <br>
    ',

    'lostpassword_success_subject' => 'Here is your new temporary password',
    'lostpassword_success' => 
    'Hello :username <br>
    <br>
    Here is your new password : <br>
    :password<br>
    <br>
    You can now <a href=":login_link" title="Go to the login form">log in</a>. <br>
    <br>
    Regards, <br>
    The VideoGamesChest team <br>
    ',
);

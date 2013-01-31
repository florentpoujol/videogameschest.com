<?php

return array(

// REVIEWS

    'developer_passed_submission_review' => array(
        'subject' => 'developer_passed_submission_review subject',

        'html' => 
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
        -- <br>
        Thanks,<br>
        The VideoGamesChest.com team
        ',
    ),

    
    'profile_passed_publishing_review' => array(
        'subject' => 'One of your profiles is now public',

        'html' =>
        'Hi :user_name <br>
        <br>
        The :profile_type profile (name : ":profile_name") you created on VideoGamesChest.com passed the publishing review ! <br>
        It is now public for everyone to enjoin (<a href=":profile_link" title="Go to the profile">you can view it by clicking here</a>), so feel free to spread the word. <br>
        <br>
        -- <br>
        Regards,<br>
        The VideoGamesChest.com team
        ',
    ),
    
// REGISTER

    'register_confirmation' => array(
        'subject' => 'Activate your new user account',
        
        'html' =>
        'Hello :username <br>
        <br>
        It seems your created a user account on <a href="http://videogameschest.com" title="Go to VideoGamesChest.com">VideoGamesChest.com</a>. <br>
        To be able to log in, you need to activate your account by clicking on the link below : <br>
        <a href=":link" title="Follow the link to activate your user account">:link</a> <br>
        <br>
        -- <br>
        Regards, <br>
        The VideoGamesChest team <br>
        ',
    ),

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

// NEW PROFILE

    'profile_created' => array( 
        'subject' => 'New profile created on VideoGamesChest.com',
        
        'html' =>
        'Hi :user_name <br>
        <br>
        You just created a new :profile_type profile ":profile_name". This profile is currently private, only you can see it when you are logged in. <br>
        An administrator must review and approve it before it becomes public and visible by everyone. You will get another email when it will be approved. <br>
        <br>
        Meanwhile, you can still edit the profiles or add another profiles. <br>
        <br>
        -- <br>
        Regards, <br>
        The VideoGamesChest team',
    ),

// PROMOTION

    'promotion_email_subscription_success' => array(
        'subject' => 'You subscribed to the promotion newsletter',

        'html' => 'Hello :username <br>
        <br>
        You subscribed to the promotion newsletter on VideoGamesChest.com with the following settings :  <br>
        - email : :email <br>
        - frequency : :hours hours (you will receive an email every :hours hours) <br>
        - profile count : :profile_count (:profile_count profiles are displayed in each emails) <br>
        - search ID : :search_id (only profiles that match this dearch ID will be displayed) <br>
        - use blacklist : :use_blacklist <br>
        <br>
        To edit your subscription or unsubscribe, just follow this link : <br>
        <a href=":update_link" title="Edit your subscription, or unsubscribe">:update_link</a> <br>
        <br>
        -- <br>
        Regards, <br>
        The VideoGamesChest team
        ',
    ),
);

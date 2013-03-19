<?php

return array(

// REVIEWS

    'profile_passed_review' => array(
        'subject' => 'One of your profiles is now public',

        'html' =>
        'Hi :user_name <br>
        <br>
        The :profile_type profile (name : ":profile_name") you created on VideoGamesChest.com passed the review ! <br>
        It is now public for everyone to enjoin (<a href=":profile_link" title="Go to the profile">you can view it by clicking here</a>), so feel free to spread the word. <br>
        <br>
        -- <br>
        Regards,<br>
        The VideoGamesChest.com team
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
        Meanwhile, you can still edit the profile or add another profiles. <br>
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
        - frequency : :frequency hours (you will receive an email every :frequency hours) <br>
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

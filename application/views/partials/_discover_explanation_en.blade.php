<p>
    We are sure you can find dosens of games to play each week from your social media feeds or the press. But you will probably play only to a fraction of those games. <br> 
    The thruth is that <strong>most of those games are just not relevant to you</strong>, because you don't have the device or you don't like the genre or how they looks. Doesn't it feels like wasted time and opportunities ?<br>
    <br>
    VGC allows you to sit at your computer and just wait for <strong>the profiles you are interested in</strong> to drop in your inbox or your favorite syndication feed reader. <br>
    <br>
    You can subscribe below to a <strong>fully customisable</strong> feed or newsletter whose sole purpose is to promote you with the kind of games (or any profiles) <strong>you choose</strong> to be promote with. <br> 
    <br>
    <strong>You control What, How much, When and How :</strong>
</p>

<!-- <h3>You choose what you get advertised for with a search ID</h3> -->

<p>
    Each searches that you can perform via the search form has a unique ID that you can put in the subscription forms below. <br>
    It will make the content of the email or feed match that search so that you can be promoted only with <strong>content that match your criteria</strong>.
</p>

<!-- <h3>Tidy up with your blacklist</h3> -->

<p>
    Even throught some games matches your criteria, you might not be interested in every single one of them, or you already poccess some of them. Either case, you don't want to be advertised for thoses games. <br>
    That's why 

    @if (is_guest())
        <a href="{{ route('get_register') }}" title="{{ lang('register.title') }}">registered users</a> 
    @else
        you 
    @endif

    

    @if (is_guest())
        <strong>a blacklist of profile</strong>. 
    @else
        <a href="{{ route('get_edituser') }}" title="{{ lang('user.edit_title') }}">a blacklist of profiles</a>.
    @endif

    
        

   <!--  You don't want to be advertised for the games you already poccess or you are not interestd to play (event throught they match your criteria).<br>
    That's why 
    
     the advertised content with a "blacklist" of profiles that won't shows up in the advertised content. -->

    <!-- If you are interested in some type of game, you will certainly pocess some of them already so you don't want them to to  So thatt you don't want to be advertised for them

    so that it's no good for you nor for the developer to shows up. <br>
    That's why registered users have a "blacklist" of profiles you don't want to see the the advertsing services. -->
</p>

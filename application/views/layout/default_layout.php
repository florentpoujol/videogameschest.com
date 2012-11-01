<!DOCTYPE html>
<html lang="<?php echo LANGUAGE?>"> 
    <head>
        <title><?php echo $page_title; ?></title>

        <!-- Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<?php if (ENVIRONMENT == "development" || CONTROLLER == "admin"): ?>
        <meta name="robots" content="noindex,nofollow" >
<?php else: ?>
        <meta name="robots" content="index,follow" >
<?php endif; ?>
        
<?php foreach ($metas as $meta): ?>
        <meta name="<?php echo $meta["name"]; ?>" content="<?php echo $meta["content"]; ?>" >
<?php endforeach; ?>
        <!-- /Meta -->

        <!-- CSS -->        
        <!--<link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link("main"); ?>" >-->
        
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link("bootstrap.min"); ?>" >
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link("bootstrap-responsive.min"); ?>" >
        <link rel="stylesheet/less" type="text/css" media="screen" href="<?php echo css_link("main", ".less");?>" >

<?php foreach ($css as $url): ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" >
<?php endforeach; ?>
        <!-- /CSS -->
    </head>
    
    <body>
        <header class="container navbar nav-inner">
                <ul class="nav">
                    <!-- Menu -->
<?php
$menu_items = array('home', 'search', 'adddeveloper', 'addgame', 'about');

foreach ($menu_items as $menu_item): 
?>
                    <?php echo '<li '.controller_selected($menu_item).'><a href="'.site_url($menu_item).'">'.lang('menu_'.$menu_item).'</a></li>'; ?> 
<?php
endforeach;

if (IS_LOGGED_IN): ?>
                    <!-- Admin menu -->
                    <li class="dropdown <?php echo controller_selected("admin", true); ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Admin
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li <?php echo method_selected('admin_index');?>><a href="<?php echo site_url('admin'); ?>">Admin hub</a></li>
                            <li <?php echo method_selected('edituser');?>><a href="<?php echo site_url('admin/edituser'); ?>">Edit user</a></li>
                            <li <?php echo method_selected('addgame');?>><a href="<?php echo site_url('admin/addgame'); ?>">Add a game</a></li>
                            <li <?php echo method_selected('editgame');?>><a href="<?php echo site_url('admin/editgame'); ?>">Edit a game</a></li>
                            <li <?php echo method_selected('gamequeue');?>><a href="<?php echo site_url('admin/gamequeue'); ?>">Game queue</a></li>
                            <li <?php echo method_selected('reports');?>><a href="<?php echo site_url('admin/reports'); ?>">Reports</a></li>
                            <li <?php echo method_selected('messages');?>><a href="<?php echo site_url('admin/messages'); ?>">Messages</a></li>
    <?php if (IS_ADMIN):  ?>
                            <li <?php echo method_selected('adduser');?>><a href="<?php echo site_url('admin/adduser'); ?>">Create user</a></li>
                            <li <?php echo method_selected('adddeveloper');?>><a href="<?php echo site_url('admin/adddeveloper'); ?>">Add a developer</a></li>
                            <li <?php echo method_selected('editdeveloper');?>><a href="<?php echo site_url('admin/editdeveloper'); ?>">Edit a developer</a></li>
    <?php elseif (IS_DEVELOPER): ?>
                            <li <?php echo method_selected('editdeveloper');?>><a href="<?php echo site_url('admin/editdeveloper/'.userdata('user_id')); ?>">Edit your dev profile</a></li>
    <?php endif; // end if is admin or dev ?>
                            <li class="divider"></li>
                            <li><a href="<?php echo site_url('admin/logout'); ?>"><?php echo lang('menu_logout');?></a></li>
                        </ul>
                    </li>
                    
                    <!-- /Admin menu -->
<?php else: // is not logged in?>
                    <li><a href="<?php echo site_url('admin/login'); ?>"><i class="icon-user"></i><?php echo lang('menu_login');?></a></li>
<?php endif; ?> 
                    <!-- /menu --> 
                    
                    <!-- Language menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo lang("menu_languages"); ?> 
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                
<?php
foreach ($this->static_model->site->languages as $lang):
    $lang == LANGUAGE ? $is_active_lang = 'class="active"': $is_active_lang = "";
    $current_url_escaped = str_replace("/", ":", uri_string()); // replace / by : in the current url
    $lang_url = site_url("admin/setlanguage/$lang:$current_url_escaped");
?>
                            <?php echo '<li '.$is_active_lang.'><a href="'.$lang_url.'" title="'.$lang.'">'.lang('languages_'.$lang).'</a></li>'; ?> 
<?php   
endforeach;
?>
                        </ul>
                    </li>
                    <!-- /#lang_menu --> 
                </ul>

                <form class="navbar-search pull-left">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
        </header>
        <!-- /header .container .navbar .nav-inner -->

        <div class="container" id="page_content">
            <!-- Body hook -->
            <?php echo $body_views; ?>
            <!-- /Body hook -->
        </div>
        <!-- /#page_content .container -->

        <!-- JavaScript -->
        <script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script src="<?php echo js_link("bootstrap.min");?>" type="text/javascript"></script>

<?php foreach ($js as $url): ?>
        <script type="text/javascript" src="<?php echo $url; ?>"></script> 
<?php endforeach; ?>
        
        <script type="text/javascript">
        </script>
        <!-- /JavaScript -->
    </body>
</html>
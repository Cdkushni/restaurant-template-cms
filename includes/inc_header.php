<?php $_this = Website::$instance; ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $_this->getTitle();?></title>
        <meta name="description" content="<?php echo $_this->getDescription();?>">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="<?php echo $_this->path;?>css/normalize.css">
        <link rel="stylesheet" href="<?php echo $_this->path;?>js/vendor/ddsmoothmenu/ddsmoothmenu.css">
        <link rel="stylesheet" href="<?php echo $_this->path;?>js/vendor/prettyphoto/css/prettyPhoto.css">    
        <link rel="stylesheet" href="<?php echo $_this->path;?>css/global_stylesheet.css">
        <link rel="stylesheet" type='text/css' href="<?php echo $_this->path;?>css/main.<?php echo time();?>.css">
        <link href='http://fonts.googleapis.com/css?family=Cinzel+Decorative' rel='stylesheet' type='text/css'>
        <?php echo $template_includes;?>
        <script src="<?php echo $_this->path;?>js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
       <div class='wrapper'>
            <div class='header clearfix'>

                <div class='content clearfix'>
                    <div class='hairwheel'></div>

                    <div class='logo'><a href='<?php echo $_this->path;?>'><img src='<?php echo $_this->path;?>images/logo.png' width='282' height='91' alt='Zazzle'/></a></div>
                    <div class='navwrapper' class='clearfix'>
                        <div id='nav' class='ddsmoothmenu'>
                            <ul class='clearfix'>
                               <?php
                                    if ($pages = $_this->db->query("SELECT * FROM nav1 WHERE showhide='0' ORDER BY ordering ASC, id ASC")){

                                        while($data = $pages->fetch_assoc()){
                                            $navarray[] = $data;
                                            echo "<li><a href='".$_this->path.$data['page']."/'".($data['page']==@$_this->segments[1] ? "class='selected'" : "")."><small>".$data['sub_page_title']."</small><br />".$data['name']."</a>";
                                               
                                            echo "</li>";
                                        }
                                    }
                                ?> 
                            </ul>
                        </div>
                    </div>

                </div>
                 <div class='headerbanner'><img src='<?php echo $_this->path;?>images/TEMP_banner.png' width='1274' height='397'/></div>

            </div>

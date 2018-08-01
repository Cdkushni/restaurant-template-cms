<?php include ('includes/inc_header.php'); ?>
<div class='main_content content clearfix'>
    <div class='maincontent'>
        <h1><?php echo $template_title;?></h1>
        <?php echo $template_content;?>
    </div>
    <?php echo $template_subcontent; ?>
</div>

<div class='photo_gallery_title'></div>
   <div class='content'>

    <ul class='carousel slider1' id='slider1'>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
        <li><img src='<?php echo $_this->path;?>images/TEMP_photogallery.png' width='244' height='133' /></li>
    </ul>
  
</div>
    

<?php include ('includes/inc_footer.php');?>

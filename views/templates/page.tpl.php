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
        <li><a href='<?php echo $_this->path;?>images/full/slideshow1.jpg' rel='prettyPhoto[]'><img src='<?php echo $_this->path;?>images/slideshow1.jpg' width='244' height='133' /></a></li>
                <li><a href='<?php echo $_this->path;?>images/full/slideshow3.jpg' rel='prettyPhoto[]'><img src='<?php echo $_this->path;?>images/slideshow3.jpg' width='244' height='133' /></a></li>

        <li><a href='<?php echo $_this->path;?>images/full/slideshow2.jpg' rel='prettyPhoto[]'><img src='<?php echo $_this->path;?>images/slideshow2.jpg' width='244' height='133' /></a></li>
        <li><a href='<?php echo $_this->path;?>images/full/slideshow4.jpg' rel='prettyPhoto[]'><img src='<?php echo $_this->path;?>images/slideshow4.jpg' width='244' height='133' /></a></li>
        <li><a href='<?php echo $_this->path;?>images/full/slideshow5.jpg' rel='prettyPhoto[]'><img src='<?php echo $_this->path;?>images/slideshow5.jpg' width='244' height='133' /></a></li>
    </ul>
  
</div>
    

<?php include ('includes/inc_footer.php');?>


<div class='photo_gallery_title'></div>
<div class='photo_gallery'>
    <div class='leftgrad'></div>
    <div class='rightgrad'></div>
    <div class='slides'>
        <?php 
     echo '<div class="thumbs-wrapper">';
        echo '<div class="thumbs-shadow small left"></div>';
        echo '<div class="thumbs-shadow small right"></div>';
        
        echo '<div class="thumbs cycle-slideshow" data-cycle-speed="600" data-cycle-timeout="0" data-cycle-slides="> div" data-cycle-fx="carousel" data-cycle-carousel-vertical="false" data-cycle-carousel-offset="0" data-cycle-allow-wrap="false">';
            
            $count = 1;
            while($slide = mysql_fetch_array($slideshow_sql,1)) {
                echo '<div class="thumb picture-frame"><img src="'.$base_uri.'import/thumb/'.$slide['image'].'" data-image="'.$base_uri.'import/'.$slide['image'].'" alt="" /></div>';
                $count++;
            }
        echo '</div>';
        
        echo '<a href="#" class="cycle-prev" id="prev"></a>';
        echo '<a href="#" class="cycle-next" id="next"></a>';
        echo '<div class="var-center-hide"><div class="var-center-wrap"><div class="var-center">';
            echo '<div class="cycle-pager clearFix">';
                for($i = 0; $i < ceil(mysql_num_rows($slideshow_sql) / 6); $i++) {
                    echo '<a'.($i == 0 ? ' class="selected"' : '').'></a>';
                }
            echo '</div>';
        echo '</div></div></div>';
        
    echo '</div>';
    ?>
    </div>
    


</div>  
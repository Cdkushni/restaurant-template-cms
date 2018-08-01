             
  <div class='push'></div>
       </div>

            <div class='footer'>
                <div class='content clearfix'> 
                    <span class='f_left'>&copy; <?php echo date('Y');?> Lacie Sousa | <a href="mailto:laciekus@shaw.ca" target="_blank">laciekus@shaw.ca</a> | (780) 450-8379<br />
                    <small>Website by <a href='http://www.pixelarmy.ca/' target='_blank'>Pixel Army</a></small>
                    <img src="<?php echo $_this->path;?>images/great-lengths.png" style="display: block; margin-top: 5px;"/>
                    </span>
                    <span class='f_right'>
                        <ul>
                            <?php 
                                foreach($navarray AS $data){
                                    echo "<li><a href='".$_this->path.$data['page']."/'>".$data['name']."</a></li>";
                                }
                            ?>
                        </ul>
                    </span>
                </div>
            </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        
        <script>window.jQuery || document.write('<script src="<?php echo $_this->path;?>js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        
        <script src="<?php echo $_this->path;?>js/vendor/ddsmoothmenu/ddsmoothmenu.js"></script>
        <script src="<?php echo $_this->path;?>js/plugins.js"></script>
        <script src="<?php echo $_this->path;?>js/vendor/prettyphoto/js/jquery.prettyPhoto.js"></script>
        <script src="<?php echo $_this->path;?>js/vendor/jquery.carouselman.js"></script>
        <script src="<?php echo $_this->path;?>js/vendor/jquery.easing.min.js"></script>
        <script src="<?php echo $_this->path;?>js/main.<?php echo time();?>.js"></script>

        <script>
        ddsmoothmenu.init({
            mainmenuid: "nav",
            orientation: 'h',
            classname: 'ddsmoothmenu',
            contentsource: "markup"
        });
            var _gaq=[['_setAccount','<?php echo $_this->globals['ga_tracking'];?>'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
              $(document).ready(function(){
                $("a[rel^='prettyPhoto']").prettyPhoto({social_tools: '',deeplinking: false});
              });
        </script>
    </body>
</html>
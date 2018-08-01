<div class='subcontent'>
    <div class='col1'>
            <div class='activelink'></div>
            <ul>
                <?php 
                foreach($vars AS $key=>$data){
                    echo "<li><a href='javascript:'>".$data['name']."</a></li>";
                }
                ?>
             
            </ul>
    </div>
    <div class='col2'>
        <?php 


            $count= 0;
            foreach($vars AS $key=>$data){
        ?>

                <div id='content-<?php echo $count;?>' class='subcontent_container'>
                    <?php echo $data['content'];?>
                </div>

        <?php
            $count++;
        }
        ?>
       
    </div>
</div>
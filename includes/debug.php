<?php 
$_this  = Website::$instance;
?>
<div class='clearFix' id='debug_bottom_panel' style='background-color:#fafafa; border: 1px solid #ccc; color:#333 !important; padding: 15px; font-size: 12px; width: 960px; margin-left: auto; margin-right: auto; position:relative; bottom: 0px; margin-bottom: 20px; clear:both; '>
<h2 style='font-size: 18px; font-weight: bold; margin-bottom:14px; padding: 0 10px;'>Development Info <span class='f_right'><small>Mem: <?php echo memory_get_peak_usage();?> // Server Time: <?php echo date('H:i:s');?></small></span></h2>

<b>Segments: </b><blockquote style='padding: 10px; background-color:#fff'><?php print_r($_this->segments); ?></blockquote>

<?php 
    if(count($_SESSION)){ ?>
    <b>Session: </b><blockquote style='padding: 10px; background-color:#fff;'><?php print_r($_SESSION); ?></blockquote>
<?php } ?>

<?php
    if (count($_POST)){ ?>
<b>Post: </b><blockquote style='padding: 10px; background-color:#fff;'><?php print_r($_POST);?></blockquote>
<?php  } ?>

<?php 
    if (count($_GET)){ ?>
<b>Get: </b><blockquote style='padding: 10px; background-color:#fff;'><?php print_r($_GET);?></blockquote>
<?php } ?>

<?php 
    if (count($_this->db->get_query_profile())){ ?>
        <h3>Mysql:</h3>
            <blockquote style='padding: 10px; background-color:#fff;'><table width='100%' style='border-collapse: collapse' cellpadding='5' cellspacing='2'>
        <thead>
            <tr>
                <td style='border-bottom: 1px solid #ccc;'></td>
                <td style='border-bottom: 1px solid #ccc;'><p><b>Query</b></p></td>
    
                <td style='border-bottom: 1px solid #ccc; text-align: right' width='140px;'><p><b>Execution Time</b></p></td>
           </tr>
           <tr><td><p></p></td><td></td><td></td></tr>
           </thead>
           <tbody>
        
        <?php 
            $count = 1;
            foreach($_this->db->get_query_profile() AS $key=>$data){

                if (isset($data['query'])){
                    echo "<tr onClick='viewResultset(this)' style='cursor: pointer'><td width='30px' style='vertical-align:top; border-bottom: 1px solid #ebebeb; padding-top: 4px;'><p>".$count."</p></td><td  style='vertical-align:top; border-bottom: 1px solid #ebebeb; padding-top: 4px;'><p>".$data['query'];
                        if ($data['errors']!=""){
                            echo "<br /><small style='color:#ff0000;'>".$data['errors']."</small>"; 
                        }else{
                            //$data['deb'][0]['file'] = ''
                            echo "<br /><small>Returned <b style='font-size: 12px'>".$data['rows']."</b> Rows. No mysql errors.</small><div class='resultset' style='display: none'><h4 style='border-bottom: 1px solid #ccc;'>Debug Info</h4><small>File: ".$data['deb']['file']."<br /> Line: ".$data['deb']['line']."</small><h4 style='border-bottom:1px solid #ccc;'>Results:</h3><small>".$data['resultset']."</small></div>";    
                        }
                        
                    echo "</td><td width='50px;' align='right' style='vertical-align:top; border-bottom: 1px solid #ebebeb; padding-top: 4px;'><p>".$data['exec_time']."</p></td></tr>";
                    $count = $count+1;    
                }
            }
        ?>
        </tbody>
        </table>
        </blockquote>
            
        <?php } ?>
</div>
<script type='text/javascript'>
    function viewResultset(e){
        $(e).find('td .resultset').slideToggle(500);
    }
    $(document).ready(function(){
        $("#debug_bottom_panel").css('top',$(window).height()+'px');
    })
</script>
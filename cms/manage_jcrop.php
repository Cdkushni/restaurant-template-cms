<?php 
	
	if ($section=='Crop Image'){
		if ($_POST['submit']=='Save Image'){

			echo '<p>Cropping...</p>';
			require_once('../includes/classes/imageman.class.php');
			$img = new Imageman();
			
			$img->load($_SESSION['jcrop'][0]['img_path'].$_SESSION['jcrop'][0]['filename']);

			$img->crop($_POST['x'],$_POST['y'],$_POST['x2'],$_POST['y2']);
			$img->resize($_SESSION['jcrop'][0]['target_width'],$_SESSION['jcrop'][0]['target_height']);
			$img->save($_SESSION['jcrop'][0]['target_path'],$_SESSION['jcrop'][0]['filename']);
			$from_section = $_SESSION['jcrop'][0]['from_section'];

			//image done, any more images? 
			if (count($_SESSION['jcrop'])>1){
				$_SESSION['jcrop_success'] = ''.renderGravatar(str_replace('full/','',$_SESSION['jcrop'][0]['img_path']).$_SESSION['jcrop'][0]['filename'],50,50).'<p>Image cropped successfully.</p>';
				$_SESSION['jcrop_success_count']++;
				array_shift($_SESSION['jcrop']);

				echo "<form action='' method='POST' id='frm'>";
					echo "<input type='hidden' name='section' value='".$_POST['section']."'/>";
					echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
					echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";

				echo "</form>";

				//$_SESSION['jcrop']['success'] = 'Image cropped successfully.';
				echo "<script type='text/javascript'>";
					//echo "document.getElementById('frm').submit();";
				echo "</script>";

			}else{
				//done all images.				
				$_SESSION['jcrop_success'] = ''.renderGravatar(str_replace('full/','',$_SESSION['jcrop'][0]['img_path']).$_SESSION['jcrop'][0]['filename'],50,50).'<p>Image cropped successfully.</p>';
								$_SESSION['jcrop_success_count']++;

				echo "<form action='' method='POST' id='frm'>";
					echo "<input type='hidden' name='section' value='".$from_section."'/>";
					echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
					echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";

				echo "</form>";
				
				echo "<script type='text/javascript'>";
					echo "document.getElementById('frm').submit();";
				echo "</script>";
			}


		}else if ($_POST['submit']=='Do Not Crop'){
			echo '<p>Saving...</p>';
			$from_section = $_SESSION['jcrop'][0]['from_section'];
			//image done, any more images? 
			if (count($_SESSION['jcrop'])>1){
				$_SESSION['jcrop_success'] = ''.renderGravatar(str_replace('full/','',$_SESSION['jcrop'][0]['img_path']).$_SESSION['jcrop'][0]['filename'],50,50).'<p>Image saved successfully.</p>';
				$_SESSION['jcrop_success_count']++;

				array_shift($_SESSION['jcrop']);

				echo "<form action='' method='POST' id='frm'>";
					echo "<input type='hidden' name='section' value='".$_POST['section']."'/>";
					echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
					echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
					echo "<input type='submit' name='submit' style='visibility: hidden'/>";
				echo "</form>";

				echo "<script type='text/javascript'>";
					echo "document.getElementById('frm').submit();";
				echo "</script>";

			}else{
				//done all images.				
				$_SESSION['jcrop_success'] = ''.renderGravatar(str_replace('full/','',$_SESSION['jcrop'][0]['img_path']).$_SESSION['jcrop'][0]['filename'],50,50).'<p>Image saved successfully.</p>';
				unset($_SESSION['jcrop'][0]);
				
				echo "<form action='' method='POST' id='frm'>";
					echo "<input type='hidden' name='section' value='".$from_section."'/>";
					echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
					echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";

				echo "</form>";
				
				echo "<script type='text/javascript'>";
					echo "document.getElementById('frm').submit();";
				echo "</script>";
			}
		}else{


		if (!$errors){
			echo "<form action='' method='POST'>";
			echo "<input type='hidden' name='username' value='".$_POST['username']."'/>";
			echo "<input type='hidden' name='password' value='".$_POST['password']."'/>";
			echo "<input type='hidden' name='section' value='".$_POST['section']."'/>";
			echo "<input type='submit' name='submit' style='visibility: hidden'/>";

			echo "<table width='100%' style='float: left'>";
				echo "<tr>";
				echo "<td><div style='width: 550px; overflow: hidden;'/><img src='".$_SESSION['jcrop'][0]['img_path'].$_SESSION['jcrop'][0]['filename']."' class='cropit'/></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td align='left'>

							<div id='inputs'>
								<input type='hidden' id='current' name='current' value='0'/>
								<input type='hidden' id='filename' name='filename' value='".$_SESSION['jcrop'][0]['img_path'].$_SESSION['jcrop'][0]['filename']."'/>
								<input type='hidden' id='x' name='x' value=''/>
								<input type='hidden' id='y' name='y' value=''/>
								<input type='hidden' id='x2' name='x2' value''/>
								<input type='hidden' id='y2' name='y2' value=''/>
								<input type='hidden' id='h' name='h' value=''/>
								<input type='hidden' id='w' name='w' value=''/>
								
								";
							echo "</div>";
							echo "<div style='width: 600px; float: left;' id='sizeDisclaimer'><div id='alert_info'><p>Notice</p></div><div id='alert_message'><p style='padding: 0px; margin: 0px'>The current selection is smaller than the target size. Final image may appear blurry.</p></div></div>";

				echo "</td></tr><td style='border-top: 1px solid #ccc; padding-top: 10px;' align='right'><input type='submit' name='submit' value='Do Not Crop'/><input type='submit' name='submit' id='savephoto' value='Save Image'/></td></tr></table></form>";

			?>

			<script type='text/javascript'>
			$(document).ready(function(){

					$('.cropit').Jcrop({
						onChange: showPreview,
						onSelect: showPreview,
						setSelect: [0,0,50,50],
						aspectRatio: <?php echo $_SESSION['jcrop'][0]['target_width'];?>/<?php echo $_SESSION['jcrop'][0]['target_height'];?>,
						boxWidth: 550
						
					},function(){
						jcrop_api = this;
					});
				});
				function showPreview(coords)
				{					
					$('#x').val(coords.x);
					$('#y').val(coords.y);
					$('#x2').val(coords.x2);
					$('#y2').val(coords.y2);
					$('#w').val(coords.w);
					$('#h').val(coords.h);
					if (coords.w<<?php echo $_SESSION['jcrop'][0]['target_width'];?> || coords.h<<?php echo $_SESSION['jcrop'][0]['target_height'];?>){
						$("#sizeDisclaimer").stop().animate({opacity: 1},100);
					}else{
						$("#sizeDisclaimer").stop().animate({opacity: 0},100);
					}
				}

			</script>


			<?php

}
		}
	}

?>
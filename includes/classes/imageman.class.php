<?php 
//------------- usage -----------
//
//	$imageman = new imageman();
//		
//	$image->load($_FILES['image']['tmp_name']);
//	$image->crop(0,0,100,100);
//	$image->fit(100,100);
//	$image->save('images/whatevernameyouwouldlike.jpg');
//-------------------------------
class Imageman {
	function __construct(){
		ini_set('memory_limit','86M');

	}
	// Loads image into memory
	public function load($filename) {
		$this->filename = $filename;
		if(file_exists($filename)) {
			$image_info = getimagesize($filename);
			$this->image_type = $image_info[2];
			$this->image_tmp = $filename;
			if($this->image_type == IMAGETYPE_JPEG) {
	 			$this->image = imagecreatefromjpeg($filename);
	 			$this->default_image_save_type = IMAGETYPE_JPEG;
			} else if($this->image_type == IMAGETYPE_GIF) {
	 			$this->image = imagecreatefromgif($filename);
	 			$this->default_image_save_type = IMAGETYPE_GIF;
			} else if($this->image_type == IMAGETYPE_PNG) {
		        $this->image = imagecreatefrompng($filename);
		        imagealphablending($this->image, false);
		        imagesavealpha($this->image, true);
		        $this->default_image_save_type = IMAGETYPE_PNG;
			} else {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}
	//
	public function valid_image($maxsize='2097152',$type=IMAGETYPE_JPEG){
		if ($this->image_type!=$type){
			return 'type error';
		}else if (filesize>$maxsize){
			return 'size error';
		}else{
			return 1;
		}
	}
	// Saves any image (set image type)
	public function save($dir, $filename, $overwrite=true, $image_type='', $compression=100, $permissions=null) {
		
		// Create a new UNIQUE name
		if(file_exists($dir.$filename) && !$overwrite) {
			$file_extension = substr($filename, strrpos($filename, '.'));
			$filename = str_replace($file_extension, '', $filename) . '_' . microtime() . $file_extension;
		}
		if($image_type == '') { 
			$image_type = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
		 }
		
		// Save the image with the specific format
		if($image_type == IMAGETYPE_JPEG || $image_type=='jpg') {
			imagejpeg($this->image,$dir.$filename,$compression);
		} else if($image_type == IMAGETYPE_GIF  || $image_type== 'gif') {
			imagegif($this->image,$dir.$filename);
		} else if($image_type == IMAGETYPE_PNG  || $image_type== 'png') {
			imagepng($this->image,$dir.$filename);
		}
		if($permissions != null) {
			chmod($dir.$filename,$permissions);
		}
		
		// Return the image name that was saved
		return $filename;
  	}
	
	// Outputs image to the buffer
	public function output($image_type=IMAGETYPE_JPEG,$headers=false) {
		
		if($image_type == IMAGETYPE_JPEG || $image_type=='jpg') {
			if($headers)
				header('Content-Type: image/jpeg');
				
			imagejpeg($this->image);
		} else if($image_type == IMAGETYPE_GIF ||  $image_type=='gif') {
			if($headers)
				header('Content-Type: image/gif');
				
			imagegif($this->image);
		} else if($image_type == IMAGETYPE_PNG || $image_type=='png') {
			if($headers)
				header('Content-Type: image/png');
				
			imagepng($this->image);
		}
	}
	
	// Gets width of the image
	public function getWidth() {
      	return imagesx($this->image);
	}
	
	// Gets width of the image
	public function getHeight() {
		return imagesy($this->image);
	}
	
	// Get the bytes of the image
	public function getBytes() {
		return filesize($this->image_tmp);
	}
	// Resizes image to height
	public function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	
 	// Resizes image to width
	public function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}
	
 	// Scales an image
	public function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}

	// Resizes an image to ensure it fills a box
	public function fill($w,$h) {
		$dims = $this->dynamicScaleToFill($w,$h);
		$this->resize($dims['w'],$dims['h']);
	}
	
	// Will just return the dimensions (and offset) required to dynamically scale image to fill box
	public function dynamicScaleToFill($w,$h, $file='') {
		if($file=='') {
			$img_w = $this->getWidth();
			$img_h = $this->getHeight();
		} else {
			$dims = getimagesize($file);	
			$img_w = $dims[0];
			$img_h = $dims[1];
		}
		
		// Calculate new widths/heights
		if($img_w >= $img_h) {
			$ratio = $w/$img_w;
			$new_w = $w;
			$new_h = $img_h*$ratio;
			
			if($new_h < $h) {
				// New height is smaller than box allowance
				$ratio = $h/$img_h;
				$new_h = $h;
				$new_w = $img_w*$ratio;
			}
			
		} else if($img_h > $img_w) {
			$ratio = $h/$img_h;
			$new_h = $h;
			$new_w = $img_w*$ratio;
			
			if($new_w < $w) {
				// New width is smaller than box allowance	
				$ratio = $w/$img_w;
				$new_w = $w;
				$new_h = $img_h*$ratio;
			}
		}
		
		// Find offset numbers		
		return array('w'=>$new_w, 'h'=>$new_h, 'offset_w'=>($new_w-$w),'offset_h'=>($new_h-$h));
	}
	
	// Fits image inside box
	public function fit($w,$h) {
		if($this->getWidth() > $h) {
			$this->resizeToWidth($w);	
			if($this->getHeight() > $h) {
				$this->resizeToHeight($h);	
			}
		} else {
			$this->resizeToHeight($h);
			if($this->getWidth() > $w) {
				$this->resizeToWidth($w);	
			}
		}

	}
	
	// Smart crop
	public function smartCrop($w,$h) {
		// Shrinks image, but ensures box is filled.
		$this->fill($w,$h);
		$woffset = ($this->getWidth()-$w)/2;
		$hoffset = ($this->getHeight()-$h)/2;
		
		if($this->getWidth()>$w) {
			// Width larger
			$this->crop($woffset,0,($this->getWidth()-$woffset),$this->getHeight());
		} else if($this->getHeight()>$h) {
			$this->crop(0,$hoffset,$this->getWidth(),($this->getHeight()-$hoffset));
		}
	}
	
	// Crops an image
	public function crop($x,$y,$x2,$y2) {
	
		// Get crop width;
		$w = $x2 - $x;
		if($x2 < $x)
			$w = $x-$x2;
			
		// Get crop height;
		$h = $y2 - $y;
		if($y2 < $y)
			$h = $y-$y2;
		
		$new_image = imagecreatetruecolor($w,$h);
		if($this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG) {
			$current_transparent = imagecolortransparent($this->image);
			if($current_transparent != -1) {
				$transparent_color = imagecolorforindex($this->image,$current_transparent);
				$current_transparent = imagecolorallocate($new_image,$transparent_color['red'],$transparent_color['green'], $transparent_color['blue']);
				imagefill($new_image,($x*-1),($y*-1),$current_transparent);
			} else if($this->image_type == IMAGETYPE_PNG) {
				imagealphablending($new_image,false);
				$color = imagecolorallocatealpha($new_image,0,0,0,127);
				imagefill($new_image,($x*-1),($y*-1),$color);
				imagesavealpha($new_image,true);
			}
				
		}		
		imagecopyresampled($new_image,$this->image,0,0,$x,$y,$w,$h,$w,$h);
		$this->image = $new_image;													 
	}	
	
	// Resizes an image
	public function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		if($this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG) {
			$current_transparent = imagecolortransparent($this->image);
			if($current_transparent != -1) {
				$transparent_color = imagecolorsforindex($this->image, $current_transparent);
				$current_transparent = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagefill($new_image, 0, 0, $current_transparent);
				imagecolortransparent($new_image, $current_transparent);
			} else if($this->image_type == IMAGETYPE_PNG) {
				imagealphablending($new_image, false);
				$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
				imagefill($new_image, 0, 0, $color);
				imagesavealpha($new_image, true);
			}
		}
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;	
	}
}

?>
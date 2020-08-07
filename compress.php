public function compressImage($fileName)
  { 
    ini_set("max_execution_time",120); 
      if(file_exists($fileName))
      {
        $new_width=700;
        $size = round(fileSize($fileName)/1000); 
        list($width) = getimagesize($fileName); 
        if($size > 100 || $width > $new_width)
        { 
          $this->resize($fileName,$new_width);
        } 
      }
  }

  private function resize($fileName,$new_width){
    list($width,$height,$type) = getimagesize($fileName);
    $new_height = round($height*$new_width/$width);
    $old_image = imagecreatetruecolor($new_width,$new_height);
    switch($type){
      case IMAGETYPE_JPEG:
        $new_image = imagecreatefromjpeg($fileName);
        break;
      case IMAGETYPE_GIF:
        $new_image = imagecreatefromgif($fileName);
        break;
      case IMAGETYPE_PNG:
        imagealphablending($old_image, true);
        imagesavealpha($old_image, false);
        $new_image = imagecreatefrompng($fileName);
        break;
    }
    switch($type){
      case IMAGETYPE_JPEG:
        imagecopyresampled($old_image,$new_image,0,0,0,0,$new_width,$new_height,$width,$height);
        imagejpeg($old_image,$fileName);
        break;
      case IMAGETYPE_GIF:
        $bgcolor = imagecolorallocatealpha($new_image,0,0,0,127);
        imagefill($old_image, 0, 0, $bgcolor);
        imagecolortransparent($old_image,$bgcolor);
        imagecopyresampled($old_image,$new_image,0,0,0,0,$new_width,$new_height,$width,$height);
        imagegif($old_image,$fileName);
        break;
      case IMAGETYPE_PNG:
        $whitecolor =imagecolorallocate($new_image, 255, 255, 255);
        imagefill($old_image, 0, 0, $whitecolor);
        imagecopyresampled($old_image,$new_image,0,0,0,0,$new_width,$new_height,$width,$height);
        imagejpeg($old_image,$fileName);
        break;
    }
    imagedestroy($old_image);
    imagedestroy($new_image);
  }

<?

//file function

// make folder if not exist
function makeDirectory($directory)
{
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

}

// remove folder and files
function recursiveRemoveDirectory($directory)
{
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) {
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}

function makeCleanDirectory($directory) // remove the existing file in directory
{
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}
else{
  recursiveRemoveDirectory($directory);
  mkdir($directory, 0777, true);
}

}

// resize to 0 : change to original size
function resize_img($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default:
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    if($newWidth == 0){ // set to original size but width not exceed 1000px

      if($width > 900){
        $newWidth = 900;
        $newHeight = ($height / $width) * $newWidth;
      }
      else{
        $newHeight = $height;
        $newWidth = $width;
      }

    }
    else{
      $newHeight = ($height / $width) * $newWidth;
    }

    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {

            unlink($targetFile);
    }
    //$image_save_func($tmp, "$targetFile.$new_image_ext");
    $image_save_func($tmp, "$targetFile");
    //return $new_image_ext;
}
?>

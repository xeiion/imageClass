<?php

/* ------------------------------------------------------------------------------
 * * File:	ImageClass.php
 * * Class:       image Class
 * * Description:	Php image editor
 * * Version:		1.0
 * * Updated:     30-maj-2015
 * * Author:	Jack petersen
 * * Homepage:	jack-petersen.com
 * *------------------------------------------------------------------------------ */

class ImageClass {

    public $image;
    private $CreateImage;

    public function __construct() {
        
    }

    function getImage() {
        return $this->image;
    }

    function setImage($image) {
        $this->image = $image;

        /* Retrive extendsion from image and check what extension */
        $FullPath = pathinfo($this->image);
        switch ($FullPath['extension']) {
            case 'jpg':
            case 'jpeg':
                $this->CreateImage = imagecreatefromjpeg($this->image);
                break;
            case 'gif':
                $this->CreateImage = imagecreatefromgif($this->image);
                break;
            case 'png':
                $this->CreateImage = imagecreatefrompng($this->image);
                break;
            default:
                $this->CreateImage = false;
                break;
        }
    }

    public function upload($file, $maxSize = 4325720, $Target_name = null, $target_path = '') {
        /* Check if file is empty or not */

        if (!empty($file)) {
            if ($file['fil']['error'] == 4) {
                throw new Exception('file cannot be empty');
            }

            /* Check if the file is an array */
            if (!is_array($file)) {
                throw new Exception('File gotta be an array');
            }

            $Mime = array(
                'image/jpeg',
                'image/gif',
                'image/png'
            );

            /* Check if the mime type is supported from Mime array */

            if (!in_array($file['fil']['type'], $Mime)) {
                throw new Exception("File type isnt supported");
            }

            /* Check if the filesize is less then max filesize else throw an Exception */

            if ($file['fil']['size'] > $maxSize) {
                throw new Exception("File is too big");
            }

            /* Checking if New file name is given else use default file name */

            if ($Target_name == null) {
                $Target_name = $file['fil']['name'];
            }

            /* upload image to server  */

            if (move_uploaded_file($file['fil']['tmp_name'], $target_path . $Target_name)) {
                return $target_path . $Target_name;
            } else {
                return 'failed to upload';
            }
        }
    }

    public function Height() {
        /* Retrive Height of image and return it  */

        $Height = getimagesize($this->image)[1];

        return $Height;
    }

    public function Width() {
        /* Retrive Width of image and return it  */

        $Width = getimagesize($this->image)[0];

        return $Width;
    }

    public function Imagesize() {
        /* Retrive full resolution of image  and return it */

        $dimension = getimagesize($this->image);

        $dimension = $dimension[0] . ' x ' . $dimension[1];
        return $dimension;
    }

    public function Extension() {
        /* Retrive extension of image and return it */

        $FullPath = pathinfo($this->image);
        $Extension = $FullPath['extension'];

        return $Extension;
    }

    public function mime() {
        /* Retrive mime of image and return it */
        $mime = getimagesize($this->image)['mime'];

        return $mime;
    }

    public function resize($NewWidth = null, $NewHeight = null) {
        /* getting width and height of image */
        list($width, $height) = getimagesize($this->image);

        /* Check if width and height is null  */

        if ($NewHeight == null AND $NewWidth == null) {
            throw new Exception('Cannot resize without height or width');
        }

        /* checking if height is a number */

        if (!is_numeric($NewHeight)) {
            $NewHeight = $height;
        }

        /* checking if width is a number */

        if (!is_numeric($NewWidth)) {

            $NewWidth = $width;
        }

        /* Create an image */

        $ImageResize = imagecreatetruecolor($NewWidth, $NewHeight);

        /* Resizeing image with new values */
        imagecopyresized($ImageResize, $this->CreateImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $width, $height);
        $this->CreateImage = $ImageResize;

        return true;
    }

    public function rotate($rotate = null) {
        if (!$rotate OR ! is_numeric($rotate)) {
            throw new Exception('Require rotate value');
        }

        $rotate = imagerotate($this->CreateImage, $rotate, 0);

        $this->CreateImage = $rotate;

        return true;
    }

    public function pixelate($pixel = 1) {
        /* checking if var is a number */

        if (!is_numeric($pixel)) {
            throw new Exception('Has to be a number');
        }

        /* checking if var is higher then 15 */

        if ($pixel > 15) {
            throw new Exception('pixelate only support 1-15 levels');
        }

        /* Pixelate the image depend on the level var given */

        imagefilter($this->CreateImage, IMG_FILTER_PIXELATE, $pixel);

        return true;
    }

    public function flip($flip = null) {
        /* check if var contain value */

        if (!$flip) {
            throw new Exception('Require flip value');
        }

        /* checking if var contains horizontal or vertical value */

        if ($flip == 'h' OR $flip == 'v') {
            if ($flip == 'v') {
                /* apply vertical flip to image */
                $flip = imageflip($this->CreateImage, IMG_FLIP_HORIZONTAL);
            }
            if ($flip == 'h') {
                /* apply horizontal flip to image */
                $flip = imageflip($this->CreateImage, IMG_FLIP_HORIZONTAL);
            }
        } else {
            throw new Exception('flip require value');
        }

        $this->image = $flip;
        return true;
    }

    public function blur($blur = 1) {
        /* checking if var is a number */

        if (!is_numeric($blur)) {
            throw new Exception('blur value has to be a number');
        }

        /* checking if var is higher then 20 */

        if ($blur > 20) {
            throw new Exception('Blur level effect only goes from 1-20');
        }

        /* apply blur depend on level effect given  */
        for ($i = 1; $i < $blur; $i++) {
            imagefilter($this->CreateImage, IMG_FILTER_GAUSSIAN_BLUR);
        }
        return true;
    }

    public function brightness($bright = 0) {
        /* checking if var is a number */

        if (!is_numeric($bright)) {
            throw new Exception('Bright value gotta be a number');
        } else {
            /* return image with new brightness */
            imagefilter($this->CreateImage, IMG_FILTER_BRIGHTNESS, $bright);
        }
        return true;
    }

    public function contrast($contrastVal = 0) {
        /* checking if contrast is betwend 100 and -100 */
        if ($contrastVal > 100 OR $contrastVal < -100) {
            throw new Exception('Contrast value is betwend -100 and 100');
        }

        /* checking if contrast is a number */

        if (!is_numeric($contrastVal)) {
            throw new Exception('Contrast gotta be a number');
        } else {
            /* return image with new Contrast */
            imagefilter($this->CreateImage, IMG_FILTER_CONTRAST, $contrastVal);
        }
        return true;
    }

    public function grayscale() {
        /* return grayscaled image */

        imagefilter($this->CreateImage, IMG_FILTER_GRAYSCALE);
        return true;
    }

    public function gamma($gammaRatio = 1) {
        /*  checking if gamma is a number value */

        if (!is_numeric($gammaRatio)) {
            throw new Exception('gamma gotta be a number');
        }

        /* Checking if gamma is between 100 and -100 */

        if ($gammaRatio > 100 OR $gammaRatio < -100) {
            throw new Exception('Gamma only goes between -100 and 100');
        }

        /* return image applied gamma */
        imagegammacorrect($this->CreateImage, 1.0, $gammaRatio);
        return true;
    }

    public function AddText($text = null, $font = null, $fontsize = 13, $option = 'bottom-left', $rgb = '255,255,255') {
        /* getting cordinates value from option */
        $option = explode('-', $option);

        /* splitting rgb color code to string */
        $Colors = explode(',', $rgb);

        /* gives error if explode string is more then 3 parts of rgb */
        if (count($Colors) > 3) {
            throw new Exception('Only 3 value is valid in rgb');
        }

        /* getting information from text */
        $bbox = imagettfbbox(0, 0, $font, $text);

        /* getting  width and height of image */
        list($width, $height) = getimagesize($this->image);

        /* getting text width */
        $text_width = $bbox[2] - $bbox[0];
        /* getting text height */
        $text_height = $bbox[3] - $bbox[1];

        /* checking option 1 is empty */
        if (empty($option[1])) {
            throw new Exception('Gotta fill second parameter for text position');
        } elseif ($option[1] != 'center' AND $option[1] != 'right' AND $option[1] != 'left') {
            /* checking if option 1 contains any keyword of values */
            throw new Exception('Wrong parameter for position Y');
        } else {
            /* settings values depend on keyword */

            if ($option[1] == 'left') {
                $y = ($text_width) + 20;
            } elseif ($option[1] == 'right') {
                $y = ($width) - ($text_width) - ($fontsize * 3);
            } elseif ($option[1] == 'center') {
                $y = ($width / 2) - ($text_width / 2);
            }
        }

        /* checking if option 0 contains any of the keywords */

        if ($option[0] != 'middle' AND $option[0] != 'bottom' AND $option[0] != 'top') {
            throw new Exception('Wrong parameter for position X');
        } else {
            /* settings values depend on given keyword */

            if ($option[0] == 'middle') {
                $x = ($height / 2) - ($text_height / 2);
            } elseif ($option[0] == 'bottom') {
                $x = ($height / 1) - ($text_height / 2) - 25;
            } elseif ($option[0] == 'top') {
                $x = ($height / 6) - ($text_height / 6) - 5;
            }
        }

        /* check if rgb var is numbers */

        if (!is_numeric($Colors[0]) OR ! is_numeric($Colors[1]) OR ! is_numeric($Colors[2])) {
            throw new Exception('has to be a rgb number');
        }

        /* setting color of text from rgb code */
        $white = imagecolorallocate($this->CreateImage, $Colors[0], $Colors[1], $Colors[2]);

        /* apply fontSize, font color, font text, to given position y and x */
        imagettftext($this->CreateImage, $fontsize, 0, $y, $x, $white, $font, $text);
        return true;
    }

    public function crop($x = 334, $y = 178, $Target_Width = 79, $Target_Height = 75) {
        /* Checking if any of the given variable is a number */
        if (!is_numeric($x) OR ! is_numeric($y) OR ! is_numeric($Target_Width) OR ! is_numeric($Target_Height)) {
            throw new Exception('Cordinates has to be numbers');
        }

        /* Create an image */
        $ImageResize = imagecreatetruecolor($Target_Width, $Target_Height);

        /* croping the image with x1 y1 width and height var */
        imagecopyresampled($ImageResize, $this->CreateImage, 0, 0, $x, $y, $Target_Width, $Target_Height, $Target_Width, $Target_Height);
        $this->CreateImage = $ImageResize;

        return true;
    }

    public function save($name, $quality = 100) {
        /* Check if var filename is empty */
        if (empty($name)) {
            throw new Exception('Need a name for image');
        } else {
            /* check if quality is a number */
            if (!is_numeric($quality)) {
                throw new Exception('Quality gotta be a number');
            } else {
                /* Creating the Image */
                imagejpeg($this->CreateImage, $name, $quality);
            }
        }
        return true;
    }

    public function destroy() {
        /* free memory with the image */

        imagedestroy($this->CreateImage);
        return true;
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author jack
 */
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
        if (!empty($file)) {
            if ($file['fil']['error'] == 4) {
                throw new Exception('file cannot be empty');
            }

            if (!is_array($file)) {
                throw new Exception('File gotta be an array');
            }

            $Mime = array(
                'image/jpeg',
                'image/gif',
                'image/png'
            );

            if (!in_array($file['fil']['type'], $Mime)) {
                throw new Exception("File type isnt supported");
            }

            if ($file['fil']['size'] > $maxSize) {
                throw new Exception("File is too big");
            }

            if ($Target_name == null) {
                $Target_name = $file['fil']['name'];
            }

            if (move_uploaded_file($file['fil']['tmp_name'], $target_path . $Target_name)) {
                echo $target_path . $Target_name;
            }
        }
    }

    public function Height() {
        $Height = getimagesize($this->image)[1];

        return $Height;
    }

    public function Width() {
        $Width = getimagesize($this->image)[0];

        return $Width;
    }

    public function Imagesize() {
        $dimension = getimagesize($this->image);

        $dimension = $dimension[0] . ' x ' . $dimension[1];
        return $dimension;
    }

    public function Extension() {
        $FullPath = pathinfo($this->image);
        $Extension = $FullPath['extension'];

        return $Extension;
    }

    public function mime() {
        $mime = getimagesize($this->image)['mime'];

        return $mime;
    }

    public function resize($NewWidth = null, $NewHeight = null) {
        list($width, $height) = getimagesize($this->image);

        if ($NewHeight == null AND $NewWidth == null) {
            throw new Exception('Cannot resize without height or width');
        }
        if (!is_numeric($NewHeight)) {
            $NewHeight = $height;
        }

        if (!is_numeric($NewWidth)) {

            $NewWidth = $width;
        }

        $ImageResize = imagecreatetruecolor($NewWidth, $NewHeight);

        imagecopyresized($ImageResize, $this->CreateImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $width, $height);
        $this->CreateImage = $ImageResize;
    }

    public function rotate($rotate = null) {
        if (!$rotate OR ! is_numeric($rotate)) {
            throw new Exception('Require rotate value');
        }

        $rotate = imagerotate($this->CreateImage, $rotate, 0);

        $this->CreateImage = $rotate;
    }

    public function pixelate($pixel = 1) {
        if (!is_numeric($pixel)) {
            throw new Exception('Has to be a number');
        }
        if ($pixel > 15) {
            throw new Exception('pixelate only support 1-15 levels');
        }
        imagefilter($this->CreateImage, IMG_FILTER_PIXELATE, $pixel);
    }

    public function flip($flip = null) {
        if (!$flip) {
            throw new Exception('Require flip value');
        }

        if ($flip == 'h' OR $flip == 'v') {
            if ($flip == 'v') {
                $flip = imageflip($this->CreateImage, IMG_FLIP_HORIZONTAL);
            }
            if ($flip == 'h') {
                $flip = imageflip($this->CreateImage, IMG_FLIP_HORIZONTAL);
            }
        } else {
            throw new Exception('flip require value');
        }

        $this->image = $flip;
    }

    public function blur($blur = 1) {
        for ($i = 1; $i < $blur; $i++) {
            imagefilter($this->CreateImage, IMG_FILTER_GAUSSIAN_BLUR);
        }
    }

    public function brightness($bright = 0) {
        if (!is_numeric($bright)) {
            throw new Exception('Bright value gotta be a number');
        } else {
            imagefilter($this->CreateImage, IMG_FILTER_BRIGHTNESS, $bright);
        }
    }

    public function contrast($contrastVal = 0) {
        if ($contrastVal > 100 OR $contrastVal < -100) {
            throw new Exception('Contrast value is betwend -100 and 100');
        }
        if (!is_numeric($contrastVal)) {
            throw new Exception('Contrast gotta be a number');
        } else {
            imagefilter($this->CreateImage, IMG_FILTER_CONTRAST, $contrastVal);
        }
    }

    public function grayscale() {
        imagefilter($this->CreateImage, IMG_FILTER_GRAYSCALE);
    }

    public function gamma($gammaRatio = 1) {
        imagegammacorrect($this->CreateImage, 1.0, $gammaRatio);
    }

    public function AddText($text = null, $font = null, $fontsize = 13, $option = 'bottom-left', $rgb = '255,255,255') {
        $option = explode('-', $option);
        $Colors = explode(',', $rgb);
        if (count($Colors) > 3) {
            throw new Exception('Only 3 value is valid in rgb');
        }
        $bbox = imagettfbbox(0, 0, $font, $text);
        list($width, $height, $type, $attr) = getimagesize($this->image);

        $text_width = $bbox[2] - $bbox[0];
        $text_height = $bbox[3] - $bbox[1];

        if (empty($option[1])) {
            throw new Exception('Gotta fill second parameter for text position');
        } elseif ($option[1] != 'center' AND $option[1] != 'right' AND $option[1] != 'left') {
            throw new Exception('Wrong parameter for position Y');
        } else {
            if ($option[1] == 'left') {
                $y = ($text_width) + 20;
            } elseif ($option[1] == 'right') {
                $y = ($width) - ($text_width) - ($fontsize * 3);
            } elseif ($option[1] == 'center') {
                $y = ($width / 2) - ($text_width / 2);
            }
        }
        if ($option[0] != 'middle' AND $option[0] != 'bottom' AND $option[0] != 'top') {
            throw new Exception('Wrong parameter for position X');
        } else {
            if ($option[0] == 'middle') {
                $x = ($height / 2) - ($text_height / 2);
            } elseif ($option[0] == 'bottom') {
                $x = ($height / 1) - ($text_height / 2) - 25;
            } elseif ($option[0] == 'top') {
                $x = ($height / 6) - ($text_height / 6) - 5;
            }
        }

        if (!is_numeric($Colors[0]) OR ! is_numeric($Colors[1]) OR ! is_numeric($Colors[2])) {
            throw new Exception('has to be a rgb number');
        }
        $white = imagecolorallocate($this->CreateImage, $Colors[0], $Colors[1], $Colors[2]);

        imagettftext($this->CreateImage, $fontsize, 0, $y, $x, $white, $font, $text);
    }

    public function crop($target_width, $target_height) {
//         list($width, $height) = getimagesize($this->image);
//         
//         $Imagecrop = imagecreatetruecolor($width, $height);
//         imagecopyresized($Imagecrop, $this->CreateImage, 0, 0, $target_width, $target_height, $width, $height, $width, $height);
//         imagecopyresized($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
    }

    public function save($name, $quality = 60) {
        if (empty($name)) {
            throw new Exception('Need a name for image');
        } else {
            if (!is_numeric($quality)) {
                throw new Exception('Quality gotta be a number');
            } else {
                imagejpeg($this->CreateImage, $name, $quality);
            }
        }
    }

    public function destroy() {
        imagedestroy($this->CreateImage);
    }

}

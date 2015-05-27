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

    public function blur() {
        imagefilter($this->CreateImage, IMG_FILTER_GAUSSIAN_BLUR);
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

    public function AddText($text = null, $font = null, $fontsize = 13, $option = 'center', $rgb = '255,255,255') {
        //unfinished
        $Colors = explode(',', $rgb);
        if (count($Colors) > 3) {
            throw new Exception('Only 3 value is valid in rgb');
        }

        if (!is_numeric($Colors[0]) OR ! is_numeric($Colors[1]) OR ! is_numeric($Colors[2])) {
            throw new Exception('has to be a rgb number');
        }
        $white = imagecolorallocate($this->CreateImage, $Colors[0], $Colors[1], $Colors[2]);


        imagettftext($this->CreateImage, $fontsize, 0, 75, 300, $white, $font, $text);
    }

    public function crop() {
        
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

<?php
namespace Techo\Image;

interface IImage
{
    public function getData();
    public function output();
    public function resize($srcWPos = 0, $srcHPos = 0, $dstWidth = 0, $dstHeight = 0, $path = null);
    public function setImage($imageSrc);
    public function merge($imgSrc, $dstWPos = 0, $dstHPos = 0, $pct = 0);
    public function destory();
}
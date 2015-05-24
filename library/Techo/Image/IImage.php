<?php
namespace Techo\Image;
interface IImage
{
    public function resize($width, $height, $path = null){}
	public function getData(){}
	public function setData($data){}
	public function setImage($imageSrc){}
	public function getImage(){}
	public function output(){}
}
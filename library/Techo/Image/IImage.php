<?php
namespace Techo\Image;
interface IImage
{
    public function resize($width, $height, $path = null);
	public function getData();
	public function setImage($imageSrc);
	public function output();
	public function destroy();
}
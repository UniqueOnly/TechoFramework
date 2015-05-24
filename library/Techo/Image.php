<?php
namespace Techo;
/**
 * 图像处理外观类
 * @author unique@hiunique.com
 * @copyright 2015-5-24
 */
class Image implements \Techo\Image\IImage
{
    public function getData()
    {
        
    }

    public function getImage()
    {
        // TODO Auto-generated method stub
        
    }

    public function output()
    {
        self::toResize($imgSrc, $width, $height);
    }

    public function resize($width, $height, $path = null)
    {
        // TODO Auto-generated method stub
        
    }

    public function setData($data)
    {
        // TODO Auto-generated method stub
        
    }

    public function setImage($imageSrc)
    {
        // TODO Auto-generated method stub
        
    }
    
    public static function toResize($imgSrc, $width, $height, $path = null)
    {
        if (is_file($imgSrc)) {
            $imgTypeCons = exif_imagetype($imgSrc);
            $imgType = substr(image_type_to_extension($imgTypeCons), 1);
            $createFunc = 'imagecreatefrom' . $imgType;
            if (function_exists($createFunc)) {
                $img = $createFunc($imgSrc);
                $imgInfo = getimagesize($imgSrc);
                $dstImg = imagecreatetruecolor($width, $height);
                imagecopyresampled($dstImg, $dstImg, 0, 0, 0, 0, $width, $height, $imgInfo[0], $imgInfo[1]);
                $dstFunc = 'image' . $imgType;
                if ($path) {
                    header('Content-type:' . image_type_to_mime_type($imgTypeCons));
                }
                $dstFunc($dstImg, $path);
                imagedestroy($img);
                imagedestroy($dstImg);
            } else {
                throw new \Techo\Image\Exception("The image type can't be operated");
            }
        } else {
            throw new \Techo\Image\Exception("The image isn't exist");
        }  
    }
    	
}

<?php
namespace Techo;
/**
 * 图像处理外观类
 * @author unique@hiunique.com
 * @copyright 2015-5-24
 */
class Image implements \Techo\Image\IImage
{
    
    protected $_resource = null;
    protected $_imgInfo = null;
    protected $_path = null;
    
    public function __construct($imgSrc = null)
    {
        if ($imgSrc && is_file($imgSrc)) {
            $imgInfo = self::getImgInfo($imgSrc);
            $func = 'imagecreatefrom' . $imgInfo['type'];
            if (function_exists($func)) {
                $this->_resource = $func($imgSrc);
                $this->_path = $imgSrc;
                $this->_imgInfo = $imgInfo;
            } else {
                throw new \Techo\Image\Exception("The image type can't be operated!");
            }
        } else {
            throw new \Techo\Image\Exception("The image isn't exist!");
        }
    }
    
    public function getData()
    {
        if ($this->_path) {
            return file_get_contents($this->_path);
        } else {
            throw new \Techo\Image\Exception("No image can be operated!");
        }
        
    }

    public function output()
    {
        if ($this->_resource) {
            header('Content-type:' . $this->_imgInfo['mime']);
            $func = 'image' . $this->_imgInfo['type'];
            $func($this->_resource);
        } else {
            throw new \Techo\Image\Exception("No image can be operated!");
        }
        
    }

    public function resize($width, $height, $path = null)
    {
        
    }

    public function setImage($imageSrc)
    {
    if ($imgSrc && is_file($imgSrc)) {
            $imgInfo = self::getImgInfo($imgSrc);
            $func = 'imagecreatefrom' . $imgInfo['type'];
            if (function_exists($func)) {
                $this->_resource = $func($imgSrc);
                $this->_path = $imgSrc;
                $this->_imgInfo = $imgInfo;
                return $this;
            } else {
                throw new \Techo\Image\Exception("The image type can't be operated!");
            }
        } else {
            throw new \Techo\Image\Exception("The image isn't exist!");
        }
    }
    
    public function destroy()
    {
        if ($this->_resource) {
            imagedestroy($this->_resource);
            $this->_resource = null;
            $this->_path = null;
            $this->_imgInfo = null;
        }
    }

	public static function getImgInfo($img)
    {
        $info = getimagesize($img);
        return array(
            'type' => substr(image_type_to_extension($info[2]), 1),
            'mime' => $info['mime'],
            'width' => $info[0],
            'height' => $info[1]
        );
    }
    
    public static function toResize($imgSrc, $width, $height, $path = null)
    {
        if (is_file($imgSrc)) {
            $imgInfo = self::getImgInfo($imgSrc);
            $imgType = $imgInfo['type'];
            $createFunc = 'imagecreatefrom' . $imgType;
            if (function_exists($createFunc)) {
                $img = $createFunc($imgSrc);
                $dstImg = imagecreatetruecolor($width, $height);
                imagecopyresampled($dstImg, $dstImg, 0, 0, 0, 0, $width, $height, $imgInfo['width'], $imgInfo['height']);
                $dstFunc = 'image' . $imgType;
                if ($path) {
                    header('Content-type:' . $imgInfo['mime']);
                }
                $dstFunc($dstImg, $path);
                imagedestroy($img);
                imagedestroy($dstImg);
                return true;
            } else {
                throw new \Techo\Image\Exception("The image type can't be operated!");
            }
        } else {
            throw new \Techo\Image\Exception("The image isn't exist!");
        }  
    }
    	
}

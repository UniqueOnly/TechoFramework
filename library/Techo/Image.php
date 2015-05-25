<?php
namespace Techo;
/**
 * 图像处理类
 * @author unique@hiunique.com
 * @copyright 2015-5-24
 */
class Image
{
    /**
     * 源图片资源
     * 
     * @access protected
     * @var resource
     */
    protected $_resource = null;
    /**
     * 源图片相关信息
     * 
     * @access protected
     * @var array
     */
    protected $_imgInfo = null;
    /**
     * 源图片路径
     * 
     * @access protected
     * @var string
     */
    protected $_path = null;
    
    /**
     * 构造器
     * 
     * @access public
     * @param string $imgSrc 源图片路径
     * @throws \Techo\Image\Exception
     */
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
    
    /**
     * 获取图片数据
     * 
     * @access public
     * @return string
     * @throws \Techo\Image\Exception
     */
    public function getData()
    {
        if ($this->_path) {
            return file_get_contents($this->_path);
        } else {
            throw new \Techo\Image\Exception("No image can be operated!");
        }
        
    }

    /**
     * 输出图片
     * 
     * @access public
     * @throws \Techo\Image\Exception
     */
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

    /**
     * 缩放、裁剪图片
     * 
     * @access public
     * @param number $srcWPos 图片裁剪x轴店
     * @param number $srcHPos 图片裁剪y轴点
     * @param number $dstWidth 目标图片宽度
     * @param number $dstHeight 目标图片高度
     * @param string $path 目标保存路径
     * @throws \Techo\Image\Exception
     */
    public function resize($srcWPos = 0, $srcHPos = 0, $dstWidth = 0, $dstHeight = 0, $path = null)
    {
        if ($this->_resource) {
            $srcWPos = $srcWidth > $this->_imgInfo['width'] ? $this->_imgInfo['width'] : $srcWPos;
            $srcHPos = $srcWidth > $this->_imgInfo['height'] ? $this->_imgInfo['height'] : $srcHPos;
            $srcWidth = $dstWidth + $srcWPos > $this->_imgInfo['width'] ? $this->_imgInfo['width'] - $srcWPos : $dstWidth;
            $srcHeight = $dstHeight + $srcHPos > $this->_imgInfo['height'] ? $this->_imgInfo['height'] - $srcHPos : $dstHeight;
            $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
            imagecopyresampled($dstImg, $this->_resource, 0, 0, $srcWPos, $srcHPos, $dstWidth, $dstHeight, $srcWidth, $srcHeight);
            $dstFunc = 'image' . $this->_imgInfo['type'];
            if ($path) {
                header('Content-type:' . $this->_imgInfo['mime']);
            }
            $dstFunc($dstImg, $path);
            imagedestroy($dstImg);
        } else {
            throw new \Techo\Image\Exception("No image can be operated!");
        }
    }

    /**
     * 设置图片资源
     * 
     * @access public
     * @param string $imageSrc 源图片路径
     * @throws \Techo\Image\Exception
     * @return \Techo\Image
     */
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
    
    /**
     * 销毁图片资源
     * 
     * @access public
     */
    public function destroy()
    {
        if ($this->_resource) {
            imagedestroy($this->_resource);
            $this->_resource = null;
            $this->_path = null;
            $this->_imgInfo = null;
        }
    }

    /**
     * 获取图片信息
     * 
     * @static
     * @access public
     * @param string $imgSrc 图片路径
     * @return multitype:string unknown Ambigous <>
     */
	public static function getImgInfo($imgSrc)
    {
        $info = getimagesize($imgSrc);
        return array(
            'type' => substr(image_type_to_extension($info[2]), 1),
            'mime' => $info['mime'],
            'width' => $info[0],
            'height' => $info[1]
        );
    }
    
    
    /**
     * 缩放、裁剪图片
     * 
     * @static
     * @access public
     * @param string $imgSrc 源图片路径
     * @param number $srcWPos 图片裁剪x轴店
     * @param number $srcHPos 图片裁剪y轴点
     * @param number $dstWidth 目标图片宽度
     * @param number $dstHeight 目标图片高度
     * @param string $path 目标保存路径
     * @throws \Techo\Image\Exception
     * @return boolean
     */
    public static function toResize($imgSrc, $srcWPos = 0, $srcHPos = 0, $dstWidth = 0, $dstHeight = 0, $path = null)
    {
        if (is_file($imgSrc)) {
            $imgInfo = self::getImgInfo($imgSrc);
            $srcWPos = $srcWidth > $imgInfo['width'] ? $imgInfo['width'] : $srcWPos;
            $srcHPos = $srcWidth > $imgInfo['height'] ? $imgInfo['height'] : $srcHPos;
            $srcWidth = $dstWidth + $srcWPos > $imgInfo['width'] ? $imgInfo['width'] - $srcWPos : $dstWidth;
            $srcHeight = $dstHeight + $srcHPos > $imgInfo['height'] ? $imgInfo['height'] - $srcHPos : $dstHeight;
            $imgType = $imgInfo['type'];
            $createFunc = 'imagecreatefrom' . $imgType;
            if (function_exists($createFunc)) {
                $img = $createFunc($imgSrc);
                $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
                imagecopyresampled($dstImg, $img, 0, 0, $srcWPos, $srcHPos, $dstWidth, $dstHeight, $srcWidth, $srcHeight);
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
    
    /**
     * 析构函数
     * 
     * @access public
     */
    public function __destruct()
    {
        $this->destroy();
    }
    	
}

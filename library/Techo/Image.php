<?php
namespace Techo;
/**
 * 图像处理类
 * @author unique@hiunique.com
 * @copyright 2015-5-24
 */
class Image implements \Techo\Image\IImage
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
     * 校验数值类型
     * 
     * @access protected
     * @param number $src
     * @param number $range
     * @return number
     */
    protected function _validateNumber($src, $range = array())
    {
        if ($src >= $range['min'] && $src <= $range['max']) {
            return $src;
        } elseif ($src < $range['min']) {
            return $range['min'];
        } elseif ($src > $range['max']) {
            return $range['max'];
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
            $srcWPos = $this->_validateNumber($srcWPos, array('min' => 0, 'max' => $this->_imgInfo['width']));
            $srcHPos = $this->_validateNumber($srcHPos, array('min' => 0, 'max' => $this->_imgInfo['height']));
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
    public function setImage($imgSrc)
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
    public function destory()
    {
        if ($this->_resource) {
            imagedestroy($this->_resource);
            $this->_resource = null;
            $this->_path = null;
            $this->_imgInfo = null;
        }
    }
    
    /**
     * 合并图片、水印图片
     * 
     * @access public
     * @param string $imgSrc 要添加的图片
     * @param number $dstWPos 添加图在底图的x轴坐标
     * @param number $dstHPos 添加图在底图的y轴坐标
     * @param number $pct 添加比重（添加后显示透明度)
     * @throws \Techo\Image\Exception
     * @return \Techo\Image
     */
    public function merge($imgSrc, $dstWPos = 0, $dstHPos = 0, $pct = 0)
    {
        if (is_file($imgSrc)) {
            if ($this->_resource) {
                $dstWPos = $this->_validateNumber($dstWPos, array('min' => 0, 'max' => $this->_imgInfo['width']));
                $dstHPos = $this->_validateNumber($dstHPos, array('min' => 0, 'max' => $this->_imgInfo['height']));
                $pct = $this->_validateNumber($pct, array('min' => 0, 'max' => 100));
                $imgSrcInfo = self::getImgInfo($imgSrc);
                $createFunc = 'imagecreatefrom' . $imgSrcInfo['type'];
                $imgSrcRes = $createFunc($imgSrc);
                imagecopymerge($this->_resource, $imgSrcRes, $dstWPos, $dstHPos, 0, 0, $imgSrcInfo['width'], $imgSrcInfo['height'], $pct);
                imagedestroy($imgSrcRes);
                return $this;
            } else {
                throw new \Techo\Image\Exception("The destination image isn't exist!");
            }
        } else {
            throw new \Techo\Image\Exception("The source image isn't exist!");
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
     */
    public static function toResize($imgSrc, $srcWPos = 0, $srcHPos = 0, $dstWidth = 0, $dstHeight = 0, $path = null)
    {
        if (is_file($imgSrc)) {
            $imgInfo = self::getImgInfo($imgSrc);
            $srcWPos = self::_validateNumber($srcWPos, array('min' => 0, 'max' => $imgInfo['width'] ));
            $srcHPos = self::_validateNumber($srcHPos, array('min' => 0, 'max' => $imgInfo['height'] ));
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
            } else {
                throw new \Techo\Image\Exception("The image type can't be operated!");
            }
        } else {
            throw new \Techo\Image\Exception("The image isn't exist!");
        }  
    }
	
    /**
     * 合并图片、水印图片
     *
     * @static
     * @access public
     * @param string $imgSrc 要添加的图片
     * @param string $imgDst 底图
     * @param number $dstWPos 添加图在底图的x轴坐标
     * @param number $dstHPos 添加图在底图的y轴坐标
     * @param number $pct 添加比重（添加后显示透明度)
     * @param boolean $output 是否输出
     * @throws \Techo\Image\Exception
     */
	public static function toMerge($imgSrc, $imgDst, $dstWPos = 0, $dstHPos = 0, $pct = 0, $output = true)
	{
	    if (is_file($imgSrc) && is_file($imgDst)) {
	        $imgDstInfo = self::getImgInfo($imgDst);
	        $imgSrcInfo = self::getImgInfo($imgSrc);
	        $createDstFunc = 'imagecreatefrom' . $imgDstInfo['type'];
	        $createSrcFunc = 'imagecreatefrom' . $imgSrcInfo['type'];
	        if (function_exists($createDstFunc) && function_exists($createSrcFunc)) { 
	            $dstWPos = $this->_validateNumber($dstWPos, array('min' => 0, 'max' => $imgDstInfo['width']));
	            $dstHPos = $this->_validateNumber($dstHPos, array('min' => 0, 'max' => $imgDstInfo['height']));
	            $pct = $this->_validateNumber($pct, array('min' => 0, 'max' => 100));
	            $imgDstRes = $createDstFunc($imgDst);
	            $imgSrcRes = $createSrcFunc($imgSrc);
	            imagecopymerge($imgDstRes, $imgSrcRes, $dstWPos, $dstHPos, 0, 0, $imgSrcInfo['width'], $imgSrcInfo['height'], $pct);
	            if ($output) {
	                $outputFunc = 'image' . $imgDstInfo['type'];
	                header('Content-type:' . $imgDstInfo['mime']);
	                $outputFunc($imgDstRes);
	            }
	            imagedestroy($imgDstRes);
	            imagedestroy($imgSrcRes);
	        } else {
	            throw new \Techo\Image\Exception("The source or destination image type can't be operated!");
	        }
	    } else {
	        throw new \Techo\Image\Exception("The source or destination image isn't exist!");
	    }
	}

	/**
	 * 输出图片
	 *
	 * @static
	 * @access public
	 * @param $imgSrc 图片路径
	 * @throws \Techo\Image\Exception
	 */
	public static function toOutput($imgSrc)
	{
	    if (is_file($imgSrc)) {
	        $imgInfo = self::getImgInfo($imgSrc);
	        header('Content-type:' . $this->_imgInfo['mime']);
	        $func = 'image' . $this->_imgInfo['type'];
	        $func($imgSrc);
	    } else {
	        throw new \Techo\Image\Exception("No image can be operated!");
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

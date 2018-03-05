<?php
/**
 * @author Fm453(方少)
 * @DACMS https://api.hiluker.com
 * @site https://www.hiluker.com
 * @url http://s.we7.cc/index.php?c=home&a=author&do=index&uid=662
 * @email fm453@lukegzs.com
 * @QQ 393213759
 * @wechat 393213759
*/

/*
 * @remark 图片处理类（直接在线修改图片、覆盖源图）
 */

class fmClass_cropImage {
  private $src;	//源图
  private $data;	//图片参数数据
  private $dst;	//存储目录
  private $type;	//图片类型
  private $extension;	//文件名后缀
  private $msg;	//返回消息

  function __construct($src, $data,$dst) {
    $this -> setSrc($src);
    $this -> setData($data);
    if(!$dst){
    	$dst = dirname($src);
    }
    $this -> setDst($dst);
    $this -> crop($this -> src, $this -> data, $this -> dst);
  }

  private function setSrc($src) {
    if (!empty($src)) {
    	if (!file_exists($src)) {
			return error('-1', '原图像不存在');
		}
      $type = exif_imagetype($src);
      if ($type) {
        $this -> src = $src;
        $this -> type = $type;
        $ext = strrpos($src,'.');
        $ext = substr($src,$ext);
        //$this -> extension = image_type_to_extension($type);
        $this -> extension = $ext;
      }
    }else{
    	return error('-1', '未指定原图像');
    }
  }

  private function setData($data) {
    if (!empty($data)) {
      $this -> data = $data;
    }else{
    	return error('-1', '未传入图像裁剪参数');
    }
  }

  private function setDst($dst) {
  	 if (!empty($dst)) {
		if (!file_exists($dst)) {
			if (!mkdirs($dst)) {
				return error('-1', '创建存储目录失败');
			}
		} elseif (!is_writable($dst)) {
			return error('-1', '存储目录无法写入');
		}
		$this -> dst =$dst;
    }else{
    	return error('-1', '未指定图片存储位置');
    }
  }

  private function crop($src, $data, $dst) {
    if (!empty($src) && !empty($dst) && !empty($data)) {
    	$org_info = @getimagesize($src);
    	if (!$org_info) {
    		return error('-1', '获取原始图像信息失败');
		}
      switch ($this -> type) {
        case IMAGETYPE_GIF:
          $src_img = imagecreatefromgif($src);
          break;

        case IMAGETYPE_JPEG:
          $src_img = imagecreatefromjpeg($src);
          break;

        case IMAGETYPE_PNG:
          $src_img = imagecreatefrompng($src);
          break;
      }

      if (!$src_img) {
        $this -> msg = "读取图片文件失败！";
        return;
      }
      $size = getimagesize($src);
      $size_w = $size[0]; // 原始宽度
      $size_h = $size[1]; // 原始高度

      $src_img_w = $size_w;
      $src_img_h = $size_h;

      $degrees = $data['rotate'];	//旋转角度

      // Rotate the source image
      if (is_numeric($degrees) && $degrees != 0) {
        // PHP's degrees is opposite to CSS's degrees
        $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );

        imagedestroy($src_img);
        $src_img = $new_img;

        $deg = abs($degrees) % 180;
        $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;

        $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
        $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);

        // Fix rotated image miss 1px issue when degrees < 0
        $src_img_w -= 1;
        $src_img_h -= 1;
      }

      $tmp_img_w = $data['width'];
      $tmp_img_h = $data['height'];
      $dst_img_w = $data['width'];
      $dst_img_h = $data['height'];

      $src_x = $data['x'];
      $src_y = $data['y'];

      if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
        $src_x = $src_w = $dst_x = $dst_w = 0;
      } else if ($src_x <= 0) {
        $dst_x = -$src_x;
        $src_x = 0;
        $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
      } else if ($src_x <= $src_img_w) {
        $dst_x = 0;
        $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
      }

      if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
        $src_y = $src_h = $dst_y = $dst_h = 0;
      } else if ($src_y <= 0) {
        $dst_y = -$src_y;
        $src_y = 0;
        $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
      } else if ($src_y <= $src_img_h) {
        $dst_y = 0;
        $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
      }

      // Scale to destination position and size
      $ratio = $tmp_img_w / $dst_img_w;
      $dst_x /= $ratio;
      $dst_y /= $ratio;
      $dst_w /= $ratio;
      $dst_h /= $ratio;

      $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);

      // Add transparent background to destination image
      imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
      imagesavealpha($dst_img, true);

      $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

      if ($result) {
      	switch ($this -> type) {
        case IMAGETYPE_GIF:
          $img = imagegif($dst_img, $src);
          break;

        case IMAGETYPE_JPEG:
          $img = imagejpeg($dst_img, $src);
          break;

        case IMAGETYPE_PNG:
          $img = imagepng($dst_img, $src);
          break;
      }
        if (!$img) {
          $this -> msg = "无法保存裁剪结果！";
        }
      } else {
        $this -> msg = "无法裁剪图片！";
      }

     	imagedestroy($src_img);
      imagedestroy($dst_img);
    }
  }

  private function codeToMessage($code) {
    switch ($code) {
      case UPLOAD_ERR_INI_SIZE:
        $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        break;

      case UPLOAD_ERR_FORM_SIZE:
        $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        break;

      case UPLOAD_ERR_PARTIAL:
        $message = 'The uploaded file was only partially uploaded';
        break;

      case UPLOAD_ERR_NO_FILE:
        $message = 'No file was uploaded';
        break;

      case UPLOAD_ERR_NO_TMP_DIR:
        $message = 'Missing a temporary folder';
        break;

      case UPLOAD_ERR_CANT_WRITE:
        $message = 'Failed to write file to disk';
        break;

      case UPLOAD_ERR_EXTENSION:
        $message = 'File upload stopped by extension';
        break;

      default:
        $message = 'Unknown upload error';
    }

    return $message;
  }

  public function getResult() {
    return !empty($this -> data) ? $this -> dst : $this -> src;
  }

  public function getMsg() {
    return $this -> msg;
  }
}

function fmFunc_image_crop($file,$data){
	$crop = new fmClass_cropImage($file,$data, '');
	$response = array(
		'state'  => 200,
		'message' => $crop -> getMsg(),
		'result' => $crop -> getResult()
	);
	return $response;
}

<?php

class Upload
{

	public $filename;

	public $width = 800;
	public $height = 800;

	public $crop = false;
	public $thumb_crop = true;
	public $thumb_width = 100;
	public $thumb_height = 100;
	public $thumb = false;
	public $max_size = 6097154; // 6MB

	private $dest_path;
	private $thumb_path;
	private $orig_path;

	private $resize = false;
	private $tmp_name;
	private $file = array();
	private $file_ok = false;

	function __construct($dest_path, $file)
	{

		$this->file = $file;

		$oldumask = umask(0);
		@mkdir($dest_path . "thumb/", 0777, true);
		@mkdir($dest_path . "orig/", 0777, true);
		umask($oldumask);

		$this->dest_path = $dest_path;
		$this->thumb_path = $dest_path . 'thumb/';
		$this->orig_path = $dest_path . 'orig/';

		$this->tmp_name = $this->file['tmp_name'];
		$this->filename = $this->get_filename($this->file['name']);

		if (($this->file["type"] == "image/gif")
			|| ($this->file["type"] == "image/jpeg")
			|| ($this->file["type"] == "image/png")
			&& ($this->file["size"] < $this->max_size)
		) {

			$this->file_ok = true;
			$this->save_original();
		}
	}

	private function get_filename($filename)
	{

		$name = pathinfo($filename, PATHINFO_FILENAME);
		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$temp_name = $name;

		$i = 1;
		while (file_exists($this->orig_path . $temp_name . "." . $extension)) {

			$temp_name = $name . '_' . $i;
			$i++;
		}

		return $temp_name . "." . $extension;
	}

	public function save_image()
	{

		if ($this->file_ok) {

			if ($this->resize == true) {

				$this->create_image($this->dest_path, $this->height, $this->width);
			} else {
				copy($this->orig_path . $this->filename, $this->dest_path . $this->filename, $this->crop);
			}

			if ($this->thumb) {
				$this->create_image($this->thumb_path, $this->thumb_height, $this->thumb_width, $this->thumb_crop);
			}
		}
	}

	public function create_image($path, $targetHeight, $targetWidth, $crop = false)
	{

		$tsrc = $this->orig_path . $this->filename;

		$exif = @exif_read_data($tsrc);
		$ort = $exif['Orientation'];

		list($sourceWidth, $sourceHeight, $type, $attr) = getimagesize($tsrc);

		echo $sourceWidth . 'x' . $sourceHeight;

		$x = 0;
		$y = 0;
		$x2 = 0;
		$y2 = 0;
		if ($sourceWidth > $targetWidth || $sourceHeight > $targetHeight) {

			// Orignal
			// if ($sourceWidth > $sourceHeight) {
			//
			// 	$ratio 			= $targetWidth / $sourceWidth;
			// 	$n_width 		= round($targetWidth);
			// 	$n_height 		= round($sourceHeight * $ratio);
			// } else {
			//
			// 	$ratio 			= $targetHeight / $sourceHeight;
			// 	$n_width 		= round($sourceWidth * $ratio);
			// 	$n_height 		= round($targetHeight);
			// }

			// New
			if ($sourceWidth > $sourceHeight) {

				$ratio 			= $targetWidth / $sourceWidth;
				$n_width 		= round($targetWidth);
				$n_height 		= round($sourceHeight * $ratio);
			} else {

				if ($targetHeight > $targetWidth) {

					$ratio 			= $targetHeight / $sourceHeight;
					$n_width 		= round($sourceWidth * $ratio);
					$n_height 		= round($targetHeight);
				}

				if ($targetHeight <= $targetWidth) {
					$ratio 			= $targetWidth / $sourceWidth;
					$n_width 		= round($targetWidth);
					$n_height 		= round($sourceHeight * $ratio);
				}
			}


			if ($crop) {

				$n_height 	= $targetHeight;
				$n_width 	= $targetWidth;

				$width_ratio 	= $sourceWidth / $targetWidth; 		// 7,77
				$height_ratio	= $sourceHeight / $targetHeight;	// 1.08

				if ($height_ratio != $width_ratio) {
					if ($width_ratio > $height_ratio) {

						$x2 = ($sourceWidth - $sourceHeight) / 2;
						$sourceWidth = ($sourceWidth - (2 * $x2));
					} else {

						$y2 = ($sourceHeight - $sourceWidth) / 2;
						$sourceHeight = ($sourceHeight - (2 * $y2));
					}
				}
			}
		} else {
			$n_width = $sourceWidth;
			$n_height = $sourceHeight;
		}

		$newimage = imagecreatetruecolor($n_width, $n_height);
		// Create transparant png
		imagecolortransparent($newimage, imagecolorallocate($newimage, 0, 0, 0));

		if ($this->file['type'] == "image/gif") {

			$im = ImageCreateFromGIF($tsrc);
			imagecopyresampled($newimage, $im, $x, $y, $x2, $y2, $n_width, $n_height, $sourceWidth, $sourceHeight);
			if ($ort > 0) $newimage = $this->rotate_exif($newimage, $ort);
			ImageGIF($newimage, $path . $this->filename);
		}

		if ($this->file['type'] == "image/jpeg") {

			// $im = ImageCreateFromJPEG($tsrc);
			// imagecreatetruecolor($newimage, $im, $x, $y, $x2, $y2, $n_width, $n_height, $sourceWidth, $sourceHeight);
			// if ($ort > 0) $newimage = $this->rotate_exif($newimage, $ort);
			// ImageJPEG($newimage, $path . $this->filename, 100);

			$im = ImageCreateFromJPEG($tsrc);
			// imagecolortransparent($newimage, imagecolorallocatealpha($newimage, 0, 0, 0, 127));
			imagealphablending($newimage, false);
			imagesavealpha($newimage, true);
			imagecopyresampled($newimage, $im, $x, $y, $x2, $y2, $n_width, $n_height, $sourceWidth, $sourceHeight);
			if ($ort > 0) $newimage = $this->rotate_exif($newimage, $ort);
			ImagePNG($newimage, $path . $this->filename);
		}

		if ($this->file['type'] == "image/png") {

			$im = ImageCreateFromPNG($tsrc);
			imagecolortransparent($newimage, imagecolorallocatealpha($newimage, 0, 0, 0, 127));
			imagealphablending($newimage, false);
			imagesavealpha($newimage, true);
			imagecopyresampled($newimage, $im, $x, $y, $x2, $y2, $n_width, $n_height, $sourceWidth, $sourceHeight);
			if ($ort > 0) $newimage = $this->rotate_exif($newimage, $ort);
			ImagePNG($newimage, $path . $this->filename);
		}
	}

	private function save_original()
	{

		if ($this->file_ok) {
			move_uploaded_file($this->tmp_name, $this->orig_path . $this->filename);
		}
	}

	private function rotate_exif($newimage, $ort)
	{

		switch ($ort) {
			case 3:
				$newimage = imagerotate($newimage, 180, 0);
				break;
			case 6:
				$newimage = imagerotate($newimage, -90, 0);
				break;
			case 8:
				$newimage = imagerotate($newimage, 90, 0);
				break;
		}
		return $newimage;
	}

	public function resize($width, $height)
	{

		$this->resize 			= true;
		$this->width 			= $width;
		$this->height 			= $height;
	}

	public function thumb($width = 100, $height = 100, $crop = true)
	{

		$this->thumb 			= true;
		$this->thumb_width 		= $width;
		$this->thumb_height 	= $height;
		$this->thumb_crop		= $crop;
	}
}

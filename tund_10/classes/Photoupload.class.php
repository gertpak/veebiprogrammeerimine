<?php
	class Photoupload {
		private $tempName;
		private $imageFileType;
		private $myTempImage; 
		private $myImage;
		
		function __construct($tmpPic, $type) {
			$this->tempName = $tmpPic;
			$this->imageFileType = $type;
			$this->createImageFromFile();
		}
		
		//destructor, mis käivitub klassi eemaldamisel
		function __destruct() {
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		
		private function createImageFromFile() {
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg") {
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png") {
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif") {
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		
		public function resizeImage($width, $height) {
			$this->createImageFromFile();
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			//leian vajalikud suurendusfaktori
			if($imageWidth > $imageHeight) {
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			$newWidth = round($imageWidth / $sizeRatio);
			$newHeight = round($imageHeight / $sizeRatio);	
			$this->myImage = $this->changePicSize($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
		}
		
		private function changePicSize($image, $ow, $oh, $w, $h) {
			$newImage = imagecreatetruecolor($w,$h);
			imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
			return $newImage;
		}
		
		public function addWatermark($pos) {
			$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_color_w100_overlay.png");
			$waterMarkWidth = imagesx($waterMark);
			$waterMarkHeight = imagesy($waterMark);
			if($pos == "ld") {	//left down
				$waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth - 10;
				$waterMarkPosY = imagesy($this->myImage) - $waterMarkWidth - 10;
				imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
			}
			if($pos == "rd") {	//right down
				$waterMarkPosX = 10;
				$waterMarkPosY = imagesy($this->myImage) - $waterMarkWidth - 10;
				imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
			}
			if($pos == "c") {	//center
				$waterMarkPosX = (imagesx($this->myImage) - $waterMarkWidth) / 2 ;
				$waterMarkPosY = (imagesy($this->myImage) - $waterMarkWidth) / 2;
				imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
			}
			if($pos == "lu") {	//left up
				$waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth - 10;
				$waterMarkPosY = 10;
				imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
			}
			if($pos == "ru") {	//right up
				$waterMarkPosX = 10;
				$waterMarkPosY = 10;
				imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
			}
		}
		
		public function addText($txt){
			$textToImage = $txt;
			$textColor = imagecolorallocatealpha($this->myImage, 255, 255, 255, 60);
			imagettftext($this->myImage, 20, 0, 10, 25, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
		}
		
		public function savePhoto($targetFile) {
			$notice = "";
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg") {
				if(imagejpeg($this->myImage, $targetFile, 90)) {
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "png") {
				if(imagepng($this->myImage, $target_file, 6)) {
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "gif") {
				if(imagegif($this->myImage, $target_file)) {
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			return $notice;
		}
		public function checkIfPicture($targetFile, $size) {
			$uploadOk = 1;
			$check = getimagesize($this->tempName);
			if($check !== false) {
				$uploadOk = 1;
			} else {
				$uploadOk = 0;
			}
			if (file_exists($targetFile)) {
				//$noticeForm = " Kahjuks on selline pilt juba olemas. ";
				$uploadOk = 0;
			}
			if ($size > 2500000) {
				//$noticeForm = " Kahjuks on fail liiga suur. ";
				$uploadOk = 0;
			}
			if($this->imageFileType != "jpg" && $this->imageFileType != "png" && $this->imageFileType != "jpeg"
			&& $this->imageFileType != "gif" ) {
				//$noticeForm = " Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid. ";
				$uploadOk = 0;
			}
			return $uploadOk;
		}
	}
		
//class lõppeb






?>
<?php
require_once('./fpdf/fpdf.php');
require_once('./fpdi/chinese.php');
require_once('./fpdi/fpdi.php');

class waterMark{
	public function __construct()
	{

	}
	public function newImage()
	{
		/* Create some objects */
		$imagick = new Imagick();
		$draw = new ImagickDraw();
		$pixel = new ImagickPixel( 'white' );

		/* New image */
		$imagick->newImage(100, 75, $pixel);

		/* Black text */
		$draw->setFillColor('red');

		/* Font properties */
		$draw->setFont('./font/brush.TTF');
		$draw->setFontSize( 30 );

		/* Create text */
		$imagick->annotateImage($draw, 20, 45, 0, 'kirito');

		/* Give image a format */
		$imagick->setImageFormat('png');
		return $imagick->writeImage('./image/kirito.png');
	}
	public function waterMarkWord($image,$path,$type='png',$waterMark='桐姥爷')
	{
		$imagick = new Imagick($image);
		$draw = new ImagickDraw();
		$imagickPro = $imagick->getImageGeometry();
		$draw->setFillColor('red');//设置字体颜色
		$draw->setFont('./font/brush.TTF') or die('设置字体失败');
		$draw->setFontSize(300);
		$imagick->annotateImage($draw,$imagickPro['width']-800, 200, 10, $waterMark) or die('添加水印失败');
		$imagick->setImageFormat($type);
		return $imagick->writeImage($path);
	}
	public function waterMarkImage($image,$path,$type='png',$waterMark='./image/kirito.png')
	{
		$imagick = new imagick();
		$waterMarkImage = new imagick($waterMark);
		$imagick->readImage($image);//从文件名读取图像 成功返回true
		$imagickPro = $imagick->getImageGeometry();
		//$imagick->readImageBlog();//从二进制字符串中读取图像 成功返回true
		//$imagick->readImageFile();//从文件句柄中读取图像 成功返回true
		$imagick->setCompressionQuality(100);//设置压缩质量
		$imagick->setImageFormat($type);//设置指定格式
		$imagickPro = $imagick->getImageGeometry();//以关联数组形式获取宽高
		$waterMarkImage->resizeImage ( 500, 0,  imagick::FILTER_LANCZOS, 1);
		$waterMarkImage->transparentPaintImage(new \ImagickPixel('rgb(255, 255, 255)'), 0, '10', 0);
		$imagick->compositeImage($waterMarkImage,$waterMarkImage->getImageCompose(),$imagickPro['width']-800, 100,);//给图像添加水印
		return $imagick->writeImage($path);
	}
	public function waterMarkPdfImage($filename,$path,$waterMark='./image/kirito.png')
	{
		$pdf = new \FPDI();
		$pageCount = $pdf->setSourceFile($filename);
		for($pageNo = 1; $pageNo <= $pageCount; $pageNo++){
			$templateId = $pdf->importPage($pageNo);
			$size = $pdf->getTemplateSize($templateId);
			if($size['w'] > $size['h']){
				$pdf->AddPage('L',array($size['w'],$size['h']));
			}else{
				$pdf->AddPage('P',array($size['w'],$size['h']));
			}
			$pdf->useTemplate($templateId);
			// $pdf->image($waterMark,135,10,50);
			$pdf->image($waterMark,135,10,50);
		}
		$pdf->Output($path);

	}
	public function waterMarkPdfWord($filename,$path,$waterMark='kirito')
	{
		$pdf = new \FPDI();
		$pageCount = $pdf->setSourceFile($filename);
		for($pageNo = 1; $pageNo <= $pageCount; $pageNo++){
			$templateId = $pdf->importPage($pageNo);
			$size = $pdf->getTemplateSize($templateId);
			if($size['w'] > $size['h']){
				$pdf->AddPage('L',array($size['w'],$size['h']));
			}else{
				$pdf->AddPage('P',array($size['w'],$size['h']));
			}
			$pdf->useTemplate($templateId);

			$pdf->SetFont('Arial','I','20');
			$pdf->SetXY($size['w']-260, 20);
			$pdf->Write(7,$waterMark);
		}
		$pdf->Output($path);

	}
	public function waterMarkPdfChineseWord($filename,$path,$waterMark='桐姥爷')
	{
		$pdf = new PDF_Chinese();
	    $pageCount = $pdf->setSourceFile($filename);
		for($pageNo = 1; $pageNo <= $pageCount; $pageNo++){
			$templateId = $pdf->importPage($pageNo);
			$size = $pdf->getTemplateSize($templateId);
			if($size['w'] > $size['h']){
				$pdf->AddPage('L',array($size['w'],$size['h']));
			}else{
				$pdf->AddPage('P',array($size['w'],$size['h']));
			}
			$pdf->useTemplate($templateId);
			$pdf->AddGBFont('simhei', '黑体');
			$pdf->SetFont('simhei', '', 13);
			$pdf->SetXY($size['w']-260, 20);
			$pdf->Write(7,iconv("utf-8","gbk",$waterMark));
		}

	    //插入图片
	    //Image参数：文件，x坐标，y坐标，宽，高
	    #$pdf->Image('test.jpg',null,null,50,50);

	    #$pdf->Output();//直接输出，即在浏览器显示
    	$pdf->Output($path,'F');//保存为example.pdf文件

	}
	public function waterMarkVideo()
	{

	}
}
$waterMark = new waterMark();
#var_dump($waterMark->waterMarkWord('./image/84889431.png','./image/84889431_waterMark_pdf_word.png'));
var_dump($waterMark->waterMarkPdfChineseWord('./image/keqing.pdf','./image/84889431_waterMark_pdf_chinese_word.pdf'));
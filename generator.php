<?php 
require_once 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;
$data = array(
	"photos/1.jpg",
	"photos/2.jpg",
	"photos/3.jpg",
	"photos/4.jpg",
	"photos/5.jpg",
	"photos/6.jpg",
	"photos/7.jpg",
	"photos/8.jpg",
	"photos/9.jpg",
	"photos/10.jpg",
	"photos/11.jpg",
	"photos/12.jpg",
	"photos/13.jpg",
	"photos/14.jpg",
);


//Burada yan yana kaç resim olacağını belirliyoruz.
$grid = 5;
//satır sayısını hesaplıyoruz.
$row = ceil(count($data) / $grid);
/**
 * Resimlerin boyutlarına göre çıkarılacak liste resimin boyutlarını hesaplamak için;
 * Klasördeki tüm resimlerin en boy oranları sabit olduğundan dizi içerisinden bir resim seçip en boy oranını alıp
 * Alınan en boy oranına göre mesela yan yana 4 lü liste;
 */
$image = Image::make($data[0]);
$photosWidth = $image->width();
$photosHeight = $image->height();
//Başlık Kısmının yüksekliği hesaplama
$titleSectionSize =  round($photosHeight / 4 );
//Başlık Font Boyutu hesaplama
$titleFontSize = floor($titleSectionSize / 100) * 50;
//Kenar boşlukları yani taban resmin çerçevesinin boyutu denebilir border
$borderWidth = 0; // round($photosWidth / 10);
//Resimler arası boşluk hesaplama
$imageGap =  round($photosWidth / 25);
//Resimlerin altındaki yazı yüksekliği hesaplama
$textSectionSize = round($photosHeight / 5);
//Resimlerin altındaki yazı font boyutu hesaplama
$textFontSize = floor(($textSectionSize - $imageGap) / 100) * 50;

//oluşturulacak alt resmin boyutlarını hesaplama
$canvasWidth = (($photosWidth * $grid) + ($borderWidth * 2 ) + ($imageGap * $grid * 2));
$canvasHeight = (($photosHeight * $row) + ($borderWidth * 2) + ($imageGap * $row * 2) + ($textSectionSize * $row) + $titleSectionSize);

$canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
$canvas->text('OKULUN ADI VE SINIF', $canvasWidth / 2, $titleSectionSize / 2, function($font) use ($titleFontSize) {
	$font->file('Roboto-Bold.ttf');
	$font->size($titleFontSize);
	$font->color('#000000');
	$font->align('center');
	$font->valign('top');
});

//ilk resmin canvastaki başlangıç x ve y korrdinatları değerleri hesaplama
$x = $borderWidth + $imageGap;
$y = $borderWidth + $titleSectionSize + $imageGap;
foreach ($data as $key => $photoPath) {
	if($key % $grid == 0 && $key != 0){
		$y = $y + $photosHeight + $imageGap * 2 + $textSectionSize;
	}
	if($key % $grid != 0 && $key != 0){
		$x = $x + $photosWidth + $imageGap * 2;
	} else {
		$x = $borderWidth + $imageGap;
	}
	$photo = Image::make('photos/'.($key+1).'.jpg');
	$photo->resize($photosWidth, $photosHeight);
	$canvas->insert($photo, 'top-left', $x, $y);
	$canvas->text('Adı Soyadı', $photosWidth / 2 + $x, $y + $photosHeight + $imageGap * 2, function($font) use ($textFontSize) {
    $font->file('Roboto-Bold.ttf');
    $font->size($textFontSize);
    $font->color('#000000');
    $font->align('center');
    $font->valign('top');
	});
}

$canvas->save('output.jpg');
//echo $canvas->response('jpg');


?>
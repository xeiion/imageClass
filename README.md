# imageClass

$image = new Image();

$image->setImage('bg-dark-grain.jpg');


$image->upload($_FILES,filesize limit,'target filename',target path,'file input name can leave default and will use file as name');

$image->crop($x,$y,$width,$height);

$image->resize('400', '400');

$image->AddText('test','CFOneTwoTrees-Regular.ttf','52','middle-center','255,255,255');

or for water mark option u can do like

$image->AddText('test','CFOneTwoTrees-Regular.ttf','52','middle-center-watermark','255,255,255');

$image->rotate('40');

$image->brightness(50);

$image->grayscale();

$image->blur(1);

$image->flip('v');

$image->pixelate(15);

$image->contrast(-100);

$image->gamma('1');

if quality is empty it just apply a 100 quality field and on png a 0 level effect

if you dont write an extension it will use the current extension

$image->save('test.jpg', 60);

$image->destroy();

# imageClass

$image = new Image();

$image->setImage('bg-dark-grain.jpg');

$image->upload($_FILES,$filesize,'test.jpg');

$image->crop($x,$y,$width,$height);

$image->resize('400', '400');

$image->AddText('test','CFOneTwoTrees-Regular.ttf','52','middle-center','255,255,255');

$image->rotate('40');

$image->brightness(50);

$image->grayscale();

$image->blur(1);

$image->flip('v');

$image->pixelate(15);

$image->contrast(-100);

$image->gamma('1');

$image->save('test.jpg', 60);

$image->destroy();

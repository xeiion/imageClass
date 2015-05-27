# imageClass

$image = new Image();

$image->setImage('bg-dark-grain.jpg');

$image->resize(null, '1080');

$image->AddText('hello','CFOneTwoTrees-Regular.ttf','127','center','255,255,255');

$image->rotate('40');

$image->brightness(50);

$image->grayscale();

$image->blur();

$image->flip('v');

$image->pixelate(15);

$image->contrast(-100);

$image->gamma('5.5');

$image->save('5.jpg', 60);

$image->destroy();

<?php
$app = 'frontend';
$debug = true;
$database = true; 

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$t = new lime_test(25);
st_gallery_refresh_assets();
database_reload();

$database = new sfDatabaseManager($configuration);
$sourceImage = sfConfig::get('sf_web_dir').'/uploads/megane1.jpg';
$sourceImageError = sfConfig::get('sf_web_dir').'/uploads/error.png';

$p = new stPicture();
$p->setSource($sourceImage);
$p->save();
$t->is($p->getSource(), '/uploads/megane1.jpg', '->setSource() trims the sf_web_dir from path');
$t->is($p->getThumbnail(), '/uploads/000001_tb.jpg', '->setSource() generates thumbnail');
$t->is($p->getImage(), '/uploads/000001_image.jpg', '->generateImage() generates right image');
$t->ok(is_file(sfConfig::get('sf_web_dir').$p->getImage()), '->generateImage() generated image exists');

$t->diag('Custom settings');

$p = new stPicture();
$p->setSource($sourceImage);
$p->save();

$thumb = sfConfig::get('sf_web_dir').$p->getThumbnail();
$img = sfConfig::get('sf_web_dir').$p->getImage();
$tbSize = getimagesize($thumb);
$imgSize = getimagesize($img);

$t->is($tbSize[0], 140, '->generateThumbnail() correctly computes width.');
$t->is($tbSize[1], 108, '->generateThumbnail() correctly computes height.');

$t->is($imgSize[0], 640, '->generateImage() correctly computes width');
$t->is($imgSize[1], 426, '->generateImage() correctly computes height');

$p = new stPicture();
$p->setSource($sourceImage);
$p->setParamValue('thumbnail_width', 102);
$p->setParamValue('thumbnail_height', 64);
$p->save();

$thumb = sfConfig::get('sf_web_dir').$p->getThumbnail();
$size = getimagesize($thumb);

$t->is($size[0], 102, '->generateThumbnail() correctly computes width from param');
$t->is($size[1], 64, '->generateThumbnail() correctly computes height from param');
$t->is(array('thumbnail_width' => 102, 'thumbnail_height' => 64), $p->getParams(), '->setParamValue() params for thumbnail generation are saved');
$t->is(102, $p->getParamValue('thumbnail_width'), '->getParamValue() returns right value for thumbnail_width');
$t->is(null, $p->getParamValue('thumbnail_method'), '->getParamValue() returns null on unknown param');


$t->diag('->generateThumbnail() record settings');

$p = new stPicture();
$p->setSource($sourceImage);
$p->setRecordModel('testModel');
$p->save();

$thumb = sfConfig::get('sf_web_dir').$p->getThumbnail();
$size = getimagesize($thumb);

$t->is($p->getRecordModel(), 'testModel', '->getRecordModel() returns right model');
$t->is($size[0], 160, '->generateThumbnial() correctly detect record settings for thumbnail width');
$t->is($size[1], 120, '->generateThumbnail() correctly detect record settings for thumbnail height');
$t->is($p->getThumbnail(), '/uploads/000004_tmtb.jpg', '->getThumbnail() returns correct path to thumbnail');

$img = sfConfig::get('sf_web_dir').$p->getImage();
$size = getimagesize($img);

$t->is($size[1], 180, '->generateImage() correctly computes the height of the image');

sfConfig::set('st_gallery_image_overlay', realpath(dirname(__FILE__).'/../../fixtures/files/overlay.png'));

$expectedColor = array('red' => 0, 'green' => 255, 'blue' => 0, 'alpha' => 0);

$t->diag('->generateImage() overlays');


sfConfig::set('st_gallery_image_overlay_position', 'top-left');
$p = new stPicture();
$p->setSource($sourceImageError);
$p->save();

$img = sfConfig::get('sf_web_dir') . $p->getImage();
$i = imagecreatefrompng($img);
$rgb = imagecolorat($i, 10, 10);
$color = imagecolorsforindex($i, $rgb);
imagedestroy($i);
unset($i);

$t->is($color, $expectedColor, '->generateImage() appends overlay to the top left');

$p = new stPicture();
$p->setSource($sourceImageError);
$p->setParamValue('overlay_position', 'top-right');
$p->save();

$img = sfConfig::get('sf_web_dir') . $p->getImage();
$i = imagecreatefrompng($img);
$rgb = imagecolorat($i, imagesx($i) - 10, 10);
$color = imagecolorsforindex($i, $rgb);
imagedestroy($i);
unset($i);

$t->is($color, $expectedColor, '->generateImage() appends overlay to the top right');

$t->diag('->delete()');

$tb = sfConfig::get('sf_web_dir') . $p->getThumbnail();
$img = sfConfig::get('sf_web_dir') . $p->getImage();

$t->ok(is_file($tb), '->getThumbnail() returns correct path to file');
$t->ok(is_file($tb), '->getImage() returns correct path to file');

$p->delete();

$t->ok(!is_file($tb), '->delete() removes also files from filesystem');
$t->ok(!is_file($img), '->delete() removes also files from filesystem');
$t->ok(!is_file($sourceImageError), '->delete() removes also files from filesystem');

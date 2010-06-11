<?php

include dirname(__FILE__).'/../bootstrap/unit.php';

$tmpDir = dirname(__FILE__).'/../fixtures/files';

$t = new lime_test(16);

class stValidatorGalleryFiles_test extends stValidatorGalleryFiles
{
    public function getTempFiles()
    {
        return $this->tempFiles;
    }
}

$v = new stValidatorGalleryFiles_test();
$t->diag('->clean() with images in zip');

try 
{
    $r = $v->clean(array('tmp_name' => $tmpDir.'/images.zip'));
    $t->pass('->clean() pass with zipped images');
    $t->isa_ok($r, 'array', '->clean() returns an array of images');
    $t->is(2, count($r), '->clean() returns right count of images');
    $t->isa_ok($r[0], 'sfValidatedFile', '->clean() returns array with first sfValidatedFile');
    $t->isa_ok($r[1], 'sfValidatedFile', '->clean() returns array with second sfValidatedFile');
    $t->is(isset($r[2]), false, '->clean() returns array with only two validated files');

    $tf = $v->getTempFiles();
    foreach ($tf as $file)
    {
        $t->is(is_file($file), true, '->clean() unpacks files from zip to temp');
    }
    $v->shutdown();
    
    foreach ($tf as $file)
    {
        $t->is(is_file($file), false, '->shutdown() cleans unpacked files');
    }
    
    $t->is($v->getTempFiles(), array(), '->shutdown() cleans temporary files');
    
}
catch (sfValidatorError $e)
{
    $t->fail('->clean() with images.zip throw an exception '.$e->getCode());
    $t->skip('', 5);
}


$t->diag('->clean() zip without images');
$v = new stValidatorGalleryFiles();
try
{
    $r = $v->clean(array('tmp_name' => $tmpDir.'/text.zip'));
    $t->fail('->clean() throws a sfValidatorError if the file contains no images');
    $t->skip('', 1);
}
catch (sfValidatorError $e)
{
    $t->pass('->clean() throws a sfValidatorError if no images found.');
    $t->is($e->getCode(), 'no_images_found', '->clean() throws an error code no_images_found');
}

$v = new stValidatorGalleryFiles();
$t->diag('->clean() with single image upload');
try
{
   $r = $v->clean(array('tmp_name' => $tmpDir.'/megane1.jpg', 'type' => 'image/jpeg'));
   $t->isa_ok($r, 'array', '->clean() returns an array of images with single file');
   $t->is(count($r), 1, '->clean() returns an image in array');
   $t->isa_ok($r[0], 'sfValidatedFile', '->clean() an array with sfValidatedFile');
}
catch (sfValidatorError $e)
{
    $t->fail('->clean() throws an sfValidatorError exception on a valid jpeg file.');
    $t->skip('', 2);
}
<?php

function st_gallery_cleanup()
{
    sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/cache');
    sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/log');
    sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/web/uploads');
}

register_shutdown_function('st_gallery_cleanup');

function st_gallery_refresh_assets()
{
    
    $uploadDir = dirname(__FILE__).'/../fixtures/project/web/uploads';
    sfToolkit::clearDirectory($uploadDir);
    copy(dirname(__FILE__).'/../fixtures/files/megane1.jpg', $uploadDir.'/megane1.jpg');
}
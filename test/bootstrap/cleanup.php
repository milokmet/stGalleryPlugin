<?php

function st_gallery_cleanup()
{
    $projectDir = dirname(__FILE__).'/../fixtures/project';
    sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/cache');
    sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/log');
    sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/web/uploads');
    // @unlink($projectDir.'/test.sqlite');
}

register_shutdown_function('st_gallery_cleanup');

function st_gallery_refresh_assets()
{
    $projectDir = dirname(__FILE__).'/../fixtures/project';   
    $uploadDir = $projectDir.'/web/uploads';
    sfToolkit::clearDirectory($uploadDir);
    copy(dirname(__FILE__).'/../fixtures/files/megane1.jpg', $uploadDir.'/megane1.jpg');
    copy(dirname(__FILE__).'/../fixtures/files/error.png', $uploadDir.'/error.png');
    // copy($projectDir.'/data/fresh_test.sqlite', $projectDir.'/data/test.sqlite');
}



function database_reload() {
    Doctrine_Core::loadModels(dirname(__FILE__).'/../fixtures/project/lib/model');   
    Doctrine_Manager::getInstance()->getCurrentConnection()->close();
    Doctrine_Core::createTablesFromModels();
    
    foreach (Doctrine_Core::getLoadedModels() as $model)
    {
        Doctrine_Core::getTable($model)->clear();
    }
    
}
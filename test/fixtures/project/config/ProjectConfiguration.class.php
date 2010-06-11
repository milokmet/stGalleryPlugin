<?php

if (!isset($_SERVER['SYMFONY']))
{
  throw new RuntimeException('Could not find symfony core libraries.');
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
    public function setup()
    {
        $this->setPlugins(array(
            'stGalleryPlugin',
            'sfDoctrinePlugin',
            'sfImageTransformPlugin',
        ));
        
        $this->setPluginPath('sfImageTransformPlugin', dirname(__FILE__).'/../../../../sfImageTransformPlugin');
        $this->setPluginPath('stGalleryPlugin', dirname(__FILE__).'/../../../..');
    }
}

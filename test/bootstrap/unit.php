<?php

if (!isset($_SERVER['SYMFONY']))
{
  throw new RuntimeException('Could not find symfony core libraries.');
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

require_once(dirname(__FILE__).'/../fixtures/project/config/ProjectConfiguration.class.php');
$configuration = new ProjectConfiguration(dirname(__FILE__).'/../fixtures/project');

require_once $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

function stGalleryPlugin_autoload_again($class)
{
  $autoload = sfSimpleAutoload::getInstance();
  $autoload->reload();
  return $autoload->autoload($class);
}
spl_autoload_register('stGalleryPlugin_autoload_again');

if (file_exists($config = dirname(__FILE__).'/../../config/stGalleryPluginConfiguration.class.php'))
{
  require_once $config;
  $plugin_configuration = new stGalleryPluginConfiguration($configuration, dirname(__FILE__).'/../..', 'stGalleryPlugin');
}
else
{
  $plugin_configuration = new sfPluginConfigurationGeneric($configuration, dirname(__FILE__).'/../..', 'stGalleryPlugin');
}


require_once(dirname(__FILE__).'/cleanup.php');
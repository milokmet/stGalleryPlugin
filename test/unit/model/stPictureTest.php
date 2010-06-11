<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../fixtures/project/lib/model/doctrine/stGalleryPlugin/stPicture.class.php');
require_once(dirname(__FILE__).'/../../fixtures/project/lib/model/doctrine/stGalleryPlugin/base/BasestPicture.class.php');
$t = new lime_test(10);
st_gallery_refresh_assets();

$database = new sfDatabaseManager($configuration);

$p = new stPicture();


<?php

include dirname(__FILE__).'/../bootstrap/unit.php';

$t = new lime_test(9);
$t->diag('->__construct()');
try
{
    $r = new stGalleryRouteCollection(array('name' => 'test'));
    $t->fail('->__construct() throws an exception if no "model" option is provided');
}
catch (InvalidArgumentException $e)
{
    $t->pass('->__construct() throws an exception if no "model" option is provided');
}

$collection = new stGalleryRouteCollection(array('name' => 'test', 'model' => 'TestModel'));
$options = $collection->getOptions();

$routes = $collection->getRoutes();
$t->isa_ok($routes['test'], 'stGalleryDoctrineRoute', '->generateRoutes() generates stGalleryDoctrineRoute');
$url = $routes['test']->generate(array('id' => 123, 'action' => 'edit'));
$t->is($url, '/test/123/edit', '->generateRoutes() generate url for edit action');
$t->isa_ok($routes['test']->matchesUrl($url, array('method' => 'get')), 'array', '->generateRoutes() create edit route that matches URL it generates');

$match = null;
foreach ($routes as $name => $route)
{
    if ($route->matchesUrl($url, array('method' => 'get')))
    {
        $match = $name;
        break;
    }
}

$t->is($match, 'test', '->generateRoutes() URL generated is matched by the right route');

$collection = new stGalleryRouteCollection(array('model' => 'TestModel', 'name' => 'test', 'prefix_path' => '/test/gallery', 'segment_names' => array('edit' => 'modify')));
$routes = $collection->getRoutes();
$editUrl = $routes['test']->generate(array('id' => 123));
$uploadUrl = $routes['test_upload']->generate(array('id' => 123));
$t->is($editUrl, '/test/gallery/123/modify', '->generateRoutes() generates correct URL for edit route');
$t->is($uploadUrl, '/test/gallery/123/upload', '->generateRoutes() generates correct URL for upload route');

$match = null;
foreach ($routes as $name => $route)
{
    if ($route->matchesUrl($uploadUrl, array('method' => 'post')))
    {
        $match = $name;
        break;
    }
}

$t->is($match, 'test_upload', '->generateRoutes() orders routes so URLs generated for upload is matched by upload route');

$match = null;
foreach ($routes as $name => $route)
{
    if ($route->matchesUrl($uploadUrl, array('method' => 'get')))
    {
        $match = $name;
        break;
    }
}

$t->is($match, null, '->generateRoutes() generates upload route that matches only on post method');
$t->diag('wirte more test +');

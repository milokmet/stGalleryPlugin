<?php

class stGalleryDoctrineRoute extends sfDoctrineRoute
{
    public function getPicture()
    {
        print_r($this->parameters);
    }
    
    public function getRouteForUpload()
    {
        return $this->options['base_route_name'].'_upload';
    }
}
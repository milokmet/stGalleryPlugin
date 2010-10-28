<?php

class stGalleryDoctrineRoute extends sfDoctrineRoute
{
    public function getPicture()
    {
        if (!isset($this->parameters['picture']))
        {
            throw new InvalidArgumentException('Get picture is not available for this route!');
        }
        
        $picture = Doctrine_Core::getTable('stPicture')->createQuery('pq')
            ->addWhere('pq.record_id = ?', $this->getObject()->get($this->getObject()->getTable()->getIdentifier()))
            ->addWhere('pq.id = ?', $this->parameters['picture'])
            ->limit(1)
            ->fetchOne();
        
        return $picture;
    }
    
    public function getRouteForLoad()
    {
        return $this->options['base_route_name'].'_load';
    }
    
    public function getRouteForUpload()
    {
        return $this->options['base_route_name'].'_upload';
    }
    
    public function getRouteForEdit()
    {
        return $this->options['base_route_name'];
    }
    
    public function getRouteForDeletePicture()
    {
        return $this->options['base_route_name'].'_delete';
    }
    
    public function getRouteForUpdatePicture()
    {
        return $this->options['base_route_name'].'_update';
    }
}
<?php

class stGalleryComponents extends sfComponents
{
    public function executeList(sfWebRequest $request)
    {
        $this->pictures = Doctrine_Core::getTable('stPicture')->findAllPublicForRecord($this->record);
        
        if ($this->pictures->count() == 0)
        {
            return sfView::NONE;
        }
        
        $this->getResponse()->addJavascript('/stGalleryPlugin/js/gallery.js');
    }
}
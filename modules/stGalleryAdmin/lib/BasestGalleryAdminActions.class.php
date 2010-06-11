<?php

class BasestGalleryAdminActions extends sfActions
{
    public function preExecute()
    {
        $this->record = $this->getRecord();
        $this->route  = $this->getRoute();
    }
    
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('stGalleryAdmin', 'edit');
    }
    
    public function executeEdit(sfWebRequest $request)
    {
        $this->form = new stGalleryUploadForm(array(), array('path' => $this->getAbsolutePath()));
        $this->pictures = Doctrine_Core::getTable('stPicture')->findAllForRecord($this->record);
    }
    
    public function executeSave(sfWebRequest $request)
    {
        $this->setTemplate('edit');
    }
    
    public function executeDeletePicture(sfWebRequest $request)
    {
        
    }

    public function executeUploadPictures(sfWebRequest $request)
    {
        $this->form = new stGalleryUploadForm(array(), array('path' => $this->getAbsolutePath()));
        
        $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

        if ($this->form->isValid())
        {
            $values = $this->form->getValues();
            $newPictures = array();
            foreach ($values['files'] as $key => $file)
            {
                $savedFile = $file->getPath() . DIRECTORY_SEPARATOR . $file->save();
                
                $newPictures[$key] = new stPicture();
                $newPictures[$key]->fromArray(array(
                    'record_model' => get_class($this->getRecord()),
                    'record_id' => $this->getRecord()->getId(),
                    'params' => array(),
                ));
                $newPictures[$key]->setSource($savedFile);
                $newPictures[$key]->save();
            }
        }
        
        echo $this->form;
        exit;
    }
    
    protected function getAbsolutePath()
    {
        return $this->record->getAbsolutePath().DIRECTORY_SEPARATOR.sfConfig::get('app_st_gallery_plugin_subdir', 'gallery');
    }
    
    protected function getPath()
    {
        return $this->record->getPath().DIRECTORY_SEPARATOR.sfConfig::get('app_st_gallery_plugin_subdir', 'gallery');
    }
    
    protected function getRecord()
    {
        return $this->getRoute()->getObject();
    }
}
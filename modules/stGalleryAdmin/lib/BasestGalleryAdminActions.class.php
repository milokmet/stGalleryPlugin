<?php

class BasestGalleryAdminActions extends sfActions
{
    public function preExecute()
    {
        $this->record = $this->getRecord();
        $this->route  = $this->getRoute();
        
        if (!$this->record->userCanModify($this->getUser()))
        {
            $this->forward404();
        }
        
    }
    
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('stGalleryAdmin', 'edit');
    }
    
    public function executeEdit(sfWebRequest $request)
    {
        $this->form = new stGalleryUploadForm(array(), array('path' => $this->getAbsolutePath()));
        $this->pictures = Doctrine_Core::getTable('stPicture')->findAllForRecord($this->record);
        
        if ($request->isMethod('save'))
        {
            $this->forward('stGalleryAdmin', 'save');
        }
    }
    
    public function executeSave(sfWebRequest $request)
    {
        $ids = explode(':', $request->getParameter('pictures', null));
        $this->pictures = Doctrine_Core::getTable('stPicture')->findAllForRecord($this->record);
        
        $primaryKeys = $this->pictures->getPrimaryKeys();
        
        $primaryKeys = array_combine(array_values($primaryKeys), array_keys($primaryKeys));
        
        foreach ($ids as $i => $id)
        {
            $picture = $this->pictures[$primaryKeys[$id]]->setPosition($i);
            if (!$picture->isPublished())
            {
                $picture->publish();
            }
        }
        
        $this->pictures->save();
        
        $this->redirect($this->route->getRouteForEdit(), array('id' => $this->record->id));
    }
    
    public function executeLoadPicture(sfWebRequest $request)
    {
        $picture = $this->route->getPicture();
        
        return $this->renderText($this->getPartial('picture_box', array('picture' => $picture, 'route' => $this->route)));
    }
    
    public function executeDeletePicture(sfWebRequest $request)
    {
        $picture = $this->route->getPicture();
        if ($picture instanceof stPicture)
        {
            $picture->delete();
        }
        else
        {
            return $this->renderText('0');
        }

        return $this->renderText('1');
    }

    public function executeUpdatePicture(sfWebRequest $request)
    {
        $picture = $this->route->getPicture();
        
        $picture->caption = $request->getParameter('caption');
        
        $picture->save();
        
        return $this->renderText($picture->id);
    }
    
    public function executeUploadPictures(sfWebRequest $request)
    {
        $validator = new stValidatorGalleryFiles(array('path' => $this->getAbsolutePath()));

        $response = array('pictures' => array());
        try
        {
            $files = $validator->doClean($request->getFiles('files'));
            
            foreach ($files as $key => $file)
            {
                $savedFile = $file->getPath() . DIRECTORY_SEPARATOR . $file->save();
                
                $picture = new stPicture();
                $picture->fromArray(array(
                    'record_model' => get_class($this->getRecord()),
                    'record_id' => $this->getRecord()->get($this->getRecord()->getTable()->getIdentifier()),
                    'params' => array()
                ));
                
                $picture->setSource($savedFile);
                $picture->save();
                
                $response['pictures'][] = $picture->getId();
            }
        }
        catch (sfValidatorError $e)
        {
            $i18n = $this->getContext()->getI18n();
            $response['error'] = $i18n->__($e->getMessage(), array(), 'st_gallery');
        }
        
        if ($request->hasParameter('submit'))
        {
            return $this->redirect($this->getRoute()->getRouteForEdit(), $this->getRecord());
        }
        else
        {
            sfConfig::set('sf_web_debug', false);
            
            $this->getResponse()->setContentType('application/javascript');
            return $this->renderText(json_encode($response));
        }
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
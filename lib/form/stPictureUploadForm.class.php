<?php

class stPictureUploadForm extends sfForm
{
    public function setup()
    {
        $this->setWidgets(array(
            'file' => new sfWidgetFormInputFile(),
        ));
    }
}
<?php

class stGalleryUploadForm extends BaseForm
{
    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
    {
        if (!isset($options['path']))
        {
            throw new InvalidArgumentException('You must pass path option to the form!');
        }
        
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function setup()
    {
        $this->setWidgets(array(
            'files' => new sfWidgetFormInputFile(),
        ));
        
        $this->setValidators(array(
            'files' => new stValidatorGalleryFiles(array(
                'path' => $this->getOption('path'),
                'required' => true, 
            )),
        ));
        
        // $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateZippedFiles'))));
        
        $this->widgetSchema->setNameFormat('st_gallery[%s]');
    }
}
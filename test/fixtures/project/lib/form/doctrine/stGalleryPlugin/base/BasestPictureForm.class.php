<?php

/**
 * stPicture form base class.
 *
 * @method stPicture getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasestPictureForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'record_id'        => new sfWidgetFormInputText(),
      'record_model'     => new sfWidgetFormInputText(),
      'position'         => new sfWidgetFormInputText(),
      'caption'          => new sfWidgetFormTextarea(),
      'image'            => new sfWidgetFormTextarea(),
      'image_width'      => new sfWidgetFormInputText(),
      'image_height'     => new sfWidgetFormInputText(),
      'thumbnail'        => new sfWidgetFormTextarea(),
      'thumbnail_width'  => new sfWidgetFormInputText(),
      'thumbnail_height' => new sfWidgetFormInputText(),
      'source'           => new sfWidgetFormTextarea(),
      'is_published'     => new sfWidgetFormInputCheckbox(),
      'params'           => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'record_id'        => new sfValidatorInteger(array('required' => false)),
      'record_model'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'position'         => new sfValidatorInteger(array('required' => false)),
      'caption'          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'image'            => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'image_width'      => new sfValidatorInteger(array('required' => false)),
      'image_height'     => new sfValidatorInteger(array('required' => false)),
      'thumbnail'        => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'thumbnail_width'  => new sfValidatorInteger(array('required' => false)),
      'thumbnail_height' => new sfValidatorInteger(array('required' => false)),
      'source'           => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'is_published'     => new sfValidatorBoolean(array('required' => false)),
      'params'           => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('st_picture[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'stPicture';
  }

}

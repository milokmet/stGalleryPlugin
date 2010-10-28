<?php use_helper('I18N'); ?>
<?php echo $form->renderFormTag(url_for($route->getRouteForUpload(), $route->getObject()), array('method' => 'post', 'id' => 'st_gallery_upload_form')); ?>
  <?php echo $form['files']->renderLabel(); ?>
  <?php echo $form['files']->render(); ?>
  <div class='sf_admin_help'>
    <?php echo __('files_upload_help', array(), 'st_gallery'); ?>
  </div>
  <noscript>
    <input type='submit' name='submit' value='Odoslať' />
  </noscript>
</form>
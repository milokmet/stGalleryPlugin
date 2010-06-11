<?php use_helper('I18N'); ?>
<?php echo $form->renderFormTag(url_for($route->getRouteForUpload(), $route->getObject()), array('method' => 'post')); ?>
  <?php echo $form; ?>
  <input type='submit' />
</form>
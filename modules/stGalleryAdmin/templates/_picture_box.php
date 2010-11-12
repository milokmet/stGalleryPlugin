<?php use_helper('I18N'); ?>
<ul class='picture_actions'>
    <!-- li class='sf_admin_action_edit'><?php echo __('Edit', array(), 'st_gallery'); ?></li -->
    <li class='move'><a title='<?php echo __('Move', array(), 'st_gallery'); ?>'><?php echo __('Move', array(), 'st_gallery'); ?></a></li>
    <li class='delete'><?php echo link_to(__('Delete', array(), 'st_gallery'), $route->getRouteForDeletePicture(), array('id' => $route->getObject()->getId(), 'picture' => $picture['id']), array('title' => __('Delete', array(), 'st_gallery'))); ?></li>
</ul>
<?php  /*<h3><?php echo __('Image %%id%%', array('%%id%%' => $picture['id'], '%%caption%%' => $picture['caption']), 'st_gallery');?></h3> */ ?>
<div class='image'>
  <?php echo image_tag($picture['thumbnail'], array('alt_title' => (string)$picture['caption'], 'width' => $picture['thumbnail_width'], 'height' => $picture['thumbnail_height'])); ?>
</div>
<input type='hidden' name='id' value='<?php echo $picture['id']; ?>' />
<div>
<div class='controls'>
    <label><?php echo __('Caption', array(), 'st_gallery'); ?></label>
    <br /><input type='text' name='caption' value='<?php echo $picture['caption']; ?>' />
</div>
<div class='hidden_controls'></div>

</div>
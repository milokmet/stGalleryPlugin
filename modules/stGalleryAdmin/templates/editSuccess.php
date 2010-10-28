<div id='sf_admin_container'>
  <h1><?php echo __('title_editing_gallery_'.sfInflector::underscore(get_class($record)), array('%%record%%' => (string)$record), sfInflector::underscore(get_class($record))); ?></h1>
<div id='sf_admin_content'>
<?php include_partial('stGalleryAdmin/upload_form', array('route' => $route, 'form' => $form)); ?>

<ul class='pictures' id='st_gallery_pictures'>
    <?php foreach ($pictures as $picture): ?><li class='picture_box<?php !$picture['is_published'] && print(' unpublished');?>' id='picture_box_<?php echo $picture['id']; ?>'>
            <?php include_partial('stGalleryAdmin/picture_box', array('picture' => $picture, 'route' => $route)); ?>
    </li><?php endforeach; ?>
</ul>

<script type='text/javascript'>
$(document).ready(function(){
$('#st_gallery_pictures').galleryAdmin({
    load: '<?php echo url_for($route->getRouteForLoad(), array('id' => $record['id'], 'picture' => '--PICTURE--'));?>',
    upload: $('form#st_gallery_upload_form').attr('action'),
    update: '<?php echo url_for($route->getRouteForUpdatePicture(), array('id' => $record['id'], 'picture' => '--PICTURE--')); ?>',
    publish: 'publish_form_<?php echo $record->id; ?>'
});

stGalleryAdminMessages = {
    are_you_sure: "<?php echo __('Are you sure you want to delete a picture?', array(), 'st_gallery'); ?>"
};

});
</script>

    <form id='publish_form_<?php echo $record->id; ?>' action='<?php echo url_for($route->getRouteForEdit(), array('id' => $record['id']));?>' method='post'>
      <input type='hidden' name='pictures' value='juhuu' />
      <ul class='sf_admin_actions'>
        <li class='sf_admin_action_publish'><input type='submit' value='Publikovať obrázky a uložiť poradie' /></li>
      </ul>  
    </form>
  </div>
</div>
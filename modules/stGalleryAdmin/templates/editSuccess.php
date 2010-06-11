<h1><?php echo __('title_editing_gallery_'.sfInflector::underscore(get_class($record)), array('%%record%%' => (string)$record)); ?></h1>

<?php include_partial('stGalleryAdmin/upload_form', array('route' => $route, 'form' => $form)); ?>

<p>
    <?php foreach ($pictures as $picture): ?>
        <?php echo $picture['id']; ?><br />
    <?php endforeach; ?>
</p>

<ul class='gallery_list'>
  <?php foreach ($pictures as $picture): ?><li>
    <a rel='gallery' href='<?php echo $picture->image; ?>'><?php echo image_tag($picture->thumbnail, array('alt_title' => $picture->caption))?>
     <?php if (!empty($picture->caption)): ?>
       <span class='caption'><?php echo $picture->caption; ?></span>
     <?php endif; ?>
    </a>
  </li><?php endforeach; ?>
</ul>

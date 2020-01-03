<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="main-slider" class="owl-carousel main-slider">
    <?php foreach ($slider_items as $item): ?>
        <div class="item">
            <a href="<?php echo $item->link; ?>">
                <img src="<?php echo get_slider_image_url($item); ?>" class="owl-image" alt="slider">
            </a>
        </div>
    <?php endforeach; ?>
</div>

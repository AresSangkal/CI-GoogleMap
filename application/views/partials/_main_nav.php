<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($general_settings->selected_navigation == 1): ?>
    <div class="container">
        <div class="navbar navbar-light navbar-expand">
            <ul class="nav navbar-nav mega-menu">
                <?php
                if (!empty($parent_categories)):
                    foreach ($parent_categories as $category): ?>
                        <li class="nav-item dropdown" data-category-id="<?php echo $category->id; ?>">
                            <a href="<?php echo generate_category_url($category); ?>" class="nav-link dropdown-toggle">
                                <?php echo html_escape($category->name); ?>
                            </a>
                            <?php $subcategories = get_subcategories_by_parent_id($category->id);
                            if (!empty($subcategories)):?>
                                <div id="mega_menu_content_<?php echo $category->id; ?>" class="dropdown-menu">
                                    <div class="row">
                                        <div class="col-8 menu-subcategories">
                                            <div class="row">
                                                <?php foreach ($subcategories as $subcategory): ?>
                                                    <div class="col-4 col-level-two text-truncate">
                                                        <a href="<?php echo generate_category_url($subcategory); ?>" class="text-truncate second-category"><?php echo html_escape($subcategory->name); ?></a>
                                                        <?php
                                                        $third_categories = get_subcategories_by_parent_id($subcategory->id);
                                                        if (!empty($third_categories)): ?>
                                                            <ul>
                                                                <?php foreach ($third_categories as $item): ?>
                                                                    <li>
                                                                        <a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <a href="<?php echo generate_category_url($category); ?>">
                                                <img src="<?php echo get_category_image_url($category, 'image_1'); ?>" alt="<?php echo html_escape($category->name); ?>" class="img-fluid">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php
                    endforeach;
                endif;
                ?>
            </ul>
        </div>
    </div>
<?php else: ?>
    <div class="container">
        <div class="navbar navbar-light navbar-expand">
            <ul class="nav navbar-nav mega-menu">
                <?php
                if (!empty($parent_categories)):
                    foreach ($parent_categories as $category): ?>
                        <li class="nav-item dropdown" data-category-id="<?php echo $category->id; ?>">
                            <a href="<?php echo generate_category_url($category); ?>" class="nav-link dropdown-toggle">
                                <?php echo html_escape($category->name); ?>
                            </a>
                            <?php $subcategories = get_subcategories_by_parent_id($category->id);
                            if (!empty($subcategories)):?>
                                <div id="mega_menu_content_<?php echo $category->id; ?>" class="dropdown-menu dropdown-menu-large">
                                    <div class="row">

                                        <div class="col-4 left">
                                            <?php
                                            $count = 0;
                                            foreach ($subcategories as $subcategory): ?>
                                                <div class="large-menu-item <?php echo ($count == 0) ? 'large-menu-item-first active' : ''; ?>" data-subcategory-id="<?php echo $subcategory->id; ?>">
                                                    <a href="<?php echo generate_category_url($subcategory); ?>" class="second-category">
                                                        <?php echo html_escape($subcategory->name); ?>
                                                        <i class="icon-arrow-right"></i>
                                                    </a>
                                                </div>
                                                <?php
                                                $count++;
                                            endforeach; ?>
                                        </div>

                                        <div class="col-8 right">
                                            <?php
                                            $count = 0;
                                            foreach ($subcategories as $subcategory): ?>
                                                <div id="large_menu_content_<?php echo $subcategory->id; ?>" class="large-menu-content <?php echo ($count == 0) ? 'large-menu-content-first active' : ''; ?>">
                                                    <?php $third_categories = get_subcategories_by_parent_id($subcategory->id);
                                                    if (!empty($third_categories)): ?>
                                                        <div class="row">
                                                            <?php foreach ($third_categories as $item): ?>
                                                                <div class="col-6 item-large-menu-content">
                                                                    <a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php
                                                $count++;
                                            endforeach; ?>
                                        </div>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php
                    endforeach;
                endif;
                ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
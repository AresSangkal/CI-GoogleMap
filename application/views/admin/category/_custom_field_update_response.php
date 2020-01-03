<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo trans('show_under_these_categories'); ?></label>
        </div>

        <div class="col-sm-12">
            <table class="table table-bordered table-striped" role="grid">
                <tbody>
                <?php
                if (!empty($field_categories)):
                    foreach ($field_categories as $item):
                        $category = get_category_joined($item->category_id);
                        if (!empty($category)): ?>

                            <?php if ($category->category_level == 1): ?>
                                <tr>
                                    <td>
                                        <?php echo html_escape($category->name); ?>
                                        <button type="button" class="btn btn-xs btn-default pull-right" onclick="delete_custom_field_category(<?php echo $field->id; ?>,<?php echo $category->id; ?>);"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($category->category_level == 2):
                                $parent = $this->category_model->get_category_joined($category->parent_id); ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($parent)) {
                                            echo html_escape($parent->name);
                                        }
                                        echo " / " . html_escape($category->name);
                                        ?>
                                        <button type="button" class="btn btn-xs btn-default pull-right" onclick="delete_custom_field_category(<?php echo $field->id; ?>,<?php echo $category->id; ?>);"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($category->category_level == 3):
                                $top_parent = $this->category_model->get_category_joined($category->top_parent_id);
                                $parent = $this->category_model->get_category_joined($category->parent_id); ?>
                                <tr>
                                    <td>
                                        <?php
                                        if (!empty($top_parent)) {
                                            echo html_escape($top_parent->name);
                                        }
                                        if (!empty($parent)) {
                                            echo " / " . html_escape($parent->name);
                                        }
                                        echo " / " . html_escape($category->name);
                                        ?>
                                        <button type="button" class="btn btn-xs btn-default pull-right" onclick="delete_custom_field_category(<?php echo $field->id; ?>,<?php echo $category->id; ?>);"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        <?php endif;
                    endforeach;
                endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12">
            <button type="button" id="btn_show_custom_field_category_select" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>&nbsp;<?php echo trans('select_category'); ?></button>
            <?php if (!empty($field_categories)): ?>
                <button type="button" id="btn_clear_custom_fields_categories" class="btn btn-sm btn-danger" onclick="clear_custom_files_categories(<?php echo $field->id; ?>);"><?php echo trans('clear'); ?></button>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="category_select_container">
    <div class="form-group">
        <label><?php echo trans('select_category'); ?></label>
        <select id="select_field_add_category" class="form-control" name="category_id" required>
            <option value="0"><?php echo trans('none'); ?></option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category->id; ?>"><?php echo html_escape($category->name); ?></option>
                <?php
                $subcategories = $this->category_model->get_subcategories_by_parent_id($category->id);
                if (!empty($subcategories)):
                    foreach ($subcategories as $subcategory): ?>
                        <option value="<?php echo $subcategory->id; ?>"><?php echo html_escape($category->name . " / " . $subcategory->name); ?></option>
                        <?php
                        $thirdcategories = $this->category_model->get_subcategories_by_parent_id($subcategory->id);
                        if (!empty($thirdcategories)):
                            foreach ($thirdcategories as $thirdcategory): ?>
                                <option value="<?php echo $thirdcategory->id; ?>"><?php echo html_escape($category->name . " / " . $subcategory->name . " / " . $thirdcategory->name); ?></option>
                            <?php
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <button type="button" id="btn_field_add_category" class="btn btn-sm btn-success" data-field-id="<?php echo $field->id; ?>"><?php echo trans('add_category'); ?></button>
        <button type="button" id="btn_close_custom_field_category_select" class="btn btn-sm btn-danger"><?php echo trans('close'); ?></button>
    </div>
</div>

<style>
    #category_select_container {
        display: none;
    }
</style>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($subcategories)): ?>
    <div class="form-group">
        <div class="selectdiv">
            <select name="third_category_id" class="form-control selecter" <?php echo (!empty($subcategories)) ? 'required' : ''; ?> onchange="get_custom_fields_by_third_category(this.value,'<?php echo $lang_id; ?>');">
                <option value=""><?php echo trans('select_category'); ?></option>
                <?php foreach ($subcategories as $item): ?>
                    <option value="<?php echo html_escape($item->id); ?>"><?php echo html_escape($item->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php else: ?>
    <input type="hidden" name="third_category_id" value="0">
<?php endif; ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if (!empty($custom_fields)):
    foreach ($custom_fields as $custom_field):
        if (!empty($custom_field)):
            $category = get_category($custom_field->category_id);
            $field_value = html_escape($custom_field->field_value); ?>

            <?php if ($custom_field->field_type == "text"): ?>
            <div class="col-12 <?php echo ($custom_field->row_width == "half") ? "col-sm-6" : "col-sm-12"; ?> col-custom-field custom-field-type-<?php echo $category->category_level; ?>">
                <label class="control-label"><?php echo html_escape($custom_field->name); ?></label>
                <input type="text" name="field_<?php echo $custom_field->id; ?>" class="form-control form-input" value="<?php echo html_escape($field_value); ?>"
                       placeholder="<?php echo html_escape($custom_field->name); ?>" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?>>
            </div>
        <?php elseif ($custom_field->field_type == "number"): ?>
            <div class="col-12 <?php echo ($custom_field->row_width == "half") ? "col-sm-6" : "col-sm-12"; ?> col-custom-field custom-field-type-<?php echo $category->category_level; ?>">
                <label class="control-label"><?php echo html_escape($custom_field->name); ?></label>
                <input type="number" name="field_<?php echo $custom_field->id; ?>" class="form-control form-input" value="<?php echo html_escape($field_value); ?>"
                       placeholder="<?php echo html_escape($custom_field->name); ?>" min="0" max="999999999" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?>>
            </div>
        <?php elseif ($custom_field->field_type == "textarea"): ?>
            <div class="col-12 <?php echo ($custom_field->row_width == "half") ? "col-sm-6" : "col-sm-12"; ?> col-custom-field custom-field-type-<?php echo $category->category_level; ?>">
                <label class="control-label"><?php echo html_escape($custom_field->name); ?></label>
                <textarea class="form-control form-input custom-field-input" name="field_<?php echo $custom_field->id; ?>"
                          placeholder="<?php echo html_escape($custom_field->name); ?>" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?>><?php echo $field_value; ?></textarea>
            </div>

        <?php elseif ($custom_field->field_type == "dropdown"): ?>
            <?php $options = get_custom_field_options($custom_field->id); ?>
            <div class="col-12 <?php echo ($custom_field->row_width == "half") ? "col-sm-6" : "col-sm-12"; ?> col-custom-field custom-field-type-<?php echo $category->category_level; ?>">
                <label class="control-label"><?php echo html_escape($custom_field->name); ?></label>
                <div class="selectdiv">
                    <select name="field_<?php echo $custom_field->id; ?>" class="form-control" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?>>
                        <option value=""><?php echo trans('select'); ?></option>
                        <?php if (!empty($options)):
                            foreach ($options as $option):?>
                                <option value="<?php echo html_escape($option->field_option); ?>" <?php echo ($field_value == html_escape($option->field_option)) ? 'selected' : ''; ?>><?php echo html_escape($option->field_option); ?></option>
                            <?php endforeach;
                        endif; ?>
                    </select>
                </div>
            </div>
        <?php elseif ($custom_field->field_type == "select_box"): ?>
            <div class="col-12 <?php echo ($custom_field->row_width == "half") ? "col-sm-6" : "col-sm-12"; ?> col-custom-field custom-field-type-<?php echo $category->category_level; ?>">
                <label class="control-label"><?php echo html_escape($custom_field->name); ?></label>
                <div class="row">
                    <?php $options = get_custom_field_options_by_lang($custom_field->id, $lang_id); ?>
                    <?php if (!empty($options)):
                        $count = 0;
                        foreach ($options as $option):?>
                            <div class="col-12 col-sm-3">
                                <div class="custom-control custom-radio">
                                    <?php if (is_null(trim($field_value))): ?>
                                        <?php if ($custom_field->is_required == 1 && $count == 0): ?>
                                            <input type="radio" class="custom-control-input" id="form_radio_<?php echo $option->id; ?>" name="field_<?php echo $custom_field->id; ?>"
                                                   value="<?php echo html_escape($option->field_option); ?>" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?> checked>
                                        <?php else: ?>
                                            <input type="radio" class="custom-control-input" id="form_radio_<?php echo $option->id; ?>" name="field_<?php echo $custom_field->id; ?>"
                                                   value="<?php echo html_escape($option->field_option); ?>" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?>>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($field_value == $option->field_option): ?>
                                            <input type="radio" class="custom-control-input" id="form_radio_<?php echo $option->id; ?>" name="field_<?php echo $custom_field->id; ?>"
                                                   value="<?php echo html_escape($option->field_option); ?>" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?> checked>
                                        <?php else: ?>
                                            <input type="radio" class="custom-control-input" id="form_radio_<?php echo $option->id; ?>" name="field_<?php echo $custom_field->id; ?>"
                                                   value="<?php echo html_escape($option->field_option); ?>" <?php echo ($custom_field->is_required == 1) ? 'required' : ''; ?>>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <label class="custom-control-label label-payment-option" for="form_radio_<?php echo $option->id; ?>"><?php echo html_escape($option->field_option); ?></label>
                                </div>
                            </div>
                            <?php $count++;
                        endforeach;
                    endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
        endif;
    endforeach;
endif; ?>
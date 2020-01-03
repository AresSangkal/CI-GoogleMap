<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if (isset($field_id)) {
    $options = $this->field_model->get_field_all_options($field->id);
} else {
    $options = get_sess_custom_field_options_array();
}

$language_ids = "";
foreach ($languages as $language):
    $language_ids .= "," . $language->id;
endforeach;
$language_ids = trim($language_ids, ',');
?>
<div class="form-group">
    <label class="m-0"><?php echo trans('options'); ?></label>
    <div id="options_response">
        <?php $count = 1; ?>
        <?php if (!empty($options)):
            $last_common_id = "";
            foreach ($options as $option):?>
                <div class="field-option-item">
                    <?php if ($last_common_id != $option->common_id): ?>
                        <p class="option-first">
                            <strong><?php echo $count; ?>:</strong><?php echo html_escape($option->field_option); ?>
                            <?php if (isset($field_id)): ?>
                                <button type="button" class="btn btn-xs btn-default pull-right" onclick="delete_custom_field_option('<?php echo $option->common_id; ?>');">
                                    <i class="fa fa-times"></i>
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-xs btn-default pull-right" onclick="delete_custom_field_option_session('<?php echo $option->common_id; ?>');">
                                    <i class="fa fa-times"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <?php $count++;
                    else: ?>
                        <p><strong></strong><?php echo html_escape($option->field_option); ?></p>
                    <?php endif; ?>
                </div>
                <?php
                $last_common_id = $option->common_id;
            endforeach;
        endif; ?>
    </div>
</div>
<div class="form-group m-b-5">
    <label><?php echo trans("add_option"); ?></label>
    <?php foreach ($languages as $language): ?>
        <input type="text" class="form-control option-input m-b-5" id="option_lang_<?php echo $language->id; ?>" placeholder="Option (<?php echo $language->name; ?>)">
    <?php endforeach; ?>
</div>

<?php if (isset($field_id)): ?>
    <div class="form-group">
        <button type="button" id="btn_add_custom_field_option" class="btn btn-sm btn-warning" data-field-id="<?php echo $field_id; ?>" data-language-ids="<?php echo $language_ids; ?>"><?php echo trans('add_option'); ?></button>
    </div>
<?php else: ?>
    <div class="form-group">
        <button type="button" id="btn_add_custom_field_option_session" class="btn btn-sm btn-warning" data-language-ids="<?php echo $language_ids; ?>"><?php echo trans('add_option'); ?></button>
    </div>
<?php endif; ?>





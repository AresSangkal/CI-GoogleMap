<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo trans("update_custom_field"); ?></h3>
                </div>
                <!-- /.box-header -->

                <!-- form start -->
                <?php echo form_open_multipart('category_controller/update_custom_field_post', ['onkeypress' => 'return event.keyCode != 13;']); ?>
                <input type="hidden" name="id" value="<?php echo $field->id; ?>">
                <div class="box-body">
                    <!-- include message block -->
                    <?php $this->load->view('admin/includes/_messages'); ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <?php foreach ($languages as $language): ?>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label><?php echo trans("field_name"); ?> (<?php echo $language->name; ?>)</label>
                                        <input type="text" class="form-control" name="name_lang_<?php echo $language->id; ?>" placeholder="<?php echo trans("field_name"); ?>"
                                               value="<?php echo get_custom_field_name_by_lang($field->id, $language->id); ?>" maxlength="255" required>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <label><?php echo trans('row_width'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="row_width" value="half" id="row_width_1" class="square-purple" <?php echo ($field->row_width == "half") ? "checked" : ""; ?>>
                                        <label for="row_width_1" class="option-label"><?php echo trans('half_width'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="row_width" value="full" id="row_width_2" class="square-purple" <?php echo ($field->row_width == "full") ? "checked" : ""; ?>>
                                        <label for="row_width_2" class="option-label"><?php echo trans('full_width'); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <label class="control-label"><?php echo trans('required'); ?></label>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <input type="checkbox" name="is_required" value="1" class="square-purple" <?php echo ($field->is_required == 1) ? "checked" : ""; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <label><?php echo trans('status'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="status" value="1" id="status_1" class="square-purple" <?php echo ($field->status == 1) ? "checked" : ""; ?>>
                                        <label for="status_1" class="option-label"><?php echo trans('active'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="status" value="0" id="status_2" class="square-purple" <?php echo ($field->status != 1) ? "checked" : ""; ?>>
                                        <label for="status_2" class="option-label"><?php echo trans('inactive'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo trans('order'); ?></label>
                                <input type="number" class="form-control" name="field_order" placeholder="<?php echo trans('order'); ?>"
                                       value="<?php echo html_escape($field->field_order); ?>" min="1" max="99999" required>
                            </div>

                            <div class="form-group">
                                <label><?php echo trans('type'); ?></label>
                                <select class="form-control" name="field_type">
                                    <option value="text" <?php echo ($field->field_type == "text") ? "selected" : ""; ?>>Text</option>
                                    <option value="textarea" <?php echo ($field->field_type == "textarea") ? "selected" : ""; ?>>Textarea</option>
                                    <option value="number" <?php echo ($field->field_type == "number") ? "selected" : ""; ?>>Number</option>
                                    <option value="dropdown" <?php echo ($field->field_type == "dropdown") ? "selected" : ""; ?>>Dropdown</option>
                                    <option value="select_box" <?php echo ($field->field_type == "select_box") ? "selected" : ""; ?>>Select Box</option>
                                </select>
                            </div>

                            <div id="custom_field_options_response" class="custom-fields-options-container" <?php echo ($field->field_type == "dropdown" || $field->field_type == "select_box") ? 'style="display:block;"' : ''; ?>>
                                <?php $this->load->view('admin/category/_custom_field_options_response', ["field_id" => $field->id]); ?>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div id="custom_fields_response">
                                <?php $this->load->view('admin/category/_custom_field_update_response'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
                </div>
                <!-- /.box-footer -->
                <?php echo form_close(); ?><!-- form end -->
            </div>
            <!-- /.box -->
        </div>
    </div>
<?php $this->load->view('admin/category/_js_custom_fields'); ?>
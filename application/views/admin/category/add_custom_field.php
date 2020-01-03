<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo trans("add_custom_field"); ?></h3>
                </div>
                <!-- /.box-header -->

                <!-- form start -->
                <?php echo form_open_multipart('category_controller/add_custom_field_post', ['onkeypress' => 'return event.keyCode != 13;']); ?>
                <div class="box-body">
                    <!-- include message block -->
                    <?php $this->load->view('admin/includes/_messages'); ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <?php foreach ($languages as $language): ?>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label><?php echo trans("field_name"); ?> (<?php echo $language->name; ?>)</label>
                                        <input type="text" class="form-control" name="name_lang_<?php echo $language->id; ?>" placeholder="<?php echo trans("field_name"); ?>" maxlength="255" required>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <label><?php echo trans('row_width'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="row_width" value="half" id="row_width_1" class="square-purple" checked>
                                        <label for="row_width_1" class="option-label"><?php echo trans('half_width'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="row_width" value="full" id="row_width_2" class="square-purple">
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
                                        <input type="checkbox" name="is_required" value="1" class="square-purple">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <label><?php echo trans('status'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="status" value="1" id="status_1" class="square-purple" checked>
                                        <label for="status_1" class="option-label"><?php echo trans('active'); ?></label>
                                    </div>
                                    <div class="col-sm-4 col-xs-12 col-option">
                                        <input type="radio" name="status" value="0" id="status_2" class="square-purple">
                                        <label for="status_2" class="option-label"><?php echo trans('inactive'); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?php echo trans('order'); ?></label>
                                <input type="number" class="form-control" name="field_order" placeholder="<?php echo trans('order'); ?>" min="1" max="99999" value="1" required>
                            </div>

                            <div class="form-group">
                                <label><?php echo trans('type'); ?></label>
                                <select class="form-control" name="field_type" onchange="show_custom_field_options(this.value);">
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="number">Number</option>
                                    <option value="dropdown">Dropdown</option>
                                    <option value="select_box">Select Box</option>
                                </select>
                            </div>

                            <div id="custom_field_options_response" class="custom-fields-options-container">
                                <?php $this->load->view('admin/category/_custom_field_options_response'); ?>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="custom_fields_response">
                                <?php $this->load->view('admin/category/_custom_field_response'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_custom_field'); ?></button>
                </div>
                <!-- /.box-footer -->
                <?php echo form_close(); ?><!-- form end -->
            </div>
            <!-- /.box -->
        </div>
    </div>
<?php $this->load->view('admin/category/_js_custom_fields'); ?>
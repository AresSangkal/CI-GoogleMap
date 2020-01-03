<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('system_settings'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/system_settings_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-10">
                            <label><?php echo trans('system_selection'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="selected_system" value="marketplace" id="selected_system_marketplace" class="square-purple" <?php echo ($general_settings->selected_system == "marketplace") ? 'checked' : ''; ?>>
                            <label for="selected_system_marketplace" class="option-label"><?php echo trans('marketplace'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="selected_system" value="classified_ads" id="selected_system_classified_ads" class="square-purple" <?php echo ($general_settings->selected_system == "classified_ads") ? 'checked' : ''; ?>>
                            <label for="selected_system_classified_ads" class="option-label"><?php echo trans('classified_ads'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group system-currency-select" <?php echo ($general_settings->selected_system == "marketplace") ? 'style="display:block;"' : ''; ?>>
                    <label class="control-label"><?php echo trans('default_product_currency'); ?></label>
                    <select name="default_product_currency" class="form-control" required>
                        <?php if (!empty($currencies)):
                            foreach ($currencies as $item): ?>
                                <option value="<?php echo $item->code; ?>" <?php echo ($this->payment_settings->default_product_currency == $item->code) ? 'selected' : ''; ?>><?php echo $item->name . " (" . $item->hex . ")"; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group system-currency-select" <?php echo ($general_settings->selected_system == "marketplace") ? 'style="display:block;"' : ''; ?>">
                <label><?php echo trans('commission_rate'); ?></label>
                <input type="number" name="commission_rate" class="form-control" min="0" max="100" value="<?php echo $general_settings->commission_rate; ?>">
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12 col-xs-12 m-b-10">
                        <label><?php echo trans('multi_vendor_system'); ?></label>
                        <small style="font-size: 13px;">(<?php echo trans("multi_vendor_system_exp"); ?>)</small>
                    </div>
                    <div class="col-sm-6 col-xs-12 col-option">
                        <input type="radio" name="multi_vendor_system" value="1" id="multi_vendor_system_1" class="square-purple" <?php echo ($general_settings->multi_vendor_system == 1) ? 'checked' : ''; ?>>
                        <label for="multi_vendor_system_1" class="option-label"><?php echo trans('enable'); ?></label>
                    </div>
                    <div class="col-sm-6 col-xs-12 col-option">
                        <input type="radio" name="multi_vendor_system" value="0" id="multi_vendor_system_2" class="square-purple" <?php echo ($general_settings->multi_vendor_system != 1) ? 'checked' : ''; ?>>
                        <label for="multi_vendor_system_2" class="option-label"><?php echo trans('disable'); ?></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo trans('timezone'); ?></label>
                <input type="text" class="form-control" name="timezone" placeholder="<?php echo trans('timezone'); ?>"
                       value="<?php echo html_escape($general_settings->timezone); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                <a href="http://php.net/manual/en/timezones.php" target="_blank">Timeszones</a>
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
        </div>
        <!-- /.box-footer -->
        <!-- /.box -->
        <?php echo form_close(); ?><!-- form end -->
    </div>
</div>
</div>
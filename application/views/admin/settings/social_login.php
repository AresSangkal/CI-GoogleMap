<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;"><?php echo trans('social_login'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('twitter_login'); ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open('admin_controller/twitter_login_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('mes_social_twitter'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('api_key'); ?></label>
                    <input type="text" class="form-control" name="twitter_api_key" placeholder="<?php echo trans('api_key'); ?>"
                           value="<?php echo $general_settings->twitter_api_key; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="twitter_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $general_settings->twitter_secret_key; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('facebook_login'); ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open('admin_controller/facebook_login_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('mes_social_facebook'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('app_id'); ?></label>
                    <input type="text" class="form-control" name="facebook_app_id" placeholder="<?php echo trans('app_id'); ?>"
                           value="<?php echo $general_settings->facebook_app_id; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
</div>


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                    </ol>
                </nav>

                <h1 class="page-title"><?php echo trans("settings"); ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("settings/_setting_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row-custom">
                    <div class="profile-tab-content">
                        <!-- include message block -->
                        <?php $this->load->view('partials/_messages'); ?>

                        <?php echo form_open("profile_controller/preferences_post", ['id' => 'form_validate']); ?>

                        <?php if ($this->general_settings->rss_system == 1): ?>
                            <div class="form-group m-t-10">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="control-label"><?php echo trans('rss_feeds'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-12 col-option">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="show_rss_feeds" value="1" id="rss_system_1" class="custom-control-input" <?php echo ($user->show_rss_feeds == 1) ? 'checked' : ''; ?>>
                                            <label for="rss_system_1" class="custom-control-label"><?php echo trans("enable"); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-12 col-option">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="show_rss_feeds" value="0" id="rss_system_2" class="custom-control-input" <?php echo ($user->show_rss_feeds != 1) ? 'checked' : ''; ?>>
                                            <label for="rss_system_2" class="custom-control-label"><?php echo trans("disable"); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="show_rss_feeds" value="<?php echo $user->show_rss_feeds; ?>">
                        <?php endif; ?>

                        <div class="form-group m-t-10">
                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label"><?php echo trans('email_option_send_email_new_message'); ?></label>
                                </div>
                                <div class="col-md-3 col-sm-4 col-12 col-option">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="send_email_new_message" value="1" id="send_email_new_message_1" class="custom-control-input" <?php echo ($user->send_email_new_message == 1) ? 'checked' : ''; ?>>
                                        <label for="send_email_new_message_1" class="custom-control-label"><?php echo trans("yes"); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-12 col-option">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="send_email_new_message" value="0" id="send_email_new_message_2" class="custom-control-input" <?php echo ($user->send_email_new_message != 1) ? 'checked' : ''; ?>>
                                        <label for="send_email_new_message_2" class="custom-control-label"><?php echo trans("no"); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-md btn-custom"><?php echo trans("save_changes") ?></button>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->


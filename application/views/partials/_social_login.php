<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ((!empty($general_settings->facebook_app_id)) ||
    (!empty($general_settings->twitter_api_key) && !empty($general_settings->twitter_secret_key))): ?>
    <p class="p-social-media"><?php echo trans("connect_with_social"); ?></p>
    <div class="row">
        <div class="col-12 text-center">
            <?php if (!empty($general_settings->facebook_app_id)): ?>
                <a href="javascript:void(0)" class="btn-social-login btn-login-facebook">
                    <i class="icon-facebook"></i>
                </a>
            <?php endif; ?>
            <?php if (!empty($general_settings->twitter_api_key) && !empty($general_settings->twitter_secret_key)): ?>
                <a href="<?php echo get_twitter_login_url(); ?>" class="btn-social-login btn-login-twitter">
                    <i class="icon-twitter"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group m-t-10">
        <p class="p-social-media m-0"><?php echo $or_text; ?></p>
    </div>
<?php endif; ?>
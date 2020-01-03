<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="row-custom">
                                <div class="footer-logo">
                                    <a href="<?php echo lang_base_url(); ?>"><img src="<?php echo get_logo($general_settings); ?>" alt="logo"></a>
                                </div>
                            </div>
                            <div class="row-custom">
                                <div class="footer-about">
                                    <?php echo html_escape($settings->about_footer); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="nav-footer">
                                <div class="row-custom">
                                    <h4 class="footer-title"><?php echo trans("footer_quick_links"); ?></h4>
                                </div>
                                <div class="row-custom">
                                    <ul>
                                        <li><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                                        <li><a href="<?php echo lang_base_url(); ?>blog"><?php echo trans("blog"); ?></a></li>
                                        <li><a href="<?php echo lang_base_url(); ?>contact"><?php echo trans("contact"); ?></a></li>
                                        <?php foreach ($footer_quick_links as $item): ?>
                                            <li><a href="<?php echo lang_base_url() . $item->slug; ?>"><?php echo html_escape($item->title); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="nav-footer">
                                <div class="row-custom">
                                    <h4 class="footer-title"><?php echo trans("footer_information"); ?></h4>
                                </div>
                                <div class="row-custom">
                                    <ul>
                                        <?php foreach ($footer_information_links as $item): ?>
                                            <li><a href="<?php echo lang_base_url() . $item->slug; ?>"><?php echo html_escape($item->title); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="footer-title"><?php echo trans("follow_us"); ?></h4>
                                    <div class="footer-social-links">
                                        <!--include social links-->
                                        <?php $this->load->view('partials/_social_links', ['show_rss' => true]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="newsletter">
                                        <h4 class="footer-title"><?php echo trans("newsletter"); ?></h4>
                                        <?php echo form_open('home_controller/add_to_subscribers', ['id' => 'form_validate']); ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="newsletter-inner">
                                                    <div class="d-table-cell">
                                                        <input type="email" class="form-control" name="email" placeholder="<?php echo trans("enter_email"); ?>" required>
                                                    </div>
                                                    <div class="d-table-cell align-middle">
                                                        <button class="btn btn-default"><?php echo trans("subscribe"); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>

                                        <div class="row">
                                            <div class="col-12">
                                                <div id="newsletter" class="m-t-5">
                                                    <?php
                                                    if ($this->session->flashdata('news_error')):
                                                        echo '<span class="text-danger">' . $this->session->flashdata('news_error') . '</span>';
                                                    endif;

                                                    if ($this->session->flashdata('news_success')):
                                                        echo '<span class="text-success">' . $this->session->flashdata('news_success') . '</span>';
                                                    endif;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-region">
                                <div class="col-12">
                                    <?php if ($general_settings->default_product_location == 0): ?>
                                        <div class="region-left">
                                            <div class="row-custom footer-location">
                                                <div class="icon-text">
                                                    <i class="icon-map-marker"></i>
                                                </div>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <?php echo $default_location; ?>&nbsp;<span class="icon-arrow-down"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="set_default_location('all');"><?php echo trans("all"); ?></a>
                                                        <?php if (!empty($countries)): foreach ($countries as $item): ?>
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="set_default_location('<?php echo $item->id; ?>');"><?php echo html_escape($item->name); ?></a>
                                                        <?php endforeach;
                                                        endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="region-right">
                                        <?php if ($general_settings->multilingual_system == 1 && count($languages) > 1): ?>
                                            <div class="row-custom">
                                                <div class="dropdown language-dropdown">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-language"></i>
                                                        <?php echo html_escape($selected_lang->name); ?>&nbsp;<span class="icon-arrow-down"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <?php foreach ($languages as $language):
                                                            $lang_url = base_url() . $language->short_form . "/";
                                                            if ($language->id == $this->general_settings->site_lang) {
                                                                $lang_url = base_url();
                                                            } ?>
                                                            <a href="<?php echo $lang_url; ?>" class="<?php echo ($language->id == $selected_lang->id) ? 'selected' : ''; ?> " class="dropdown-item">
                                                                <?php echo $language->name; ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="footer-bottom">
                <div class="container">
                    <div class="copyright">
                        <?php echo html_escape($settings->copyright); ?>
                    </div>
                    <div class="payments">
                        <img src="<?php echo base_url(); ?>assets/img/payments.png" alt="payments" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php if (!isset($_COOKIE["modesy_cookies_warning"]) && $settings->cookies_warning): ?>
    <div class="cookies-warning">
        <div class="text"><?php echo $this->settings->cookies_warning_text; ?></div>
        <a href="javascript:void(0)" onclick="hide_cookies_warning();" class="icon-cl"> <i class="icon-close"></i></a>
    </div>
<?php endif; ?>
<!-- Scroll Up Link -->
<a href="javascript:void(0)" class="scrollup"><i class="icon-arrow-up"></i></a>
<script>$('<input>').attr({type: 'hidden', name: 'form_lang_base_url', value: '<?php echo lang_base_url(); ?>'}).appendTo('form');</script>
<script>$('<input>').attr({type: 'hidden', name: 'lang_folder', value: '<?php echo $this->selected_lang->folder_name; ?>'}).appendTo('form');</script>
<!-- Popper JS-->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/popper.min.js"></script>
<!-- Bootstrap JS-->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Owl-carousel -->
<script src="<?php echo base_url(); ?>assets/vendor/owl-carousel/owl.carousel.min.js"></script>
<!-- Plugins JS-->
<script src="<?php echo base_url(); ?>assets/js/plugins.js"></script>
<script>
var base_url = '<?php echo base_url(); ?>'; var thousands_separator='<?php echo $this->thousands_separator; ?>'; var lang_folder = '<?php echo $this->selected_lang->folder_name; ?>';var fb_app_id = '<?php echo $this->general_settings->facebook_app_id; ?>';var csfr_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';var csfr_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';var is_recaptcha_enabled = false;<?php if ($recaptcha_status == true): ?>is_recaptcha_enabled = true;<?php endif; ?>var img_uplaod_max_file_size = '<?php echo $this->img_uplaod_max_file_size; ?>';
</script>
<!-- Custom JS-->
<script src="<?php echo base_url(); ?>assets/js/script-1.3.2.min.js"></script>
<?php if (!auth_check()): ?>
<?php if (!empty($general_settings->facebook_app_id)): ?>
<script>$(document).on("click",".btn-login-facebook",function(){FB.login(function(a){if(a.status==="connected"){FB.api("/me?fields=email,first_name,last_name",function(c){var b={id:c.id,email:c.email,first_name:c.first_name,last_name:c.last_name,};b[csfr_token_name]=$.cookie(csfr_cookie_name);$.ajax({type:"POST",url:base_url+"auth_controller/login_with_facebook",data:b,success:function(d){location.reload()}})})}else{}},{scope:"email"})});window.fbAsyncInit=function(){FB.init({appId:fb_app_id,cookie:true,xfbml:true,version:"v2.8"})};(function(a,f,c){var e,b=a.getElementsByTagName(f)[0];if(a.getElementById(c)){return}e=a.createElement(f);e.id=c;e.src="https://connect.facebook.net/en_US/sdk.js";b.parentNode.insertBefore(e,b)}(document,"script","facebook-jssdk"));</script>
<?php endif; ?>
<?php endif; ?>
</body>
</html>
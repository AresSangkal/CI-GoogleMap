<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo html_escape($title); ?> - <?php echo html_escape($settings->site_title); ?></title>
    <meta name="description" content="<?php echo html_escape($description); ?>"/>
    <meta name="keywords" content="<?php echo html_escape($keywords); ?>"/>
    <meta name="author" content="Codingest"/>
    <link rel="shortcut icon" type="image/png" href="<?php echo get_favicon($general_settings); ?>"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:site_name" content="<?php echo html_escape($general_settings->application_name); ?>"/>
<?php if (isset($show_og_tags)): ?>
    <meta property="og:type" content="<?php echo $og_type; ?>"/>
    <meta property="og:title" content="<?php echo $og_title; ?>"/>
    <meta property="og:description" content="<?php echo $og_description; ?>"/>
    <meta property="og:url" content="<?php echo $og_url; ?>"/>
    <meta property="og:image" content="<?php echo $og_image; ?>"/>
    <meta property="og:image:width" content="<?php echo $og_width; ?>"/>
    <meta property="og:image:height" content="<?php echo $og_height; ?>"/>
    <meta property="article:author" content="<?php echo $og_author; ?>"/>
    <meta property="fb:app_id" content="<?php echo $this->general_settings->facebook_app_id; ?>"/>
<?php if (!empty($og_tags)):foreach ($og_tags as $tag): ?>
    <meta property="article:tag" content="<?php echo $tag->tag; ?>"/>
<?php endforeach; endif; ?>
    <meta property="article:published_time" content="<?php echo $og_published_time; ?>"/>
    <meta property="article:modified_time" content="<?php echo $og_modified_time; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?php echo html_escape($general_settings->application_name); ?>"/>
    <meta name="twitter:creator" content="@<?php echo html_escape($og_creator); ?>"/>
    <meta name="twitter:title" content="<?php echo html_escape($og_title); ?>"/>
    <meta name="twitter:description" content="<?php echo html_escape($og_description); ?>"/>
    <meta name="twitter:image" content="<?php echo $og_image; ?>"/>
<?php else: ?>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="<?php echo html_escape($title); ?> - <?php echo html_escape($settings->site_title); ?>"/>
    <meta property="og:description" content="<?php echo html_escape($description); ?>"/>
    <meta property="og:url" content="<?php echo base_url(); ?>"/>
    <meta property="fb:app_id" content="<?php echo $this->general_settings->facebook_app_id; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?php echo html_escape($general_settings->application_name); ?>"/>
    <meta name="twitter:title" content="<?php echo html_escape($title); ?> - <?php echo html_escape($settings->site_title); ?>"/>
    <meta name="twitter:description" content="<?php echo html_escape($description); ?>"/>
<?php endif; ?>
    <link rel="canonical" href="<?php echo current_url(); ?>"/>
<?php if ($general_settings->multilingual_system == 1):
foreach ($languages as $language):
if ($language->id == $site_lang->id):?>
    <link rel="alternate" href="<?php echo base_url(); ?>" hreflang="<?php echo $language->language_code ?>"/>
<?php else: ?>
    <link rel="alternate" href="<?php echo base_url() . $language->short_form . "/"; ?>" hreflang="<?php echo $language->language_code ?>"/>
<?php endif; endforeach; endif; ?>
    <!-- Modesy Icons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-icons/css/modesy-icons.min.css"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css"/>
     
    <!-- Owl Carousel -->
    <link href="<?php echo base_url(); ?>assets/vendor/owl-carousel/owl.carousel.min.css" rel="stylesheet"/>
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins.css"/>
    <!-- Style CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css"/>
<?php if (!empty($general_settings->site_color)): ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/colors/<?php echo $general_settings->site_color; ?>.min.css"/>
<?php else: ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/colors/default.min.css"/>
<?php endif; ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery JS-->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <?php echo $general_settings->google_analytics; ?>
    <?php echo $general_settings->head_code; ?>
</head>
<body>
<header id="header" class="<?php echo ($general_settings->selected_system == 'marketplace') ? 'marketplace' : 'classified' ?>">
    <div class="main-menu">
        <div class="container-fluid">
            <div class="row">
                <div class="nav-top">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-7 nav-top-left">
                                <div class="row-align-items-center">
                                    <div class="logo">
                                        <a href="<?php echo lang_base_url(); ?>"><img src="<?php echo get_logo($general_settings); ?>" alt="logo"></a>
                                    </div>

                                    <div class="top-search-bar">
                                        <?php echo form_open(lang_base_url() . 'filter-products', ['id' => 'form_validate_s']); ?>
                                        <div class="left">
                                            <div class="dropdown search-select">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    <?php if (isset($search_type)): ?>
                                                        <?php echo trans("member"); ?>
                                                    <?php else: ?>
                                                        <?php echo trans("product"); ?>
                                                    <?php endif; ?>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" data-value="product" href="javascript:void(0)"><?php echo trans("product"); ?></a>
                                                    <a class="dropdown-item" data-value="member" href="javascript:void(0)"><?php echo trans("member"); ?></a>
                                                </div>
                                            </div>
                                            <?php if (isset($search_type)): ?>
                                                <input type="hidden" class="search_type_input" name="search_type" value="member">
                                            <?php else: ?>
                                                <input type="hidden" class="search_type_input" name="search_type" value="product">
                                            <?php endif; ?>
                                        </div>
                                        <div class="right">
                                            <input type="text" name="search" maxlength="300" pattern=".*\S+.*" class="form-control input-search" value="<?php echo (!empty($filter_search)) ? $filter_search : ''; ?>" placeholder="<?php echo trans("search_exp"); ?>" required>
                                            <button class="btn btn-default btn-search"><i class="icon-search"></i></button>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-5 nav-top-right">
                                <ul class="nav align-items-center">
                                    <?php if ($this->selected_system == "marketplace"): ?>
                                        <li class="nav-item nav-item-cart li-main-nav-right">
                                            <a href="<?php echo lang_base_url(); ?>cart">
                                                <i class="icon-cart"></i><span><?php echo trans("cart"); ?></span>
                                                <?php $cart_product_count = get_cart_product_count();
                                                if ($cart_product_count > 0): ?>
                                                    <span class="notification"><?php echo $cart_product_count; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <!--Check auth-->
                                    <?php if (auth_check()): ?>
                                        <li class="nav-item li-main-nav-right">
                                            <a href="<?php echo lang_base_url(); ?>favorites/<?php echo user()->slug; ?>" class="">
                                                <i class="icon-heart-o"></i><?php echo trans("favorites"); ?>
                                            </a>
                                        </li>
                                        <li class="dropdown profile-dropdown">
                                            <a class="dropdown-toggle a-profile" data-toggle="dropdown" href="javascript:void(0)"
                                               aria-expanded="false">
                                                <?php if ($unread_message_count > 0): ?>
                                                    <span class="notification"><?php echo $unread_message_count; ?></span>
                                                <?php endif; ?>
                                                <img src="<?php echo get_user_avatar(user()); ?>" alt="<?php echo html_escape(user()->username); ?>">
                                                <span class="username"><?php echo html_escape(user()->username); ?></span>
                                                <span class="icon-arrow-down"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php if (user()->role == "admin"): ?>
                                                    <li>
                                                        <a href="<?php echo admin_url(); ?>">
                                                            <i class="icon-dashboard"></i>
                                                            <?php echo trans("admin_panel"); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <li>
                                                    <a href="<?php echo lang_base_url(); ?>profile/<?php echo user()->slug; ?>">
                                                        <i class="icon-user"></i>
                                                        <?php echo trans("view_profile"); ?>
                                                    </a>
                                                </li>
                                                <?php if ($this->selected_system == "marketplace"): ?>
                                                    <li>
                                                        <a href="<?php echo lang_base_url(); ?>orders">
                                                            <i class="icon-shopping-basket"></i>
                                                            <?php echo trans("orders"); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo lang_base_url(); ?>sales">
                                                            <i class="icon-shopping-bag"></i>
                                                            <?php echo trans("sales"); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo lang_base_url(); ?>earnings">
                                                            <i class="icon-wallet"></i>
                                                            <?php echo trans("earnings"); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <li>
                                                    <a href="<?php echo lang_base_url(); ?>messages">
                                                        <i class="icon-mail"></i>
                                                        <?php echo trans("messages"); ?>
                                                        <?php if ($unread_message_count > 0): ?>
                                                            <span class="span-message-count"><?php echo $unread_message_count; ?></span>
                                                        <?php endif; ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo lang_base_url(); ?>settings/update-profile">
                                                        <i class="icon-settings"></i>
                                                        <?php echo trans("settings"); ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url(); ?>logout" class="logout">
                                                        <i class="icon-logout"></i>
                                                        <?php echo trans("logout"); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php if (is_multi_vendor_active()): ?>
                                            <li class="nav-item"><a href="<?php echo lang_base_url(); ?>sell-now" class="btn btn-md btn-custom btn-sell-now"><?php echo trans("sell_now"); ?></a></li>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <li class="nav-item"><a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal"><?php echo trans("login"); ?></a></li>
                                        <li class="nav-item"><a href="<?php echo lang_base_url(); ?>register"><?php echo trans("register"); ?></a></li>
                                        <?php if (is_multi_vendor_active()): ?>
                                            <li class="nav-item"><a href="javascript:void(0)" class="btn btn-md btn-custom btn-sell-now" data-toggle="modal" data-target="#loginModal"><?php echo trans("sell_now"); ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="nav-main">
                    <!--main navigation-->
                    <?php $this->load->view("partials/_main_nav"); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-menu">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-3 col-menu">
                    <div class="mobile-menu-button">
                        <a href="javascript:void(0)" class="btn-open-mobile-nav"><i class="icon-menu"></i></a>
                    </div>
                </div>

                <div class="col-6 col-logo">
                    <div class="mobile-logo">
                        <a href="<?php echo lang_base_url(); ?>"><img src="<?php echo get_logo($general_settings); ?>" alt="logo"></a>
                    </div>
                </div>

                <div class="col-3 col-buttons">
                    <div class="mobile-button-buttons">
                        <div class="mobile-cart-container">
                            <a href="<?php echo lang_base_url(); ?>cart"><i class="icon-cart-mobile"></i>
                                <?php $cart_product_count = get_cart_product_count();
                                if ($cart_product_count > 0): ?>
                                    <span class="notification"><?php echo $cart_product_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <a href="javascript:void(0)" class="search-icon"><i class="icon-search"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="top-search-bar mobile-search-form">
    <?php echo form_open(lang_base_url() . 'filter-products', ['id' => 'form_validate_ms']); ?>
    <div class="left">
        <div class="dropdown search-select">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                <?php if (isset($search_type)): ?>
                    <?php echo trans("member"); ?>
                <?php else: ?>
                    <?php echo trans("product"); ?>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" data-value="product" href="javascript:void(0)"><?php echo trans("product"); ?></a>
                <a class="dropdown-item" data-value="member" href="javascript:void(0)"><?php echo trans("member"); ?></a>
            </div>
        </div>
        <?php if (isset($search_type)): ?>
            <input type="hidden" class="search_type_input" name="search_type" value="member">
        <?php else: ?>
            <input type="hidden" class="search_type_input" name="search_type" value="product">
        <?php endif; ?>
    </div>
    <div class="right">
        <input type="text" name="search" maxlength="300" pattern=".*\S+.*" class="form-control input-search" value="<?php echo (!empty($filter_search)) ? $filter_search : ''; ?>" placeholder="<?php echo trans("search"); ?>" required>
        <button class="btn btn-default btn-search"><i class="icon-search"></i></button>
    </div>
    <?php echo form_close(); ?>
</div>

<!--include mobile menu-->
<?php $this->load->view("partials/_mobile_nav"); ?>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered login-modal" role="document">
        <div class="modal-content">
            <div class="auth-box">
                <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                <h4 class="title"><?php echo trans("login"); ?></h4>
                <!-- form start -->
                <form id="form_login" novalidate="novalidate">
                    <div class="social-login-cnt">
                        <?php $this->load->view("partials/_social_login", ["or_text" => trans("login_with_email")]); ?>
                    </div>
                    <!-- include message block -->
                    <div id="result-login"></div>

                    <div class="form-group">
                        <input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" minlength="4" required>
                    </div>
                    <div class="form-group text-right">
                        <a href="<?php echo lang_base_url(); ?>forgot-password" class="link-forgot-password"><?php echo trans("forgot_password"); ?></a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("login"); ?></button>
                    </div>

                    <p class="p-social-media m-0 m-t-5"><?php echo trans("dont_have_account"); ?>&nbsp;<a href="<?php echo lang_base_url(); ?>register" class="link"><?php echo trans("register"); ?></a></p>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>

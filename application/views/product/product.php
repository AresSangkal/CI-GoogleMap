<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <?php if (!empty($category)): ?>
                            <li class="breadcrumb-item"><a href="<?php echo generate_category_url($category); ?>"><?php echo html_escape($category->name); ?></a></li>
                        <?php endif; ?>
                        <?php if (!empty($subcategory)): ?>
                            <li class="breadcrumb-item"><a href="<?php echo generate_category_url($subcategory); ?>"><?php echo html_escape($subcategory->name); ?></a></li>
                        <?php endif; ?>
                        <?php if (!empty($third_category)): ?>
                            <li class="breadcrumb-item"><a href="<?php echo generate_category_url($third_category); ?>"><?php echo html_escape($third_category->name); ?></a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo html_escape($product->title); ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-7 col-lg-8">
                        <div class="product-content-left">

                            <div class="row">
                                <div class="col-12">
                                    <div class="product-slider-container">
                                        <div class="left">
                                            <div class="dots-container <?php echo (count($product_images) < 2) ? 'hide-dosts-mobile' : ''; ?>">
                                                <?php if (!empty($product_images)):
                                                    foreach ($product_images as $image): ?>
                                                        <button class="dot"><img src="<?php echo get_product_image_url($image, 'image_small'); ?>" alt="dot"></button>
                                                    <?php endforeach;
                                                endif; ?>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div id="product-slider" class="owl-carousel product-slider">
                                                <?php if (!empty($product_images)):
                                                    foreach ($product_images as $image): ?>
                                                        <div class="item">
                                                            <a class="image-popup lightbox" href="<?php echo get_product_image_url($image, 'image_big'); ?>" data-effect="mfp-zoom-out" title="">
                                                                <img src="<?php echo get_product_image_url($image, 'image_default'); ?>" alt="<?php echo html_escape($product->title); ?>">
                                                            </a>
                                                        </div>
                                                    <?php endforeach;
                                                endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="product-content-details product-content-details-mobile">
                                        <h1 class="product-title"><?php echo html_escape($product->title); ?></h1>
                                        <?php if ($product->status == 0): ?>
                                            <label class="badge badge-warning"><?php echo trans("pending"); ?></label>
                                        <?php elseif ($product->visibility == 0): ?>
                                            <label class="badge badge-danger"><?php echo trans("hidden"); ?></label>
                                        <?php endif; ?>
                                        <div class="row-custom meta">
                                            <?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo html_escape($product->user_username); ?></a>
                                            <?php if ($general_settings->product_reviews == 1): ?>
                                                <span><i class="icon-comment"></i><?php echo html_escape($comment_count); ?></span>
                                            <?php endif; ?>
                                            <span><i class="icon-heart"></i><?php echo get_product_favorited_count($product->id); ?></span>
                                            <span><i class="icon-eye"></i><?php echo html_escape($product->hit); ?></span>
                                        </div>
                                        <div class="row-custom price">
                                            <?php if ($product->is_sold == 1): ?>
                                                <strong class="lbl-price" style="color: #9a9a9a;"><?php echo print_price($product->price, $product->currency); ?><span class="price-line"></span></strong>
                                                <strong class="lbl-sold"><?php echo trans("sold"); ?></strong>
                                            <?php else: ?>
                                                <strong class="lbl-price"><?php echo print_price($product->price, $product->currency); ?></strong>
                                            <?php endif; ?>
                                            <?php if (auth_check()): ?>
                                                <button class="btn btn-contact-seller" data-toggle="modal" data-target="#messageModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
                                            <?php else: ?>
                                                <button class="btn btn-contact-seller" data-toggle="modal" data-target="#loginModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row-custom details">
                                            <div class="item-details">
                                                <div class="left">
                                                    <label><?php echo trans("condition"); ?></label>
                                                </div>
                                                <div class="right">
                                                    <span><?php echo trans($product->product_condition); ?></span>
                                                </div>
                                            </div>

                                            <div class="item-details">
                                                <div class="left">
                                                    <label><?php echo trans("uploaded"); ?></label>
                                                </div>
                                                <div class="right">
                                                    <span><?php echo time_ago($product->created_at); ?></span>
                                                </div>
                                            </div>
                                            <?php if ($general_settings->product_location_system == 1): ?>
                                                <div class="item-details">
                                                    <div class="left">
                                                        <label><?php echo trans("location"); ?></label>
                                                    </div>
                                                    <div class="right">
                                                        <span><?php echo get_location($product); ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($general_settings->selected_system == "marketplace"): ?>
                                            <?php if ($product->is_sold == 0): ?>
                                                <?php if ($product->quantity > 1): ?>
                                                    <div class="row-custom">
                                                        <label class="lbl-quantity"><?php echo trans("quantity"); ?></label>
                                                    </div>
                                                    <div class="row-custom">
                                                        <div class="dropdown quantity-select quantity-select-product">
                                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                                <span>1</span>
                                                                <i class="icon-arrow-down"></i></button>
                                                            <div class="dropdown-menu">
                                                                <?php for ($i = 1; $i <= $product->quantity; $i++): ?>
                                                                    <button type="button" value="<?php echo $i; ?>" class="dropdown-item"><?php echo $i; ?></button>
                                                                <?php endfor; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="row-custom m-t-15">
                                                    <?php echo form_open(lang_base_url() . 'add-to-cart'); ?>
                                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                    <input type="hidden" name="product_quantity" value="1">
                                                    <button class="btn btn-md btn-block"><?php echo trans("add_to_cart") ?></button>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>

                                            <?php if (!empty($product->external_link)): ?>
                                                <div class="row-custom">
                                                    <a href="<?php echo $product->external_link; ?>" class="btn btn-md btn-block"><?php echo trans("buy_now") ?></a>
                                                </div>
                                            <?php endif; ?>

                                        <?php endif; ?>

                                        <div class="row-custom m-t-10">
                                            <?php if (auth_check()): ?>
                                                <!-- form start -->
                                                <?php echo form_open('product_controller/add_remove_favorites'); ?>
                                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                <?php if (is_product_in_favorites(user()->id, $product->id)): ?>
                                                    <button class="btn btn-favorite"><i class="icon-heart"></i><?php echo trans("remove_from_favorites") ?></button>
                                                <?php else: ?>
                                                    <button class="btn btn-favorite"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
                                                <?php endif; ?>
                                                <?php echo form_close(); ?>
                                                <!-- form end -->
                                            <?php else: ?>
                                                <button class="btn btn-favorite" data-toggle="modal" data-target="#loginModal"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
                                            <?php endif; ?>
                                        </div>

                                        <!--Include social share-->
                                        <?php $this->load->view("product/_product_share"); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="product-description">
                                        <h4 class="section-title"><?php echo trans("description"); ?></h4>
                                        <div class="description">
                                            <?php echo $product->description; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="product-details-section">
                                        <h4 class="section-title"><?php echo trans("details"); ?></h4>
                                        <div class="description">
                                            <div class="row">
                                                <?php if (!empty($custom_fields)): ?>
                                                    <?php foreach ($custom_fields as $custom_field):
                                                        $field_val = trim($custom_field->field_value);
                                                        if (isset($field_val) && $field_val != ""):?>
                                                            <div class="col-12 col-sm-6">
                                                                <div class="item-details">
                                                                    <strong><?php echo html_escape($custom_field->name); ?></strong>:&nbsp;&nbsp;<?php echo html_escape($custom_field->field_value); ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <div class="col-12 col-sm-6">
                                                    <div class="item-details">
                                                        <strong><?php echo trans("shipping"); ?></strong>:&nbsp;&nbsp;<?php echo trans($product->shipping_time); ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="item-details">
                                                        <?php if ($product->shipping_cost_type == 'shipping_included'): ?>
                                                            <strong><?php echo trans("shipping_cost"); ?></strong>:&nbsp;&nbsp;<?php echo trans("shipping_included"); ?>
                                                        <?php elseif ($product->shipping_cost_type == 'free_shipping'): ?>
                                                            <strong><?php echo trans("shipping_cost"); ?></strong>:&nbsp;&nbsp;<?php echo trans("free_shipping"); ?>
                                                        <?php else: ?>
                                                            <strong><?php echo trans("shipping_cost"); ?></strong>:&nbsp;&nbsp;<?php echo print_price($product->shipping_cost, $product->currency); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row-custom row-bn">
                                        <!--Include banner-->
                                        <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "product", "class" => "m-b-30"]); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <?php if ($general_settings->product_reviews == 1 || $general_settings->product_comments == 1 || $general_settings->facebook_comment_status == 1): ?>
                                        <div class="product-reviews">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs">
                                                <?php if ($general_settings->product_reviews == 1): ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab" href="#reviews"><?php echo trans("reviews"); ?></a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($general_settings->product_comments == 1): ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link <?php echo ($general_settings->product_reviews != 1) ? 'active' : ''; ?>" data-toggle="tab" href="#comments">
                                                            <?php echo trans("comments"); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($general_settings->facebook_comment_status == 1): ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link <?php echo ($general_settings->product_reviews != 1 && $general_settings->product_comments != 1) ? 'active' : ''; ?>" data-toggle="tab" href="#facebook_comments">
                                                            <?php echo trans("facebook_comments"); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <?php if ($general_settings->product_reviews == 1): ?>
                                                    <div class="tab-pane container active" id="reviews">
                                                        <!-- include reviews -->
                                                        <div id="review-result">
                                                            <?php $this->load->view('product/_make_review'); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($general_settings->product_comments == 1): ?>
                                                    <div class="tab-pane container <?php echo ($general_settings->product_reviews != 1) ? 'active' : 'fade'; ?>" id="comments">
                                                        <!-- include comments -->
                                                        <?php $this->load->view('product/_make_comment'); ?>
                                                        <div id="comment-result">
                                                            <?php $this->load->view('product/_comments'); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($general_settings->facebook_comment_status == 1): ?>
                                                    <div class="tab-pane container <?php echo ($general_settings->product_reviews != 1 && $general_settings->product_comments != 1) ? 'active' : 'fade'; ?>" id="facebook_comments">
                                                        <div class="fb-comments" data-href="<?php echo current_url(); ?>" data-width="100%" data-numposts="5"
                                                             data-colorscheme="light"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-5 col-lg-4">
                        <div class="product-content-right">
                            <div class="row">
                                <div class="col-12">
                                    <div class="product-content-details">
                                        <h1 class="product-title"><?php echo html_escape($product->title); ?></h1>
                                        <?php if ($product->status == 0): ?>
                                            <label class="badge badge-warning"><?php echo trans("pending"); ?></label>
                                        <?php elseif ($product->visibility == 0): ?>
                                            <label class="badge badge-danger"><?php echo trans("hidden"); ?></label>
                                        <?php endif; ?>
                                        <div class="row-custom meta">
                                            <?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo html_escape($product->user_username); ?></a>
                                            <?php if ($general_settings->product_reviews == 1): ?>
                                                <span><i class="icon-comment"></i><?php echo html_escape($comment_count); ?></span>
                                            <?php endif; ?>
                                            <span><i class="icon-heart"></i><?php echo get_product_favorited_count($product->id); ?></span>
                                            <span><i class="icon-eye"></i><?php echo html_escape($product->hit); ?></span>
                                        </div>
                                        <div class="row-custom price">
                                            <?php if ($product->is_sold == 1): ?>
                                                <strong class="lbl-price" style="color: #9a9a9a;"><?php echo print_price($product->price, $product->currency); ?><span class="price-line"></span></strong>
                                                <strong class="lbl-sold"><?php echo trans("sold"); ?></strong>
                                            <?php else: ?>
                                                <strong class="lbl-price"><?php echo print_price($product->price, $product->currency); ?></strong>
                                            <?php endif; ?>
                                            <?php if (auth_check()): ?>
                                                <button class="btn btn-contact-seller" data-toggle="modal" data-target="#messageModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
                                            <?php else: ?>
                                                <button class="btn btn-contact-seller" data-toggle="modal" data-target="#loginModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row-custom details">
                                            <div class="item-details">
                                                <div class="left">
                                                    <label><?php echo trans("condition"); ?></label>
                                                </div>
                                                <div class="right">
                                                    <span><?php echo trans($product->product_condition); ?></span>
                                                </div>
                                            </div>

                                            <div class="item-details">
                                                <div class="left">
                                                    <label><?php echo trans("uploaded"); ?></label>
                                                </div>
                                                <div class="right">
                                                    <span><?php echo time_ago($product->created_at); ?></span>
                                                </div>
                                            </div>
                                            <?php if ($general_settings->product_location_system == 1): ?>
                                                <div class="item-details">
                                                    <div class="left">
                                                        <label><?php echo trans("location"); ?></label>
                                                    </div>
                                                    <div class="right">
                                                        <span><?php echo get_location($product); ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($general_settings->selected_system == "marketplace"): ?>
                                            <?php if ($product->is_sold == 0): ?>
                                                <?php if ($product->quantity > 1): ?>
                                                    <div class="row-custom">
                                                        <label class="lbl-quantity"><?php echo trans("quantity"); ?></label>
                                                    </div>
                                                    <div class="row-custom">
                                                        <div class="dropdown quantity-select quantity-select-product">
                                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                                <span>1</span>
                                                                <i class="icon-arrow-down"></i></button>
                                                            <div class="dropdown-menu">
                                                                <?php for ($i = 1; $i <= $product->quantity; $i++): ?>
                                                                    <button type="button" value="<?php echo $i; ?>" class="dropdown-item"><?php echo $i; ?></button>
                                                                <?php endfor; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="row-custom m-t-15">
                                                    <?php echo form_open(lang_base_url() . 'add-to-cart'); ?>
                                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                    <input type="hidden" name="product_quantity" value="1">
                                                    <button class="btn btn-md btn-block"><?php echo trans("add_to_cart") ?></button>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>

                                            <?php if (!empty($product->external_link)): ?>
                                                <div class="row-custom">
                                                    <a href="<?php echo $product->external_link; ?>" class="btn btn-md btn-block"><?php echo trans("buy_now") ?></a>
                                                </div>
                                            <?php endif; ?>

                                        <?php endif; ?>

                                        <div class="row-custom m-t-10">
                                            <?php if (auth_check()): ?>
                                                <!-- form start -->
                                                <?php echo form_open('product_controller/add_remove_favorites'); ?>
                                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                <?php if (is_product_in_favorites(user()->id, $product->id)): ?>
                                                    <button class="btn btn-favorite"><i class="icon-heart"></i><?php echo trans("remove_from_favorites") ?></button>
                                                <?php else: ?>
                                                    <button class="btn btn-favorite"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
                                                <?php endif; ?>
                                                <?php echo form_close(); ?>
                                                <!-- form end -->
                                            <?php else: ?>
                                                <button class="btn btn-favorite" data-toggle="modal" data-target="#loginModal"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
                                            <?php endif; ?>
                                        </div>

                                        <!--Include social share-->
                                        <?php $this->load->view("product/_product_share"); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="widget-seller">
                                        <h4 class="sidebar-title"><?php echo trans("seller"); ?></h4>

                                        <div class="widget-content">
                                            <div class="left">
                                                <div class="user-avatar">
                                                    <a href="<?php echo lang_base_url() . 'profile/' . $product->user_slug; ?>">
                                                        <img src="<?php echo get_user_avatar($user); ?>" alt="<?php echo html_escape($user->username); ?>">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="right">
                                                <p>
                                                    <a href="<?php echo lang_base_url() . 'profile/' . $product->user_slug; ?>">
                                                        <span class="user"><?php echo html_escape($user->username); ?></span>
                                                    </a>
                                                </p>
                                                <p>
                                                <span class="last-seen">
                                                    <i class="icon-circle"></i> <?php echo trans("last_seen"); ?>&nbsp;<?php echo time_ago($user->last_seen); ?>
                                                </span>
                                                </p>
                                                <?php if (!empty($user->phone_number) && $user->show_phone == 1): ?>
                                                    <p>
                                                    <span class="info"><i class="icon-phone"></i>
                                                        <a href="javascript:void(0)" id="show_phone_number"><?php echo trans("show"); ?></a>
                                                        <a href="tel:<?php echo html_escape($user->phone_number); ?>" id="phone_number" class="display-none"><?php echo html_escape($user->phone_number); ?></a>
                                                    </span>
                                                    </p>
                                                <?php elseif (!empty($user->email) && $user->show_email == 1): ?>
                                                    <p>
                                                        <span class="info"><i class="icon-envelope"></i><?php echo html_escape($user->email); ?></span>
                                                    </p>
                                                <?php endif; ?>

                                                <?php if (auth_check()): ?>
                                                    <?php if (user()->id != $user->id): ?>
                                                        <!--form follow-->
                                                        <?php echo form_open('profile_controller/follow_unfollow_user', ['class' => 'form-inline']); ?>
                                                        <input type="hidden" name="following_id" value="<?php echo $user->id; ?>">
                                                        <input type="hidden" name="follower_id" value="<?php echo user()->id; ?>">
                                                        <?php if (is_user_follows($user->id, user()->id)): ?>
                                                            <p class="m-t-5">
                                                                <button class="btn btn-md btn-outline-gray"><i class="icon-user-minus"></i>&nbsp;<?php echo trans("unfollow"); ?></button>
                                                            </p>
                                                        <?php else: ?>
                                                            <p class="m-t-5">
                                                                <button class="btn btn-md btn-outline-gray"><i class="icon-user-plus"></i>&nbsp;<?php echo trans("follow"); ?></button>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php echo form_close(); ?>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <p class="m-t-15">
                                                        <button class="btn btn-md btn-outline-gray" data-toggle="modal" data-target="#loginModal"><i class="icon-user-plus"></i>&nbsp;<?php echo trans("follow"); ?></button>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($user_products)): ?>
                                            <div class="more-from-seller">
                                                <span class="title"> <?php echo trans("more_products_by"); ?>&nbsp;<?php echo html_escape($user->username); ?></span>
                                                <div class="row">
                                                    <?php foreach ($user_products as $item): ?>
                                                        <div class="col-4 col-user-product">
                                                            <div class="user-product">
                                                                <a href="<?php echo generate_product_url($item); ?>">
                                                                    <img src="<?php echo get_product_image($item->id, 'image_small'); ?>" alt="<?php echo html_escape($item->title); ?>" class="img-fluid img-product">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <?php if ($general_settings->product_location_system == 1): ?>
                                        <div class="widget-location">
                                            <h4 class="sidebar-title"><?php echo trans("location"); ?></h4>
                                            <div class="sidebar-map">
                                                <!--load map-->
                                                <iframe src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo get_location($product); ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row-custom">
                                        <!--Include banner-->
                                        <?php $this->load->view("partials/_ad_spaces_sidebar", ["ad_space" => "product_sidebar", "class" => "m-b-5"]); ?>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="related-products">
                    <h4 class="section-title"><?php echo trans("related_products"); ?></h4>
                    <div class="row">
                        <!--print related posts-->
                        <?php foreach ($related_products as $item): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <?php $this->load->view('product/_product_item', ['product' => $item]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Wrapper End-->

<!-- include send message modal -->
<?php $this->load->view("partials/_modal_send_message", ["subject" => $product->title]); ?>
<script>
    $(".fb-comments").attr("data-href", window.location.href);
</script>
<?php
if ($general_settings->facebook_comment_status == 1) {
    echo $general_settings->facebook_comment;
} ?>

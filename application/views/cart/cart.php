<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($cart_items != null): ?>
                    <div class="shopping-cart">
                        <div class="row">
                            <div class="col-sm-12 col-lg-8">
                                <div class="left">
                                    <h1 class="cart-section-title"><?php echo trans("my_cart"); ?> (<?php echo get_cart_product_count(); ?>)</h1>
                                    <?php if (!empty($cart_items)):
                                        foreach ($cart_items as $cart_item):
                                            $product = get_cart_product($cart_item->product_id); ?>
                                            <div class="item">
                                                <div class="row">
                                                    <div class="col-3 cart-item-col-image">
                                                        <div class="img-cart-product">
                                                            <a href="<?php echo generate_product_url($product); ?>">
                                                                <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($cart_item->product_id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="list-item">
                                                            <a href="<?php echo generate_product_url($product); ?>">
                                                                <?php echo html_escape($product->title); ?>
                                                            </a>
                                                            <?php if ($product->quantity > 1): ?>
                                                                <div class="dropdown quantity-select quantity-select-cart">
                                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                                        <span><?php echo $cart_item->quantity; ?></span>
                                                                        <i class="icon-arrow-down"></i></button>
                                                                    <div class="dropdown-menu">
                                                                        <?php for ($i = 1; $i <= $product->quantity; $i++): ?>
                                                                            <button type="button" value="<?php echo $i; ?>" class="dropdown-item btn-cart-product-quantity-item" data-product-id="<?php echo $product->id; ?>"><?php echo $i; ?></button>
                                                                        <?php endfor; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="list-item seller">
                                                            <?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo html_escape($product->user_username); ?></a>
                                                        </div>
                                                        <div class="list-item m-t-15">
                                                            <label><?php echo trans("unit_price"); ?>:</label>
                                                            <strong class="lbl-price"><?php echo print_price($cart_item->unit_price, $cart_item->currency); ?></strong>
                                                        </div>
                                                        <div class="list-item">
                                                            <label><?php echo trans("total"); ?>:</label>
                                                            <strong class="lbl-price"><?php echo print_price($cart_item->total_price, $cart_item->currency); ?></strong>
                                                        </div>
                                                        <div class="list-item">
                                                            <label><?php echo trans("shipping"); ?>:</label>
                                                            <strong><?php echo print_price($product->shipping_cost, $product->currency); ?></strong>
                                                        </div>
                                                        <a href="javascript:void(0)" class="btn btn-md btn-outline-gray btn-cart-remove" onclick="remove_from_cart('<?php echo $product->id; ?>');"><i class="icon-close"></i> <?php echo trans("remove"); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $last_product_url = generate_product_url($product);
                                        endforeach;
                                    endif; ?>
                                </div>
                                <a href="<?php echo $last_product_url; ?>" class="btn btn-md btn-custom m-t-15"><i class="icon-arrow-left m-r-2"></i><?php echo trans("keep_shopping") ?></a>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <div class="right">
                                    <p>
                                        <strong><?php echo trans("subtotal"); ?><span class="float-right"><?php echo print_price($cart_total->subtotal, $cart_total->currency); ?></span></strong>
                                    </p>
                                    <p>
                                        <?php echo trans("shipping"); ?><span class="float-right"><?php echo print_price($cart_total->shipping_cost, $cart_total->currency); ?></span>
                                    </p>
                                    <p class="line-seperator"></p>
                                    <p>
                                        <strong><?php echo trans("total"); ?><span class="float-right"><?php echo print_price($cart_total->total, $cart_total->currency); ?></span></strong>
                                    </p>
                                    <p class="m-t-30">
                                        <a href="<?php echo lang_base_url(); ?>cart/shipping" class="btn btn-block"><?php echo trans("continue_to_checkout"); ?></a>
                                    </p>
                                    <img src="<?php echo base_url(); ?>assets/img/payments.png" alt="payments" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="shopping-cart-empty">
                        <p><strong class="font-600"><?php echo trans("your_cart_is_empty"); ?></strong></p>
                        <a href="<?php echo lang_base_url(); ?>" class="btn btn-lg btn-custom"><i class="icon-arrow-left"></i>&nbsp;<?php echo trans("shop_now"); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->



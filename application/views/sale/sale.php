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

                <h1 class="page-title"><?php echo $title; ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("sale/_sale_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row">
                    <div class="col-12">
                        <!-- include message block -->
                        <?php $this->load->view('product/_messages'); ?>
                    </div>
                </div>

                <div class="order-details-container">
                    <div class="order-head">
                        <h2 class="title"><?php echo trans("sale"); ?>:&nbsp;#<?php echo $order->order_number; ?></h2>
                    </div>
                    <div class="order-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("status"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php
                                        $order_status = 1;
                                        foreach ($order_products as $item):
                                            if ($item->is_approved == 0) {
                                                $order_status = 0;
                                            }
                                        endforeach; ?>

                                        <?php if ($order_status == 1): ?>
                                            <strong><?php echo trans("completed"); ?></strong>
                                        <?php else: ?>
                                            <strong><?php echo trans("order_processing"); ?></strong>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("payment_status"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php echo trans($order->payment_status); ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("payment_method"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php
                                        if ($order->payment_method == "Bank Transfer") {
                                            echo trans("bank_transfer");
                                        } else {
                                           echo $order->payment_method;
                                        } ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("date"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php echo date("Y-m-d / h:i", strtotime($order->created_at)); ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("updated"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php echo time_ago($order->updated_at); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row shipping-container">
                            <div class="col-md-12 col-lg-6">
                                <h3 class="block-title"><?php echo trans("shipping_address"); ?></h3>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("first_name"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_first_name; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("last_name"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_last_name; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("email"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_email; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("phone_number"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_phone_number; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("address"); ?>&nbsp;1
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_address_1; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("address"); ?>&nbsp;2
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_address_2; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("country"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_country; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("state"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_state; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("city"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_city; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("zip_code"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->shipping_zip_code; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <h3 class="block-title"><?php echo trans("billing_address"); ?></h3>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("first_name"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_first_name; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("last_name"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_last_name; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("email"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_email; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("phone_number"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_phone_number; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("address"); ?>&nbsp;1
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_address_1; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("address"); ?>&nbsp;2
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_address_2; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("country"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_country; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("state"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_state; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("city"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_city; ?>
                                    </div>
                                </div>
                                <div class="row shipping-row-item">
                                    <div class="col-5">
                                        <?php echo trans("zip_code"); ?>
                                    </div>
                                    <div class="col-7">
                                        <?php echo $order->billing_zip_code; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row table-orders-container">
                            <div class="col-12">
                                <h3 class="block-title"><?php echo trans("products"); ?></h3>
                                <div class="table-responsive">
                                    <table class="table table-orders">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo trans("product"); ?></th>
                                            <th scope="col"><?php echo trans("status"); ?></th>
                                            <th scope="col"><?php echo trans("updated"); ?></th>
                                            <th scope="col"><?php echo trans("options"); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sale_subtotal = 0;
                                        $sale_shipping = 0;
                                        $sale_total = 0;
                                        foreach ($order_products as $item):
                                            if ($item->seller_id == user()->id):
                                                $sale_subtotal += $item->product_unit_price * $item->product_quantity;
                                                $sale_shipping += $item->product_shipping_cost;
                                                $sale_total += $item->product_total_price; ?>
                                                <tr>
                                                    <td>
                                                        <div class="product-item-table">
                                                            <div class="left">
                                                                <div class="img-table">
                                                                    <a href="<?php echo base_url() . $item->product_slug; ?>" target="_blank">
                                                                        <img src="<?php echo get_product_image($item->product_id, 'image_small'); ?>" data-src="" alt="" class="lazyload img-responsive post-image"/>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="right">
                                                                <a href="<?php echo base_url() . $item->product_slug; ?>" target="_blank" class="table-product-title">
                                                                    <?php echo html_escape($item->product_title); ?>
                                                                </a>
                                                                <p>
                                                                    <span><?php echo trans("seller"); ?>:</span>
                                                                    <?php $seller = get_user($item->seller_id); ?>
                                                                    <?php if (!empty($seller)): ?>
                                                                        <a href="<?php echo base_url(); ?>profile/<?php echo $seller->slug; ?>" target="_blank" class="table-product-title">
                                                                            <strong class="font-600"><?php echo html_escape($seller->username); ?></strong>
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </p>
                                                                <p><?php echo trans("unit_price"); ?>:&nbsp;<?php echo print_price($item->product_unit_price, $item->product_currency); ?></p>
                                                                <p><?php echo trans("quantity"); ?>:&nbsp;<?php echo $item->product_quantity; ?></p>
                                                                <p><?php echo trans("shipping"); ?>:&nbsp;<?php echo print_price($item->product_shipping_cost, $item->product_currency); ?></p>
                                                                <p><?php echo trans("total"); ?>:&nbsp;<?php echo print_price($item->product_total_price, $item->product_currency); ?></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo trans($item->order_status) ?></strong>
                                                    </td>
                                                    <td><?php echo date("Y-m-d / h:i", strtotime($item->updated_at)); ?></td>
                                                    <td>
                                                        <?php if ($item->order_status == "completed"): ?>
                                                            <strong class="font-600"><i class="icon-check"></i>&nbsp;<?php echo trans("approved"); ?></strong>
                                                        <?php else: ?>
                                                            <div class="dropdown-order-options">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm btn-secondary dropdown-toggle color-white" type="    on" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <?php echo trans('select_option'); ?>
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#updateStatusModal_<?php echo $item->id; ?>"><?php echo trans('update_order_status'); ?></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php if ($item->order_status == "shipped"): ?>
                                                <tr class="tr-shipping">
                                                    <td colspan="4">
                                                        <div class="order-shipping-tracking-number">
                                                            <p><strong><?php echo trans("shipping") ?></strong></p>
                                                            <p><?php echo trans("tracking_number") ?>:&nbsp;<?php echo html_escape($item->shipping_tracking_number); ?></p>
                                                            <p><?php echo trans("url") ?>: <a href="<?php echo html_escape($item->shipping_tracking_url); ?>" target="_blank" class="link-underlined"><?php echo html_escape($item->shipping_tracking_url); ?></a></p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="tr-shipping-seperator">
                                                    <td colspan="4"></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php endif;
                                        endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="order-total">
                                    <div class="row">
                                        <div class="col-6 col-left">
                                            <?php echo trans("subtotal"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo print_price($sale_subtotal, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-left">
                                            <?php echo trans("shipping"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo print_price($sale_shipping, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row-seperator"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-left">
                                            <?php echo trans("total"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo print_price($sale_total, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->
<?php foreach ($order_products as $item):
    if ($item->seller_id == user()->id):?>
        <div class="modal fade" id="updateStatusModal_<?php echo $item->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-custom">
                    <!-- form start -->
                    <?php echo form_open_multipart('order_controller/update_order_product_status_post'); ?>
                    <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo trans("update_order_status"); ?></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true"><i class="icon-close"></i> </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label"><?php echo trans('status'); ?></label>
                                    <div class="selectdiv">
                                        <select name="order_status" class="form-control order-status-select" data-order-product-id="<?php echo $item->id; ?>">
                                            <?php if ($order->payment_method == "Bank Transfer"): ?>
                                                <option value="awaiting_payment" <?php echo ($item->order_status == 'awaiting_payment') ? 'selected' : ''; ?>><?php echo trans("awaiting_payment"); ?></option>
                                            <?php endif; ?>
                                            <option value="payment_received" <?php echo ($item->order_status == 'payment_received') ? 'selected' : ''; ?>><?php echo trans("payment_received"); ?></option>
                                            <option value="order_processing" <?php echo ($item->order_status == 'order_processing') ? 'selected' : ''; ?>><?php echo trans("order_processing"); ?></option>
                                            <option value="shipped" <?php echo ($item->order_status == 'shipped') ? 'selected' : ''; ?>><?php echo trans("shipped"); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row tracking-number-container tracking-number-container-<?php echo $item->id; ?>" <?php echo ($item->order_status == 'shipped') ? 'style="display:block;"' : ''; ?>>
                            <h5 class="title"><?php echo trans("add_shipping_tracking_number"); ?></h5>
                            <div class="col-12">
                                <div class="form-group">
                                    <label><?php echo trans('tracking_number'); ?></label>
                                    <input type="text" name="shipping_tracking_number" class="form-control form-input" value="<?php echo html_escape($item->shipping_tracking_number); ?>">
                                </div>
                                <div class="form-group">
                                    <label><?php echo trans('url'); ?></label>
                                    <input type="text" name="shipping_tracking_url" class="form-control form-input" value="<?php echo html_escape($item->shipping_tracking_url); ?>">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-red" data-dismiss="modal"><?php echo trans("close"); ?></button>
                        <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
                    </div>
                    <?php echo form_close(); ?><!-- form end -->
                </div>
            </div>
        </div>
    <?php endif;
endforeach; ?>

<script>
    $(".order-status-select").change(function () {
        $order_product_id = $(this).attr("data-order-product-id");
        if (this.value == 'shipped') {
            $('.tracking-number-container-' + $order_product_id).show();
            $(".tracking-number-container-" + $order_product_id + " input").prop('required', true);
        } else {
            $('.tracking-number-container-' + $order_product_id).hide();
            $(".tracking-number-container-" + $order_product_id + " input").prop('required', false);
        }
    });
</script>


<?php if (!empty($this->session->userdata('mds_send_email_order_shipped'))): ?>
    <script>
        $(document).ready(function () {
            var data = {
                "order_product_id": '<?php echo $this->session->userdata('mds_send_email_order_shipped_order_product_id'); ?>',
                'lang_folder': lang_folder,
                'form_lang_base_url': '<?php echo lang_base_url(); ?>'
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "ajax_controller/send_email_order_shipped",
                data: data,
                success: function (response) {
                }
            });
        });
    </script>
    <?php
    $this->session->unset_userdata('mds_send_email_order_shipped_order_product_id');
    $this->session->unset_userdata('mds_send_email_order_shipped');
endif; ?>

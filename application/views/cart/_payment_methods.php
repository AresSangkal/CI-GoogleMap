<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "paypal"): ?>
    <div class="row">
        <div class="col-12">
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <div id="payment-button-container" class=paypal-button-cnt">
        <p class="p-complete-payment"><?php echo trans("msg_complete_payment"); ?></p>
        <div id="paypal-button"></div>
    </div>
<?php endif; ?>

<?php if ($cart_payment_method->payment_option == "stripe"): ?>
    <div class="row">
        <div class="col-12">
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <div id="payment-button-container" class=paypal-button-cnt">
        <p class="p-complete-payment"><?php echo trans("msg_complete_payment"); ?></p>
        <button type="button" id="btn_stripe_checkout" class="btn btn-lg custom-stripe-button"><?php echo trans("stripe_checkout") ?></button>
    </div>
<?php endif; ?>

<?php if ($cart_payment_method->payment_option == "iyzico"): ?>
    <div class="row">
        <div class="col-12">
            <!-- include message block -->
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <?php
    $ci =& get_instance();
    $options = $ci->initialize_iyzico();
    $buyer_id = "guest_" . uniqid();
    if (auth_check()) {
        $buyer_id = user()->id;
    }
    $ip = $this->input->ip_address();
    if (empty($ip)) {
        $ip = "85.34.78.112";
    }
    $country_name = "Turkey";
    $country = get_country($shipping_address->shipping_country_id);
    if (!empty($country)) {
        $country_name = $country->name;
    }
    # create request class
    $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
    $request->setLocale(\Iyzipay\Model\Locale::TR);
    $request->setConversationId("123456");
    $request->setPrice(price_format_decimal($cart_total->total));
    $request->setPaidPrice(price_format_decimal($cart_total->total));
    $request->setCurrency(\Iyzipay\Model\Currency::TL);
    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
    $request->setCallbackUrl(base_url() . "cart_controller/iyzico_payment_post?lang_base_url=" . lang_base_url());
    $request->setEnabledInstallments(array(2, 3, 6, 9));

    $buyer = new \Iyzipay\Model\Buyer();
    $buyer->setId($buyer_id);
    $buyer->setName($shipping_address->shipping_first_name);
    $buyer->setSurname($shipping_address->shipping_last_name);
    $buyer->setGsmNumber($shipping_address->shipping_phone_number);
    $buyer->setEmail($shipping_address->shipping_email);
    $buyer->setIdentityNumber("11111111111");
    $buyer->setRegistrationAddress($shipping_address->shipping_address_1);
    $buyer->setIp($ip);
    $buyer->setCity($shipping_address->shipping_city);
    $buyer->setCountry($country_name);
    $buyer->setZipCode($shipping_address->shipping_zip_code);
    $request->setBuyer($buyer);

    $shippingAddress = new \Iyzipay\Model\Address();
    $shippingAddress->setContactName($shipping_address->shipping_first_name . " " . $shipping_address->shipping_last_name);
    $shippingAddress->setCity($shipping_address->shipping_city);
    $shippingAddress->setCountry($country_name);
    $shippingAddress->setAddress($shipping_address->shipping_address_1);
    $shippingAddress->setZipCode($shipping_address->shipping_zip_code);
    $request->setShippingAddress($shippingAddress);

    $billingAddress = new \Iyzipay\Model\Address();
    $billingAddress->setContactName($shipping_address->billing_first_name . " " . $shipping_address->billing_last_name);
    $billingAddress->setCity($shipping_address->billing_city);
    $billingAddress->setCountry($country_name);
    $billingAddress->setAddress($shipping_address->billing_address_1);
    $billingAddress->setZipCode($shipping_address->billing_zip_code);
    $request->setBillingAddress($billingAddress);

    $basketItems = array();
    //shipping
    $count = 1;
    if ($cart_total->shipping_cost > 0) {
        $BasketItem = new \Iyzipay\Model\BasketItem();
        $BasketItem->setId("0");
        $BasketItem->setName(trans("shipping"));
        $BasketItem->setCategory1(trans("shipping"));
        $BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $BasketItem->setPrice(price_format_decimal($cart_total->shipping_cost));
        $basketItems[0] = $BasketItem;
        //cart items
        $count = 1;
    } else {
        $count = 0;
    }
    if (!empty($cart_items)):
        foreach ($cart_items as $cart_item):
            $product = get_cart_product($cart_item->product_id);
            if (!empty($product)) {
                $BasketItem = new \Iyzipay\Model\BasketItem();
                $BasketItem->setId($product->id);
                $BasketItem->setName($product->title);
                $BasketItem->setCategory1(trans("order"));
                $BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                $BasketItem->setPrice(price_format_decimal($cart_item->total_price));
                $basketItems[$count] = $BasketItem;
                $count++;
            }
        endforeach;
    endif;
    $request->setBasketItems($basketItems);
    # make request
    $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
    echo $checkoutFormInitialize->getcheckoutFormContent(); ?>
    <div id="iyzipay-checkout-form" class="responsive"></div>
<?php endif; ?>

<?php if ($cart_payment_method->payment_option == "bank_transfer"): ?>
    <div class="row">
        <div class="col-12">
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <?php echo form_open_multipart('cart_controller/bank_transfer_payment_post'); ?>
    <div id="payment-button-container" class=paypal-button-cnt">
        <div class="bank-account-container">
            <?php echo $payment_settings->bank_transfer_accounts; ?>
        </div>
        <p class="p-complete-payment"><?php echo trans("msg_bank_transfer_text"); ?></p>
        <button type="submit" name="submit" value="update" class="btn btn-lg btn-custom float-right"><?php echo trans("place_order") ?></button>
    </div>
    <?php echo form_close(); ?>
<?php endif; ?>

<script>
    var total_amount = '<?php echo price_format_decimal($cart_total->total); ?>';
    var currency = '<?php echo $payment_settings->default_product_currency; ?>';
    var paypal_mode = '<?php echo $payment_settings->paypal_mode; ?>';
    var paypal_client_id = '<?php echo $payment_settings->paypal_client_id; ?>';
    var stripe_key = '<?php echo $payment_settings->stripe_publishable_key; ?>';
    $(window).bind("load", function () {
        $("#payment-button-container").css("visibility", "visible");
    });
</script>

<!--PAYPAL-->
<?php if ($cart_payment_method->payment_option == "paypal"): ?>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
        paypal.Button.render({
            // Configure environment
            env: paypal_mode,
            client: {
                sandbox: paypal_client_id,
                production: paypal_client_id
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'medium',
                color: 'black',
                shape: 'rect',
            },
            // Enable Pay Now checkout flow (optional)
            commit: true,
            // Set up a payment
            payment: function (data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: total_amount,
                            currency: currency
                        }
                    }]
                });
            },
            // Execute the payment
            onAuthorize: function (data, actions) {
                return actions.payment.execute().then(function () {
                    var data_array = {
                        'payment_id': data.paymentID,
                        'currency': currency,
                        'payment_amount': total_amount,
                        'payment_status': 'succeeded',
                        'lang_folder': lang_folder,
                        'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                    };
                    data_array[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: "POST",
                        url: base_url + "cart_controller/paypal_payment_post",
                        data: data_array,
                        success: function (response) {
                            var obj = JSON.parse(response);
                            if (obj.result == 1) {
                                window.location.href = obj.redirect;
                            } else {
                                location.reload();
                            }
                        }
                    });
                });
            }
        }, '#paypal-button');
    </script>
<?php endif; ?>

<!--STRIPE-->
<?php if ($cart_payment_method->payment_option == "stripe"): ?>
    <script src="https://checkout.stripe.com/v2/checkout.js"></script>
    <script>
        var handler = StripeCheckout.configure({
            key: stripe_key,
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            locale: 'auto',
            currency: currency,
            token: function (token) {
                var data = {
                    'payment_id': token.id,
                    'email': token.email,
                    'currency': currency,
                    'payment_amount': <?php echo $cart_total->total; ?>,
                    'payment_status': 'succeeded',
                    'lang_folder': lang_folder,
                    'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                };
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "cart_controller/stripe_payment_post",
                    data: data,
                    success: function (response) {
                        var obj = JSON.parse(response);
                        if (obj.result == 1) {
                            window.location.href = obj.redirect;
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        });
        document.getElementById('btn_stripe_checkout').addEventListener('click', function (e) {
            handler.open({
                name: '<?php echo html_escape($general_settings->application_name); ?>',
                description: '<?php echo trans("stripe_checkout"); ?>',
                amount: '<?php echo $cart_total->total; ?>'
            });
            e.preventDefault();
        });
        // Close Checkout on page navigation:
        window.addEventListener('popstate', function () {
            handler.close();
        });
    </script>
<?php endif; ?>

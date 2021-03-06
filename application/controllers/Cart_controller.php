<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->site_settings = get_site_settings();
        $this->cart_model->calculate_cart_total();
        //check system
        if ($this->selected_system != "marketplace") {
            redirect(lang_base_url());
        }
    }

    /**
     * Cart
     */
    public function cart()
    {
        $data['title'] = trans("shopping_cart");
        $data['description'] = trans("shopping_cart") . " - " . $this->app_name;
        $data['keywords'] = trans("shopping_cart") . "," . $this->app_name;

        $data['cart_items'] = $this->cart_model->get_sess_cart_items();
        $data['cart_total'] = $this->cart_model->get_sess_cart_total();

        $this->load->view('partials/_header', $data);
        $this->load->view('cart/cart', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Add to Cart
     */
    public function add_to_cart()
    {
        $product_id = $this->input->post('product_id', true);
        $product = $this->product_model->get_product_by_id($product_id);
        if (!empty($product)) {
            $this->cart_model->add_to_cart($product);
            redirect(lang_base_url() . 'cart');
        }
        redirect($this->agent->referrer());
    }

    /**
     * Remove from Cart
     */
    public function remove_from_cart()
    {
        $product_id = $this->input->post('product_id', true);
        $this->cart_model->remove_from_cart($product_id);
    }

    /**
     * Update Cart Product Quantity
     */
    public function update_cart_product_quantity()
    {
        $product_id = $this->input->post('product_id', true);
        $quantity = $this->input->post('quantity', true);
        $product = $this->product_model->get_product_by_id($product_id);

        if (!empty($product)) {
            $this->cart_model->update_cart_product_quantity($product, $quantity);
        }
    }

    /**
     * Shipping
     */
    public function shipping()
    {
        $data['title'] = trans("shopping_cart");
        $data['description'] = trans("shopping_cart") . " - " . $this->app_name;
        $data['keywords'] = trans("shopping_cart") . "," . $this->app_name;

        $data['cart_items'] = $this->cart_model->get_sess_cart_items();

        if ($data['cart_items'] == null) {
            redirect(lang_base_url() . "cart");
        }

        $data['cart_total'] = $this->cart_model->get_sess_cart_total();
        $data["shipping_address"] = $this->cart_model->get_sess_cart_shipping_address();

        $this->load->view('partials/_header', $data);
        $this->load->view('cart/shipping', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Shipping Post
     */
    public function shipping_post()
    {
        $this->cart_model->set_sess_cart_shipping_address();
        redirect(lang_base_url() . "cart/payment-method");
    }

    /**
     * Payment Method
     */
    public function payment_method()
    {
        $data['title'] = trans("shopping_cart");
        $data['description'] = trans("shopping_cart") . " - " . $this->app_name;
        $data['keywords'] = trans("shopping_cart") . "," . $this->app_name;

        $data['cart_items'] = $this->cart_model->get_sess_cart_items();

        if ($data['cart_items'] == null) {
            redirect(lang_base_url() . "cart");
        }

        $data['cart_total'] = $this->cart_model->get_sess_cart_total();
        $user_id = null;
        if (auth_check()) {
            $user_id = user()->id;
        }
        $data["shipping_address"] = $this->profile_model->get_user_shipping_address($user_id);

        $this->cart_model->unset_sess_cart_payment_method();

        $this->load->view('partials/_header', $data);
        $this->load->view('cart/payment_method', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Payment Method Post
     */
    public function payment_method_post()
    {
        $this->cart_model->set_sess_cart_payment_method();
        redirect(lang_base_url() . "cart/payment");
    }

    /**
     * Payment
     */
    public function payment()
    {
        $data['title'] = trans("shopping_cart");
        $data['description'] = trans("shopping_cart") . " - " . $this->app_name;
        $data['keywords'] = trans("shopping_cart") . "," . $this->app_name;

        $data['cart_items'] = $this->cart_model->get_sess_cart_items();
        if ($data['cart_items'] == null) {
            redirect(lang_base_url() . "cart");
        }
        $data['cart_total'] = $this->cart_model->get_sess_cart_total();
        $data['cart_payment_method'] = $this->cart_model->get_sess_cart_payment_method();
        if (empty($data['cart_payment_method'])) {
            redirect(lang_base_url() . "cart/payment-method");
        }
        $data["shipping_address"] = $this->cart_model->get_sess_cart_shipping_address();

        $this->load->view('partials/_header', $data);
        $this->load->view('cart/payment', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Payment with Paypal
     */
    public function paypal_payment_post()
    {
        //add order
        $order_id = 0;
        $result = $this->order_model->add_order("PayPal");
        if ($result) {
            $order_id = $this->db->insert_id();

            //update order number
            $this->order_model->update_order_number($order_id);
            //execute payment
            $this->order_model->execute_order_payment_paypal($order_id);
            //add order products
            $this->order_model->add_order_products($order_id);
            //update order payment
            $this->order_model->update_order_payment_received($order_id);

            $order = $this->order_model->get_order($order_id);

            //decrease product quantity after sale
            $this->order_model->decrease_product_quantity_after_sale($order);
            //clear cart
            $this->cart_model->clear_cart();

            //set email session
            $this->session->set_userdata('mds_send_email_order_summary', 1);

            if (!empty($order)) {
                $data = array(
                    'result' => 1,
                    'redirect' => lang_base_url() . "order/" . $order->order_number
                );
                if ($order->buyer_id == 0) {
                    $this->session->set_userdata('mds_show_order_completed_page', 1);
                    $data["redirect"] = lang_base_url() . "order-completed/" . $order->order_number;
                } else {
                    $this->session->set_flashdata('success', trans("msg_order_completed"));
                }
                echo json_encode($data);
            } else {
                $this->session->set_flashdata('error', trans("msg_payment_database_error"));
                $data = array(
                    'status' => 0,
                    'redirect' => lang_base_url() . "cart/payment/"
                );
                echo json_encode($data);
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_payment_database_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "cart/payment/"
            );
            echo json_encode($data);
        }
    }

    /**
     * Payment with Stripe
     */
    public function stripe_payment_post()
    {
        require_once(APPPATH . 'third_party/stripe/vendor/autoload.php');
        $stripe_vars = array();
        include APPPATH . 'config/stripe.php';
        try {
            $token = $this->input->post('payment_id', true);
            $email = $this->input->post('email', true);
            $payment_amount = $this->input->post('payment_amount', true);
            $currency = $this->input->post('currency', true);
            //Init stripe
            $stripe = array(
                "secret_key" => $stripe_vars["secret_key"],
                "publishable_key" => $stripe_vars["publishable_key"]
            );
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            //customer
            $customer = \Stripe\Customer::create(array(
                'email' => $email,
                'source' => $token
            ));
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $payment_amount,
                'currency' => $currency,
                'description' => trans("stripe_checkout")
            ));

            //add to database
            $order_id = 0;
            $result = $this->order_model->add_order("Stripe");
            if ($result) {
                $order_id = $this->db->insert_id();
                //update order number
                $this->order_model->update_order_number($order_id);
                //execute payment
                $this->order_model->execute_order_payment_stripe($order_id);
                //add order products
                $this->order_model->add_order_products($order_id);
                //update order payment
                $this->order_model->update_order_payment_received($order_id);

                $order = $this->order_model->get_order($order_id);
                //decrease product quantity after sale
                $this->order_model->decrease_product_quantity_after_sale($order);
                //clear cart
                $this->cart_model->clear_cart();
                //set email session
                $this->session->set_userdata('mds_send_email_order_summary', 1);
                if (!empty($order)) {
                    $data = array(
                        'result' => 1,
                        'redirect' => lang_base_url() . "order/" . $order->order_number
                    );
                    if ($order->buyer_id == 0) {
                        $this->session->set_userdata('mds_show_order_completed_page', 1);
                        $data["redirect"] = lang_base_url() . "order-completed/" . $order->order_number;
                    } else {
                        $this->session->set_flashdata('success', trans("msg_order_completed"));
                    }
                    echo json_encode($data);
                } else {
                    $this->session->set_flashdata('error', trans("msg_payment_database_error"));
                    $data = array(
                        'status' => 0,
                        'redirect' => lang_base_url() . "cart/payment/"
                    );
                    echo json_encode($data);
                }
            }
            //add to databas end


        } catch (\Stripe\Error\Base $e) {
            $this->session->set_flashdata('error', trans("msg_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "cart/payment/"
            );
            echo json_encode($data);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', trans("msg_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "cart/payment/"
            );
            echo json_encode($data);
        }
    }

    /**
     * Payment with Iyzico
     */
    public function iyzico_payment_post()
    {
        $token = $this->input->post('token', true);
        $lang_base_url = $this->input->get('lang_base_url', true);

        $options = $this->initialize_iyzico();
        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456");
        $request->setToken($token);

        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $options);

        if ($checkoutForm->getPaymentStatus() == "SUCCESS") {

            $currency = $checkoutForm->getCurrency();
            $price = $checkoutForm->getPrice();

            //add order
            $order_id = 0;
            $result = $this->order_model->add_order("Iyzico");
            if ($result) {
                $order_id = $this->db->insert_id();

                //update order number
                $this->order_model->update_order_number($order_id);
                //execute payment
                $this->order_model->execute_order_payment_iyzico($order_id, $token, $currency, $price);
                //add order products
                $this->order_model->add_order_products($order_id);
                //update order payment
                $this->order_model->update_order_payment_received($order_id);

                $order = $this->order_model->get_order($order_id);

                //decrease product quantity after sale
                $this->order_model->decrease_product_quantity_after_sale($order);
                //clear cart
                $this->cart_model->clear_cart();
                //set email session
                $this->session->set_userdata('mds_send_email_order_summary', 1);

                if (!empty($order)) {
                    if ($order->buyer_id == 0) {
                        $this->session->set_userdata('mds_show_order_completed_page', 1);
                        redirect($lang_base_url . "order-completed/" . $order->order_number);
                    } else {
                        $this->session->set_flashdata('success', trans("msg_order_completed"));
                        redirect($lang_base_url . "order/" . $order->order_number);
                    }
                }
            }
            $this->session->set_flashdata('error', trans("msg_payment_database_error"));
            redirect($lang_base_url . "/cart/payment");
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($lang_base_url . "/cart/payment");
        }
    }

    /**
     * Payment with Bank Transfer
     */
    public function bank_transfer_payment_post()
    {
        //add order
        $order_id = 0;
        $result = $this->order_model->add_order("Bank Transfer");
        if ($result) {
            $order_id = $this->db->insert_id();

            //update order number
            $this->order_model->update_order_number($order_id);
            //add order products
            $this->order_model->add_order_products($order_id);

            $order = $this->order_model->get_order($order_id);

            //decrease product quantity after sale
            $this->order_model->decrease_product_quantity_after_sale($order);

            //clear cart
            $this->cart_model->clear_cart();
            //set email session
            $this->session->set_userdata('mds_send_email_order_summary', 1);

            if (!empty($order)) {
                if ($order->buyer_id == 0) {
                    $this->session->set_userdata('mds_show_order_completed_page', 1);
                    redirect(lang_base_url() . "order-completed/" . $order->order_number);
                } else {
                    $this->session->set_flashdata('success', trans("msg_order_completed"));
                    redirect(lang_base_url() . "order/" . $order->order_number);
                }
            }
        }

        $this->session->set_flashdata('error', trans("msg_error"));
        redirect(lang_base_url() . "/cart/payment");
    }

    /**
     * Order Completed
     */
    public function order_completed($order_number)
    {
        $data['title'] = trans("msg_order_completed");
        $data['description'] = trans("msg_order_completed") . " - " . $this->app_name;
        $data['keywords'] = trans("msg_order_completed") . "," . $this->app_name;

        $data['order'] = $this->order_model->get_order_by_order_number($order_number);

        if (empty($data['order'])) {
            redirect(lang_base_url());
        }

        if (empty($this->session->userdata('mds_show_order_completed_page'))) {
            redirect(lang_base_url());
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('cart/order_completed', $data);
        $this->load->view('partials/_footer');
    }

}

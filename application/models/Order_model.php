<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    //add order
    public function add_order($payment_method)
    {
        $cart_total = $this->cart_model->get_sess_cart_total();
        if (!empty($cart_total)) {
            $data = array(
                'buyer_id' => 0,
                'buyer_type' => "guest",
                'price_subtotal' => $cart_total->subtotal,
                'price_shipping' => $cart_total->shipping_cost,
                'price_total' => $cart_total->total,
                'price_currency' => $cart_total->currency,
                'shipping_first_name' => "",
                'shipping_last_name' => "",
                'shipping_email' => "",
                'shipping_phone_number' => "",
                'shipping_address_1' => "",
                'shipping_address_2' => "",
                'shipping_country' => "",
                'shipping_state' => "",
                'shipping_city' => "",
                'shipping_zip_code' => "",
                'billing_first_name' => "",
                'billing_last_name' => "",
                'billing_email' => "",
                'billing_phone_number' => "",
                'billing_address_1' => "",
                'billing_address_2' => "",
                'billing_country' => "",
                'billing_state' => "",
                'billing_city' => "",
                'billing_zip_code' => "",
                'payment_method' => $payment_method,
                'payment_status' => "awaiting_payment",
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            );

            if (auth_check()) {
                $data["buyer_type"] = "registered";
                $data["buyer_id"] = user()->id;
            }

            $shipping_address = $this->cart_model->get_sess_cart_shipping_address();
            $data["shipping_first_name"] = $shipping_address->shipping_first_name;
            $data["shipping_last_name"] = $shipping_address->shipping_last_name;
            $data["shipping_email"] = $shipping_address->shipping_email;
            $data["shipping_phone_number"] = $shipping_address->shipping_phone_number;
            $data["shipping_address_1"] = $shipping_address->shipping_address_1;
            $data["shipping_address_2"] = $shipping_address->shipping_address_2;
            $data["shipping_country"] = $shipping_address->shipping_country_id;
            $country = get_country($shipping_address->shipping_country_id);
            if (!empty($country)) {
                $data["shipping_country"] = $country->name;
            }
            $data["shipping_state"] = $shipping_address->shipping_state;
            $data["shipping_city"] = $shipping_address->shipping_city;
            $data["shipping_zip_code"] = $shipping_address->shipping_zip_code;

            $data["billing_first_name"] = $shipping_address->billing_first_name;
            $data["billing_last_name"] = $shipping_address->billing_last_name;
            $data["billing_email"] = $shipping_address->billing_email;
            $data["billing_phone_number"] = $shipping_address->billing_phone_number;
            $data["billing_address_1"] = $shipping_address->billing_address_1;
            $data["billing_address_2"] = $shipping_address->billing_address_2;
            $data["billing_country"] = $shipping_address->billing_country_id;
            $country = get_country($shipping_address->billing_country_id);
            if (!empty($country)) {
                $data["billing_country"] = $country->name;
            }
            $data["billing_state"] = $shipping_address->billing_state;
            $data["billing_city"] = $shipping_address->billing_city;
            $data["billing_zip_code"] = $shipping_address->billing_zip_code;

            return $this->db->insert('orders', $data);
        }

        return false;
    }

    //add order products
    public function add_order_products($order_id)
    {
        $cart_items = $this->cart_model->get_sess_cart_items();
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                $product = get_cart_product($cart_item->product_id);
                $data = array(
                    'order_id' => $order_id,
                    'seller_id' => $product->user_id,
                    'buyer_id' => 0,
                    'buyer_type' => "guest",
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'product_slug' => $product->slug,
                    'product_unit_price' => $product->price,
                    'product_quantity' => $cart_item->quantity,
                    'product_currency' => $product->currency,
                    'product_shipping_cost' => $product->shipping_cost,
                    'product_total_price' => $product->price,
                    'commission_rate' => $this->general_settings->commission_rate,
                    'order_status' => "awaiting_payment",
                    'is_approved' => 0,
                    'shipping_tracking_number' => "",
                    'shipping_tracking_url' => "",
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                if (auth_check()) {
                    $data["buyer_id"] = user()->id;
                    $data["buyer_type"] = "registered";
                }

                $total_price = ($product->price * $cart_item->quantity) + $product->shipping_cost;
                $data["product_total_price"] = $total_price;

                $this->db->insert('order_products', $data);
            }
        }
    }

    //update order number
    public function update_order_number($order_id)
    {
        $data = array(
            'order_number' => $order_id + 10000
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data);
    }

    //execute paypal order payment
    public function execute_order_payment_paypal($order_id)
    {
        $data = array(
            'payment_method' => "PayPal",
            'payment_id' => $this->input->post('payment_id', true),
            'order_id' => $order_id,
            'user_id' => 0,
            'user_type' => "guest",
            'currency' => $this->input->post('currency', true),
            'payment_amount' => $this->input->post('payment_amount', true),
            'payment_status' => $this->input->post('payment_status', true),
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        if (auth_check()) {
            $data["user_id"] = user()->id;
            $data["user_type"] = "registered";
        }
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }
        $this->db->insert('transactions', $data);
    }

    //execute stripe order payment
    public function execute_order_payment_stripe($order_id)
    {
        $data = array(
            'payment_method' => "Stripe",
            'payment_id' => $this->input->post('payment_id', true),
            'order_id' => $order_id,
            'user_id' => 0,
            'user_type' => "guest",
            'currency' => $this->input->post('currency', true),
            'payment_amount' => $this->input->post('payment_amount', true),
            'payment_status' => $this->input->post('payment_status', true),
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        if (auth_check()) {
            $data["user_id"] = user()->id;
            $data["user_type"] = "registered";
        }
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }
        $this->db->insert('transactions', $data);
    }

    //execute order payment iyzico
    public function execute_order_payment_iyzico($order_id, $token, $currency, $price)
    {
        $data = array(
            'payment_method' => "Iyzico",
            'payment_id' => $token,
            'order_id' => $order_id,
            'user_id' => 0,
            'user_type' => "guest",
            'currency' => $currency,
            'payment_amount' => $price,
            'payment_status' => "succeeded",
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        if (auth_check()) {
            $data["user_id"] = user()->id;
            $data["user_type"] = "registered";
        }
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }

        $this->db->insert('transactions', $data);
    }

    //update order payment as received
    public function update_order_payment_received($order_id)
    {
        $order = $this->get_order($order_id);
        if (!empty($order)) {
            //update product payment status
            $data_order = array(
                'payment_status' => "payment_received",
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data_order);

            //update order products payment status
            $order_products = $this->get_order_products($order_id);
            if (!empty($order_products)) {
                foreach ($order_products as $order_product) {
                    $data = array(
                        'order_status' => "payment_received",
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->db->where('id', $order_product->id);
                    $this->db->update('order_products', $data);
                }
            }
        }
    }

    //get orders count
    public function get_orders_count($user_id)
    {
        $this->db->where('buyer_id', $user_id);
        $this->db->where('status', 0);
        $query = $this->db->get('orders');
        return $query->num_rows();
    }

    //get paginated orders
    public function get_paginated_orders($user_id, $per_page, $offset)
    {
        $this->db->where('buyer_id', $user_id);
        $this->db->where('status', 0);
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('orders');
        return $query->result();
    }

    //get completed orders count
    public function get_completed_orders_count($user_id)
    {
        $this->db->where('buyer_id', $user_id);
        $this->db->where('status', 1);
        $query = $this->db->get('orders');
        return $query->num_rows();
    }

    //get paginated completed orders
    public function get_paginated_completed_orders($user_id, $per_page, $offset)
    {
        $this->db->where('buyer_id', $user_id);
        $this->db->where('status', 1);
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('orders');
        return $query->result();
    }

    //get order products
    public function get_order_products($order_id)
    {
        $this->db->where('order_id', $order_id);
        $query = $this->db->get('order_products');
        return $query->result();
    }

    //get order product
    public function get_order_product($order_product_id)
    {
        $this->db->where('id', $order_product_id);
        $query = $this->db->get('order_products');
        return $query->row();
    }

    //get order
    public function get_order($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('orders');
        return $query->row();
    }

    //get order by order number
    public function get_order_by_order_number($order_number)
    {
        $this->db->where('order_number', $order_number);
        $query = $this->db->get('orders');
        return $query->row();
    }

    //update order product status
    public function update_order_product_status($order_product_id)
    {
        $order_product = $this->get_order_product($order_product_id);
        if (!empty($order_product)) {
            if ($order_product->seller_id == user()->id) {
                $data = array(
                    'order_status' => $this->input->post('order_status', true),
                    'is_approved' => 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                if ($data['order_status'] == "shipped") {
                    $data['shipping_tracking_number'] = $this->input->post('shipping_tracking_number', true);
                    $data['shipping_tracking_url'] = $this->input->post('shipping_tracking_url', true);

                    //set email session
                    $this->session->set_userdata('mds_send_email_order_shipped', 1);
                    $this->session->set_userdata('mds_send_email_order_shipped_order_product_id', $order_product->id);
                }

                $this->db->where('id', $order_product_id);
                return $this->db->update('order_products', $data);
            }
        }
        return false;
    }

    //add bank transfer payment report
    public function add_bank_transfer_payment_report()
    {
        $data = array(
            'order_number' => $this->input->post('order_number', true),
            'payment_note' => $this->input->post('payment_note', true),
            'receipt_path' => "",
            'user_id' => 0,
            'user_type' => "guest",
            'status' => "pending",
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        if (auth_check()) {
            $data["user_id"] = user()->id;
            $data["user_type"] = "registered";
        }
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }

        $this->load->model('upload_model');
        $file_path = $this->upload_model->receipt_upload('file');
        if (!empty($file_path)) {
            $data["receipt_path"] = $file_path;
        }

        return $this->db->insert('bank_transfers', $data);
    }

    //get sales count
    public function get_sales_count($user_id)
    {
        $this->db->join('order_products', 'order_products.order_id = orders.id');
        $this->db->select('orders.id');
        $this->db->group_by('orders.id');
        $this->db->where('order_products.seller_id', $user_id);
        $this->db->where('order_products.is_approved', 0);
        $query = $this->db->get('orders');
        return $query->num_rows();
    }

    //get paginated sales
    public function get_paginated_sales($user_id, $per_page, $offset)
    {
        $this->db->join('order_products', 'order_products.order_id = orders.id');
        $this->db->select('orders.id');
        $this->db->group_by('orders.id');
        $this->db->where('order_products.seller_id', $user_id);
        $this->db->where('order_products.is_approved', 0);
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('orders');
        return $query->result();
    }

    //get completed sales count
    public function get_completed_sales_count($user_id)
    {
        $this->db->join('order_products', 'order_products.order_id = orders.id');
        $this->db->select('orders.id');
        $this->db->group_by('orders.id');
        $this->db->where('order_products.seller_id', $user_id);
        $this->db->where('order_products.is_approved', 1);
        $query = $this->db->get('orders');
        return $query->num_rows();
    }

    //get paginated completed sales
    public function get_paginated_completed_sales($user_id, $per_page, $offset)
    {
        $this->db->join('order_products', 'order_products.order_id = orders.id');
        $this->db->select('orders.id');
        $this->db->group_by('orders.id');
        $this->db->where('order_products.seller_id', $user_id);
        $this->db->where('order_products.is_approved', 1);
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('orders');
        return $query->result();
    }

    //check order seller
    public function check_order_seller($order_id)
    {
        $order_products = $this->get_order_products($order_id);
        $result = false;
        if (!empty($order_products)) {
            foreach ($order_products as $product) {
                if ($product->seller_id == user()->id) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    //get seller total price
    public function get_seller_total_price($order_id)
    {
        $order_products = $this->get_order_products($order_id);
        $total = 0;
        if (!empty($order_products)) {
            foreach ($order_products as $product) {
                if ($product->seller_id == user()->id) {
                    $total += $product->product_total_price;
                }
            }
        }
        return $total;
    }

    //approve order product
    public function approve_order_product($order_product_id)
    {
        $order_product = $this->get_order_product($order_product_id);

        if (!empty($order_product)) {
            if (user()->id == $order_product->buyer_id) {
                $data = array(
                    'is_approved' => 1,
                    'order_status' => "completed",
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->db->where('id', $order_product_id);
                return $this->db->update('order_products', $data);
            }
        }

        return false;
    }

    //decrease product quantity after sale
    public function decrease_product_quantity_after_sale($order)
    {
        $order_products = $this->get_order_products($order->id);
        if (!empty($order_products)) {
            foreach ($order_products as $order_product) {
                $product = get_product($order_product->product_id);
                if (!empty($product)) {
                    if ($product->quantity > 1) {
                        $data = array(
                            'quantity' => $product->quantity - $order_product->product_quantity
                        );
                        if ($data['quantity'] < 1) {
                            $data['is_sold'] = 1;
                        }
                        $this->db->where('id', $product->id);
                        $this->db->update('products', $data);
                    } elseif ($product->quantity == 1) {
                        $data = array(
                            'quantity' => 0,
                            'is_sold' => 1
                        );
                        $this->db->where('id', $product->id);
                        $this->db->update('products', $data);
                    }
                }
            }
        }
    }

    //check order products status / update if all suborders completed
    public function update_order_status_if_completed($order_id)
    {
        $all_complated = true;
        $order_products = $this->get_order_products($order_id);
        if (!empty($order_products)) {
            foreach ($order_products as $order_product) {
                if ($order_product->order_status != "completed" && $order_product->order_status != "cancelled") {
                    $all_complated = false;
                }
            }
            $data = array(
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($all_complated == true) {
                $data["status"] = 1;
            }
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        }
    }

}
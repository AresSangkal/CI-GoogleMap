<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!auth_check()) {
            redirect(lang_base_url());
        }
        $this->order_per_page = 15;
        $this->earnings_per_page = 15;
        $this->user_id = user()->id;
        //check system
        if ($this->selected_system != "marketplace") {
            redirect(lang_base_url());
        }
    }

    /**
     * Orders
     */
    public function orders()
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $data["active_tab"] = "active_orders";
        $data["curr_user_role"] = get_current_user_role();

        $pagination = $this->paginate(lang_base_url() . 'orders', $this->order_model->get_orders_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_orders($this->user_id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('order/orders', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Completed Orders
     */
    public function completed_orders()
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $data["active_tab"] = "completed_orders";
        $data["curr_user_role"] = get_current_user_role();

        $pagination = $this->paginate(lang_base_url() . 'orders', $this->order_model->get_completed_orders_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_completed_orders($this->user_id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('order/orders', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Order
     */
    public function order($order_number)
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $data["active_tab"] = "";
        $data["curr_user_role"] = get_current_user_role();

        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect($this->agent->referrer());
        }
        if ($data["order"]->buyer_id != $this->user_id) {
            redirect($this->agent->referrer());
        }
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);

        $data["last_bank_transfer"] = $this->order_admin_model->get_bank_transfer_by_order_number($data["order"]->order_number);

        $this->load->view('partials/_header', $data);
        $this->load->view('order/order', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Bank Transfer Payment Report Post
     */
    public function bank_transfer_payment_report_post()
    {
        $this->order_model->add_bank_transfer_payment_report();
        redirect($this->agent->referrer());
    }

    /**
     * Sales
     */
    public function sales()
    {
        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "active_sales";
        $data["curr_user_role"] = get_current_user_role();

        $pagination = $this->paginate(lang_base_url() . 'sales', $this->order_model->get_sales_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_sales($this->user_id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Completed Sales
     */
    public function completed_sales()
    {
        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "completed_sales";
        $data["curr_user_role"] = get_current_user_role();

        $pagination = $this->paginate(lang_base_url() . 'sales', $this->order_model->get_completed_sales_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_completed_sales($this->user_id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Sale
     */
    public function sale($order_number)
    {
        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "";
        $data["curr_user_role"] = get_current_user_role();

        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect($this->agent->referrer());
        }
        if (!$this->order_model->check_order_seller($data["order"]->id)) {
            redirect($this->agent->referrer());
        }
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sale', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Update Order Product Status Post
     */
    public function update_order_product_status_post()
    {
        $id = $this->input->post('id', true);
        $order_product = $this->order_model->get_order_product($id);
        if (!empty($order_product)) {
            $this->order_model->update_order_product_status($id);
            $this->order_model->update_order_status_if_completed($order_product->order_id);
        }
        redirect($this->agent->referrer());
    }

    /**
     * Approve Order Product
     */
    public function approve_order_product_post()
    {
        $order_product_id = $this->input->post('order_product_id', true);
        if ($this->order_model->approve_order_product($order_product_id)) {
            //order product
            $order_product = $this->order_model->get_order_product($order_product_id);
            //add earnings
            $this->earnings_model->add_earnings($order_product);
            //add to seller balance
            $this->earnings_model->add_to_seller_balance($order_product);
            //increase seller sales
            $this->earnings_model->increase_seller_sales($order_product);
            //update order status
            $this->order_model->update_order_status_if_completed($order_product->order_id);
        }
    }

}
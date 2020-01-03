<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->review_limit = 6;
    }

    //get custom fields by lang
    public function get_custom_fields_by_lang()
    {
        $category_id = $this->input->post("category_id", true);
        $data["lang_id"] = $this->input->post("lang_id", true);
        $fields = $this->field_model->get_custom_fields_by_lang($category_id, $data["lang_id"]);
        $array = array();
        foreach ($fields as $field) {
            $data_field = new stdClass();
            $data_field->id = $field->id;
            $data_field->field_type = $field->field_type;
            $data_field->name = $field->name;
            $data_field->is_required = $field->is_required;
            $data_field->category_id = $field->category_id;
            $data_field->row_width = $field->row_width;
            $data_field->field_value = "";
            array_push($array, $data_field);
        }
        $data["custom_fields"] = $array;
        $this->load->view('product/_response_custom_fields', $data);
    }


    /*
    *------------------------------------------------------------------------------------------
    * USER REVIEW FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //add user review
    public function add_user_review()
    {
        if ($this->general_settings->user_reviews != 1) {
            exit();
        }
        $seller_id = $this->input->post('seller_id', true);
        $review = $this->user_review_model->get_review_by_user($seller_id, user()->id);
        if (!empty($review)) {
            echo "voted_error";
        } else {
            $this->user_review_model->add_review();
        }
    }

    //load more review
    public function load_more_user_review()
    {
        $seller_id = $this->input->post('seller_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->review_limit;
        $data["user"] = $this->auth_model->get_user($seller_id);
        $data["reviews"] = $this->user_review_model->get_limited_reviews($seller_id, $new_limit);
        $data['review_count'] = $this->user_review_model->get_review_count($seller_id);
        $data['review_limit'] = $new_limit;

        $this->load->view('profile/_user_reviews', $data);
    }

    //delete user review
    public function delete_user_review()
    {
        $id = $this->input->post('review_id', true);
        $this->user_review_model->delete_review($id);
    }

    /*
    *------------------------------------------------------------------------------------------
    * EMAIL FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //send email order summary to user
    public function send_email_order_summary()
    {
        $order_id = $this->input->post('order_id', true);
        //send email
        if ($this->general_settings->send_email_buyer_purchase == 1) {
            $this->load->model("email_model");
            $this->email_model->send_email_new_order($order_id);
        }
        reset_flash_data();
    }

    //send email new product
    public function send_email_new_product()
    {
        $product_link = $this->input->post('product_link', true);
        //send email
        if ($this->general_settings->send_email_new_product == 1) {
            $this->load->model('email_model');
            $this->email_model->send_email_new_product($product_link);
        }
        reset_flash_data();
    }

    //send email new message
    public function send_email_new_message()
    {
        $receiver_id = $this->input->post('receiver_id', true);
        $message_subject = $this->input->post('message_subject', true);
        $message_text = $this->input->post('message_text', true);

        $user = get_user($receiver_id);
        if (!empty($user)) {
            //send email
            if ($user->send_email_new_message == 1) {
                $this->load->model('email_model');
                $this->email_model->send_email_new_message($user, $message_subject, $message_text);
            }
        }
        reset_flash_data();
    }

    //send email order shipped
    public function send_email_order_shipped()
    {
        $order_product_id = $this->input->post('order_product_id', true);
        $order_product = $this->order_model->get_order_product($order_product_id);
        if (!empty($order_product)) {
            if ($this->general_settings->send_email_order_shipped == 1) {
                $this->load->model('email_model');
                $this->email_model->send_email_order_shipped($order_product);
            }
        }
        reset_flash_data();
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * BACK-END
    *-------------------------------------------------------------------------------------------------
    */

    //add category to custom field
    public function add_category_to_custom_field()
    {
        $field_id = $this->input->post("field_id");
        $category_id = $this->input->post("category_id");
        $this->field_model->add_category_to_field($field_id, $category_id);

        $data['field'] = $this->field_model->get_field($field_id);
        $data['categories'] = $this->category_model->get_parent_categories();
        $data['field_categories'] = $this->field_model->get_field_categories($data['field']->id);

        $this->load->view('admin/category/_custom_field_update_response', $data);
    }

    //add category to custom fields array
    public function add_category_to_custom_fields_array()
    {
        $category_id = $this->input->post("category_id");
        $array = $this->field_model->get_sess_custom_fields_category_array();
        //check array
        $index = array_search($category_id, $array);
        if (empty($index)) {
            array_push($array, $category_id);
            $this->field_model->set_sess_custom_fields_category_array($array);
        }

        $data['categories'] = $this->category_model->get_parent_categories();
        $this->load->view('admin/category/_custom_field_response', $data);
    }

    //clear custom fields array
    public function clear_custom_fields_session_array()
    {
        $this->field_model->unset_sess_custom_fields_category_array();
        $data['categories'] = $this->category_model->get_parent_categories();
        $this->load->view('admin/category/_custom_field_response', $data);
    }

    //clear custom files categories
    public function clear_custom_files_categories()
    {
        $field_id = $this->input->post("field_id");
        $this->field_model->clear_field_categories($field_id);

        $data['field'] = $this->field_model->get_field($field_id);
        $data['categories'] = $this->category_model->get_parent_categories();
        $data['field_categories'] = $this->field_model->get_field_categories($data['field']->id);

        $this->load->view('admin/category/_custom_field_update_response', $data);
    }

    //delete category from a custom field session
    public function delete_custom_field_category_session()
    {
        $category_id = $this->input->post("category_id");
        $array = $this->field_model->get_sess_custom_fields_category_array();
        $new_array = array();

        if (!empty($array)) {
            foreach ($array as $item) {
                if ($item != $category_id) {
                    $new_array[] = $item;
                }
            }
        }
        $this->field_model->set_sess_custom_fields_category_array($new_array);

        $data['categories'] = $this->category_model->get_parent_categories();
        $this->load->view('admin/category/_custom_field_response', $data);
    }

    //delete category from a custom field
    public function delete_custom_field_category()
    {
        $field_id = $this->input->post("field_id");
        $category_id = $this->input->post("category_id");
        $this->field_model->delete_category_from_field($field_id, $category_id);

        $data['field'] = $this->field_model->get_field($field_id);
        $data['categories'] = $this->category_model->get_parent_categories();
        $data['field_categories'] = $this->field_model->get_field_categories($data['field']->id);

        $this->load->view('admin/category/_custom_field_update_response', $data);
    }

    //add custom field option session
    public function add_custom_field_option_session()
    {
        $array = $this->field_model->get_sess_custom_field_options_array();
        $common_id = uniqid();
        foreach ($this->languages as $language) {
            $option = $this->input->post('option_lang_' . $language->id, true);
            if (!empty($option)) {
                $data = new stdClass();
                $data->lang_id = $language->id;
                $data->field_option = trim($option);
                $data->common_id = $common_id;
                array_push($array, $data);
            }
        }
        $this->field_model->set_sess_custom_field_options_array($array);
        $this->load->view('admin/category/_custom_field_options_response');
    }

    //delete custom field option session
    public function delete_custom_field_option_session()
    {
        $common_id = $this->input->post("common_id");
        $array = $this->field_model->get_sess_custom_field_options_array();
        $new_array = array();

        if (!empty($array)) {
            foreach ($array as $item) {
                if ($item->common_id != $common_id) {
                    $new_array[] = $item;
                }
            }
        }
        $this->field_model->set_sess_custom_field_options_array($new_array);
        $this->load->view('admin/category/_custom_field_options_response');
    }

    //add custom field optiom
    public function add_custom_field_option()
    {
        $field_id = $this->input->post("field_id");
        $this->field_model->add_field_option($field_id);
    }

    //delete custom field optiom
    public function delete_custom_field_option()
    {
        $common_id = $this->input->post("common_id");
        $this->field_model->delete_custom_field_option($common_id);
    }


}

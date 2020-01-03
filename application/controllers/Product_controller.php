<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->review_limit = 5;
        $this->comment_limit = 5;
        $this->product_per_page = 15;
    }

    /**
     * Add Product
     */
    public function add_product()
    {
        //check auth
        if (!auth_check()) {
            redirect(lang_base_url());
        }
        if (!is_multi_vendor_active()) {
            redirect(lang_base_url());
        }
        if ($this->general_settings->email_verification == 1 && user()->email_status != 1) {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(lang_base_url() . "settings/update-profile");
        }

        $data['title'] = trans("sell_now");
        $data['description'] = trans("sell_now") . " - " . $this->app_name;
        $data['keywords'] = trans("sell_now") . "," . $this->app_name;
        $data["lang_settings"] = get_language_settings();

        $this->load->view('partials/_header', $data);
        $this->load->view('product/add_product');
        $this->load->view('partials/_footer');
    }

    /**
     * Add Product Post
     */
    public function add_product_post()
    {
        //check auth
        if (!auth_check()) {
            redirect(lang_base_url());
        }
        if (!is_multi_vendor_active()) {
            redirect(lang_base_url());
        }
        if ($this->general_settings->email_verification == 1 && user()->email_status != 1) {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(lang_base_url() . "settings/update-profile");
        }
        //add product
        if ($this->product_model->add_product()) {
            //last id
            $last_id = $this->db->insert_id();
            //update slug
            $this->product_model->update_slug($last_id);
            //add product images
            $this->file_model->add_product_images($last_id);
            //qdd custom fields
            $this->product_model->add_product_custom_fields($last_id);

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data(user()->id);
            //set email session
            $this->session->set_userdata('mds_send_email_new_product', 1);

            if ($this->promoted_products_enabled == 1) {
                $this->session->set_flashdata('success', trans("msg_product_added"));
                redirect(lang_base_url() . "promote-product/pricing/" . $last_id . "?type=new");
            } else {
                $this->session->set_flashdata('success', trans("msg_product_added"));
                redirect($this->agent->referrer());
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Update Product
     */
    public function update_product($id)
    {
        //check auth
        if (!auth_check()) {
            redirect(lang_base_url());
        }
        if (!is_multi_vendor_active()) {
            redirect(lang_base_url());
        }
        $data["product"] = $this->product_admin_model->get_product($id);
        if (empty($data["product"])) {
            redirect($this->agent->referrer());
        }
        if ($data["product"]->is_deleted == 1) {
            if (user()->role != "admin") {
                redirect($this->agent->referrer());
            }
        }
        if ($data["product"]->user_id != user()->id && user()->role != "admin") {
            redirect($this->agent->referrer());
        }

        $data['title'] = trans("update_product");
        $data['description'] = trans("update_product") . " - " . $this->app_name;
        $data['keywords'] = trans("update_product") . "," . $this->app_name;
        $data["lang_settings"] = get_language_settings();
        $data['subcategories'] = $this->category_model->get_subcategories_by_parent_id($data["product"]->category_id);
        $data['third_categories'] = $this->category_model->get_subcategories_by_parent_id($data["product"]->subcategory_id);
        if ($this->general_settings->default_product_location == 0) {
            $data["states"] = $this->location_model->get_states_by_country($data["product"]->country_id);
        } else {
            $data["states"] = $this->location_model->get_states_by_country($this->general_settings->default_product_location);
        }
        $data["images_array"] = $this->file_model->get_product_images_array($data["product"]->id);
        $data["custom_field_array"] = $this->field_model->generate_custom_fields_array($data["product"]->category_id, $data["product"]->subcategory_id, $data["product"]->third_category_id, $data["product"]->id);
        $data["lang_id"] = $this->selected_lang->id;
        if (!empty($data["custom_field_array"])) {
            $array = array();
            foreach ($data["custom_field_array"] as $field) {
                $array['field_value_' . $field->id] = $field->field_value;
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('product/update_product');
        $this->load->view('partials/_footer');
    }

    /**
     * Update Product Post
     */
    public function update_product_post()
    {
        //check auth
        if (!auth_check()) {
            redirect(lang_base_url());
        }

        //validate inputs
        $this->form_validation->set_rules('title', trans("title"), 'required|xss_clean|max_length[500]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect($this->agent->referrer());
        } else {
            //product id
            $product_id = $this->input->post('id', true);
            //user id
            $user_id = 0;
            $product = $this->product_admin_model->get_product($product_id);
            if (!empty($product)) {
                $user_id = $product->user_id;
            }

            if ($product->user_id != user()->id && user()->role != "admin") {
                redirect($this->agent->referrer());
            }

            if ($this->product_model->update_product($product_id)) {
                //update slug
                $this->product_model->update_slug($product_id);
                //update custom fields
                $this->product_model->update_product_custom_fields($product_id);

                //reset cache
                reset_cache_data_on_change();
                reset_user_cache_data($user_id);
                reset_product_img_cache_data($product_id);

                $this->session->set_flashdata('success', trans("msg_product_updated"));
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Filter Products
     */
    public function filter_products()
    {
        $search_type = $this->input->post('search_type', true);
        $search = trim($this->input->post('search', true));

        if ($search_type == "member") {
            redirect(lang_base_url() . "members?search=" . $search);
            exit();
        }

        //set sort
        $sort = $this->input->post("sort", true);
        if (!empty($sort)) {
            $this->session->set_userdata('modesy_sort_products', $sort);
        }

        //filters
        $category_id = $this->input->post("category_id", true);
        $subcategory_id = $this->input->post("subcategory_id", true);
        $third_category_id = $this->input->post("third_category_id", true);
        $country = $this->input->post("country", true);
        $state = $this->input->post("state", true);
        $condition = $this->input->post("condition", true);
        $p_min = $this->input->post("p_min", true);
        $p_max = $this->input->post("p_max", true);

        //reset filters
        $reset_search = $this->input->post("reset_search", true);
        $rreset_price = $this->input->post("reset_price", true);
        if (!empty($reset_search)) {
            $search = "";
        }
        if (!empty($rreset_price)) {
            $p_min = "";
            $p_max = "";
        }

        if (!empty($category_id)) {
            $category = $this->category_model->get_category($category_id);
        }
        if (!empty($subcategory_id)) {
            $subcategory = $this->category_model->get_category($subcategory_id);
        }
        if (!empty($third_category_id)) {
            $third_category = $this->category_model->get_category($third_category_id);
        }
        if (empty($condition)) {
            $condition = "all";
        }
        //generate query string
        $query_string = "?condition=" . $condition;
        if (!empty(get_sess_modesy_sort_products())) {
            $query_string .= "&sort=" . get_sess_modesy_sort_products();
        }
        if (!empty($country)) {
            $query_string .= "&country=" . $country;
        }
        if (!empty($state)) {
            $query_string .= "&state=" . $state;
        }
        if ($p_min != "") {
            $query_string .= "&p_min=" . intval($p_min);
        }
        if ($p_max != "") {
            $query_string .= "&p_max=" . intval($p_max);
        }
        if ($search != "" && $search_type == "product") {
            $query_string .= "&search=" . $search;
        }

        if (!empty($third_category) && !empty($subcategory) && !empty($category)) {
            redirect(lang_base_url() . 'category' . '/' . $category->slug . '/' . $subcategory->slug . '/' . $third_category->slug . $query_string);
        } elseif (!empty($subcategory) && !empty($category)) {
            redirect(lang_base_url() . 'category' . '/' . $category->slug . '/' . $subcategory->slug . $query_string);
        } elseif (!empty($category)) {
            redirect(lang_base_url() . 'category' . '/' . $category->slug . $query_string);
        } else {
            redirect(lang_base_url() . 'products' . $query_string);
        }
    }

    /**
     * Products
     */
    public function products()
    {
        $data['title'] = trans("products");
        $data['description'] = trans("products") . " - " . $this->app_name;
        $data['keywords'] = trans("products") . "," . $this->app_name;
        //get paginated posts
        $link = lang_base_url() . 'products';
        $pagination = $this->paginate($link, $this->product_model->get_paginated_filtered_products_count(null, null, null), $this->product_per_page);
        $data['products'] = $this->product_model->get_paginated_filtered_products(null, null, null, $pagination['per_page'], $pagination['offset']);
        $data["categories"] = $this->category_model->get_parent_categories();

        //filters
        $data['filter_country'] = $this->input->get("country");
        $data['filter_state'] = $this->input->get("state");
        $data['filter_condition'] = $this->input->get("condition");
        $data['filter_p_min'] = $this->input->get("p_min");
        $data['filter_p_max'] = $this->input->get("p_max");
        $data['filter_sort'] = $this->input->get("sort");
        $data['filter_search'] = $this->input->get("search");
        $data["lang_settings"] = get_language_settings();
        if (empty($data['filter_sort'])) {
            if (!empty(get_sess_modesy_sort_products())) {
                $this->session->unset_userdata('modesy_sort_products');
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('product/products', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Category
     */
    public function category($slug)
    {
        $slug = clean_slug($slug);
        $data["category"] = $this->category_model->get_category_by_slug($slug);
        if (empty($data['category'])) {
            redirect($this->agent->referrer());
        }
        $data["subcategories"] = $this->category_model->get_subcategories_by_parent_id($data["category"]->id);

        $data['title'] = $data["category"]->name;
        $data['description'] = $data["category"]->description;
        $data['keywords'] = $data["category"]->keywords;
        //get paginated posts
        $link = lang_base_url() . 'category/' . $data["category"]->slug;
        $pagination = $this->paginate($link, $this->product_model->get_paginated_filtered_products_count($data["category"]->id, null, null), $this->product_per_page);
        $data['products'] = $this->product_model->get_paginated_filtered_products($data["category"]->id, null, null, $pagination['per_page'], $pagination['offset']);

        //filters
        $data['filter_country'] = $this->input->get("country");
        $data['filter_state'] = $this->input->get("state");
        $data['filter_condition'] = $this->input->get("condition");
        $data['filter_p_min'] = $this->input->get("p_min");
        $data['filter_p_max'] = $this->input->get("p_max");
        $data['filter_sort'] = $this->input->get("sort");
        $data['filter_search'] = $this->input->get("search");
        $data["lang_settings"] = get_language_settings();
        if (empty($data['filter_sort'])) {
            if (!empty(get_sess_modesy_sort_products())) {
                $this->session->unset_userdata('modesy_sort_products');
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('product/products', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Subcategory
     */
    public function subcategory($category_slug, $subcategory_slug)
    {
        $data["category"] = $this->category_model->get_category_by_slug($category_slug);
        if (empty($data['category'])) {
            redirect($this->agent->referrer());
        }
        $data["subcategory"] = $this->category_model->get_category_by_slug($subcategory_slug);
        if (empty($data['subcategory'])) {
            redirect($this->agent->referrer());
        }
        $data["third_categories"] = $this->category_model->get_subcategories_by_parent_id($data["subcategory"]->id);

        $data['title'] = $data["subcategory"]->name;
        $data['description'] = $data["subcategory"]->description;
        $data['keywords'] = $data["subcategory"]->keywords;
        //get paginated posts
        $link = lang_base_url() . 'category/' . $data["category"]->slug . '/' . $data["subcategory"]->slug;
        $pagination = $this->paginate($link, $this->product_model->get_paginated_filtered_products_count($data["category"]->id, $data["subcategory"]->id, null), $this->product_per_page);
        $data['products'] = $this->product_model->get_paginated_filtered_products($data["category"]->id, $data["subcategory"]->id, null, $pagination['per_page'], $pagination['offset']);

        //filters
        $data['filter_country'] = $this->input->get("country");
        $data['filter_state'] = $this->input->get("state");
        $data['filter_condition'] = $this->input->get("condition");
        $data['filter_p_min'] = $this->input->get("p_min");
        $data['filter_p_max'] = $this->input->get("p_max");
        $data['filter_sort'] = $this->input->get("sort");
        $data['filter_search'] = $this->input->get("search");
        $data["lang_settings"] = get_language_settings();
        if (empty($data['filter_sort'])) {
            if (!empty(get_sess_modesy_sort_products())) {
                $this->session->unset_userdata('modesy_sort_products');
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('product/products', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Third Category
     */
    public function third_category($category_slug, $subcategory_slug, $thirdcategory_slug)
    {
        $data["category"] = $this->category_model->get_category_by_slug($category_slug);
        if (empty($data['category'])) {
            redirect($this->agent->referrer());
        }
        $data["subcategory"] = $this->category_model->get_category_by_slug($subcategory_slug);
        if (empty($data['subcategory'])) {
            redirect($this->agent->referrer());
        }
        $data["third_category"] = $this->category_model->get_category_by_slug($thirdcategory_slug);
        if (empty($data['third_category'])) {
            redirect($this->agent->referrer());
        }

        $data["third_categories"] = $this->category_model->get_subcategories_by_parent_id($data["subcategory"]->id);

        $data['title'] = $data["third_category"]->name;
        $data['description'] = $data["third_category"]->description;
        $data['keywords'] = $data["third_category"]->keywords;
        //get paginated posts
        $link = lang_base_url() . 'category/' . $data["category"]->slug . '/' . $data["subcategory"]->slug . '/' . $data["third_category"]->slug;
        $pagination = $this->paginate($link, $this->product_model->get_paginated_filtered_products_count($data["category"]->id, $data["subcategory"]->id, $data["third_category"]->id), $this->product_per_page);
        $data['products'] = $this->product_model->get_paginated_filtered_products($data["category"]->id, $data["subcategory"]->id, $data["third_category"]->id, $pagination['per_page'], $pagination['offset']);

        //filters
        $data['filter_country'] = $this->input->get("country");
        $data['filter_state'] = $this->input->get("state");
        $data['filter_condition'] = $this->input->get("condition");
        $data['filter_p_min'] = $this->input->get("p_min");
        $data['filter_p_max'] = $this->input->get("p_max");
        $data['filter_sort'] = $this->input->get("sort");
        $data['filter_search'] = $this->input->get("search");
        $data["lang_settings"] = get_language_settings();
        if (empty($data['filter_sort'])) {
            if (!empty(get_sess_modesy_sort_products())) {
                $this->session->unset_userdata('modesy_sort_products');
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('product/products', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Delete Product
     */
    public function delete_product()
    {
        //check auth
        if (!auth_check()) {
            redirect(lang_base_url());
        }

        $id = $this->input->post('id', true);

        //user id
        $user_id = 0;
        $product = $this->product_admin_model->get_product($id);
        if (!empty($product)) {
            $user_id = $product->user_id;
        }

        if (user()->role == "admin" || user()->id == $user_id) {
            if ($this->product_model->delete_product($id)) {
                $this->session->set_flashdata('success', trans("msg_product_deleted"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data($user_id);
        }
    }

    //make review
    public function make_review()
    {
        if ($this->general_settings->product_reviews != 1) {
            exit();
        }
        $limit = $this->input->post('limit', true);
        $product_id = $this->input->post('product_id', true);
        $review = $this->review_model->get_review($product_id, user()->id);
        $data["product"] = $this->product_model->get_product_by_id($product_id);

        if (!empty($review)) {
            echo "voted_error";
        } elseif ($data["product"]->user_id == user()->id) {
            echo "error_own_product";
        } else {
            $this->review_model->add_review();
            $data["reviews"] = $this->review_model->get_limited_reviews($product_id, $limit);
            $data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
            $data['review_limit'] = $limit;
            $data["product"] = $this->product_model->get_product_by_id($product_id);
            $this->load->view('product/_make_review', $data);
        }
    }

    //load more review
    public function load_more_review()
    {
        $product_id = $this->input->post('product_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->review_limit;
        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data["reviews"] = $this->review_model->get_limited_reviews($product_id, $new_limit);
        $data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
        $data['review_limit'] = $new_limit;

        $this->load->view('product/_make_review', $data);
    }

    //delete review
    public function delete_review()
    {
        $id = $this->input->post('id', true);
        $product_id = $this->input->post('product_id', true);
        $user_id = $this->input->post('user_id', true);
        $limit = $this->input->post('limit', true);

        $review = $this->review_model->get_review($product_id, $user_id);
        if (auth_check() && !empty($review)) {
            if (user()->role == "admin" || user()->id == $review->user_id) {
                $this->review_model->delete_review($id, $product_id);
            }
        }

        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data["reviews"] = $this->review_model->get_limited_reviews($product_id, $limit);
        $data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
        $data['review_limit'] = $limit;

        $this->load->view('product/_make_review', $data);
    }

    //make comment
    public function make_comment()
    {
        if ($this->general_settings->product_comments != 1) {
            exit();
        }
        $limit = $this->input->post('limit', true);
        $product_id = $this->input->post('product_id', true);

        if (auth_check()) {
            $this->comment_model->add_comment();
        } else {
            if ($this->recaptcha_verify_request()) {
                $this->comment_model->add_comment();
            }
        }

        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data['comment_count'] = $this->comment_model->get_product_comment_count($product_id);
        $data['comments'] = $this->comment_model->get_comments($product_id, $limit);
        $data['comment_limit'] = $limit;

        $this->load->view('product/_comments', $data);
    }

    //load more comment
    public function load_more_comment()
    {
        $product_id = $this->input->post('product_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->comment_limit;
        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data["comments"] = $this->comment_model->get_comments($product_id, $new_limit);
        $data['comment_count'] = $this->comment_model->get_product_comment_count($data["product"]->id);
        $data['comment_limit'] = $new_limit;

        $this->load->view('product/_comments', $data);
    }

    //delete comment
    public function delete_comment()
    {
        $id = $this->input->post('id', true);
        $product_id = $this->input->post('product_id', true);
        $limit = $this->input->post('limit', true);

        $comment = $this->comment_model->get_comment($id);
        if (auth_check() && !empty($comment)) {
            if (user()->role == "admin" || user()->id == $comment->user_id) {
                $this->comment_model->delete_comment($id);
            }
        }

        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data["comments"] = $this->comment_model->get_comments($product_id, $limit);
        $data['comment_count'] = $this->comment_model->get_product_comment_count($data["product"]->id);
        $data['comment_limit'] = $limit;

        $this->load->view('product/_comments', $data);
    }

    //delete comment
    public function load_subcomment_box()
    {
        $comment_id = $this->input->post('comment_id', true);
        $limit = $this->input->post('limit', true);
        $data["parent_comment"] = $this->comment_model->get_comment($comment_id);
        $data["comment_limit"] = $limit;
        $this->load->view('product/_make_subcomment', $data);
    }

    //set product as sold
    public function set_product_as_sold()
    {
        $product_id = $this->input->post('product_id', true);
        if (auth_check()) {
            $this->product_model->set_product_as_sold($product_id);
        }
    }

    //add or remove favorites
    public function add_remove_favorites()
    {
        if (auth_check()) {
            $product_id = $this->input->post('product_id', true);
            $user_id = user()->id;
            $this->product_model->add_remove_favorites($user_id, $product_id);
            redirect($this->agent->referrer());
        }
    }

    //add or remove favorites
    public function add_remove_favorite_ajax()
    {
        if (auth_check()) {
            $product_id = $this->input->post('product_id', true);
            $user_id = user()->id;
            $this->product_model->add_remove_favorites($user_id, $product_id);
        }
    }

    //get states
    public function get_states()
    {
        $country_id = $this->input->post('country_id', true);
        $states = $this->location_model->get_states_by_country($country_id);
        foreach ($states as $item) {
            echo '<option value="' . $item->id . '">' . $item->name . '</option>';
        }
    }

    //show address on map
    public function show_address_on_map()
    {
        $country_text = $this->input->post('country_text', true);
        $country_val = $this->input->post('country_val', true);
        $state_text = $this->input->post('state_text', true);
        $state_val = $this->input->post('state_val', true);
        $address = $this->input->post('address', true);
        $zip_code = $this->input->post('zip_code', true);

        $adress_details = $address . " " . $zip_code;
        $data["map_address"] = "";
        if (!empty($adress_details)) {
            $data["map_address"] = $adress_details . " ";
        }
        if (!empty($state_val)) {
            $data["map_address"] = $data["map_address"] . $state_text . " ";
        }
        if (!empty($country_val)) {
            $data["map_address"] = $data["map_address"] . $country_text;
        }

        $this->load->view('product/_load_map', $data);
    }

}
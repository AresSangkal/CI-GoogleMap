<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        //default location
        $this->default_location_id = 0;
        if (!empty($this->session->userdata('modesy_default_location'))) {
            $this->default_location_id = $this->session->userdata('modesy_default_location');
        }
    }

    //add product
    public function add_product()
    {
        $data = array(
            'title' => $this->input->post('title', true),
            'category_id' => $this->input->post('category_id', true),
            'subcategory_id' => $this->input->post('subcategory_id', true),
            'third_category_id' => $this->input->post('third_category_id', true),
            'price' => $this->input->post('price', true),
            'currency' => $this->input->post('currency', true),
            'description' => $this->input->post('description', false),
            'product_condition' => $this->input->post('product_condition', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'address' => $this->input->post('address', true),
            'zip_code' => $this->input->post('zip_code', true),
            'user_id' => user()->id,
            'status' => 0,
            'is_promoted' => 0,
            'promote_start_date' => date('Y-m-d H:i:s'),
            'promote_end_date' => date('Y-m-d H:i:s'),
            'promote_plan' => "none",
            'promote_day' => 0,
            'visibility' => 1,
            'rating' => 0,
            'hit' => 0,
            'external_link' => trim($this->input->post('external_link', true)),
            'quantity' => $this->input->post('quantity', true),
            'shipping_time' => $this->input->post('shipping_time', true),
            'shipping_cost_type' => $this->input->post('shipping_cost_type', true),
            'shipping_cost' => $this->input->post('shipping_cost', true),
            'is_sold' => 0,
            'is_deleted' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );

        $data["slug"] = str_slug($data["title"]);
        $data["price"] = price_database_format($data["price"]);
        if (empty($data["subcategory_id"])) {
            $data["subcategory_id"] = 0;
        }
        if (empty($data["third_category_id"])) {
            $data["third_category_id"] = 0;
        }
        if (empty($data["country_id"])) {
            $data["country_id"] = 0;
        }
        if (empty($data["state_id"])) {
            $data["state_id"] = 0;
        }
        if (empty($data["address"])) {
            $data["address"] = "";
        }
        if (empty($data["zip_code"])) {
            $data["zip_code"] = "";
        }
        if (empty($data["external_link"])) {
            $data["external_link"] = "";
        }
        if ($data["shipping_cost_type"] == "shipping_buyer_pays") {
            $data["shipping_cost"] = price_database_format($data["shipping_cost"]);
        } else {
            $data["shipping_cost"] = 0;
        }
        if ($this->general_settings->approve_before_publishing == 0) {
            $data["status"] = 1;
        }
        return $this->db->insert('products', $data);
    }

    //add custom fields
    public function add_product_custom_fields($product_id)
    {
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $custom_fields = $this->field_model->generate_custom_fields_array($product->category_id, $product->subcategory_id, $product->third_category_id, null);
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_field) {
                    $data = array(
                        'field_id' => $custom_field->id,
                        'product_id' => $product_id,
                        'field_value' => $this->input->post('field_' . $custom_field->id, true)
                    );
                    $this->db->insert('custom_fields_product', $data);
                }
            }
        }
    }

    //update custom fields
    public function update_product_custom_fields($product_id)
    {
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $custom_fields = $this->field_model->generate_custom_fields_array($product->category_id, $product->subcategory_id, $product->third_category_id, null);
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_field) {
                    //check field
                    $field = $this->field_model->get_product_custom_field($custom_field->id, $product_id);
                    if (empty($field)) {
                        $data = array(
                            'field_id' => $custom_field->id,
                            'product_id' => $product_id,
                            'field_value' => $this->input->post('field_' . $custom_field->id, true)
                        );
                        $this->db->insert('custom_fields_product', $data);
                    } else {
                        $data = array(
                            'field_value' => $this->input->post('field_' . $custom_field->id, true)
                        );
                        $this->db->where('field_id', $custom_field->id);
                        $this->db->where('product_id', $product_id);
                        $this->db->update('custom_fields_product', $data);
                    }
                }
            }
        }
    }

    //update product
    public function update_product($id)
    {
        $data = array(
            'title' => $this->input->post('title', true),
            'category_id' => $this->input->post('category_id', true),
            'subcategory_id' => $this->input->post('subcategory_id', true),
            'third_category_id' => $this->input->post('third_category_id', true),
            'price' => $this->input->post('price', true),
            'currency' => $this->input->post('currency', true),
            'description' => $this->input->post('description', false),
            'product_condition' => $this->input->post('product_condition', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'address' => $this->input->post('address', true),
            'zip_code' => $this->input->post('zip_code', true),
            'visibility' => 1,
            'external_link' => trim($this->input->post('external_link', true)),
            'quantity' => $this->input->post('quantity', true),
            'shipping_time' => $this->input->post('shipping_time', true),
            'shipping_cost_type' => $this->input->post('shipping_cost_type', true),
            'shipping_cost' => $this->input->post('shipping_cost', true)
        );

        $data["slug"] = str_slug($data["title"]);
        $data["price"] = price_database_format($data["price"]);

        if (empty($data["subcategory_id"])) {
            $data["subcategory_id"] = 0;
        }
        if (empty($data["third_category_id"])) {
            $data["third_category_id"] = 0;
        }
        if (empty($data["country_id"])) {
            $data["country_id"] = 0;
        }
        if (empty($data["state_id"])) {
            $data["state_id"] = 0;
        }
        if (empty($data["address"])) {
            $data["address"] = "";
        }
        if (empty($data["zip_code"])) {
            $data["zip_code"] = "";
        }
        if (empty($data["external_link"])) {
            $data["external_link"] = "";
        }
        if ($data["shipping_cost_type"] == "shipping_buyer_pays") {
            $data["shipping_cost"] = price_database_format($data["shipping_cost"]);
        } else {
            $data["shipping_cost"] = 0;
        }
        $is_sold = $this->input->post('status_sold', true);
        if ($is_sold == "active") {
            $data["is_sold"] = 0;
        } elseif ($is_sold == "sold") {
            $data["is_sold"] = 1;
        }
        if (is_admin()) {
            $data["visibility"] = $this->input->post('visibility', true);
        }

        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    //update slug
    public function update_slug($id)
    {
        $product = $this->get_product_by_id($id);

        if (empty($product->slug) || $product->slug == "-") {
            $data = array(
                'slug' => $product->id,
            );
        } else {
            if ($this->general_settings->product_link_structure == "id-slug") {
                $data = array(
                    'slug' => $product->id . "-" . $product->slug,
                );
            } else {
                $data = array(
                    'slug' => $product->slug . "-" . $product->id,
                );
            }
        }

        if (!empty($this->page_model->check_page_slug_for_product($data["slug"]))) {
            $data["slug"] .= uniqid();
        }

        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    //build query
    public function build_query()
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.is_deleted', 0);

        //default location
        if ($this->default_location_id != 0) {
            $this->db->where('products.country_id', $this->default_location_id);
        }
    }

    //build query unlocated
    public function build_query_unlocated()
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.is_deleted', 0);
    }

    //filter products
    public function filter_products($category_id, $subcategory_id, $third_category_id)
    {
        $country = $this->input->get("country", true);
        $state = $this->input->get("state", true);
        $condition = $this->input->get("condition", true);
        $p_min = $this->input->get("p_min", true);
        $p_max = $this->input->get("p_max", true);
        $sort = $this->input->get("sort", true);
        $search = trim($this->input->get('search', true));

        if (!empty($category_id)) {
            $this->db->where('products.category_id', $category_id);
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        if (!empty($subcategory_id)) {
            $this->db->where('products.subcategory_id', $subcategory_id);
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        if (!empty($third_category_id)) {
            $this->db->where('products.third_category_id', $third_category_id);
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        if (!empty($country)) {
            $this->db->where('products.country_id', $country);
        }
        if (!empty($state)) {
            $this->db->where('products.state_id', $state);
        }
        if (!empty($condition) && ($condition == "new_with_tags" || $condition == "new" || $condition == "very_good" || $condition == "good" || $condition == "satisfactory" || $condition == "used")) {
            $this->db->where('products.product_condition', $condition);
        }
        if ($p_min != "") {
            $this->db->where('products.price >=', intval($p_min * 100));
        }
        if ($p_max != "") {
            $this->db->where('products.price <=', intval($p_max * 100));
        }
        if ($search != "") {
            $this->db->group_start();
            $this->db->like('products.title', $search);
            $this->db->or_like('products.description', $search);
            $this->db->group_end();
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        //sort products
        if (!empty($sort) && $sort == "lowest_price") {
            $this->db->order_by('products.price');
        } elseif (!empty($sort) && $sort == "highest_price") {
            $this->db->order_by('products.price', 'DESC');
        } else {
            $this->db->order_by('products.created_at', 'DESC');
        }
    }

    //get products
    public function get_products()
    {
        $this->build_query();
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get limited products
    public function get_products_limited($limit)
    {
        $this->build_query();
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('products');
        return $query->result();
    }

    //get promoted products
    public function get_promoted_products()
    {
        $key = "promoted_products";
        if ($this->default_location_id != 0) {
            $key = "promoted_products_location_" . $this->default_location_id;
        }
        $promoted_products = get_cached_data($key);
        if (empty($promoted_products)) {
            $this->build_query();
            $this->db->where('products.is_promoted', 1);
            $this->db->order_by('products.created_at', 'DESC');
            $query = $this->db->get('products');
            $promoted_products = $query->result();
            set_cache_data($key, $promoted_products);
        }
        return $promoted_products;
    }

    //get promoted products limited
    public function get_promoted_products_limited($limit)
    {
        $this->build_query();
        $this->db->where('products.is_promoted', 1);
        $this->db->limit($limit);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get promoted products count
    public function get_promoted_products_count()
    {
        $products = $this->get_promoted_products();
        if (!empty($products)) {
            return count($products);
        }
        return 0;
    }

    //check promoted products
    public function check_promoted_products()
    {
        $products = $this->get_promoted_products();
        if (!empty($products)) {
            foreach ($products as $item) {
                if (date_difference($item->promote_end_date, date('Y-m-d H:i:s')) < 1) {
                    $data = array(
                        'is_promoted' => 0,
                    );
                    $this->db->where('id', $item->id);
                    $this->db->update('products', $data);
                }
            }
        }
    }

    //get paginated filtered products
    public function get_paginated_filtered_products($category_id, $subcategory_id, $third_category_id, $per_page, $offset)
    {
        $this->build_query();
        $this->filter_products($category_id, $subcategory_id, $third_category_id);
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');
        return $query->result();
    }

    //get paginated filtered products count
    public function get_paginated_filtered_products_count($category_id, $subcategory_id, $third_category_id)
    {
        $this->build_query();
        $this->filter_products($category_id, $subcategory_id, $third_category_id);
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    //get products count by category
    public function get_products_count_by_category($category_id)
    {
        $this->build_query();
        $this->db->where('products.category_id', $category_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    //get products count by subcategory
    public function get_products_count_by_subcategory($category_id)
    {
        $this->build_query();
        $this->db->where('products.subcategory_id', $category_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    //get products count by third category
    public function get_products_count_by_third_category($category_id)
    {
        $this->build_query();
        $this->db->where('products.third_category_id', $category_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    //get related products
    public function get_related_products($product)
    {
        $this->build_query();
        if ($product->third_category_id != 0) {
            $this->db->where('products.third_category_id', $product->third_category_id);
        } elseif ($product->subcategory_id != 0) {
            $this->db->where('products.subcategory_id', $product->subcategory_id);
        } else {
            $this->db->where('products.category_id', $product->category_id);
        }
        $this->db->where('products.id !=', $product->id);
        $this->db->limit(4);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get user products
    public function get_user_products($user_slug, $limit, $product_id)
    {
        $this->build_query_unlocated();
        $this->db->where('users.slug', $user_slug);
        $this->db->where('products.id !=', $product_id);
        $this->db->limit($limit);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get paginated user products
    public function get_paginated_user_products($user_slug, $per_page, $offset)
    {
        $this->build_query_unlocated();
        $this->db->where('users.slug', $user_slug);
        $this->db->limit($per_page, $offset);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get user products count
    public function get_user_products_count($user_slug)
    {
        $user = $this->auth_model->get_user_by_slug($user_slug);
        if (empty($user)) {
            return 0;
        }
        $key = 'user_products_count_user' . $user->id . 'cache';
        $num_rows = get_cached_data($key);
        if (empty($num_rows)) {
            $this->build_query_unlocated();
            $this->db->where('users.slug', $user_slug);
            $this->db->order_by('products.created_at', 'DESC');
            $query = $this->db->get('products');
            $num_rows = $query->num_rows();
            set_cache_data($key, $num_rows);
        }
        return $num_rows;
    }

    //get paginated user pending products
    public function get_paginated_user_pending_products($user_id, $per_page, $offset)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('products.status', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');
        return $query->result();
    }

    //get user pending products count
    public function get_user_pending_products_count($user_id)
    {
        $key = 'user_pending_products_count_user' . $user_id . 'cache';
        $num_rows = get_cached_data($key);
        if (empty($num_rows)) {
            $this->db->join('users', 'products.user_id = users.id');
            $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
            $this->db->where('products.status', 0);
            $this->db->where('users.id', $user_id);
            $this->db->where('products.is_deleted', 0);
            $query = $this->db->get('products');
            $num_rows = $query->num_rows();
            set_cache_data($key, $num_rows);
        }
        return $num_rows;
    }

    //get user hidden products count
    public function get_user_hidden_products_count($user_id)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('products.visibility', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    //get paginated user hidden products
    public function get_paginated_user_hidden_products($user_id, $per_page, $offset)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('products.visibility', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');
        return $query->result();
    }

    //get user favorited products
    public function get_user_favorited_products($user_id)
    {
        $this->build_query_unlocated();
        $this->db->join('favorites', 'products.id = favorites.product_id');
        $this->db->select('products.*');
        $this->db->where('favorites.user_id', $user_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get user favorited products count
    public function get_user_favorited_products_count($user_id)
    {
        $this->build_query_unlocated();
        $this->db->join('favorites', 'products.id = favorites.product_id');
        $this->db->select('products.*');
        $this->db->where('favorites.user_id', $user_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    //get product by id
    public function get_product_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('products');
        return $query->row();
    }

    //get cart product
    public function get_cart_product($id)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('products.id', $id);
        $query = $this->db->get('products');
        return $query->row();
    }

    //get product by slug
    public function get_product_by_slug($slug)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('products.slug', $slug);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->row();
    }

    //is product favorited
    public function is_product_in_favorites($user_id, $product_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('favorites');
        if (!empty($query->row())) {
            return true;
        }
        return false;
    }

    //get product favorited count
    public function get_product_favorited_count($product_id)
    {
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('favorites');
        return $query->num_rows();
    }

    //add remove favorites
    public function add_remove_favorites($user_id, $product_id)
    {
        if ($this->is_product_in_favorites($user_id, $product_id)) {
            $this->db->where('user_id', $user_id);
            $this->db->where('product_id', $product_id);
            $this->db->delete('favorites');
        } else {
            $data = array(
                'user_id' => $user_id,
                'product_id' => $product_id
            );
            $this->db->insert('favorites', $data);
        }
    }

    //increase product hit
    public function increase_product_hit($product)
    {
        if (!empty($product)):
            if (!isset($_COOKIE['modesy_product_' . $product->id])) :
                //increase hit
                setcookie("modesy_product_" . $product->id, '1', time() + (86400 * 300), "/");
                $data = array(
                    'hit' => $product->hit + 1
                );

                $this->db->where('id', $product->id);
                $this->db->update('products', $data);

            endif;
        endif;
    }

    //get rss products by category
    public function get_rss_products_by_category($category_id)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.category_id', $category_id);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //get rss products by user
    public function get_rss_products_by_user($user_id)
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('users.id', $user_id);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result();
    }

    //set product as sold
    public function set_product_as_sold($product_id)
    {
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            if (user()->id == $product->user_id) {
                if ($product->is_sold == 1) {
                    $data = array(
                        'is_sold' => 0
                    );
                } else {
                    $data = array(
                        'is_sold' => 1
                    );
                }
                $this->db->where('id', $product_id);
                return $this->db->update('products', $data);
            }
        }
        return false;
    }


    //delete product
    public function delete_product($product_id)
    {
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $data = array(
                'is_deleted' => 1
            );
            $this->db->where('id', $product_id);
            return $this->db->update('products', $data);
        }
        return false;
    }

}
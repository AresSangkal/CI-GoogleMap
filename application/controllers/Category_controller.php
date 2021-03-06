<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check user
        if (!is_admin()) {
            redirect(base_url());
        }
    }


    /**
     * Categories
     */
    public function categories()
    {
        $data['title'] = trans("categories");
        $data['categories'] = $this->category_model->get_categories_all();
        $data['lang_settings'] = get_language_settings();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/categories', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Add Category
     */
    public function add_category()
    {
        $data['title'] = trans("add_category");
        $data['categories'] = $this->category_model->get_parent_categories();
        $data['lang_settings'] = get_language_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/add_category', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Add Category Post
     */
    public function add_category_post()
    {
        if ($this->category_model->add_category()) {
            //last id
            $last_id = $this->db->insert_id();
            //add category info
            $this->category_model->add_category_name($last_id);
            //update slug
            $this->category_model->update_slug($last_id);

            $this->session->set_flashdata('success_form', trans("msg_category_added"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('form_data', $this->category_model->input_values());
            $this->session->set_flashdata('error_form', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Update Category
     */
    public function update_category($id)
    {
        $data['title'] = trans("update_category");

        //get category
        $data['category'] = $this->category_model->get_category($id);
        $data['lang_settings'] = get_language_settings();
        if (empty($data['category'])) {
            redirect($this->agent->referrer());
        }
        $data['categories'] = $this->category_model->get_parent_categories();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/update_category', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Update Category Post
     */
    public function update_category_post()
    {
        //category id
        $id = $this->input->post('id', true);
        if ($this->category_model->update_category($id)) {
            //update category info
            $this->category_model->update_category_name($id);
            //update slug
            $this->category_model->update_slug($id);

            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect(admin_url() . 'categories');
        } else {
            $this->session->set_flashdata('form_data', $this->category_model->input_values());
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Delete Category Post
     */
    public function delete_category_post()
    {
        $id = $this->input->post('id', true);

        //check subcategories
        if (!empty($this->category_model->get_subcategories_by_parent_id($id))) {
            $this->session->set_flashdata('error', trans("msg_delete_subcategories"));
        } else {
            if ($this->category_model->delete_category($id)) {
                $this->session->set_flashdata('success', trans("msg_category_deleted"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
    }

    //get categories by language
    public function get_categories_by_lang()
    {
        $lang_id = $this->input->post('lang_id', true);
        if (!empty($lang_id)):
            $categories = $this->category_model->get_categories_by_lang($lang_id);
            foreach ($categories as $item) {
                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        endif;
    }

    //get subcategories
    public function get_subcategories()
    {
        $parent_id = $this->input->post('parent_id', true);
        $subcategories = $this->category_model->get_subcategories_by_parent_id($parent_id);
        foreach ($subcategories as $item) {
            echo '<option value="' . $item->id . '">' . $item->name . '</option>';
        }
    }


    /*
    *-------------------------------------------------------------------------------------------------
    * CUSTOM FIELDS
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Add Custom Field
     */
    public function add_custom_field()
    {
        $data['title'] = trans("add_custom_field");
        $data['categories'] = $this->category_model->get_parent_categories();
        $data['lang_settings'] = get_language_settings();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/add_custom_field', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Add Custom Field Post
     */
    public function add_custom_field_post()
    {
        if ($this->field_model->add_field()) {
            //last id
            $last_id = $this->db->insert_id();
            //add field name
            $this->field_model->add_field_name($last_id);
            //add field categories
            $this->field_model->add_field_categories($last_id);
            //add field options
            $this->field_model->add_field_options($last_id);
            $this->session->set_flashdata('success', trans("msg_custom_field_added"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('form_data', $this->field_model->input_values());
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Update Custom Field
     */
    public function update_custom_field($id)
    {
        $data['title'] = trans("update_custom_field");
        //get field
        $data['field'] = $this->field_model->get_field($id);
        $data['lang_settings'] = get_language_settings();

        if (empty($data['field'])) {
            redirect($this->agent->referrer());
        }
        $data['categories'] = $this->category_model->get_parent_categories();
        $data['field_categories'] = $this->field_model->get_field_categories($data['field']->id);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/update_custom_field', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Update Custom Field Post
     */
    public function update_custom_field_post()
    {
        //field id
        $id = $this->input->post('id', true);
        if ($this->field_model->update_field($id)) {
            //update field name
            $this->field_model->update_field_name($id);
            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect(admin_url() . 'custom-fields');
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Custom Fields
     */
    public function custom_fields()
    {
        $data['title'] = trans("custom_fields");
        $data['fields'] = $this->field_model->get_fields();
        $data['lang_settings'] = get_language_settings();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/custom_fields', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Custom Field Post
     */
    public function delete_custom_field_post()
    {
        $id = $this->input->post('id', true);
        if ($this->field_model->delete_field($id)) {
            $this->session->set_flashdata('success', trans("msg_custom_field_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }
}

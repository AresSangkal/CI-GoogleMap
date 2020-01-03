<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Field_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'field_type' => $this->input->post('field_type', true),
            'row_width' => $this->input->post('row_width', true),
            'is_required' => $this->input->post('is_required', true),
            'status' => $this->input->post('status', true),
            'field_order' => $this->input->post('field_order', true)
        );
        return $data;
    }

    //add field
    public function add_field()
    {
        $data = $this->input_values();
        if (empty($data["is_required"])) {
            $data["is_required"] = 0;
        }

        return $this->db->insert('custom_fields', $data);
    }

    //update field
    public function update_field($id)
    {
        $data = $this->input_values();
        $this->db->where('id', $id);
        return $this->db->update('custom_fields', $data);
    }

    //add field categories
    public function add_field_categories($field_id)
    {
        $categories_ids = $this->get_sess_custom_fields_category_array();
        if (!empty($categories_ids)) {
            foreach ($categories_ids as $category_id) {
                $data = array(
                    'category_id' => $category_id,
                    'field_id' => $field_id
                );
                $this->db->insert('custom_fields_category', $data);
            }
        }
        $this->unset_sess_custom_fields_category_array();
    }

    //add field options
    public function add_field_options($field_id)
    {
        $options = $this->get_sess_custom_field_options_array();
        if (!empty($options)) {
            foreach ($options as $option) {
                $data = array(
                    "lang_id" => $option->lang_id,
                    "field_id" => $field_id,
                    "field_option" => $option->field_option,
                    "common_id" => $option->common_id
                );
                $this->db->insert('custom_fields_options', $data);
            }
        }
        $this->unset_sess_custom_field_options_array();
    }

    //add field option
    public function add_field_option($field_id)
    {
        $common_id = uniqid();
        foreach ($this->languages as $language) {
            $option = $this->input->post('option_lang_' . $language->id, true);
            if (!empty($option)) {
                $data = array(
                    "lang_id" => $language->id,
                    "field_id" => $field_id,
                    "field_option" => trim($option),
                    "common_id" => $common_id
                );
                $this->db->insert('custom_fields_options', $data);
            }
        }
    }

    //add field name
    public function add_field_name($field_id)
    {
        $data = array();
        foreach ($this->languages as $language) {
            $data["field_id"] = $field_id;
            $data["lang_id"] = $language->id;
            $data["name"] = $this->input->post('name_lang_' . $language->id, true);
            $this->db->insert('custom_fields_lang', $data);
        }
    }

    //update field name
    public function update_field_name($field_id)
    {
        $data = array();
        foreach ($this->languages as $language) {
            $data["field_id"] = $field_id;
            $data["lang_id"] = $language->id;
            $data["name"] = $this->input->post('name_lang_' . $language->id, true);
            //check field name exists
            $this->db->where('field_id', $field_id);
            $this->db->where('lang_id', $language->id);
            $row = $this->db->get('custom_fields_lang')->row();
            if (empty($row)) {
                $this->db->insert('custom_fields_lang', $data);
            } else {
                $this->db->where('field_id', $field_id);
                $this->db->where('lang_id', $language->id);
                $this->db->update('custom_fields_lang', $data);
            }
        }
    }

    //get field
    public function get_field($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('custom_fields');
        return $query->row();
    }

    //get field joined
    public function get_field_joined($id)
    {
        $this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
        $this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name');
        $this->db->where('custom_fields.id', $id);
        $query = $this->db->get('custom_fields');
        return $query->row();
    }

    //get fields
    public function get_fields()
    {
        $this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
        $this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name');
        $this->db->where('custom_fields_lang.lang_id', $this->selected_lang->id);
        $this->db->order_by('field_order');
        $query = $this->db->get('custom_fields');
        return $query->result();
    }

    //get category fields
    public function get_category_fields($category_id)
    {
        $this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
        $this->db->join('custom_fields_category', 'custom_fields_category.field_id = custom_fields.id');
        $this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name, custom_fields_category.category_id as category_id');
        $this->db->where('custom_fields_lang.lang_id', $this->selected_lang->id);
        $this->db->where('custom_fields.status', 1);
        $this->db->where('custom_fields_category.category_id', $category_id);
        $this->db->order_by('custom_fields.field_order');
        $query = $this->db->get('custom_fields');
        return $query->result();
    }

    //get category fields by lang
    public function get_custom_fields_by_lang($category_id, $lang_id)
    {
        $this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
        $this->db->join('custom_fields_category', 'custom_fields_category.field_id = custom_fields.id');
        $this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name, custom_fields_category.category_id as category_id');
        $this->db->where('custom_fields_lang.lang_id', $lang_id);
        $this->db->where('custom_fields.status', 1);
        $this->db->where('custom_fields_category.category_id', $category_id);
        $this->db->order_by('custom_fields.field_order');
        $query = $this->db->get('custom_fields');
        return $query->result();
    }

    //generate custom fields array
    public function generate_custom_fields_array($category_id, $subcategory_id, $third_category_id, $product_id)
    {
        $array = array();
        $custom_fields = $this->field_model->get_category_fields($category_id);
        foreach ($custom_fields as $custom_field) {
            $data = new stdClass();
            $data->id = $custom_field->id;
            $data->field_type = $custom_field->field_type;
            $data->name = $custom_field->name;
            $data->is_required = $custom_field->is_required;
            $data->category_id = $custom_field->category_id;
            $data->row_width = $custom_field->row_width;
            if ($product_id == null) {
                $data->field_value = $this->input->post('field_' . $custom_field->id, true);
            } else {
                $field_row = $this->get_product_custom_field($custom_field->id, $product_id);
                if (!empty($field_row)) {
                    $data->field_value = $field_row->field_value;
                } else {
                    $data->field_value = "";
                }
            }
            array_push($array, $data);
        }

        $custom_fields = $this->field_model->get_category_fields($subcategory_id);
        foreach ($custom_fields as $custom_field) {
            $data = new stdClass();
            $data->id = $custom_field->id;
            $data->field_type = $custom_field->field_type;
            $data->name = $custom_field->name;
            $data->is_required = $custom_field->is_required;
            $data->category_id = $custom_field->category_id;
            $data->row_width = $custom_field->row_width;
            if ($product_id == null) {
                $data->field_value = $this->input->post('field_' . $custom_field->id, true);
            } else {
                $field_row = $this->get_product_custom_field($custom_field->id, $product_id);
                if (!empty($field_row)) {
                    $data->field_value = $field_row->field_value;
                } else {
                    $data->field_value = "";
                }
            }
            array_push($array, $data);
        }

        $custom_fields = $this->field_model->get_category_fields($third_category_id);
        foreach ($custom_fields as $custom_field) {
            $data = new stdClass();
            $data->id = $custom_field->id;
            $data->field_type = $custom_field->field_type;
            $data->name = $custom_field->name;
            $data->is_required = $custom_field->is_required;
            $data->category_id = $custom_field->category_id;
            $data->row_width = $custom_field->row_width;
            if ($product_id == null) {
                $data->field_value = $this->input->post('field_' . $custom_field->id, true);
            } else {
                $field_row = $this->get_product_custom_field($custom_field->id, $product_id);
                if (!empty($field_row)) {
                    $data->field_value = $field_row->field_value;
                } else {
                    $data->field_value = "";
                }
            }
            array_push($array, $data);
        }
        return $array;
    }

    //get field categories
    public function get_field_categories($field_id)
    {
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_category');
        return $query->result();
    }

    //get field name by lang
    public function get_field_name_by_lang($field_id, $lang_id)
    {
        $this->db->where('custom_fields_lang.field_id', $field_id);
        $this->db->where('custom_fields_lang.lang_id', $lang_id);
        $query = $this->db->get('custom_fields_lang');
        $row = $query->row();
        if (!empty($row)) {
            return $row->name;
        } else {
            return "";
        }
    }

    //get field options
    public function get_field_options($field_id)
    {
        $this->db->where('lang_id', $this->selected_lang->id);
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_options');
        return $query->result();
    }

    //get field options by lang
    public function get_custom_field_options_by_lang($field_id, $lang_id)
    {
        $this->db->where('lang_id', $lang_id);
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_options');
        return $query->result();
    }

    //get field all options
    public function get_field_all_options($field_id)
    {
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_options');
        return $query->result();
    }

    //get field option
    public function get_field_option($option_id)
    {
        $this->db->where('id', $option_id);
        $query = $this->db->get('custom_fields_options');
        return $query->row();
    }

    //get field option by common id
    public function get_field_option_by_common_id($common_id)
    {
        $this->db->where('common_id', $common_id);
        $query = $this->db->get('custom_fields_options');
        return $query->result();
    }

    //add category to field
    public function add_category_to_field($field_id, $category_id)
    {
        $row = $this->get_category_field($field_id, $category_id);
        if (empty($row)) {
            $data = array(
                'field_id' => $field_id,
                'category_id' => $category_id
            );
            return $this->db->insert('custom_fields_category', $data);
        }
        return false;
    }

    //get category field
    public function get_category_field($field_id, $category_id)
    {
        $this->db->where('field_id', $field_id);
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('custom_fields_category');
        return $query->row();
    }

    //get product custom field
    public function get_product_custom_field($field_id, $product_id)
    {
        $this->db->where('field_id', $field_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('custom_fields_product');
        return $query->row();
    }

    //delete category from field
    public function delete_category_from_field($field_id, $category_id)
    {
        $this->db->where('field_id', $field_id);
        $this->db->where('category_id', $category_id);
        return $this->db->delete('custom_fields_category');
    }

    //delete custom field option
    public function delete_custom_field_option($common_id)
    {
        $option = $this->get_field_option_by_common_id($common_id);
        if (!empty($option)) {
            foreach ($option as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('custom_fields_options');
            }
        }
    }

    //clear field categories
    public function clear_field_categories($field_id)
    {
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_category');
        $fields = $query->result();
        if (!empty($fields)) {
            foreach ($fields as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('custom_fields_category');
            }
        }
    }

    //delete field options
    public function delete_field_options($field_id)
    {
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_options');
        $fields = $query->result();
        if (!empty($fields)) {
            foreach ($fields as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('custom_fields_options');
            }
        }
    }

    //delete field product values
    public function delete_field_product_values($field_id)
    {
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_product');
        $fields = $query->result();
        if (!empty($fields)) {
            foreach ($fields as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('custom_fields_product');
            }
        }
    }

    //delete field name
    public function delete_field_name($field_id)
    {
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('custom_fields_lang');
        $results = $query->result();
        if (!empty($results)) {
            foreach ($results as $item) {
                $this->db->where('id', $item->id);
                $this->db->delete('custom_fields_lang');
            }
        }
    }

    //delete field
    public function delete_field($id)
    {
        $field = $this->get_field($id);
        if (!empty($field)) {

            //delete field name
            $this->delete_field_name($id);
            //delete fields category
            $this->clear_field_categories($id);
            //delete options
            $this->delete_field_options($id);
            //delete product values
            $this->delete_field_product_values($id);

            $this->db->where('id', $id);
            return $this->db->delete('custom_fields');
        }
        return false;
    }

    //set sess custom fields category array
    public function set_sess_custom_fields_category_array($array)
    {
        $this->session->set_userdata('custom_fields_category_array', $array);
    }

    //get sess custom fields category array
    public function get_sess_custom_fields_category_array()
    {
        $array = array();
        if (!empty($this->session->userdata('custom_fields_category_array'))) {
            $array = $this->session->userdata('custom_fields_category_array');
        }
        return $array;
    }

    //unset sess custom fields category array
    public function unset_sess_custom_fields_category_array()
    {
        if (!empty($this->session->userdata('custom_fields_category_array'))) {
            $this->session->unset_userdata('custom_fields_category_array');
        }
    }

    //set sess custom fields options array
    public function set_sess_custom_field_options_array($array)
    {
        $this->session->set_userdata('custom_field_options_array', $array);
    }

    //get sess custom fields options array
    public function get_sess_custom_field_options_array()
    {
        $array = array();
        if (!empty($this->session->userdata('custom_field_options_array'))) {
            $array = $this->session->userdata('custom_field_options_array');
        }
        return $array;
    }

    //unset sess custom fields options array
    public function unset_sess_custom_field_options_array()
    {
        if (!empty($this->session->userdata('custom_field_options_array'))) {
            $this->session->unset_userdata('custom_field_options_array');
        }
    }

}
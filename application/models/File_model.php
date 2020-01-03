<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model
{
    //upload image
    public function upload_image()
    {
        if (isset($_FILES['file']['size']) && $_FILES['file']['size'] > $this->img_uplaod_max_file_size) {
            exit();
        }

        $product_id = $this->input->post('product_id', true);
        $image_order = $this->input->post('image_order', true);

        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            $data = array(
                'product_id' => $product_id,
                'image_default' => $this->upload_model->product_default_image_upload($temp_path, "images"),
                'image_big' => $this->upload_model->product_big_image_upload($temp_path, "images"),
                'image_small' => $this->upload_model->product_small_image_upload($temp_path, "images"),
                'image_order' => $image_order,
                'storage' => "local"
            );
            $this->upload_model->delete_temp_image($temp_path);

            //move to s3
            if ($this->storage_settings->storage == "aws_s3") {
                $this->load->model("aws_model");
                $data["storage"] = "aws_s3";
                //move images
                if (!empty($data["image_default"])) {
                    $this->aws_model->put_product_object($data["image_default"], FCPATH . "uploads/images/" . $data["image_default"]);
                    delete_file_from_server("uploads/images/" . $data["image_default"]);
                }
                if (!empty($data["image_big"])) {
                    $this->aws_model->put_product_object($data["image_big"], FCPATH . "uploads/images/" . $data["image_big"]);
                    delete_file_from_server("uploads/images/" . $data["image_big"]);
                }
                if (!empty($data["image_small"])) {
                    $this->aws_model->put_product_object($data["image_small"], FCPATH . "uploads/images/" . $data["image_small"]);
                    delete_file_from_server("uploads/images/" . $data["image_small"]);
                }
            }
            $this->db->insert('images', $data);
        }
    }

    //upload image session
    public function upload_image_session()
    {
        if (isset($_FILES['file']['size']) && $_FILES['file']['size'] > $this->img_uplaod_max_file_size) {
            exit();
        }
        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            $image_order = $this->input->post('image_order', true);
            $modesy_images = $this->get_sess_product_images_array();
            if (isset($modesy_images[$image_order])) {
                unset($modesy_images[$image_order]);
            }
            $modesy_images[$image_order]["img_default"] = $this->upload_model->product_default_image_upload($temp_path, "temp");
            $modesy_images[$image_order]["img_big"] = $this->upload_model->product_big_image_upload($temp_path, "temp");
            $modesy_images[$image_order]["img_small"] = $this->upload_model->product_small_image_upload($temp_path, "temp");
            $this->set_sess_product_images_array($modesy_images);
            $this->upload_model->delete_temp_image($temp_path);
        }
    }

    //add product images
    public function add_product_images($product_id)
    {
        $modesy_images = $this->get_sess_product_images_array();
        if (!empty($modesy_images)) {
            for ($i = 1; $i < 6; $i++) {
                if (isset($modesy_images[$i])) {
                    $image_storage = "local";
                    if ($this->storage_settings->storage == "aws_s3") {
                        $image_storage = "aws_s3";
                        $this->load->model("aws_model");
                        //move default image
                        $this->aws_model->put_product_object($modesy_images[$i]["img_default"], FCPATH . "uploads/temp/" . $modesy_images[$i]["img_default"]);
                        delete_file_from_server("uploads/temp/" . $modesy_images[$i]["img_default"]);
                        //move big image
                        $this->aws_model->put_product_object($modesy_images[$i]["img_big"], FCPATH . "uploads/temp/" . $modesy_images[$i]["img_big"]);
                        delete_file_from_server("uploads/temp/" . $modesy_images[$i]["img_big"]);
                        //move small image
                        $this->aws_model->put_product_object($modesy_images[$i]["img_small"], FCPATH . "uploads/temp/" . $modesy_images[$i]["img_small"]);
                        delete_file_from_server("uploads/temp/" . $modesy_images[$i]["img_small"]);
                    } else {
                        //move default image
                        copy(FCPATH . "uploads/temp/" . $modesy_images[$i]["img_default"], FCPATH . "uploads/images/" . $modesy_images[$i]["img_default"]);
                        delete_file_from_server("uploads/temp/" . $modesy_images[$i]["img_default"]);
                        //move big image
                        copy(FCPATH . "uploads/temp/" . $modesy_images[$i]["img_big"], FCPATH . "uploads/images/" . $modesy_images[$i]["img_big"]);
                        delete_file_from_server("uploads/temp/" . $modesy_images[$i]["img_big"]);
                        //move small image
                        copy(FCPATH . "uploads/temp/" . $modesy_images[$i]["img_small"], FCPATH . "uploads/images/" . $modesy_images[$i]["img_small"]);
                        delete_file_from_server("uploads/temp/" . $modesy_images[$i]["img_small"]);
                    }

                    //add to database
                    $data = array(
                        'product_id' => $product_id,
                        'image_default' => $modesy_images[$i]["img_default"],
                        'image_big' => $modesy_images[$i]["img_big"],
                        'image_small' => $modesy_images[$i]["img_small"],
                        'image_order' => $i,
                        'storage' => $image_storage
                    );
                    $this->db->insert('images', $data);
                }
            }
        }

        $this->unset_sess_product_images_array();
    }

    //set product images array session
    public function set_sess_product_images_array($modesy_images)
    {
        $this->session->set_userdata('modesy_product_images', $modesy_images);
    }

    //get product images array session
    public function get_sess_product_images_array()
    {
        $modesy_images = array();
        if (!empty($this->session->userdata('modesy_product_images'))) {
            $modesy_images = $this->session->userdata('modesy_product_images');
        }
        return $modesy_images;
    }

    //unset product images array session
    public function unset_sess_product_images_array()
    {
        if (!empty($this->session->userdata('modesy_product_images'))) {
            $this->session->unset_userdata('modesy_product_images');
        }
    }

    //get product images
    public function get_product_images($product_id)
    {
        $key = "product_images_" . $product_id;
        $rows = get_cached_data($key);
        if (empty($rows)) {
            $this->db->where('product_id', $product_id);
            $this->db->order_by('images.image_order');
            $query = $this->db->get('images');
            $rows = $query->result();
            set_cache_data($key, $rows);
        }
        return $rows;
    }

    //get product image
    public function get_product_image($image_id)
    {
        $this->db->where('images.id', $image_id);
        $query = $this->db->get('images');
        return $query->row();
    }

    //get product image by image order
    public function get_product_image_by_image_order($product_id, $image_order)
    {
        $this->db->where('images.product_id', $product_id);
        $this->db->where('images.image_order', $image_order);
        $query = $this->db->get('images');
        return $query->row();
    }

    //get image by product
    public function get_image_by_product($product_id)
    {
        $key = "img_product_" . $product_id;
        $row = get_cached_data($key);
        if (empty($row)) {
            $this->db->where('product_id', $product_id);
            $this->db->where('image_order', 1);
            $query = $this->db->get('images');
            $row = $query->row();
            if (empty($row)) {
                $this->db->where('product_id', $product_id);
                $query = $this->db->get('images');
                $row = $query->row();
            }
            set_cache_data($key, $row);
        }
        return $row;
    }

    //get product images array
    public function get_product_images_array($product_id)
    {
        $images_array = array();
        for ($i = 1; $i < 6; $i++) {
            $image = $this->get_product_image_by_image_order($product_id, $i);
            $std = new stdClass();
            if (!empty($image)) {
                $std->id = $image->id;
                $std->product_id = $product_id;
                $std->image_default = $image->image_default;
                $std->image_big = $image->image_big;
                $std->image_small = $image->image_small;
                $std->image_order = $image->image_order;
                $std->storage = $image->storage;
                $images_array[$i] = $std;
            } else {
                $std->id = "";
                $std->product_id = "";
                $std->image_default = "";
                $std->image_big = "";
                $std->image_small = "";
                $std->image_order = "";
                $std->storage = "";
                $images_array[$i] = $std;
            }
        }
        return $images_array;
    }

    //delete image session
    public function delete_image_session($image_order)
    {
        $modesy_images = $this->get_sess_product_images_array();
        if (isset($modesy_images[$image_order])) {
            delete_file_from_server("uploads/temp/" . $modesy_images[$image_order]["img_default"]);
            delete_file_from_server("uploads/temp/" . $modesy_images[$image_order]["img_big"]);
            delete_file_from_server("uploads/temp/" . $modesy_images[$image_order]["img_small"]);
            unset($modesy_images[$image_order]);
        }
        $this->set_sess_product_images_array($modesy_images);
    }

    //delete product image
    public function delete_product_image($image_id)
    {
        $image = $this->get_product_image($image_id);
        if (!empty($image)) {
            if ($image->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_product_object($image->image_default);
                $this->aws_model->delete_product_object($image->image_big);
                $this->aws_model->delete_product_object($image->image_small);
            } else {
                delete_file_from_server("uploads/images/" . $image->image_default);
                delete_file_from_server("uploads/images/" . $image->image_big);
                delete_file_from_server("uploads/images/" . $image->image_small);
            }
            $this->db->where('id', $image->id);
            $this->db->delete('images');
        }
    }

    //delete product images
    public function delete_product_images($product_id)
    {
        $images = $this->get_product_images($product_id);
        if (!empty($images)) {
            foreach ($images as $image) {
                $this->delete_product_image($image->id);
            }
        }
    }

}
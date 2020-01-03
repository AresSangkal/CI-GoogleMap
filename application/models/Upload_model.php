<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . "third_party/image-resize/ImageResize.php";
include APPPATH . "third_party/image-resize/ImageResizeException.php";

use \Gumlet\ImageResize;
use \Gumlet\ImageResizeException;

class Upload_model extends CI_Model
{
    //upload temp image
    public function upload_temp_image($file_name)
    {
        if (isset($_FILES[$file_name])) {
            if (empty($_FILES[$file_name]['name'])) {
                return null;
            }
        }
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'img_temp_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = array('upload_data' => $this->upload->data());
            if (isset($data['upload_data']['full_path'])) {
                return $data['upload_data']['full_path'];
            }
            return null;
        } else {
            return null;
        }
    }

    //product default image upload
    public function product_default_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToHeight(500);
            $new_name = 'img_x500_' . uniqid() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_name;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //product big image upload
    public function product_big_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToWidth(1920);
            $new_name = 'img_1920x_' . uniqid() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_name;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //product small image upload
    public function product_small_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToHeight(300);
            $new_name = 'img_x300_' . uniqid() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_name;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //blog image default upload
    public function blog_image_default_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToWidth(880);
            $new_path = 'uploads/blog/img_' . uniqid() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_path;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //blog image default upload
    public function blog_image_small_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->crop(500, 332, true);
            $new_path = 'uploads/blog/img_thumb_' . uniqid() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_path;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //category image upload
    public function category_image_upload($path, $width, $height)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->crop($width, $height, true);
            $new_path = 'uploads/category/category_' . $width . '-' . $height . '_' . uniqid() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_path;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //slider image upload
    public function slider_image_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 95;
            $image->crop(1170, 356, true);
            $new_path = 'uploads/slider/slider_' . uniqid() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_path;
        } catch (ImageResizeException $e) {
            return null;
        }
    }

    //avatar image upload
    public function avatar_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->crop(240, 240, true);
            $new_path = 'uploads/profile/avatar_' . uniqid() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            return $new_path;
        } catch (ImageResizeException $e) {
            return null;
        }
    }


    //logo image upload
    public function logo_upload($file_name)
    {
        $config['upload_path'] = './uploads/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'logo_' . uniqid();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = array('upload_data' => $this->upload->data());
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/logo/' . $data['upload_data']['file_name'];
            }
        }
        return null;
    }

    //favicon image upload
    public function favicon_upload($file_name)
    {
        $config['upload_path'] = './uploads/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'favicon_' . uniqid();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = array('upload_data' => $this->upload->data());
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/logo/' . $data['upload_data']['file_name'];
            }
        }
        return null;
    }

    //ad upload
    public function ad_upload($file_name)
    {
        $config['upload_path'] = './uploads/blocks/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'block_' . uniqid();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = array('upload_data' => $this->upload->data());
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/blocks/' . $data['upload_data']['file_name'];
            }
        }
        return null;
    }

    //receipt upload
    public function receipt_upload($file_name)
    {
        $config['upload_path'] = './uploads/receipts/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'receipt_' . uniqid();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = array('upload_data' => $this->upload->data());
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/receipts/' . $data['upload_data']['file_name'];
            }
        }
        return null;
    }

    //delete temp image
    public function delete_temp_image($path)
    {
        if (file_exists($path)) {
            @unlink($path);
        }
    }
}
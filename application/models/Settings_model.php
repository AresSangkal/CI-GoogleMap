<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    //update settings
    public function update_settings()
    {
        $data = array(
            'facebook_url' => $this->input->post('facebook_url', true),
            'twitter_url' => $this->input->post('twitter_url', true),
            'instagram_url' => $this->input->post('instagram_url', true),
            'pinterest_url' => $this->input->post('pinterest_url', true),
            'linkedin_url' => $this->input->post('linkedin_url', true),
            'vk_url' => $this->input->post('vk_url', true),
            'youtube_url' => $this->input->post('youtube_url', true),
            'about_footer' => $this->input->post('about_footer', true),
            'contact_text' => $this->input->post('contact_text', false),
            'contact_address' => $this->input->post('contact_address', true),
            'contact_email' => $this->input->post('contact_email', true),
            'contact_phone' => $this->input->post('contact_phone', true),
            'copyright' => $this->input->post('copyright', true),
            'cookies_warning' => $this->input->post('cookies_warning', false),
            'cookies_warning_text' => $this->input->post('cookies_warning_text', false)
        );
        $lang_id = $this->input->post('lang_id', true);

        $this->db->where('lang_id', $lang_id);
        return $this->db->update('settings', $data);
    }

    //update general settings
    public function update_general_settings()
    {
        $data = array(
            'application_name' => $this->input->post('application_name', true),
            'head_code' => $this->input->post('head_code', false),
            'facebook_comment_status' => $this->input->post('facebook_comment_status', false),
            'facebook_comment' => $this->input->post('facebook_comment', false)
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update recaptcha settings
    public function update_recaptcha_settings()
    {
        $data = array(
            'recaptcha_site_key' => $this->input->post('recaptcha_site_key', true),
            'recaptcha_secret_key' => $this->input->post('recaptcha_secret_key', true),
            'recaptcha_lang' => $this->input->post('recaptcha_lang', true),
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);

    }

    //update email settings
    public function update_email_settings()
    {
        $data = array(
            'mail_library' => $this->input->post('mail_library', true),
            'mail_protocol' => $this->input->post('mail_protocol', true),
            'mail_title' => $this->input->post('mail_title', true),
            'mail_host' => $this->input->post('mail_host', true),
            'mail_port' => $this->input->post('mail_port', true),
            'mail_username' => $this->input->post('mail_username', true),
            'mail_password' => $this->input->post('mail_password', true),
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update email verification
    public function update_email_verification()
    {
        $data = array(
            'email_verification' => $this->input->post('email_verification', true),
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update email options
    public function update_email_options()
    {
        $data = array(
            'send_email_new_product' => $this->input->post('send_email_new_product', true),
            'send_email_buyer_purchase' => $this->input->post('send_email_buyer_purchase', true),
            'send_email_order_shipped' => $this->input->post('send_email_order_shipped', true),
            'send_email_contact_messages' => $this->input->post('send_email_contact_messages', true),
            'mail_options_account' => $this->input->post('mail_options_account', true)
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update facebook login
    public function update_facebook_login()
    {
        $data = array(
            'facebook_app_id' => $this->input->post('facebook_app_id', true)
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }


    //update twitter login
    public function update_twitter_login()
    {
        $data = array(
            'twitter_api_key' => $this->input->post('twitter_api_key', true),
            'twitter_secret_key' => $this->input->post('twitter_secret_key', true)
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update seo tools
    public function update_seo_tools()
    {
        $data_general = array(
            'google_analytics' => $this->input->post('google_analytics', false)
        );
        $this->db->where('id', 1);
        $this->db->update('general_settings', $data_general);

        $lang_id = $this->input->post('lang_id', true);
        $data = array(
            'site_title' => $this->input->post('site_title', true),
            'homepage_title' => $this->input->post('homepage_title', true),
            'site_description' => $this->input->post('site_description', true),
            'keywords' => $this->input->post('keywords', true)
        );
        $this->db->where('lang_id', $lang_id);
        return $this->db->update('settings', $data);
    }

    //update paypal settings
    public function update_paypal_settings()
    {
        $data = array(
            'paypal_enabled' => $this->input->post('paypal_enabled', true),
            'paypal_mode' => $this->input->post('paypal_mode', true),
            'paypal_client_id' => trim($this->input->post('paypal_client_id', true))
        );
        $this->db->where('id', 1);
        return $this->db->update('payment_settings', $data);
    }

    //update stripe settings
    public function update_stripe_settings()
    {
        $data = array(
            'stripe_enabled' => $this->input->post('stripe_enabled', true),
            'stripe_publishable_key' => trim($this->input->post('stripe_publishable_key', true))
        );

        $this->db->where('id', 1);
        return $this->db->update('payment_settings', $data);
    }

    //update iyzico settings
    public function update_iyzico_settings()
    {
        $data = array(
            'iyzico_enabled' => $this->input->post('iyzico_enabled', true),
            'iyzico_mode' => $this->input->post('iyzico_mode', true),
            'iyzico_api_key' => trim($this->input->post('iyzico_api_key', true)),
            'iyzico_secret_key' => trim($this->input->post('iyzico_secret_key', true))
        );

        $this->db->where('id', 1);
        return $this->db->update('payment_settings', $data);
    }

    //update bank transfer settings
    public function update_bank_transfer_settings()
    {
        $data = array(
            'bank_transfer_enabled' => $this->input->post('bank_transfer_enabled', true),
            'bank_transfer_accounts' => $this->input->post('bank_transfer_accounts', false)
        );

        $this->db->where('id', 1);
        return $this->db->update('payment_settings', $data);
    }

    //update pricing settings
    public function update_pricing_settings()
    {
        $data = array(
            'price_per_day' => $this->input->post('price_per_day', true),
            'price_per_month' => $this->input->post('price_per_month', true)
        );

        $data['price_per_day'] = price_database_format($data["price_per_day"]);
        $data['price_per_month'] = price_database_format($data["price_per_month"]);

        $this->db->where('id', 1);
        return $this->db->update('payment_settings', $data);
    }

    //update preferences
    public function update_preferences()
    {
        $data = array(
            'approve_before_publishing' => $this->input->post('approve_before_publishing', true),
            'promoted_products' => $this->input->post('promoted_products', true),
            'multilingual_system' => $this->input->post('multilingual_system', true),
            'rss_system' => $this->input->post('rss_system', true),
            'product_reviews' => $this->input->post('product_reviews', true),
            'user_reviews' => $this->input->post('user_reviews', true),
            'product_comments' => $this->input->post('product_comments', true),
            'blog_comments' => $this->input->post('blog_comments', true),
            'index_slider' => $this->input->post('index_slider', true),
            'index_categories' => $this->input->post('index_categories', true),
            'index_promoted_products' => $this->input->post('index_promoted_products', true),
            'index_latest_products' => $this->input->post('index_latest_products', true),
            'index_blog_slider' => $this->input->post('index_blog_slider', true),
            'product_link_structure' => $this->input->post('product_link_structure', true),
            'index_promoted_products_count' => $this->input->post('index_promoted_products_count', true),
            'index_latest_products_count' => $this->input->post('index_latest_products_count', true)
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update visual settings
    public function update_visual_settings()
    {
        $data = array(
            'site_color' => $this->input->post('site_color', true)
        );

        $this->load->model('upload_model');
        $file_path = $this->upload_model->logo_upload('logo');
        if (!empty($file_path)) {
            $data["logo"] = $file_path;
        }

        $file_path = $this->upload_model->logo_upload('logo_email');
        if (!empty($file_path)) {
            $data["logo_email"] = $file_path;
        }

        $file_path = $this->upload_model->favicon_upload('favicon');
        if (!empty($file_path)) {
            $data["favicon"] = $file_path;
        }

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update admin panel link
    public function update_admin_panel_link($link)
    {
        $link = str_slug($link);
        if (empty($link)) {
            $link = "admin";
        }
        $start = '<?php defined("BASEPATH") OR exit("No direct script access allowed");' . PHP_EOL;
        $keys = '$custom_slug_array["admin"] = "' . $link . '";';
        $end = '?>';

        $content = $start . $keys . $end;

        file_put_contents(FCPATH . "application/config/route_slugs.php", $content);
    }

    //update cache system
    public function update_cache_system()
    {
        $data = array(
            'cache_system' => $this->input->post('cache_system', true),
            'refresh_cache_database_changes' => $this->input->post('refresh_cache_database_changes', true),
            'cache_refresh_time' => $this->input->post('cache_refresh_time', true) * 60
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update storage settings
    public function update_storage_settings()
    {
        $data = array(
            'storage' => $this->input->post('storage', true)
        );

        $this->db->where('id', 1);
        return $this->db->update('storage_settings', $data);
    }

    //update system settings
    public function update_system_settings()
    {
        $data = array(
            'selected_system' => $this->input->post('selected_system', true),
            'commission_rate' => $this->input->post('commission_rate', true),
            'multi_vendor_system' => $this->input->post('multi_vendor_system', true),
            'timezone' => $this->input->post('timezone', true)
        );

        //update marketplace currency
        if ($data['selected_system'] == 'marketplace') {
            $data_payment = array(
                'default_product_currency' => $this->input->post('default_product_currency', true),
            );

            $this->db->where('id', 1);
            $this->db->update('payment_settings', $data_payment);
        }

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update aws s3
    public function update_aws_s3()
    {
        $data = array(
            'aws_key' => trim($this->input->post('aws_key', true)),
            'aws_secret' => trim($this->input->post('aws_secret', true)),
            'aws_bucket' => trim($this->input->post('aws_bucket', true)),
            'aws_region' => trim($this->input->post('aws_region', true))
        );

        $this->db->where('id', 1);
        return $this->db->update('storage_settings', $data);
    }

    //update navigation
    public function update_navigation()
    {
        $data = array(
            'selected_navigation' => $this->input->post('navigation', true),
        );

        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //get general settings
    public function get_general_settings()
    {
        $this->db->where('id', 1);
        $query = $this->db->get('general_settings');
        return $query->row();
    }

    //get system settings
    public function get_system_settings()
    {
        $this->db->where('id', 1);
        $query = $this->db->get('general_settings');
        return $query->row();
    }

    //get payment settings
    public function get_payment_settings()
    {
        $this->db->where('id', 1);
        $query = $this->db->get('payment_settings');
        return $query->row();
    }

    //get storage settings
    public function get_storage_settings()
    {
        $this->db->where('id', 1);
        $query = $this->db->get('storage_settings');
        return $query->row();
    }

    //get settings
    public function get_settings($lang_id)
    {
        $this->db->where('lang_id', $lang_id);
        $query = $this->db->get('settings');
        return $query->row();
    }

}
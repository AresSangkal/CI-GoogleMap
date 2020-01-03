<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_controller extends Home_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Login Post
     */
    public function login_post()
    {
        //check auth
        if (auth_check()) {
            $data = array(
                'result' => 1
            );
            echo json_encode($data);
            exit();
        }
        //validate inputs
        $this->form_validation->set_rules('email', trans("email_address"), 'required|xss_clean|max_length[100]');
        $this->form_validation->set_rules('password', trans("password"), 'required|xss_clean|max_length[30]');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            $this->load->view('partials/_messages');
        } else {
            if ($this->auth_model->login()) {
                $data = array(
                    'result' => 1
                );
                echo json_encode($data);
            } else {
                $data = array(
                    'result' => 0,
                    'error_message' => $this->load->view('partials/_messages', '', true)
                );
                echo json_encode($data);
            }
            reset_flash_data();
        }
    }

    /**
     * Login with Facebook
     */
    public function login_with_facebook()
    {
        //check auth
        if (auth_check()) {
            exit();
        }
        $this->auth_model->login_with_facebook();
    }

    /**
     * Login with Twitter
     */
    public function login_with_twitter()
    {
        //check auth
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $this->auth_model->login_with_twitter();
        redirect($this->agent->referrer());
    }


    /**
     * Register
     */
    public function register()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("register");
        $data['description'] = trans("register") . " - " . $this->app_name;
        $data['keywords'] = trans("register") . "," . $this->app_name;
        $data["site_settings"] = get_site_settings();

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/register');
        $this->load->view('partials/_footer');
    }


    /**
     * Register Post
     */
    public function register_post()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        if ($this->recaptcha_status == true) {
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans("msg_recaptcha"));
                redirect($this->agent->referrer());
                exit();
            }
        }

        //validate inputs
        $this->form_validation->set_rules('username', trans("username"), 'required|xss_clean|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('email', trans("email_address"), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('password', trans("password"), 'required|xss_clean|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('confirm_password', trans("password_confirm"), 'required|xss_clean|matches[password]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $email = $this->input->post('email', true);
            $username = $this->input->post('username', true);

            //is email unique
            if (!$this->auth_model->is_unique_email($email)) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans("msg_email_unique_error"));
                redirect($this->agent->referrer());
            }
            //is username unique
            if (!$this->auth_model->is_unique_username($username)) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans("msg_username_unique_error"));
                redirect($this->agent->referrer());
            }
            //register
            $user = $this->auth_model->register();
            if ($user) {
                //update slug
                $this->auth_model->update_slug($user->id);
                $this->auth_model->login_direct($user);
                if ($this->general_settings->email_verification == 1) {
                    $this->session->set_flashdata('success', trans("msg_send_confirmation_email"));
                } else {
                    $this->session->set_flashdata('success', trans("msg_register_success"));
                }
                redirect(lang_base_url() . "settings");
            } else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }
    }


    /**
     * Logout
     */
    public function logout()
    {
        $this->auth_model->logout();
        redirect($this->agent->referrer());
    }


    /**
     * Confirm Email
     */
    public function confirm_email()
    {
        $data['title'] = trans("confirm_your_email");
        $data['description'] = trans("confirm_your_email") . " - " . $this->app_name;
        $data['keywords'] = trans("confirm_your_email") . "," . $this->app_name;

        $token = trim($this->input->get("token", true));
        $data["user"] = $this->auth_model->get_user_by_token($token);

        if (empty($data["user"])) {
            redirect(lang_base_url());
        }
        if ($data["user"]->email_status == 1) {
            redirect(lang_base_url());
        }

        if ($this->auth_model->verify_email($data["user"])) {
            $data["success"] = trans("msg_confirmed");
        } else {
            $data["error"] = trans("msg_error");
        }
        $this->load->view('partials/_header', $data);
        $this->load->view('auth/confirm_email', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Forgot Password
     */
    public function forgot_password()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("reset_password");
        $data['description'] = trans("reset_password") . " - " . $this->app_name;
        $data['keywords'] = trans("reset_password") . "," . $this->app_name;
        $data["site_settings"] = get_site_settings();

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/forgot_password');
        $this->load->view('partials/_footer');
    }


    /**
     * Forgot Password Post
     */
    public function forgot_password_post()
    {
        //check auth
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $email = $this->input->post('email', true);
        //get user
        $user = $this->auth_model->get_user_by_email($email);

        //if user not exists
        if (empty($user)) {
            $this->session->set_flashdata('error', html_escape(trans("msg_reset_password_error")));
            redirect($this->agent->referrer());
        } else {
            $this->load->model("email_model");
            $this->email_model->send_email_reset_password($user->id);
            $this->session->set_flashdata('success', trans("msg_reset_password_success"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Reset Password
     */
    public function reset_password()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("reset_password");
        $data['description'] = trans("reset_password") . " - " . $this->app_name;
        $data['keywords'] = trans("reset_password") . "," . $this->app_name;

        $token = $this->input->get('token', true);
        //get user
        $data["user"] = $this->auth_model->get_user_by_token($token);
        $data["success"] = $this->session->flashdata('success');

        if (empty($data["user"]) && empty($data["success"])) {
            redirect(lang_base_url());
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/reset_password');
        $this->load->view('partials/_footer');
    }


    /**
     * Reset Password Post
     */
    public function reset_password_post()
    {
        $success = $this->input->post('success', true);
        if ($success == 1) {
            redirect(lang_base_url());
        }

        $this->form_validation->set_rules('password', trans("new_password"), 'required|xss_clean|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('password_confirm', trans("password_confirm"), 'required|xss_clean|matches[password]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->profile_model->change_password_input_values());
            redirect($this->agent->referrer());
        } else {
            $user_id = $this->input->post('id', true);
            if ($this->auth_model->reset_password($user_id)) {
                $this->session->set_flashdata('success', trans("msg_change_password_success"));
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', trans("msg_change_password_error"));
                redirect($this->agent->referrer());
            }
        }
    }


}

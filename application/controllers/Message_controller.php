<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check user
        if (!auth_check()) {
            redirect(lang_base_url());
        }

        $this->pagination_per_page = 10;
    }

    /**
     * Messages
     */
    public function messages()
    {
        $data['title'] = trans("messages");
        $data['description'] = trans("messages") . " - " . $this->app_name;
        $data['keywords'] = trans("messages") . "," . $this->app_name;
        $data["conversation_count"] = $this->message_model->get_conversations_count(user()->id);
        $data["curr_user_role"] = get_current_user_role();

        //get paginated messages
        $pagination = $this->paginate(lang_base_url() . 'messages', $data["conversation_count"], $this->pagination_per_page);
        $data['conversations'] = $this->message_model->get_paginated_conversations(user()->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('message/messages', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Message
     */
    public function message($conversation_id)
    {
        $data['title'] = trans("message");
        $data['description'] = trans("message") . " - " . $this->app_name;
        $data['keywords'] = trans("message") . "," . $this->app_name;
        $data["curr_user_role"] = get_current_user_role();
        $data['conversation'] = $this->message_model->get_conversation($conversation_id);
        //check message
        if (empty($data['conversation'])) {
            redirect(lang_base_url() . "messages");
        }
        //check message owner
        if (user()->id != $data['conversation']->sender_id && user()->id != $data['conversation']->receiver_id) {
            redirect(lang_base_url() . "messages");
        }
        $data['messages'] = $this->message_model->get_messages($conversation_id);

        $this->message_model->set_conversation_as_read($data['conversation']);

        $this->load->view('partials/_header', $data);
        $this->load->view('message/message', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Send Message
     */
    public function send_message()
    {
        $conversation_id = $this->input->post('conversation_id', true);
        if ($this->message_model->add_message($conversation_id)) {
            $this->message_model->update_conversation($conversation_id);

            //send email
            $receiver_id = $this->input->post('receiver_id', true);
            $message = $this->input->post('message', true);
            $user = get_user($receiver_id);
            if (!empty($user)) {
                if ($user->send_email_new_message == 1) {
                    //set email session
                    $this->session->set_userdata('mds_send_email_new_message', 1);
                    $this->session->set_userdata('mds_send_email_new_message_send_to',$receiver_id);
                    $this->session->set_userdata('mds_send_email_new_message_text', $message);
                }
            }
        }
        redirect($this->agent->referrer());
    }


    /**
     * Add Conversation
     */
    public function add_conversation()
    {
        if ($this->message_model->add_conversation()) {
            $last_id = $this->db->insert_id();
            if ($this->message_model->add_message($last_id)) {
                $this->session->set_flashdata('success', trans("msg_message_sent"));
                $this->load->view('partials/_messages');
                reset_flash_data();
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
                $this->load->view('partials/_messages');
                reset_flash_data();
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            $this->load->view('partials/_messages');
            reset_flash_data();
        }
    }


    /**
     * Delete Conversation
     */
    public function delete_conversation()
    {
        $conversation_id = $this->input->post('conversation_id', true);
        $this->message_model->delete_conversation($conversation_id);
    }

    /**
     * Delete Conversations
     */
    public function delete_selected_conversations()
    {
        $conversation_ids = $this->input->post('conversation_ids', true);
        $this->message_model->delete_multi_conversations($conversation_ids);
    }

}
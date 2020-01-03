<?php (!defined('BASEPATH')) and exit('No direct script access allowed');

/**
 * CodeIgniter Twitter login library
 *
 * @package CodeIgniter
 * @author  Codingest
 *
 */

require APPPATH . 'third_party/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{
    /**
     * ci instance object
     *
     */
    private $ci;

    /**
     * constructor
     *
     * @param string $config
     */
    public function __construct()
    {
        $this->ci = &get_instance();

        $this->api_key = $this->ci->general_settings->twitter_api_key;
        $this->api_secret_key = $this->ci->general_settings->twitter_secret_key;
        $this->callback_url = lang_base_url() . "login-with-twitter";
    }

    /**
     * Generate Twitter login url
     *
     * @return string response
     *
     */
    public function generate_login_url()
    {
        //generate request token
        $connection = new TwitterOAuth($this->api_key, $this->api_secret_key);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->callback_url));

        //set tokens
        $this->ci->session->set_userdata('mds_twitter_oauth_token', $request_token['oauth_token']);
        $this->ci->session->set_userdata('mds_twitter_oauth_token_secret', $request_token['oauth_token_secret']);

        //generate login url
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        return $url;
    }

    /**
     * Get user data
     *
     * @return object response
     *
     */
    public function get_user_data()
    {
        //get tokens from session
        $oauth_token = $this->ci->session->userdata('mds_twitter_oauth_token');
        $oauth_token_secret = $this->ci->session->userdata('mds_twitter_oauth_token_secret');

        //generate access tokens
        $connection = new TwitterOAuth($this->api_key, $this->api_secret_key, $oauth_token, $oauth_token_secret);
        $access_token = $connection->oauth("oauth/access_token", array('oauth_verifier' => $this->ci->input->get('oauth_verifier', true)));

        //get user data
        $connection = new TwitterOAuth($this->api_key, $this->api_secret_key, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user = $connection->get('account/verify_credentials', ['include_email' => 'true', 'include_entities' => 'true']);

        return $user;
    }

}

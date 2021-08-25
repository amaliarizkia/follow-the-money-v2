<?php 

namespace BD\Ajax;

defined ('ABSPATH') || die("Can't access directly");

use BD\Sequence\Sequence;

class ChooseSequence {

    private $_post_id;
    
    public function __construct() {
        add_action('wp_ajax_choose_sequence',[$this, 'ajax']);
        add_action('wp_ajax_nopriv_choose_sequence',[$this,'ajax']);
    }

    public function ajax() {
        $this->_sanitize();
        $this->_validate();
        $this->_response();
    }

    private function _sanitize()
    {
        $this->_post_id = isset($_POST[$this->_post_id]) ? sanitize_text_field($_POST[$this->_post_id]) : false;

    }

    private function _validate()
    {
        $post = get_post($this->_post_id);

        if(!$post) {
            wp_send_json_error(__('Post not found','followthemoney'));
        }
    }

    private function _response()
    {
        $next_sequence = Sequence::get_next_sequence($this->_post_id);

        wp_send_json_success($next_sequence);
    }





}
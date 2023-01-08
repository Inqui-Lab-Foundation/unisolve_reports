<?php defined('BASEPATH') OR exit('No direct script access allowed');


	function ChkDB(){
		$ci =& get_instance();
		$ci->load->library('session');
        // if ($this->session->userdata('admin_logs')){
		// $sess = $this->session->userdata('admin_logs');
		$instance = $ci->session->userdata('instance');

		return $instance;
	}



?>
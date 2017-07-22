<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barrier extends CI_Controller
{
  public function __construct()
  {
	parent::__construct();
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");
  	$this->load->library('session');
  	$this->load->helper('security');
  	$this->load->helper('form');
  	$this->load->helper('url');
  	$this->load->helper('html');
  	$this->load->database();
  	$this->load->model('Barrier_model');
  }

  public function landing_page()
  {
  	$markers = $this->Barrier_model->get_markers();
  	$data = array(
  		'markers' => $markers
  		);
  	$this->load->view('landing_page.php', $data);
  }

  public function add_marker()
  {
  	$barrier_id = $this->security->xss_clean($this->input->post("ajax_barrier_id"));
  	$barrier_key = $this->security->xss_clean($this->input->post("ajax_barrier_key"));
  	$barrier_longitude = $this->security->xss_clean($this->input->post("ajax_longitude"));
  	$barrier_latitude = $this->security->xss_clean($this->input->post("ajax_latitude"));
  	$barrier_salt = $this->rand_string(64);
  	$barrier_password = hash('sha256', $barrier_key.$barrier_salt);
  	$data = array(
       	'barrier_id' => $barrier_id,
       	'barrier_password' => $barrier_password,
       	'barrier_salt' => $barrier_salt,
       	'barrier_longitude' => $barrier_longitude,
       	'barrier_latitude' => $barrier_latitude,
  		'barrier_status' => 1
    		);
  	$this->Barrier_model->insert_marker($data);
  }

  public function update_marker()
  {
  	$barrier_id = $this->security->xss_clean($this->input->post("ajax_barrier_id"));
  	$this->Barrier_model->update_marker($barrier_id);
  }

  public function async_update_marker()
  {
  	echo json_encode($this->Barrier_model->get_markers_async());
  }

  public function delete_marker()
  {
  	$barrier_id = $this->security->xss_clean($this->input->post("ajax_barrier_id"));
  	$this->Barrier_model->delete_marker($barrier_id);
  }

  public function test_page()
  {
  	$markers = $this->Barrier_model->get_markers();
  	$marker = $this->Barrier_model->get_marker('barrier_01');
  	$data = array(
  		'markers' => $markers,
  		'marker' => $marker
  		);
  	$this->load->view('test_page.php', $data);
  }

  public function alert()
  {
  	$barrier_id = $this->security->xss_clean($this->input->post('barrier_id'));
  	$barrier_key = $this->security->xss_clean($this->input->post('barrier_key'));
  	$marker = $this->Barrier_model->get_marker($barrier_id);
  	if($marker['barrier_password'] == hash('sha256', $barrier_key.$marker['barrier_salt'])){
  		$this->Barrier_model->alert_marker($barrier_id);
  	}
  }

  public function rand_string($length)
  {
  	$str="";
  	$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  	$size = strlen($chars);
  	for($i = 0;$i < $length;$i++) {
  	  $str .= $chars[rand(0,$size-1)];
  	}
  	return $str;
  }
}

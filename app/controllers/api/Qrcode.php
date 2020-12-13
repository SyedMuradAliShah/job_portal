<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Qrcode extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
		$this->load->model('api/qrcode_model', 'qrcode_model');
	}

	public function get_qrdata($job_id)
	{
		if ($qr = $this->qrcode_model->get_qrdata($job_id)) {
			$this->qrcode_model->update_qr_status($qr->id);
			echo $this->api->success_response_std('Got CV', ['redirect' => base_url('candidate/' . $this->custom_encryption->encrypt_data($qr->job_seeker_id))]);
			return;
		}
	}

	public function set_qrdata()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'job_seeker_id' => 'int() user_id of job seeker',
							'qr_data' => 'The data you got from qr.'
						],
						'Note' => ['Make sure not to send data rapidly after one scan wait for about 15-25 seconds, it will help the users to not send data quickly. Or if the reader pick up the data, just wait for server response.']
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('job_seeker_id', 'job_seeker_id', 'trim|required');
			$this->form_validation->set_rules('qr_data', 'qr_data', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response_std(validation_errors());
				return;
			}
			try {
				$job_id = explode("-", $this->input->post('qr_data'));
				$job_id = $this->custom_encryption->decrypt_data($job_id[0]);
				if ($this->qrcode_model->set_qrdata($job_id, $this->input->post('job_seeker_id'))) {
					echo $this->api->success_response_std('Your CV has been transfer');
					return;
				} else {
					echo $this->api->error_response_std('Can not transfer your CV');
					return;
				}
			} catch (\Exception $e) {
				header("HTTP/1.0 500 Internal Server Error");
				echo $this->api->error_response_std('Error in code');
				return;
			}
		}
		header("HTTP/1.0 400 Bad Request");
		echo $this->api->error_response_std('Bad Request');
		return;
	}
}

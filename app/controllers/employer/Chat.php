<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Chat extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->ads = '';
		$this->ads = $this->ads_model->get_ads();
		$this->load->model('api/User_model', 'user');
	}

	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME . ': Chat';
		$data['one2one_users'] = $this->one2one_users();
		$this->load->view('employer/chat_view', $data);
		return;
	}
	public function open_chat($id)
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME . ': Chat';
		$data['one2one_users'] = $this->one2one_users();
		$data['one2one_chats'] = $this->one2one_chat($id);
		$this->load->view('employer/chat_view', $data);
		return;
	}
	public function one2one_chat($other_user_id)
	{
		$reseponse = [];
		try {
			$reseponse = $this->user->one2one_get($other_user_id, $this->session->userdata('user_id'));
		} catch (\Exception $e) {
			return $e;
		}
		return $reseponse;
	}

	public function get_chat($other_user_id, $chat_id = NULL)
	{
		try {
			if ($data = $this->user->one2one_get($other_user_id, $this->session->userdata('user_id'), $chat_id)) {
				echo $this->api->success_response_std('SUCCESS', $data);
				return;
			} else {
				echo $this->api->error_response_std('No message found.');
				return;
			}
		} catch (\Exception $e) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $this->api->error_response('Error in code');
			return;
		}
	}
	public function one2one_send($to_user_id)
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'message' => 'string(300)'
						]
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('message', 'message', 'trim|required|min_length[2]|max_length[300]');

			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$is_job_seeker = FALSE;
			$is_employer = TRUE;

			try {
				if ($this->user->one2one_send($this->session->userdata('user_id'), $to_user_id, $this->input->post('message'), $is_job_seeker, $is_employer)) {
					echo $this->api->success_response_std('Message sent successfully');
					return;
				} else {
					echo $this->api->error_response_std('Failed to send message.');
					return;
				}
			} catch (\Exception $e) {
				header("HTTP/1.0 500 Internal Server Error");
				echo $this->api->error_response('Error in code');
				return;
			}
		}
		header("HTTP/1.0 400 Bad Request");
		echo $this->api->error_response('Bad Request');
		return;
	}



	public function one2one_users()
	{
		$reseponse = [];
		$status = 'employer';
		try {
			if ($data = $this->user->one2one_users($this->session->userdata('user_id'), $status)) {
				// $i = 1;
				foreach ($data as $user) :
					if ($status == 'jobseeker' && ($user->sent_from == 'employer' || $user->sent_from == 'jobseeker')) :
						$reseponse[$user->employer_id]['employer_id'] = $user->employer_id;
						$reseponse[$user->employer_id]['name'] = $user->employer_name;
						$reseponse[$user->employer_id]['last_message'] = $user->message;
						$reseponse[$user->employer_id]['date'] = $user->sent_on;
					// $i++;
					endif;
					if ($status == 'employer' && ($user->sent_from == 'employer' || $user->sent_from == 'jobseeker')) :
						$reseponse[$user->jobseeker_id]['jobseeker_id'] = $user->jobseeker_id;
						$reseponse[$user->jobseeker_id]['name'] = $user->jobseeker_name;
						$reseponse[$user->jobseeker_id]['last_message'] = $user->message;
						$reseponse[$user->jobseeker_id]['date'] = $user->sent_on;
					// $i++;
					endif;
				endforeach;
				// print_r($reseponse);
				// die;
				return $reseponse;
			} else {
				return $reseponse;
			}
		} catch (\Exception $e) {
			return $reseponse;
		}
	}
}

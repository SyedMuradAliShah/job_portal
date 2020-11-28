<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
		$this->load->model('api/User_model', 'user');
	}

	public function login()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'email' => 'string(100)',
							'pass' => [
								'minimum' => 6,
								'maximum' => 100
							],
						],
						'Note' => []
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|min_length[10]|max_length[100]');
			$this->form_validation->set_rules('pass', 'password', 'trim|required|min_length[6]|max_length[100]');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			try {

				$is_job_seeker = TRUE;
				$is_employer = FALSE;
				$userRow = $this->job_seekers_model->authenticate_job_seeker($this->input->post('email'), $this->input->post('pass'));
				$slug = '';
				if (!$userRow) {
					$is_job_seeker = FALSE;
					$is_employer = TRUE;
					$userRow = $this->employers_model->authenticate_employer($this->input->post('email'), $this->input->post('pass'));
					if (!$userRow) {
						echo $this->api->error_response_std('Wrong email or password provided');
						return;
					}
				}

				if ($userRow->sts == 'pending') {
					echo $this->api->error_response_std('You have not yet verified your email address.');
					return;
				}

				if ($userRow->sts == 'blocked') {
					echo $this->api->error_response_std('Your account was suspended. Please contact site admin for further information.');
					return;
				}

				$slug = @$userRow->company_slug;
				$user_data = array(
					'user_id' => $userRow->ID,
					'user_email' => $userRow->email,
					'first_name' => $userRow->first_name,
					'slug' => $slug,
					'last_name' => $userRow->last_name,
					'is_user_login' => TRUE,
					'is_job_seeker' => $is_job_seeker,
					'is_employer' => $is_employer
				);

				$this_model_name = ($is_employer == TRUE) ? 'employers_model' : 'job_seekers_model';

				if ($userRow->first_login_date == '') {
					$this->$this_model_name->update($userRow->ID, array('first_login_date' => date("Y-m-d H:i:s"), 'last_login_date' => date("Y-m-d H:i:s"), 'sts' => 'active'));
				} else {
					$this->$this_model_name->update($userRow->ID, array('last_login_date' => date("Y-m-d H:i:s")));
				}
				echo $this->api->success_response_std('Logged in successful', $user_data);
				return;
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

	public function one2one_send()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'from_user_id' => 'int()',
							'to_user_id' => 'int()',
							'message' => 'string(300)',
							'from_job_seeker' => 'boolean',
							'from_employer' => 'boolean'
						],
						'Note' => ['You have to send from_job_seeker and from_employer, if you want to sent message from job seeker, then set from_employer = true, and from_job_seeker will be false']
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('from_user_id', 'from_user_id', 'trim|required');
			$this->form_validation->set_rules('to_user_id', 'to_user_id', 'trim|required');
			$this->form_validation->set_rules('message', 'message', 'trim|required|min_length[2]|max_length[300]');
			if ($this->input->post('from_job_seeker') && $this->input->post('from_employer')) {
				$this->form_validation->set_rules('from_job_seeker', 'from_job_seeker', 'trim|required');
				$this->form_validation->set_rules('from_employer', 'from_employer', 'trim|required');
			}
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$is_job_seeker = ($this->input->post('from_job_seeker')) ? TRUE : FALSE;
			$is_employer = ($this->input->post('from_employer')) ? TRUE : FALSE;

			try {
				if ($this->user->one2one_send($this->input->post('from_user_id'), $this->input->post('to_user_id'), $this->input->post('message'), $is_job_seeker, $is_employer)) {
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



	public function one2one_get()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'other_user_id' => 'int()',
							'my_user_id' => 'int()',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('other_user_id', 'other_user_id', 'trim|required');
			$this->form_validation->set_rules('my_user_id', 'my_user_id', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}

			try {
				if ($data = $this->user->one2one_get($this->input->post('other_user_id'), $this->input->post('my_user_id'))) {
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
		header("HTTP/1.0 400 Bad Request");
		echo $this->api->error_response('Bad Request');
		return;
	}
	public function one2one_users()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'my_user_id' => 'int()',
							'my_status' => 'jobseeker | employer'
						],
						'Note' => []
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('my_user_id', 'my_user_id', 'trim|required');
			$this->form_validation->set_rules('my_status', 'my_status', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			if ($this->input->post('my_status') == 'jobseeker')
				$status = 'jobseeker';
			elseif ($this->input->post('my_status') == 'employer')
				$status = 'employer';
			else {
				echo $this->api->error_response('Please send a valid my_status');
				return;
			}


			try {
				if ($data = $this->user->one2one_users($this->input->post('my_user_id'), $status)) {
					$reseponse = [];
					// $i = 1;
					foreach ($data as $user) :
						if ($status == 'jobseeker' && ($user->sent_from == 'employer' || $user->sent_from == 'jobseeker'))  :
							$reseponse[$user->employer_id]['employer_id'] = $user->employer_id;
							$reseponse[$user->employer_id]['name'] = $user->employer_name;
							$reseponse[$user->employer_id]['last_message'] = $user->message;
							// $i++;
						endif;
						if ($status == 'employer' && ($user->sent_from == 'employer' || $user->sent_from == 'jobseeker')) :
							$reseponse[$user->jobseeker_id]['jobseeker_id'] = $user->jobseeker_id;
							$reseponse[$user->jobseeker_id]['name'] = $user->jobseeker_name;
							$reseponse[$user->jobseeker_id]['last_message'] = $user->message;
							// $i++;
						endif;
					endforeach;

					echo $this->api->success_response_std('SUCCESS', $reseponse);
					return;
				} else {
					echo $this->api->error_response_std('No chat found.');
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
}

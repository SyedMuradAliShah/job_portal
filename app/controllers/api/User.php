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
				echo $this->api->error_response_std('Error in code');
				return;
			}
		}
		header("HTTP/1.0 400 Bad Request");
		echo $this->api->error_response_std('Bad Request');
		return;
	}
	public function skills()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT'
						],
						'Note' => ['At least 3 skills allowed']
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$result = $this->jobseeker_skills_model->get_records_by_seeker_id($this->input->post('user_id'));
			$data['result'] = $result;
			if ($result == 0)
				$data['count_skills'] = $result;
			else
				$data['count_skills'] = count($result);

			echo $this->api->success_response($data);
			return;
		}
	}
	public function add_skills()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT',
							'skill' => 'string(100)',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			$this->form_validation->set_rules('skill', 'skill name', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}

			$skill = trim(strtolower($this->input->post('skill')));
			$skill = strip_tags($skill);

			$row = $this->jobseeker_skills_model->get_records_by_seeker_id_skill_name($this->input->post('user_id'), $skill);
			if ($row) {
				echo $this->api->error_response("This skill is already added.");
				exit;
			}

			$data_array = array('seeker_ID' => $this->input->post('user_id'), 'skill_name' => $skill);
			$this->jobseeker_skills_model->add($data_array);
			$this->jobseeker_skills_model->count_jobseeker_skills_by_seeker_id($this->input->post('user_id'));
			echo $this->api->success_response_std('Skill has been added successfully');
			exit;
		}
	}

	public function remove_skills()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT',
							'skill_id' => 'INT',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}

			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			$this->form_validation->set_rules('skill_id', 'skill_id', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$res = $this->jobseeker_skills_model->delete($this->input->post('skill_id'));
			$this->jobseeker_skills_model->count_jobseeker_skills_by_seeker_id($this->input->post('user_id'));

			if ($res) {
				echo $this->api->success_response_std('Skill has been removed successfully');
				exit;
			}
			echo $this->api->error_response('Unable to remove this skill');
			return;
		}
	}
	public function countries()
	{
		$result_countries = $this->countries_model->get_all_countries();
		echo $this->api->success_response_std('', $result_countries);
	}
	public function register()
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
							'full_name' => 'string(100)',
							'gender' => 'Male | Female',
							'dob_day' => 'dd',
							'dob_month' => 'mm',
							'dob_year' => 'YY',
							'dob_year' => 'YY',
							'dob_year' => 'YY',
							'current_address' => 'string(100)',
							'city' => 'string(100)',
							'country' => 'string(100), from the /api/user/countries',
							'nationality' => 'string(100), from the /api/user/countries',
							'mobile_number' => 'INT',
							'phone' => 'OPTIONAL | INT',
							// 'cv_file' => 'Multipart file only in doc, docx, pdf, rtf, jpg, txt format with maximum size of 6 MB',
						],
						'Note' => ['NOTE CV file must be in multipart.']
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_job_seekers.email]|strip_all_tags');
			$this->form_validation->set_rules('pass', 'Password', 'trim|required|min_length[6]|strip_all_tags');
			$this->form_validation->set_rules('full_name', 'Full name', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('gender', 'Gender', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('dob_day', 'dob_day', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('dob_month', 'dob_month', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('dob_year', 'dob_year', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('current_address', 'Current address', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('country', 'Country', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('city', 'City', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('mobile_number', 'Mobile', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|strip_all_tags');
			$this->form_validation->set_message('is_unique', 'The %s is already taken');

			// if (empty($_FILES['cv_file']['name']))
			// 	$this->form_validation->set_rules('cv_file', 'Resume', 'required');

			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}

			try {

				// if (!empty($_FILES['cv_file']['name'])) {
				$current_date = date("Y-m-d H:i:s");
				$job_seeker_array = array(
					'first_name' => $this->input->post('full_name'),
					'email' => $this->input->post('email'),
					'password' => $this->input->post('pass'),
					'dob' => $this->input->post('dob_year') . '-' . $this->input->post('dob_month') . '-' . $this->input->post('dob_day'),
					'mobile' => $this->input->post('mobile_number'),
					'home_phone' => $this->input->post('phone'),
					'present_address' => $this->input->post('current_address'),
					'country' => $this->input->post('country'),
					'city' => $this->input->post('city'),
					'nationality' => $this->input->post('nationality'),
					'gender' => $this->input->post('gender'),
					'ip_address' => $this->input->ip_address(),
					'dated' => $current_date
				);
				// $extention = get_file_extension($_FILES['cv_file']['name']);
				// $allowed_types = array('doc', 'docx', 'pdf', 'rtf', 'jpg', 'png', 'txt');

				// if (!in_array($extention, $allowed_types)) {
				// 	echo $this->api->error_response('This file type is not allowed.');
				// 	return;
				// }

				$seeker_id = $this->job_seekers_model->add_job_seekers($job_seeker_array);
				// $resume_array = array();
				// $real_path = realpath(APPPATH . '../public/uploads/candidate/resumes/');
				// $config['upload_path'] = $real_path;
				// $config['allowed_types'] = 'doc|docx|pdf|rtf|jpg|png|txt';
				// $config['overwrite'] = true;
				// $config['max_size'] = 6000;
				// $config['file_name'] = replace_string(' ', '-', strtolower($this->input->post('full_name'))) . '-' . $seeker_id;
				// $this->upload->initialize($config);
				// if (!$this->upload->do_upload('cv_file')) {
				// 	$this->job_seekers_model->delete_job_seeker($seeker_id);
				// 	echo $this->api->error_response($this->upload->display_errors());
				// 	return;
				// }
				// $resume = array('upload_data' => $this->upload->data());
				// $resume_file_name = $resume['upload_data']['file_name'];
				$resume_file_name = 'xyz.png';
				$resume_array = array(
					'seeker_ID' => $seeker_id,
					'file_name' => $resume_file_name,
					'dated' => $current_date,
					'is_uploaded_resume' => 'yes'

				);
				$this->resume_model->add($resume_array);
				$this->jobseeker_additional_info_model->add(array('seeker_ID' => $seeker_id));
				$user_data = array(
					'user_id' => $seeker_id,
					'user_email' => $this->input->post('email'),
					'first_name' => $this->input->post('full_name'),
					'slug' => '',
					'last_name' => '',
					'is_user_login' => TRUE,
					'is_job_seeker' => TRUE,
					'is_employer' => FALSE
				);

				//Sending email to the user
				$row_email = $this->email_model->get_records_by_id(2);

				$config = $this->email_drafts_model->email_configuration();
				$this->email->initialize($config);
				$this->email->clear(TRUE);
				$this->email->from($row_email->from_email, $row_email->from_name);
				$this->email->to($this->input->post('email'));
				$mail_message = $this->email_drafts_model->jobseeker_signup($row_email->content, $job_seeker_array);
				$this->email->subject($row_email->subject);
				$this->email->message($mail_message);
				$this->email->send();

				echo $this->api->success_response_std('Registration successfull', $user_data);
				return;
				// }
			} catch (\Exception $e) {
				header("HTTP/1.0 500 Internal Server Error");
				echo $this->api->error_response('Registration failed');
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
					// $i = 0;
					foreach ($data as $user) :
						if ($status == 'jobseeker' && ($user->sent_from == 'employer' || $user->sent_from == 'jobseeker')) :
							$res['employer_id'] = $user->employer_id;
							$res['name'] = $user->employer_name;
							$res['last_message'] = $user->message;
							$reseponse[] = $res;
						endif;
						if ($status == 'employer' && ($user->sent_from == 'employer' || $user->sent_from == 'jobseeker')) :
							$res['jobseeker_id'] = $user->jobseeker_id;
							$res['name'] = $user->jobseeker_name;
							$res['last_message'] = $user->message;
							$reseponse[] = $res;
						endif;
						// $i++;
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

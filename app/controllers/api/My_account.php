<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class My_Account extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}

			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}

			//Skills
			$keywords = $this->jobseeker_skills_model->count_jobseeker_skills_by_seeker_id($this->input->post('user_id'));
			$is_keywords_provided = $keywords;

			if ($is_keywords_provided < 3) {
				echo $this->api->error_response('Please add skills.');
				exit;
			}

			$row = $this->job_seekers_model->get_job_seeker_by_id($this->input->post('user_id'));
			$data['row'] = $row;
			$data['result_countries'] = $this->countries_model->get_all_countries();
			echo $this->api->success_response($data);
		}
	}
	public function update()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT',
							'email' => 'string(100)',
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
						],
						'Note' => ['NOTE CV file must be in multipart.']
					];
					echo json($data);
					return;
				}

			$this->form_validation->set_rules('full_name', 'full name', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('mobile', 'mobile', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('dob_day', 'DOB', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('dob_month', 'DOB', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('dob_year', 'DOB', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('gender', 'gender', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('present_address', 'present_address', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('country', 'country', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('city', 'city', 'trim|required|strip_all_tags');
			if ($this->form_validation->run() === FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$profile_array = array(
				'first_name'		=> $this->input->post('full_name'),
				'last_name'			=> '',
				'mobile'			=> $this->input->post('mobile'),
				'dob'				=> $this->input->post('dob_year') . '-' . $this->input->post('dob_month') . '-' . $this->input->post('dob_day'),
				'present_address' 	=> $this->input->post('present_address'),
				'country' 			=> $this->input->post('country'),
				'city' 				=> $this->input->post('city'),
			);
			$this->job_seekers_model->update($this->input->post('user_id'), $profile_array);
			echo $this->api->success_response('Profile has been updated successfully.');
		}
	}
	public function change_password()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT',
							'old_password' => 'string(100)',
							'new_password' => 'string(100)',
							'confirm_password' => 'string(100)',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}


			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[new_password]');

			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}

			$old_password = $this->input->post('old_password');

			$rs = $this->job_seekers_model->authenticate_job_seeker_by_id_password($this->input->post('user_id'), $old_password);
			if ($rs) {
				$jobseeker_array = array(
					'password' => $this->input->post('new_password')
				);

				$this->job_seekers_model->update($rs->ID, $jobseeker_array);
				echo $this->api->success_response('Password has been changed successfully.');
				return;
			}
			echo $this->api->error_response('Old password is wrong.');
			return;
		}
	}
}

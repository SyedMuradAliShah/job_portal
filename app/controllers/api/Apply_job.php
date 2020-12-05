<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Apply_Job extends CI_Controller
{

	public function index()
	{
		if (is_post()) {
			if (ENVIRONMENT == 'development')
				if ($this->input->post('params_info') == true) {
					$data = [
						'Method' => 'POST',
						'Accepted paramters' => [
							'user_id' => 'INT',
							'job_id' => 'INT',
							'expected_salary' => 'string(300)',
							'cover_letter' => 'string(300)',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}

			$data['msg'] = '';

			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			$this->form_validation->set_rules('job_id', 'job_id', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('expected_salary', 'Expected Salary', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('cover_letter', 'Cover letter', 'trim|required|strip_all_tags');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$row = $this->posted_jobs_model->get_active_posted_job_by_id($this->input->post('job_id'));

			if (!$row) {
				echo $this->api->error_response('Something went wrong.');
				exit;
			}

			if ($this->is_already_applied_for_job($this->input->post('user_id'), $this->input->post('job_id'))) {
				echo $this->api->error_response('Job is already applied');
				exit;
			}


			$is_already_applied = $this->applied_jobs_model->get_applied_job_by_seeker_and_job_id($this->session->userdata('user_id'), $this->input->post('job_id'));

			if ($is_already_applied > 0) {
				echo $this->api->error_response('You have already applied for this job job has been closed.');
				exit;
			}

			$current_date_time = date("Y-m-d H:i:s");

			$job_array = array(
				'seeker_ID' 		=> $this->input->post('user_id'),
				'job_ID' 			=> $this->input->post('job_id'),
				'employer_ID' 		=> $row->employer_ID,
				'cover_letter' 		=> $this->input->post('cover_letter'),
				'expected_salary' 	=> $this->input->post('expected_salary'),
				'dated' 			=> $current_date_time
			);
			$this->applied_jobs_model->add_applied_job($job_array);

			//Sending email
			$row_email = $this->email_model->get_records_by_id(5);
			$config = array();
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';

			$data_array = $this->posted_jobs_model->get_active_posted_job_by_id($this->input->post('job_id'));
			$seeker_id = $this->custom_encryption->encrypt_data($this->input->post('user_id'));

			$subject = str_replace('{JOB_TITLE}', $data_array->job_title, $row_email->subject);


			$config = $this->email_drafts_model->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from($row_email->from_email, $row_email->from_name);
			$this->email->to($data_array->employer_email);
			$mail_message = $this->email_drafts_model->apply_job($seeker_id, $row_email->content, $data_array);
			$this->email->subject($subject);
			$this->email->message($mail_message);
			$this->email->send();
			echo $this->api->success_response('Job submitted');
			return;
		}
	}

	public function is_already_applied_for_job($user_id = '', $job_id)
	{
		$is_already_applied = false;
		$is_already_applied = $this->applied_jobs_model->get_applied_job_by_seeker_and_job_id($user_id, $job_id);
		$is_already_applied = ($is_already_applied > 0) ? true : false;
		return $is_already_applied;
	}
}

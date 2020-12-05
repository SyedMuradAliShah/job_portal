<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class My_Jobs extends CI_Controller
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
			//Additional Info
			$row_additional = $this->jobseeker_additional_info_model->get_record_by_userid($this->input->post('user_id'));

			//Skills
			$keywords = $this->jobseeker_skills_model->count_jobseeker_skills_by_seeker_id($this->input->post('user_id'));
			$is_keywords_provided = $keywords;

			if ($is_keywords_provided < 3) {
				echo $this->api->error_response('Please add skills.');
				exit;
			}

			//Applied Jobs by Employer ID
			$result_applied_jobs = $this->applied_jobs_model->get_applied_jobs_by_jobseeker_id($this->input->post('user_id'), 20, 1);
			$data['result_applied_jobs'] = $result_applied_jobs;
			echo $this->api->success_response($data);
			exit;
		}
	}
}

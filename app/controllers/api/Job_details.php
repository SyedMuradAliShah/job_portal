<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Job_details extends CI_Controller
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
							'job_id' => 'INT',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			$this->form_validation->set_rules('job_id', 'job_id', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}

			$is_already_applied = 'no';

			$row_posted_job = $this->posted_jobs_model->get_active_posted_job_by_id($this->input->post('job_id'));
			if (!$row_posted_job) {
				echo $this->api->error_response('No job');
				return;
			}
			$currently_opened_jobs = $this->posted_jobs_model->count_active_opened_jobs_by_company_id($row_posted_job->CID);


			$company_logo = ($row_posted_job->company_logo) ? $row_posted_job->company_logo : 'thumb/no_pic.jpg';
			if (!file_exists(realpath(APPPATH . '../public/uploads/employer/' . $company_logo))) {
				$company_logo = 'thumb/no_pic.jpg';
			}

			$can_apply = ($row_posted_job->last_date > date("Y-m-d") ? 'yes' : 'no');

			$is_already_applied = $this->applied_jobs_model->get_applied_job_by_seeker_and_job_id($this->input->post('user_id'), $this->input->post('job_id'));
			$is_already_applied = ($is_already_applied > 0) ? 'yes' : 'no';
			$data['result_resume'] = $this->resume_model->get_records_by_seeker_id($this->input->post('user_id'));
			$data['required_skills'] = explode(', ', $row_posted_job->required_skills);
			$data['row_posted_job'] = $row_posted_job;
			$data['company_logo'] = $company_logo;
			$data['can_apply'] = $can_apply;
			$data['is_already_applied'] = $is_already_applied;
			$data['currently_opened_jobs'] = $currently_opened_jobs;
			$data['result_salaries'] = $this->salaries_model->get_all_records();
			echo $this->api->success_response($data);
			return;
			$this->load->view('job_details_view', $data);
		}
	}
}

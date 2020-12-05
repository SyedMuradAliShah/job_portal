<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Company extends CI_Controller
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
							'company_slug' => 'string(300)',
						],
						'Note' => []
					];
					echo json($data);
					return;
				}
			$this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
			$this->form_validation->set_rules('company_slug', 'company_slug', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				echo $this->api->error_response(validation_errors());
				return;
			}
			$company_name = $this->input->post('company_slug');

			$row_company = $this->employers_model->get_company_details_by_slug($company_name);

			if (!$row_company) {
				echo $this->api->error_response('No company found.');
				exit;
			}

			//Jobs by company
			$result_posted_jobs = $this->posted_jobs_model->get_active_posted_job_by_company_id($row_company->ID, 100, 0);
			$total_opened_jobs = ($result_posted_jobs == 0) ? '0' : count($result_posted_jobs);

			$company_logo = ($row_company->company_logo) ? $row_company->company_logo : 'no_pic.jpg';
			if (!file_exists(realpath(APPPATH . '../public/uploads/employer/' . $company_logo))) {
				$company_logo = 'no_pic.jpg';
			}
			foreach ($result_posted_jobs as $key => $value) {
				$qu[$value->ID][$key] = $value;
				$qu[$value->ID]['applied'] = $this->is_already_applied_for_job($this->input->post('user_id'), $value->ID);
			}

			$company_website = ($row_company->company_website != '') ? validate_company_url($row_company->company_website) : '';
			$data['row_company'] 		= $row_company;
			$data['total_opened_jobs'] 	= $total_opened_jobs;
			$data['result_posted_jobs'] = $qu;
			$data['company_logo'] 		= $company_logo;
			$data['company_website'] 	= $company_website;
			echo $this->api->success_response($data);
			exit;
		}
	}

	public function is_already_applied_for_job($user_id = '', $job_id)
	{
		$is_already_applied = 'no';
		$is_already_applied = $this->applied_jobs_model->get_applied_job_by_seeker_and_job_id($user_id, $job_id);
		$is_already_applied = ($is_already_applied > 0) ? 'yes' : 'no';
		return $is_already_applied;
	}
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Matching_Jobs extends CI_Controller
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
			//Job Seeker Skils
			$job_seeker_skills = $this->job_seekers_model->get_grouped_skills_by_seeker_id($this->input->post('user_id'));
			$skills = explode(',', @$job_seeker_skills);

			$skill_qry = '';
			if ($skills) {
				foreach ($skills as $sk) {
					$skill_qry .= " OR required_skills LIKE '%" . trim($sk) . "%'";
				}
			} else {
				$skill_qry .= " required_skills LIKE '%" . trim($skills) . "%'";
			}

			$skill_qry = ltrim($skill_qry, 'OR ');

			//Jobs by skills
			$result_jobs = $this->posted_jobs_model->get_matching_searched_jobs($skill_qry, 50, 0);

			
			foreach ($result_jobs as $key => $value) {
				$qu['ID'] = $value->ID;
				$qu['job_title'] = $value->job_title;
				$qu['job_slug'] = $value->job_slug;
				$qu['employer_ID'] = $value->employer_ID;
				$qu['company_ID'] = $value->company_ID;
				$qu['job_description'] = $value->job_description;
				$qu['city'] = $value->city;
				$qu['dated'] = $value->dated;
				$qu['last_date'] = $value->last_date;
				$qu['is_featured'] = $value->is_featured;
				$qu['sts'] = $value->sts;
				$qu['company_name'] = $value->company_name;
				$qu['company_logo'] = $value->company_logo;
				$qu['company_slug'] = $value->company_slug;
				$qu['applied'] = $this->is_already_applied_for_job($this->input->post('user_id'), $value->ID);
				$response[] = $qu; 
			}

			$data['result'] = $response;
			echo $this->api->success_response($data);
		}
	}

	public function is_already_applied_for_job($user_id = '', $job_id)
	{
		$is_already_applied = '';
		$is_already_applied = $this->applied_jobs_model->get_applied_job_by_seeker_and_job_id($user_id, $job_id);
		$is_already_applied = ($is_already_applied > 0) ? 'yes' : 'no';
		return $is_already_applied;
	}
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Job_search extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Karachi");
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
							'query' => 'OPTIONAL | string(100)',
							'city' => 'OPTIONAL | string(100)',
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

			$job_param = $this->input->post('query');
			$jcity = $this->input->post('city');

			$string1 = preg_replace('/[^a-zA-Z0-9 ]/s', '', $job_param);
			$param = strtolower(preg_replace('/\s+/', '-', $string1));

			$jstring1 = preg_replace('/[^a-zA-Z0-9 ]/s', '', $jcity);
			$param2 = strtolower(preg_replace('/\s+/', '-', $jstring1));

			$data['msg'] = '';

			//Pagination starts
			$total_rows = $this->posted_jobs_model->count_searched_job_records($param, $param2);
			$obj_result = $this->posted_jobs_model->get_searched_jobs($param, $param2, 40, 1);
			foreach ($obj_result as $key => $value) {
				$qu[$value->ID][$key] = $value;
				$qu[$value->ID]['applied'] = $this->is_already_applied_for_job($this->input->post('user_id'), $value->ID);
			}
			//Left Side Starts
			$left_side_array = $this->left_side_data($param);
			//Left Side Ends
			$current_records = ($this->input->post('city')) ?  $this->input->post('city') * 20 : 20;
			$current_records = ($current_records > $total_rows) ? $total_rows : $current_records;
			$data['total_rows'] = $total_rows;
			$data['page'] = $current_records;
			$data['result'] = $qu;
			$data['param'] = $param;
			$data['title_group'] = $left_side_array['title_group'];
			$data['city_group'] = $left_side_array['city_group'];
			$data['salary_range'] = $left_side_array['salary_range_group'];
			echo $this->api->success_response($data);
			return;
		}
	}
	public function left_side_data($param)
	{
		//Group By Title
		$title_group = $this->posted_jobs_model->get_searched_group_by_title($param);

		//Group By City
		$city_group = $this->posted_jobs_model->get_searched_group_by_city($param);

		//Group By Companies
		$company_group = $this->posted_jobs_model->get_searched_group_by_company($param);
		//Group By Salary Range
		$salary_range_group = $this->posted_jobs_model->get_searched_group_by_salary_range($param);

		$left_array =  array(
			'title_group' => $title_group,
			'city_group' => $city_group,
			'company_group' => $company_group,
			'salary_range_group' => $salary_range_group
		);

		return $left_array;
	}

	public function is_already_applied_for_job($user_id = '', $job_id)
	{
		$is_already_applied = '';
		$is_already_applied = $this->applied_jobs_model->get_applied_job_by_seeker_and_job_id($user_id, $job_id);
		$is_already_applied = ($is_already_applied > 0) ? 'yes' : 'no';
		return $is_already_applied;
	}
}

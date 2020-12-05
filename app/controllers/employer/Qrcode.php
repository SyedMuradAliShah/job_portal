<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Qrcode extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->ads = '';
		$this->ads = $this->ads_model->get_ads();
	}

	public function index()
	{
		$data['ads_row'] = $this->ads;
		$job_url = $this->uri->segment(3);
		$row_posted_job = $this->posted_jobs_model->get_active_posted_job_by_id($job_url);
		if (!$row_posted_job) {
			$this->load->view('404_view');
			return;
		}
		$currently_opened_jobs = $this->posted_jobs_model->count_active_opened_jobs_by_company_id($row_posted_job->CID);


		$company_logo = ($row_posted_job->company_logo) ? $row_posted_job->company_logo : 'thumb/no_pic.jpg';
		if (!file_exists(realpath(APPPATH . '../public/uploads/employer/' . $company_logo))) {
			$company_logo = 'thumb/no_pic.jpg';
		}


		$can_apply = ($row_posted_job->last_date > date("Y-m-d") ? 'yes' : 'no');

		$tt = explode(', ', $row_posted_job->required_skills);
		$data['required_skills'] = explode(', ', $row_posted_job->required_skills);
		$data['row_posted_job'] = $row_posted_job;
		$data['company_logo'] = $company_logo;
		$data['can_apply'] = $can_apply;
		$data['currently_opened_jobs'] = $currently_opened_jobs;
		$data['title'] = 'SCAN QR ' . $row_posted_job->job_title;
		$data['cpt_code'] = create_ml_captcha();
		$this->load->view('employer/qrcode_view', $data);
	}
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Job_Applications extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->ads = '';
		$this->ads = $this->ads_model->get_ads();
		$this->load->model('api/User_model', 'api_user');
	}

	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME . ': List of Received Job Applications';

		//Pagination starts
		$total_rows = $this->applied_jobs_model->count_applied_job_by_employer_id($this->session->userdata('user_id'));
		$config = pagination_configuration(base_url("employer/job_applications"), $total_rows, 50, 3, 5, true);

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(2)) ? $this->uri->segment(3) : 0;
		$page_num = $page - 1;
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num * $config["per_page"];
		$data["links"] = $this->pagination->create_links();
		//Pagination ends

		//Applied Jobs by Employer ID
		$result_applied_jobs = $this->applied_jobs_model->get_applied_job_by_employer_id($this->session->userdata('user_id'), $config["per_page"], $page);
		$data['result_applied_jobs'] = $result_applied_jobs;
		$this->load->view('employer/job_applications_view', $data);
	}

	public function send_message_to_candidate()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'All fields are mandatory.';
			exit;
		}
		$this->form_validation->set_rules('message', 'message', 'trim|required|strip_all_tags|time_diff');
		$this->form_validation->set_rules('jsid', 'ID', 'trim|required|strip_all_tags');
		$this->form_validation->set_error_delimiters('', '');
		if ($this->form_validation->run() === FALSE) {
			echo validation_errors();
			exit;
		}

		if ($this->session->userdata('is_employer') != TRUE) {
			echo 'You are not logged in with a employer account. Please login with a employer account to send message to the candidate.';
			exit;
		}

		$decrypted_id = $this->custom_encryption->decrypt_data($this->input->post('jsid'));

		$row_jobseeker 	= $this->job_seekers_model->get_job_seeker_by_id($decrypted_id);
		$row_employer 	= $this->employers_model->get_employer_by_id($this->session->userdata('user_id'));
		if (!$row_jobseeker) {
			echo 'Something went wrong.';
			exit;
		}

		if (!$row_employer) {
			echo 'Something went wrong.';
			exit;
		}

		echo $this->one2one_send($this->session->userdata('user_id'), $decrypted_id, $this->input->post('message'));
		exit;
	}


	public function one2one_send($from, $to, $message)
	{
		$is_job_seeker = FALSE;
		$is_employer = TRUE;

		try {
			if ($this->api_user->one2one_send($from, $to, $message, $is_job_seeker, $is_employer)) {
				return 'done'; // return 'Message sent successfully';
			} else {
				return 'Failed to send message';
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}







	public function update_rating()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'All fields are mandatory.';
			exit;
		}
		$this->form_validation->set_rules('message', 'message', 'trim|required|strip_all_tags|time_diff');
		$this->form_validation->set_rules('jsid', 'ID', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('skillrating', 'skillrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_rules('techrating', 'techrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_rules('commrating', 'commrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_rules('personrating', 'personrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_error_delimiters('', '');
		if ($this->form_validation->run() === FALSE) {
			echo validation_errors();
			exit;
		}

		if ($this->session->userdata('is_employer') != TRUE) {
			echo 'You are not logged in with a employer account. Please login with a employer account to update rating.';
			exit;
		}

		$decrypted_id = $this->custom_encryption->decrypt_data($this->input->post('jsid'));

		$row_jobseeker 	= $this->job_seekers_model->get_job_seeker_by_id($decrypted_id);
		$row_employer 	= $this->employers_model->get_employer_by_id($this->session->userdata('user_id'));
		if (!$row_jobseeker) {
			echo 'Something went wrong.';
			exit;
		}

		if (!$row_employer) {
			echo 'Something went wrong.';
			exit;
		}
		$rating = $this->jobseeker_experience_model->get_rating($decrypted_id, $this->session->userdata('user_id'));
		if ($rating->num_rows()) {
			if ($this->jobseeker_experience_model->update_rating($decrypted_id, $this->session->userdata('user_id'), $this->input->post('message'), $this->input->post('skillrating'), $this->input->post('techrating'), $this->input->post('commrating'), $this->input->post('personrating'))) {
				echo 'done';
				exit;
			}
		}
		echo 'Failed to update this rating.';
	}
	public function add_rating()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'All fields are mandatory.';
			exit;
		}
		$this->form_validation->set_rules('message', 'message', 'trim|required|strip_all_tags|time_diff');
		$this->form_validation->set_rules('jsid', 'ID', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('skillrating', 'skillrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_rules('techrating', 'techrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_rules('commrating', 'commrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_rules('personrating', 'personrating', 'trim|strip_all_tags|time_diff');
		$this->form_validation->set_error_delimiters('', '');
		if ($this->form_validation->run() === FALSE) {
			echo validation_errors();
			exit;
		}

		if ($this->session->userdata('is_employer') != TRUE) {
			echo 'You are not logged in with a employer account. Please login with a employer account to add rating.';
			exit;
		}

		$decrypted_id = $this->custom_encryption->decrypt_data($this->input->post('jsid'));

		$row_jobseeker 	= $this->job_seekers_model->get_job_seeker_by_id($decrypted_id);
		$row_employer 	= $this->employers_model->get_employer_by_id($this->session->userdata('user_id'));
		if (!$row_jobseeker) {
			echo 'Something went wrong.';
			exit;
		}

		if (!$row_employer) {
			echo 'Something went wrong.';
			exit;
		}
		$rating = $this->jobseeker_experience_model->get_rating($decrypted_id, $this->session->userdata('user_id'));
		if (!$rating->num_rows()) {
			if ($this->jobseeker_experience_model->add_rating($decrypted_id, $this->session->userdata('user_id'), $this->input->post('message'), $this->input->post('skillrating'), $this->input->post('techrating'), $this->input->post('commrating'), $this->input->post('personrating'))) {
				echo 'done';
				exit;
			}
		}
		echo 'Failed to add this rating.';
	}
}

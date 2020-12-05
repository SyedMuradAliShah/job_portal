<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Karachi");
  }




  public function one2one_online_user($id)
  {
    $time_ago = date('Y-m-d H:i:s', strtotime('5 minutes ago'));
    $query = $this->db->query("SELECT * FROM `users` LEFT JOIN `agencies` ON `users`.`email` = `agencies`.`email_id` WHERE `users`.`id` = $id ");
    return $query;
  }
  public function _one2one_get($other_user_id, $personal_user_id)
  {
    // update_last_activity($personal_user_id);
    $query = $this->db->query("SELECT `chat`.* , `users`.`f_name` as `f_name`, `users`.`l_name` as `l_name`, `agencies`.`agency_name` as agency_name FROM `chat`
    INNER JOIN `users` ON `chat`.`user_id_from` = `users`.`id` LEFT JOIN `agencies` ON `users`.`email` = `agencies`.`email_id` WHERE (`user_id_from` = $personal_user_id AND user_id_to = $other_user_id) OR (`user_id_from` = $other_user_id AND user_id_to = $personal_user_id)");
    return $query;
  }






  public function one2one_users($personal_user_id, $status)
  {
    $this->db->select('tbl_chat.chat_id, tbl_chat.message, tbl_chat.sent_from, tbl_chat.sent_on, tbl_employers.first_name as employer_name, tbl_employers.ID as employer_id, tbl_job_seekers.first_name as jobseeker_name, tbl_job_seekers.ID as jobseeker_id');
    $this->db->join('tbl_employers', 'tbl_employers.ID = tbl_chat.user_id_from_employer OR tbl_employers.ID = tbl_chat.user_id_to_employer', 'left');
    $this->db->join('tbl_job_seekers', 'tbl_job_seekers.ID = tbl_chat.user_id_from_jobseeker OR tbl_job_seekers.ID = tbl_chat.user_id_to_jobseeker', 'left');
    if ($status == 'jobseeker') {
      $this->db->where('tbl_chat.user_id_from_jobseeker', $personal_user_id);
      $this->db->or_where('tbl_chat.user_id_to_jobseeker', $personal_user_id);
      // $this->db->group_by('tbl_chat.user_id_to_jobseeker, tbl_chat.user_id_from_jobseeker');
    }
    if ($status == 'employer') {
      $this->db->where('tbl_chat.user_id_from_employer', $personal_user_id);
      $this->db->or_where('tbl_chat.user_id_to_employer', $personal_user_id);
      // $this->db->group_by('tbl_chat.user_id_to_employer, tbl_chat.user_id_from_employer');
    }
    $this->db->order_by('tbl_chat.sent_on', 'desc');

    $query = $this->db->get('tbl_chat');
    // echo $this->db->last_query();
    // die;
    if ($query->num_rows())
      return $query->result();
    return false;
  }




  public function one2one_get($other_user_id, $personal_user_id, $offset = NULL)
  {
    $this->db->select('tbl_chat.chat_id, tbl_chat.message, tbl_chat.sent_from, tbl_chat.sent_on, tbl_employers.first_name as employer_name, tbl_job_seekers.first_name as jobseeker_name');
    $this->db->join('tbl_employers', 'tbl_employers.ID = tbl_chat.user_id_from_employer OR tbl_employers.ID = tbl_chat.user_id_to_employer', 'left');
    $this->db->join('tbl_job_seekers', 'tbl_job_seekers.ID = tbl_chat.user_id_from_jobseeker OR tbl_job_seekers.ID = tbl_chat.user_id_to_jobseeker', 'left');
    $this->db->group_start();
    $this->db->where('tbl_employers.ID', $other_user_id);
    $this->db->where('tbl_job_seekers.ID', $personal_user_id);
    $this->db->group_end();
    $this->db->or_group_start();
    $this->db->where('tbl_employers.ID', $personal_user_id);
    $this->db->where('tbl_job_seekers.ID', $other_user_id);
    $this->db->group_end();
    if ($offset)
      $this->db->limit(300, $offset);

    $query = $this->db->get('tbl_chat');

    if ($query->num_rows())
      return $query->result_array();
    return false;
  }


  public function one2one_send($from, $to, $message, $from_job_seeker, $from_employer)
  {
    if ($from_job_seeker) {
      if ($this->db->get_where('tbl_employers', array('ID' => $to))->num_rows() > 0) {
        $save = array(
          'user_id_from_jobseeker' => $from,
          'user_id_to_employer' => $to,
          'message' => $message,
          'sent_from' => 'job_seeker',
          'sent_on' => date('Y-m-d H:i:S'),
        );
        $this->db->insert('tbl_chat', $save);
        return true;
      }
    }
    if ($from_employer) {
      if ($this->db->get_where('tbl_job_seekers', array('ID' => $to))->num_rows() > 0) {
        $save = array(
          'user_id_from_employer' => $from,
          'user_id_to_jobseeker' => $to,
          'message' => $message,
          'sent_from' => 'employer',
          'sent_on' => date('Y-m-d H:i:S'),
        );
        $this->db->insert('tbl_chat', $save);
        return true;
      }
    }
    return false;
  }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qrcode_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Karachi");
  }
  public function get_qrdata($id)
  {
    $this->db->order_by('added_at', 'desc');
    $q = $this->db->get_where('qrdata', ['job_id' => $id, 'status' => 'pending'], 1);

    if ($q->num_rows())
      return $q->row();
  }

  public function update_qr_status($id)
  {
    $this->db->update('qrdata', ['status' => 'read'], ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function set_qrdata($job_id, $job_seeker_id)
  {
    $data = [
      'job_id' => $job_id,
      'job_seeker_id' => $job_seeker_id,
      'status' => 'pending'
    ];
    $this->db->insert('qrdata', $data);
    return $this->db->insert_id();
  }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GhConsultantBooking extends CI_Model {
    private $table = 'gh_consultant_booking';

	public function get($where = []) {
        return $this->db->get_where($this->table, $where)->result_array();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function updateById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        $result = $this->db->affected_rows();
        return $result;
    }

    public function delete($district_id) {
        $this->db->where('id' , $district_id);
        $this->db->delete($this->table);
        $result = $this->db->affected_rows();
        return $result;
    }

    public function getGroupByUserId($time_booking = 0){
        $sql = "SELECT gh_consultant_booking.*, count(id) AS counter FROM gh_consultant_booking WHERE time_booking >= $time_booking GROUP BY booking_user_id";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function getGroupByDistrict($time_booking = 0){
        $sql = "SELECT gh_consultant_booking.*, count(id) AS counter FROM gh_consultant_booking WHERE time_booking >= $time_booking GROUP BY booking_user_id";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
}

/* End of file mApartment.php */
/* Location: ./application/models/role-manager/mApartment.php */
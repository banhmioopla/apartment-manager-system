<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CustomBaseStep {
    private $access_control;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('shareUser');
        $this->load->library('encryption');
    }


    public function create() {

        $data = $this->input->post();
        $data['status'] = 'New';
        $data['password'] = 'New';
        if(!empty($data['user_name'])) {
            $result = $this->ghTag->insert($data);
            $this->session->set_flashdata('fast_notify', [
                'message' => 'Tạo #'.$data['name'].' thành công ',
                'status' => 'success'
            ]);
            return redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    // Ajax
    public function update() {
        $tag_id = $this->input->post('tag_id');
        $field_value = $this->input->post('field_value');
        $field_name = $this->input->post('field_name');

        if(!empty($tag_id) and !empty($field_value)) {
            $data = [
                $field_name => $field_value
            ];
            $result = $this->ghTag->updateById($tag_id, $data);
            echo json_encode(['status' => $result]); die;
        }
        echo json_encode(['status' => false]); die;
    }

    public function updateEditable() {
        $tag_id = $this->input->post('pk');
        $field_name = $this->input->post('name');
        $field_value = $this->input->post('value');

        if(!empty($tag_id) and !empty($field_value)) {
            $data = [
                $field_name => $field_value
            ];

            $old_tag = $this->shareUser->getById($tag_id);
            $old_log = json_encode($old_Tag[0]);

            $result = $this->shareUser->updateById($tag_id, $data);

            $modified_tag = $this->shareUser->getFirstById($tag_id);
            $modified_log = json_encode($modified_tag[0]);

            $log = [
                'table_name' => 'share_user',
                'old_content' => $old_log,
                'modified_content' => $modified_log,
                'time_insert' => time(),
                'action' => 'update'
            ];
            $tracker = $this->ghActivityTrack->insert($log);

            echo json_encode(['status' => $result]); die;
        }
        echo json_encode(['status' => false]); die;
    }

    public function delete(){
        $tag_id = $this->input->post('tag_id');
        if(!empty($Tag_id)) {
            $old_tag = $this->ghTag->getById($tag_id);

            $log = [
                'table_name' => 'gh_tag',
                'old_content' => null,
                'modified_content' => json_encode($old_tag[0]),
                'time_insert' => time(),
                'action' => 'delete'
            ];

            // call model
            $tracker = $this->ghActivityTrack->insert($log);
            $result = $this->shareUser->delete($tag_id);

            if($result > 0) {
                echo json_encode(['status' => true]); die;
            }
            echo json_encode(['status' => false]); die;
        }
        echo json_encode(['status' => false]); die;
    }

}
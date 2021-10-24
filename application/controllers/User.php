<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CustomBaseStep {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ghUser');
		$this->load->model('ghRole');
		$this->load->library('LibRole', null, 'libRole');
		$this->load->library('LibUser', null, 'libUser');
	}

	public function show(){
		$data['list_user'] = $this->ghUser->get(['account_id >' => 171020000]);

        $data['list_all_user'] = $this->ghUser->get(['account_id >' => 171020000]);

		$data['max_account_id'] = $this->ghUser->getMaxAccountId()[0]['account_id'];
		$data['libRole'] = $this->libRole;
		$data['libUser'] = $this->libUser;
        $data['select_user'] = $this->libUser->cb(0,'YES');
		/*--- Load View ---*/
		$this->load->view('components/header');
		$this->load->view('user/show', $data);
		$this->load->view('components/footer');
	}

	public function create() {
	
		$post = $this->input->post();
		$data['time_insert'] = $data['time_update'] = time();
		$data['password'] = $data['account_id'] = $post['account_id'];
		$data['role_code'] = 'consultant';
		$data['name'] = $post['name'];
		$data['phone_number'] = $post['phone_number'];

		if($post['date_of_birth']) {
			if(empty($post['date_of_birth'])) {
				$post['date_of_birth'] = null;
			} else {
				$post['date_of_birth'] = str_replace('/', '-', $post['date_of_birth']);
				$post['date_of_birth'] = strtotime((string)$post['date_of_birth']);
			}
		}
		if($post['time_joined']) {
			if(empty($post['time_joined'])) {
				$post['time_joined'] = null;
			} else {
				$post['time_joined'] = str_replace('/', '-', $post['time_joined']);
				$post['time_joined'] = strtotime((string)$post['time_joined']);
			}
		}
		$data['time_joined'] = $post['time_joined'];
		$data['date_of_birth'] = $post['date_of_birth'];
		$data['time_insert'] = time();
		$data['user_refer_id'] = $post['user_refer_id'] > 0 ? $post['user_refer_id']:null;
		$result = $this->ghUser->insert($data);
		$this->session->set_flashdata('fast_notify', [
			'message' => 'Tạo thành viên: <strong>'.$data['account_id'].'<strong> thành công ',
			'status' => 'success'
		]);
		return redirect('admin/list-user');
	}


	public function edit(){
        $user_profile = $this->ghUser->getFirstByAccountId($this->input->get('account_id'));
        /*--- Load View ---*/
        $list_role = $this->ghRole->getAll();
        $this->load->view('components/header');
        $this->load->view('user/edit', [
            'user' =>$user_profile,
            'list_role' => $list_role,
            'list_user' => $this->ghUser->get(['account_id <> ' => $this->input->get('account_id')]),
        ]);
        $this->load->view('components/footer');
    }

	public function changePassword() {
		$user_profile = $this->ghUser->get(['account_id' => $this->auth['account_id']]);
		/*--- Load View ---*/
		if(isset($_POST['submitForm'])) {
			$new_password = $this->input->post('password');
			$this->ghUser->updateById($this->auth['id'], ['password' => $new_password]);
			$user_profile = $this->ghUser->get(['account_id' => $this->auth['account_id']]);
		}
		$this->load->view('components/header', ['menu' => $this->menu]);
		$this->load->view('user/change-password', $user_profile[0]);
		$this->load->view('components/footer');
	}

	// Ajax
	public function update() {
		$user_id = $this->input->post('user_id');
		$field_value = $this->input->post('field_value');
		$field_name = $this->input->post('field_name');

		if(!empty($user_id) and !empty($field_value)) {
			$data = [
				$field_name => $field_value
			];
			$result = $this->ghUser->updateById($user_id, $data);
			echo json_encode(['status' => $result]); die;
		}
		echo json_encode(['status' => false]); die;
	}

	public function updateEditable() {
		$user_id = $this->input->post('pk');
		$field_name = $this->input->post('name');
		$field_value = $this->input->post('value');

		if(!empty($user_id) and !empty($field_name)) {
			if($field_name == 'authorised_user_id') {
				if(empty($field_value)) {
					$field_value = null;
				} else {
					$field_value = $this->auth['account_id'];
				}
			}
			
			if($field_name == 'date_of_birth' || $field_name == 'time_joined') {
				if(empty($field_value)) {
					$field_value = null;
				} else {
					$field_value = str_replace('/', '-', $field_value);
					$field_value = strtotime((string)$field_value);
				}
			}
			$data = [
				$field_name => $field_value
			];
			$old_user = $this->ghUser->getById($user_id);
			$old_log = json_encode($old_user[0]);
			
			$result = $this->ghUser->updateById($user_id, $data);
			
			$modified_user = $this->ghUser->getById($user_id);
			$modified_log = json_encode($modified_user[0]);
			
			$log = [
				'table_name' => 'gh_user',
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

	public function getRole() {
		$list_role = $this->ghRole->getAll();
		$result = [];
		foreach($list_role as $role) {
			$result[] = ["value" => $role['code'], "text" => $role["name"]];
		}
		$pk = $this->input->post('pk');
		if(isset($pk)) {
			return die($this->updateEditable()); 
		}
		echo json_encode($result); die;
	}

	public function delete(){
		$user_id = $this->input->post('user_id');
	
		if(!empty($user_id)) {
			$old_user = $this->ghUser->getById($user_id);
			$log = [
				'table_name' => 'gh_user',
				'old_content' => null,
				'modified_content' => json_encode($old_user[0]),
				'time_insert' => time(),
				'action' => 'delete'
			];

			// call model
			$tracker = $this->ghActivityTrack->insert($log);
			$result = $this->ghUser->delete($user_id);
			
			if($result > 0) {
				echo json_encode(['status' => true]); die;
			}
			echo json_encode(['status' => false]); die;
		}
		echo json_encode(['status' => false]); die;
	}


	public function getSelectUser(){
        $list = $this->ghUser->get(['account_id >=' => 171020000, 'name <>' => "", "active" => "YES"]);
        $result = [];
        foreach($list as $item) {
            $result[] = ["value" => $item['account_id'], "text" => $item["name"]];
        }
        echo json_encode($result); die;
    }


    public function showOverviewGetNewApartment(){
	    $account_id = $this->input->get('account');
	    $user = $this->ghUser->getFirstByAccountId($account_id);
	    $list_apartment = $this->ghApartment->get(['user_collected_id' => $account_id]);

        $this->load->view('components/header');
        $this->load->view('user/show-overview-get-new-apartment', [
            'list_apartment' => $list_apartment,
            'cb_user' => $this->libUser->cb($account_id)
        ]);
        $this->load->view('components/footer');
    }
    public function showOverviewReferNewUser(){
	    $account_id = $this->input->get('account');
	    $user = $this->ghUser->getFirstByAccountId($account_id);

	    $list_refer = $this->ghUser->get(['user_refer_id' => $account_id]);

        $this->load->view('components/header');
        $this->load->view('user/show-overview-refer-new-user', [
            'list_refer' => $list_refer,
            'cb_user' => $this->libUser->cb($account_id)
        ]);
        $this->load->view('components/footer');
    }

}

/* End of file role.php */
/* Location: ./application/controllers/role-manager/role.php */
<?php 
$check_edit = false;
if(isYourPermission($this->current_controller, 'updateEditable', $this->permission_set)){
    $check_edit = true;
}

?>

<div class="wrapper">
<div class="sk-wandering-cubes" style="display:none" id="loader">
    <div class="sk-cube sk-cube1"></div>
    <div class="sk-cube sk-cube2"></div>
</div>
    <div class="container-fluid">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group pull-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="#">test</a></li>
                            <li class="breadcrumb-item"><a href="#">Extra Pages</a></li>
                            <li class="breadcrumb-item active">Starter</li>
                        </ol>
                    </div>
                    <h3 class="page-title">Danh sách Hợp đồng</h3>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->
        <?php if($this->session->has_userdata('fast_notify')) {
                $flash_mess = $this->session->flashdata('fast_notify')['message'];
                $flash_status = $this->session->flashdata('fast_notify')['status'];
                unset($_SESSION['fast_notify']);
            }  
        ?>
        <div class="contract-alert"></div>
        <div class="row">
        <?php $this->load->view('components/list-navigation'); ?>
            <div class="col-md-12">
                <h3>Danh sách hợp đồng tháng hiện tại  </h3>
                <div class="row">
                    <?php if(isYourPermission($this->current_controller,'syncStatusExpire', $this->permission_set)): ?>
                    <div class="col-md-12">
                        <a href="/admin/contract/sync-status-expire" class="btn btn-info">Cập nhật trạng thái hợp đồng</a>
                    </div>
                    <?php endif; ?>
                </div>
                <div class=" mt-md-2 card-box table-responsive">
                    <table class="table-contract table table-bordered">
                        <thead>
                        <tr>
                            <th># ID Hợp Đồng</th>
                            <th width="350px">Khách thuê</th>
                            <th>Giá thuê</th>
                            <th>Ngày ký</th>
                            <th>Ngày hết hạn</th>
                            <th class="text-center">Thời hạn</th>
                            <th width="200px">Ghi chú HD</th>
                            <th class="text-center" width="200px">Tình trạng</th>
                            <th class="text-center">Hình Ảnh</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($list_contract as $row ):
                                    if($row['time_check_in'] < strtotime(date('01-m-Y'))) {
                                        continue;
                                    }
                            ?>
                            <?php $service = json_decode($row['service_set'], true) ?>
                            <tr>
                                <td>
                                    <div>
                                        #<?= (10000 + $row['id']) ?>
                                    </div>
                                </td>
                                <td>
                                <div class="text-muted"><?= $libCustomer->getNameById($row['customer_id']).' - '. $libCustomer->getPhoneById($row['customer_id']) ?> </div>
                                <div class="font-weight-bold text-primary">
                                    <?php 
                                        $apartment = $ghApartment->get(['id' => $row['apartment_id']])
                                    ?>
                                        <?= $apartment ? $apartment[0]['address_street']:'' ?>
                                    </div>
                                    <h6 class="text-danger">
                                            <?= $row['room_code'] ? 'mã phòng: '.$row['room_code'] : null ?>
                                    </h6>
                                </td>
                                <td>
                                    <div class="contract-room_price font-weight-bold" 
                                        data-pk="<?= $row['id'] ?>"
                                        data-value="<?= $row['room_price'] ?>"
                                        data-name="room_price">
                                        <?= number_format($row['room_price']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?=$row['time_check_in'] ? date('d/m/Y',$row['time_check_in']):'-' ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="contract-time_expire"
                                        data-pk="<?= $row['id'] ?>"
                                        data-value="<?= date('d/m/Y',$row['time_expire']) ?>"
                                        data-name="time_expire">
                                        <?=$row['time_expire'] ? date('d/m/Y',$row['time_expire']):'-' ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div>
                                        <?=$row['number_of_month'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?=$row['note'] ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div>
                                    <?php 
                                        $statusClass = 'muted';
                                        if($row['status'] == 'Active') {
                                            $statusClass = 'success';
                                        }
                                        if($row['status'] == 'Pending') {
                                            $statusClass = 'warning';
                                        }
                                        if($row['status'] == 'Cancel') {
                                            $statusClass = 'danger';
                                        }
                                    ?>
                                    <span class="badge badge-<?= $statusClass ?> badge-pill" style="font-size:100%">
                                    <?= $label_apartment['contract.'.$row['status']] ?>
                                    </span>
                                        
                                    </div>
                                </td>
                                
                                <?php 
                                    $image = $ghImage->getContract($row['id'])
                                ?>
                                <td>
                                <?php  if($image): ?>
                                    <div class="d-flex justify-content-center">
                                        <a href="<?= '/media/contract/'.$image[0]['name'] ?>" target="_blank">
                                            Hình Ảnh
                                        </a>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>      
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card-box table-responsive">
                    <table class="table-contract table table-bordered">
                        <thead>
                        <tr>
                            <th># ID Hợp Đồng</th>
                            <th width="350px">Khách thuê</th>
                            <th>Giá thuê</th>
                            <th>Ngày ký</th>
                            <th>Ngày hết hạn</th>
                            <th class="text-center">Thời hạn</th>
                            <th width="200px">Ghi chú HD</th>
                            <th class="text-center" width="200px">Tình trạng</th>
                            <th class="text-center">Tùy Chọn</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($list_contract as $row ): ?>
                            <?php $service = json_decode($row['service_set'], true) ?>
                            <tr>
                                <td>
                                    <div>
                                        #<?= (10000 + $row['id']) ?>
                                    </div>
                                </td>
                                <td>
                                <div class="text-muted"><?= $libCustomer->getNameById($row['customer_id']).' - '. $libCustomer->getPhoneById($row['customer_id']) ?> </div>
                                <div class="font-weight-bold text-primary">
                                    <?php 
                                        $apartment = $ghApartment->get(['id' => $row['apartment_id']])
                                    ?>
                                        <?= $apartment ? $apartment[0]['address_street']:'' ?>
                                    </div>
                                    <h6 class="text-danger">
                                         <?= $row['room_code'] ? 'mã phòng: '.$row['room_code'] : null ?>
                                    </h6>
                                </td>
                                <td>
                                    <div class="contract-room_price font-weight-bold" 
                                        data-pk="<?= $row['id'] ?>"
                                        data-value="<?= $row['room_price'] ?>"
                                        data-name="room_price">
                                        <?= number_format($row['room_price']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?=$row['time_check_in'] ? date('d/m/Y',$row['time_check_in']):'-' ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="contract-time_expire"
                                        data-pk="<?= $row['id'] ?>"
                                        data-value="<?= date('d/m/Y',$row['time_expire']) ?>"
                                        data-name="time_expire">
                                        <?=$row['time_expire'] ? date('d/m/Y',$row['time_expire']):'-' ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div>
                                        <?=$row['number_of_month'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?=$row['note'] ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div>
                                    <?php 
                                        $statusClass = 'muted';
                                        if($row['status'] == 'Active') {
                                            $statusClass = 'success';
                                        }
                                        if($row['status'] == 'Pending') {
                                            $statusClass = 'warning';
                                        }
                                        if($row['status'] == 'Cancel') {
                                            $statusClass = 'danger';
                                        }
                                    ?>
                                    <span class="badge badge-<?= $statusClass ?> badge-pill" style="font-size:100%">
                                    <?= $label_apartment['contract.'.$row['status']] ?>
                                    </span>
                                        
                                    </div>
                                </td>
                                <?php 
                                    $image = $ghImage->getContract($row['id'])
                                   
                                ?>
                                <td>
                                <?php  if($image): ?>
                                    <div class="d-flex justify-content-center">
                                        <a href="<?= '/media/contract/'.$image[0]['name'] ?>" target="_blank">
                                            Hình Ảnh
                                        </a>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>      
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end row -->

    </div> <!-- end container -->
</div>
<!-- end wrapper -->

<script type="text/javascript">
    commands.push(function() {
        $(document).ready(function() {
            $('.table-contract').DataTable({
                "pageLength": 10,
                'pagingType': "full_numbers",
                responsive: true,
                "fnDrawCallback": function() {
                    // x editable
                    <?php if($check_edit): ?>
                    $('.contract-room_price').editable({
                        type: "number",
                        url: '<?= base_url() ?>admin/update-contract-editable',
                        inputclass: '',
                        success: function(response) {
                            var data = JSON.parse(response);
                            if(data.status == true) {
                                $('.contract-alert').html(notify_html_success);
                            } else {
                                $('.contract-alert').html(notify_html_fail);
                            }
                        }
                    });
                    $('.contract-time_expire').editable({
                        placement: 'right',
                        type: 'combodate',
                        template:"D / MM / YYYY",
                        format:"DD-MM-YYYY",
                        viewformat:"DD-MM-YYYY",
                        mode: 'inline',
                        combodate: {
                            firstItem: 'name',
                            maxYear: '2030',
                            minYear: '2017'
                        },
                        inputclass: 'form-control-sm',
                        url: '<?= base_url() ?>admin/update-contract-editable',
                        success: function(response) {
                            var data = JSON.parse(response);
                            if(data.status == true) {
                                $('.contract-alert').html(notify_html_success);
                            } else {
                                $('.contract-alert').html(notify_html_fail);
                            }
                        }
                    });
                    <?php endif; ?>
                } // end fnDrawCallback
            });
            
            

            $('.delete-contract').click(function(){
                var this_id = $(this).attr('id');
                var this_click = $(this);
                var matches = this_id.match(/(\d+)/);
                var district_id = matches[0];
                if(district_id > 0) {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url() ?>admin/delete-contract',
                        data: {district_id: district_id},
                        success: function(response) {
                            var data = JSON.parse(response);
                            if(data.status == true) {
                                $('.contract-alert').html(notify_html_success);
                                this_click.parents('tr').remove();

                            } else {
                                $('.contract-alert').html(notify_html_fail);
                            }
                        }
                    });
                }
            });
        });
    });
</script>
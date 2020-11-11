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

                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="district-alert"></div>
        <div class="row">
            <div class="col-12 col-md-5 offset-md-1 ">
                <div class="card-box table-responsive shadow">
                    <h3 class="text-danger text-center">Bảng Cơ Chế Thu Nhập: Chuyên
                        Viên</h3>
                    <table id="" class="table table-data">
                        <thead>
                        <tr>
                            <th>Điều Kiện Giá Thuê / HĐ</th>
                            <th class="text-right" width="180px"> Thu nhập / mỗi
                                tháng thuê </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($list_income_mechanism as $item ):
                            if($item['role_code'] !== 'consultant'){
                                continue;
                            }
                            ?>
                            <?php
                            $rank_color = '';
                            $rank_color = $item['income_unit'] >= 5000000 ? 'bg-warning text-light' :
                                $rank_color;
                            $rank_color = $item['income_unit'] >= 10000000 ? 'bg-info text-light' :
                                $rank_color;
                            $rank_color = $item['income_unit'] >= 15000000 ? 'bg-success text-light' :
                                $rank_color;

                            ?>

                            <tr class="<?= $rank_color ?>">
                                <td >
                                    <div> <?= number_format($item['income_unit']) ?> </div>
                                </td>
                                <td >
                                    <div> <?= number_format($item['income_final']) ?> </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-5 ">
                <div class="card-box table-responsive shadow">
                    <h3 class="text-danger text-center">Bảng Cơ Chế Thu Nhập: Cộng
                        Tác Viên</h3>
                    <table id="" class="table table-data">
                        <thead>
                        <tr>
                            <th>Điều Kiện Giá Thuê / HĐ</th>
                            <th class="text-right" width="180px"> Thu nhập / mỗi
                                tháng thuê </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($list_income_mechanism as $item ):
                            if($item['role_code'] !== 'collaborators'){
                                continue;
                            }
                            ?>
                            <?php
                            $rank_color = '';
                            $rank_color = $item['income_unit'] >= 5000000 ? 'bg-warning text-light' :
                                $rank_color;
                            $rank_color = $item['income_unit'] >= 10000000 ? 'bg-info text-light' :
                                $rank_color;
                            $rank_color = $item['income_unit'] >= 15000000 ? 'bg-success text-light' :
                                $rank_color;

                            ?>

                            <tr class="<?= $rank_color ?>">
                                <td >
                                    <div> <?= number_format($item['income_unit']) ?> </div>
                                </td>
                                <td >
                                    <div> <?= number_format($item['income_final']) ?> </div>
                                </td>
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

<script>

    commands.push(function(){
        $('.table-data').DataTable({
            "pageLength": 10,
            'pagingType': "full_numbers",
            responsive: true,});
    });
</script>
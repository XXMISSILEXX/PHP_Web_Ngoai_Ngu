<?php

// import configs dùng chung
require_once __DIR__ . "/../../configs/config.php";
require_once __DIR__ . "/../../configs/function.php";

// set title
$title = 'Quản lý khóa học';

// import các component layout
require_once __DIR__ . "/Header.php";
require_once __DIR__ . "/Sidebar.php";

/**
 * Xóa đánh giá khóa học của người học
 */
if (isset($_GET["delete_danh_gia"])) {
    $getTaiKhoan = $_GET["taiKhoan"];
    $getKhoaHoc = $_GET["khoaHoc"];
    $Database->query("DELETE FROM danhgiakhoahoc WHERE TaiKhoan = '" . $getTaiKhoan . "' AND MaKhoaHoc = '" . $getKhoaHoc . "' ");
    admin_msg_success("Xóa thành công", "courses", 1000);
}

/**
 * Thêm khóa học mới
 */
if (isset($_POST['btnThemKhoaHoc'])) {

    // Kiểm tra rỗng
    if (empty($_POST['tenKhoaHoc']) || empty($_POST['linkAnhKhoaHoc']) || empty($_POST['moTaKhoaHoc'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }
    $tenKhoaHoc = ($_POST['tenKhoaHoc']);
    $linkAnh = ($_POST['linkAnhKhoaHoc']);
    $moTa = ($_POST['moTaKhoaHoc']);

    // Insert
    $Database->insert("khoahoc", array(
        'TenKhoaHoc' => $tenKhoaHoc,
        'LinkAnh' => $linkAnh,
        'NguoiTao' => $_SESSION["account"],
        'NoiDung' => $moTa,

    ));

    // Thông báo thành công sau 1s
    admin_msg_success("Thêm thành công", "", 1000);
}

?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý khóa học</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <!-- Hiển thị danh sách khóa học -->
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">DANH SÁCH KHÓA HỌC</h3>
                        <!-- Nút điều khiển collapse -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_khoahoc" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th title="Số thứ tự">STT</th>
                                        <th>Mã khóa học</th>
                                        <th>Tên khóa học</th>
                                        <th>Ảnh khóa học</th>
                                        <th>Số học viên</th>
                                        <th>Người tạo</th>
                                        <th>Ngày tạo</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
// Số thứ tự
$i = 0;

// Danh sách khóa học sắp xếp theo mã khóa học tăng dần
foreach ($Database->get_list(" SELECT * FROM khoahoc ORDER BY MaKhoaHoc ASC ") as $row) {
    ?>
                                        <tr>
                                            <!-- STT -->
                                            <td><?=$i++;?></td>

                                            <!-- Mã khóa học -->
                                            <td><?=$row['MaKhoaHoc'];?></td>

                                            <!-- Tên khóa học -->
                                            <td><?=$row['TenKhoaHoc'];?></td>

                                            <!-- Ảnh khóa học -->
                                            <td><img style="border: 1px solid; width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" src="<?=$row['LinkAnh'];?>" /></td>

                                            <!-- Số học viên của khóa học -->
                                            <td><?=$Database->num_rows("SELECT * FROM dangkykhoahoc WHERE MaKhoaHoc = '" . $row["MaKhoaHoc"] . "'  ");?></td>

                                            <!-- Người tạo -->
                                            <td><a href="<?=BASE_URL('admin/users/edit/');?><?=$row['NguoiTao'];?>"><?=$row['NguoiTao'];?></a></td>

                                            <!-- Ngày tạo -->
                                            <td><span class="badge badge-dark"><?=$row['ThoiGianTaoKhoaHoc'];?></span></td>

                                            <!-- Trạng thái -->
                                            <td><?=displayStatusAccount($row['TrangThaiKhoaHoc']);?></td>

                                            <!-- ACTION -->
                                            <td>
                                                <a style="color: white; display: block;" href="<?=BASE_URL('admin/courses/edit/');?><?=$row['MaKhoaHoc'];?>" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i><span>Sửa</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thêm khóa học -->
            <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">THÊM KHÓA HỌC</h3>
                        <!-- Nút điều khiển collapse -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <!-- Tên khóa học mới -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Tên khóa học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="tenKhoaHoc" value="">
                                    </div>
                                </div>
                            </div>
                            <!-- Ảnh khóa học -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Link ảnh khóa học</label>
                                <div class="col-sm-6">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="hinhAnh" name="linkAnhKhoaHoc" value="">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                <div class="btn btn-primary btn-block waves-effect" id="uploadHinhAnh">Chọn ảnh</div>
                                </div>
                            </div>
                            <!-- Mô tả khóa học -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mô tả khóa học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="moTaKhoaHoc" value="">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="btnThemKhoaHoc" class="btn btn-primary btn-block waves-effect">
                                <span>XÁC NHẬN</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách đánh giá các khóa học -->
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách đánh giá các khóa học</h3>
                        <!-- Nút điều khiển collapse -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_danhgiakhoahoc" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th title="Số thứ tự">STT</th>
                                        <th>Tài khoản</th>
                                        <th>Khóa học</th>
                                        <th>Nội dung</th>
                                        <th>Biểu cảm</th>
                                        <th>Thời gian</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
// STT
$i = 0;
// Danh sách đánh giá khoahoc từ mới đến cũ
foreach ($Database->get_list(" SELECT * FROM danhgiakhoahoc A inner join khoahoc B on A.MaKhoaHoc = B.MaKhoaHoc  ORDER BY A.ThoiGian DESC ") as $row) {
    ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><a href="<?=BASE_URL('admin/users/edit/');?><?=$row['TaiKhoan'];?>"><?=$row['TaiKhoan'];?></a></td>
                                            <td><?=$row['TenKhoaHoc'];?></td>
                                            <td><?=$row['NoiDungDanhGia'];?></td>

                                            <?php
// Rating
    $rating = $row['Rating'];

    // Chuyển thành icon
    switch ($rating) {
        case 1:
            $icon = "Like-icon";
            break;
        case 2:
            $icon = "Heart-icon";
            break;
        case 3:
            $icon = "Smile-icon";
            break;
        case 4:
            $icon = "Sad-icon";
            break;
        case 5:
            $icon = "Angry-icon";
            break;
    }
    // Đường dẫn đến icon
    $icon = BASE_URL("/") . "/assets/img/" . $icon . ".svg";
    ?>
                                            <td><img src="<?=$icon;?>" style="width: 40px; height: 40px; object-fit: cover;"/></td>
                                            <td><span class="badge badge-dark px-3"><?=$row['ThoiGian'];?></span></td>
                                            <td>
                                                <a style="color: white; display: block;" onclick="confirmDelete('<?=$row['TaiKhoan'];?>', '<?=$row['MaKhoaHoc'];?>', '<?=$row['TenKhoaHoc'];?>', '<?=$row['NoiDungDanhGia'];?>');" class="btn btn-primary">
                                                <i class="fas fa-trash"></i>
                                                    <span>Xóa</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>



<script>
    /**
     * Khởi tạo DataTable cho các phần tử "datatable" bằng JQuery
     * - Phân trang
     * - Tìm kiếm
     * - Sắp xếp
     * - Responsive ...
     */
    $(function() {
        $("#datatable_khoahoc").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $("#datatable_danhgiakhoahoc").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    /**
     * Xử lý upload file lên cloudinary
     */
    $(function() {
        // Khởi tạo widget hình ảnh
        let myWidgetHinhAnh = cloudinary.createUploadWidget({
            cloudName: 'diih7pze7', /* tên cloudinary account */
            uploadPreset: 'zdug8flf', /* preset sẽ quyết định cách xử lý upload */
            folder: 'hinhanh', /* chỉ định folder lưu trữ. (nếu chưa có sẽ tự động tạo). */
        }, (error, result) => {
            // Tải lên thành công, không bị lỗi
            if (!error && result && result.event === "success") {
                console.log('Done! Here is the image info: ', result.info);
                $("#hinhAnh").val(result.info.url);
            }
        })

        // Thêm sự kiện click cho button uploadHinhAnh
        document.getElementById("uploadHinhAnh").addEventListener("click", function() {
            console.log(myWidgetHinhAnh.open());
        }, false);
    })

    /**
     * Xóa đánh giá khóa học của người học
     * @param {*} taiKhoan - tên tài khoản người dùng
     * @param {*} tenKhoaHoc - tên khóa học cần xóa
     * @param {*} maKhoaHoc - mã khóa học
     */
    function confirmDelete(taiKhoan, maKhoaHoc, tenKhoaHoc, noiDungDanhGia) {
        // Tạo popup bằng thư viện SweetAlert2
        Swal.fire({
            title: 'Bạn có thực sự muốn xóa đánh giá: "' + noiDungDanhGia + '" của người dùng "' + taiKhoan + '" trong khóa học "' + tenKhoaHoc + '" không?',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chọn Xóa
                window.location.href = `?delete_danh_gia&taiKhoan=${taiKhoan}&khoaHoc=${maKhoaHoc}`;
            }
        });
    }
</script>

<?php
// import component layout
require_once __DIR__ . "/Footer.php";
?>
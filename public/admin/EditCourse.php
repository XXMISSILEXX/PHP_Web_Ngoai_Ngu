<?php
/**
 * import configs dùng chung
 */
require_once __DIR__ . "/../../configs/config.php";
require_once __DIR__ . "/../../configs/function.php";

// Set title
$title = 'Quản lý khóa học';

/**
 * import components layout
 */
require_once __DIR__ . "/Header.php";
require_once __DIR__ . "/Sidebar.php";
?>


<?php

/**
 * Kiểm tra liên kết đến trang, MaKhoaHoc có tồn tại hay không
 */
if (isset($_GET['id'])) {
    $row = $Database->get_row(" SELECT * FROM `khoahoc` WHERE `MaKhoaHoc` = '" . check_string($_GET['id']) . "'  ");
    if (!$row) {
        admin_msg_error("Khóa học này không tồn tại", BASE_URL(''), 500);
    }
} else {
    admin_msg_error("Liên kết không tồn tại", BASE_URL(''), 0);
}

/**
 * Cập nhật thông tin về khóa học
 */
if (isset($_POST['btnSave'])) {

    // Kiểm tra các trường thông tin không được bỏ trống
    if (empty($_POST['tenKhoaHoc']) || empty($_POST['linkAnh']) || empty($_POST['noiDung']) || empty($_POST['trangThai'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }

    // Lấy thông tin từ POST REQUEST
    $tenKhoaHoc = ($_POST['tenKhoaHoc']);
    $linkAnh = ($_POST['linkAnh']);
    $noiDung = ($_POST['noiDung']);
    $trangThai = ($_POST['trangThai']);

    // Cập nhật thông tin khóa học vào DB
    $Database->update("khoahoc", array(
        'TenKhoaHoc' => $tenKhoaHoc,
        'LinkAnh' => $linkAnh,
        'NoiDung' => $noiDung,
        'TrangThaiKhoaHoc' => $trangThai,

    ), " `MaKhoaHoc` = '" . $_GET['id'] . "' ");

    // Thông báo thành công sau 1s
    admin_msg_success("Thay đổi thành công", "", 1000);
}

/**
 * Thêm bài học mới
 */
if (isset($_POST['btnThemBaiHoc'])) {

    // Kiểm tra các trường thông tin không được để trống
    if (empty($_POST['maBaiHoc']) || empty($_POST['tenBaiHoc'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }

    // Lấy thông tin của POST REQUEST
    $maKhoaHoc = ($_GET['id']);
    $maBaiHoc = check_string($_POST['maBaiHoc']);
    $tenBaiHoc = ($_POST['tenBaiHoc']);

    // Thêm bài học mới vào DB
    $Database->insert("baihoc", array(
        'MaKhoaHoc' => $maKhoaHoc,
        'MaBaiHoc' => $maBaiHoc,
        'TenBaiHoc' => $tenBaiHoc,
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
                    <h1>Chỉnh sửa khóa học</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <!-- Chỉnh sửa khóa học hiện tại -->
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CHỈNH SỬA KHÓA HỌC</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tên khóa học</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="tenKhoaHoc" value="<?=$row['TenKhoaHoc'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Link Ảnh</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="hinhAnh" name="linkAnh" value="<?=$row['LinkAnh'];?>">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <a id="uploadHinhAnh" class="btn btn-primary btn-block waves-effect">Chọn ảnh</a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nội dung</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="noiDung" value="<?=$row['NoiDung'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Số học viên</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" value="<?=$Database->num_rows("SELECT * FROM dangkykhoahoc WHERE MaKhoaHoc = '" . $row["MaKhoaHoc"] . "'  ");?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" name="trangThai">
                                        <!-- Trạng thái hiện tại của khóa học -->
                                        <option value="<?=$row['TrangThaiKhoaHoc'];?>">
                                            <?php
if ($row['TrangThaiKhoaHoc'] == "1") {
    echo 'Hoạt động';
}
if ($row['TrangThaiKhoaHoc'] == "0") {
    echo 'Banned';
}
?>
                                        </option>
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Banned</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ngày tạo</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" value="<?=$row['ThoiGianTaoKhoaHoc'];?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="btnSave" class="btn btn-primary btn-block waves-effect">
                                <span>LƯU</span>
                            </button>
                            <a type="button" href="<?=BASE_URL('admin/courses');?>" class="btn btn-danger btn-block waves-effect">
                                <span>TRỞ LẠI</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Thêm bài học mới cho khóa học -->
            <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">THÊM BÀI HỌC</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã khóa học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <!-- Select option tất cả mã khóa học -->
                                        <select class="custom-select" name="maKhoaHoc" disabled>
                                            <!-- Mã khóa học hiện tại -->
                                            <option value="<?=$row['MaKhoaHoc'];?>" selected="selected">
                                                <?=$row["MaKhoaHoc"]?>
                                            </option>

                                            <!-- Tất cả mã khóa học -->
                                            <?php
foreach ($Database->get_list(" select * from khoahoc order by MaKhoaHoc asc") as $optionKhoaHoc) {
    ?>
                                                                                            <option value="<?=$optionKhoaHoc["MaKhoaHoc"]?>"><?=$optionKhoaHoc["MaKhoaHoc"]?></option>
                                                                                        <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php
/**
 * Tạo mã bài học mới của khóa học này = mã bài học mới nhất của khóa học này + 1
 */
$maBaiHocMoi = 0;
$getMaBaiHocMoi = $Database->get_row("select * from baihoc where MaKhoaHoc = '" . $row["MaKhoaHoc"] . "'  order by MaBaiHoc desc limit 1");
if ($getMaBaiHocMoi) {
    $maBaiHocMoi = $getMaBaiHocMoi["MaBaiHoc"] + 1;
}
?>

                            <!-- Mã bài học mới -->
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã bài học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="maBaiHoc" value="<?=$maBaiHocMoi?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Tên bài học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="tenBaiHoc" value="">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="btnThemBaiHoc" class="btn btn-primary btn-block waves-effect">
                                <span>XÁC NHẬN</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Hiển thị danh sách bài học của khóa học -->
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách bài học</h3>
                        <!-- Nút điều khiển collapse -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th title="Số thứ tự">STT</th>
                                        <th>Mã bài học</th>
                                        <th>Tên bài học</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
// Số thứ tự
$i = 0;

// Danh sách bài học của khóa học sắp xếp theo mã bài học tăng dần
foreach ($Database->get_list(" SELECT * FROM baihoc A inner join khoahoc B on A.MaKhoaHoc = B.MaKhoaHoc WHERE A.MaKhoaHoc = '" . $row['MaKhoaHoc'] . "' ORDER BY MaBaiHoc ASC ") as $row) {
    ?>
                                        <tr>
                                            <!-- Số thứ tự -->
                                            <td><?=$i++;?></td>
                                            <!-- Mã bài học -->
                                            <td><?=$row['MaBaiHoc'];?></td>
                                            <!-- Tên bài học -->
                                            <td><?=$row['TenBaiHoc'];?></td>
                                            <!-- Trạng thái bài học -->
                                            <td><?=displayStatusAccount($row['TrangThaiBaiHoc']);?></td>
                                            <!-- Thời gian tạo -->
                                            <td><span class="badge badge-dark px-3"><?=$row['ThoiGianTaoBaiHoc'];?></span></td>
                                            <!-- Hành động -->
                                            <td>
                                                <a type="button" href="<?=BASE_URL('admin/courses/' . $row['MaKhoaHoc'] . '/lesson/edit/');?><?=$row['MaBaiHoc'];?>" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                                    <span>Sửa</span>
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
    </section>
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
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    /**
     * Upload file
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

</script>

<!-- import components layout -->
<?php
require_once __DIR__ . "/Footer.php";
?>
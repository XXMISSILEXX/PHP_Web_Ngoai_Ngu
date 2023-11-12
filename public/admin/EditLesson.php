<?php
/**
 * import configs dùng chung
 */
require_once __DIR__ . "/../../configs/config.php";
require_once __DIR__ . "/../../configs/function.php";

// Set title
$title = 'Quản lý bài học';

/**
 * import components layout
 */
require_once __DIR__ . "/Header.php";
require_once __DIR__ . "/Sidebar.php";
?>

<?php

/**
 * Kiểm tra liên kết đến trang, bài học chỉ định có tồn tại hay không?
 */
if (isset($_GET['maBaiHoc']) && isset($_GET['maKhoaHoc'])) {
    $row = $Database->get_row(" SELECT * FROM baihoc A inner join khoahoc B on A.MaKhoaHoc = B.MaKhoaHoc WHERE A.MaKhoaHoc = '" . check_string($_GET['maKhoaHoc']) . "'  and A.MaBaiHoc = '" . check_string($_GET['maBaiHoc']) . "'  ");
    if (!$row) {
        admin_msg_error("Bài học này không tồn tại", BASE_URL(''), 500);
    }
} else {
    admin_msg_error("Liên kết không tồn tại", BASE_URL(''), 0);
}

/**
 * Chỉnh sửa thông tin bài học
 */
if (isset($_POST['btnSave']) && $row) {
    // Kiểm tra các thường không được bỏ trống
    if (empty($_POST['tenBaiHoc']) || empty($_POST['trangThai'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }

    // Lấy thông tin của POST REQUEST
    $tenBaiHoc = ($_POST['tenBaiHoc']);
    $trangThai = check_string($_POST['trangThai']);

    $Database->update(
        "baihoc",
        array(
            'TenBaiHoc' => $tenBaiHoc,
            'TrangThaiBaiHoc' => $trangThai,
        ),
        " `MaKhoaHoc` = '" . $row['MaKhoaHoc'] . "' and `MaBaiHoc` = '" . $row['MaBaiHoc'] . "'  "
    );

    // Thông báo thành công sau 1s
    admin_msg_success("Thay đổi thành công", "", 1000);
}

/**
 * Thêm từ vựng mới vào bài học
 */
if (isset($_POST['btnThemTuVung']) && $row) {

    // Kiểm tra các trường không được bỏ trống
    if (empty($_POST['maTuVung']) || empty($_POST['noiDungTuVung']) || empty($_POST['dichNghia']) || empty($_POST['diem']) || empty($_POST['hinhAnh']) || empty($_POST['amThanh'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }

    // Lấy thông tin của POST REQUEST
    $maKhoaHoc = $_GET['maKhoaHoc'];
    $maBaiHoc = $_GET['maBaiHoc'];
    $maTuVung = ($_POST['maTuVung']);
    $noiDungTuVung = ($_POST['noiDungTuVung']);
    $dichNghia = ($_POST['dichNghia']);
    $diem = ($_POST['diem']);
    $hinhAnh = ($_POST['hinhAnh']);
    $amThanh = ($_POST['amThanh']);

    // Insert vào DB
    $Database->insert(
        "tuvung",
        array(
            'MaKhoaHoc' => $maKhoaHoc,
            'MaBaiHoc' => $maBaiHoc,
            'MaTuVung' => $maTuVung,
            'NoiDungTuVung' => $noiDungTuVung,
            'DichNghia' => $dichNghia,
            'Diem' => $diem,
            'HinhAnh' => $hinhAnh,
            'AmThanh' => $amThanh,
        )
    );

    // Thông báo thành công sau 1s
    admin_msg_success("Thêm thành công", "", 1000);
}
?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa bài học</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <!-- Chỉnh sửa thông tin bài học đã chỉ định -->
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CHỈNH SỬA BÀI HỌC</h3>
                        <!-- Nút điều khiển collapse -->
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
                                        <input type="text" class="form-control" name="tenKhoaHoc" value="<?=$row['TenKhoaHoc'];?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tên bài học</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="tenBaiHoc" value="<?=$row['TenBaiHoc'];?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" name="trangThai">
                                        <option value="<?=$row['TrangThaiBaiHoc'];?>">
                                            <?php
if ($row['TrangThaiBaiHoc'] == "1") {
    echo 'Hoạt động';
}
if ($row['TrangThaiBaiHoc'] == "0") {
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
                                        <input type="text" class="form-control" id="inputEmail3" value="<?=$row['ThoiGianTaoBaiHoc'];?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btnSave" class="btn btn-primary btn-block waves-effect">
                                <span>LƯU</span>
                            </button>
                            <a type="button" href="<?=BASE_URL('admin/courses/edit/' . $row["MaKhoaHoc"] . '');?>" class="btn btn-danger btn-block waves-effect">
                                <span>TRỞ LẠI</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Thêm từ vựng mới -->
            <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">THÊM TỪ VỰNG</h3>
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
                                            <option value="<?=$row['MaKhoaHoc'];?>" selected="selected">
                                                <?=$row["MaKhoaHoc"]?>

                                            </option>
                                            <?php
// Danh sách tất cả mã khóa học sắp xếp tăng dần
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

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã bài học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <!-- Select option tất cả mã bài học của khóa học -->
                                        <select class="custom-select" name="maBaiHoc" disabled>
                                            <option value="<?=$row['MaBaiHoc'];?>" selected="selected">
                                                <?=$row["MaBaiHoc"]?>

                                            </option>
                                            <?php
// Danh sách tất cả mã bài học của khóa học sắp xếp tăng dần
foreach ($Database->get_list(" select * from baihoc where MaKhoaHoc = '" . $row['MaKhoaHoc'] . "'  order by MaBaiHoc asc") as $optionBaiHoc) {
    ?>
                                                <option value="<?=$optionBaiHoc["MaBaiHoc"]?>"><?=$optionBaiHoc["MaBaiHoc"]?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php
/**
 * Sinh mã từ vựng mới = mã từ vựng mới nhất + 1
 */
$maTuVungMoi = 1;
$getMaTuVungMoi = $Database->get_row("select * from tuvung where MaKhoaHoc = '" . $row["MaKhoaHoc"] . "' and MaBaiHoc = '" . $row["MaBaiHoc"] . "' order by MaTuVung desc limit 1");
if ($getMaTuVungMoi) {
    $maTuVungMoi = $getMaTuVungMoi["MaTuVung"] + 1;
}
?>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã từ vựng</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="maTuVung" value="<?=$maTuVungMoi?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Nội dung từ vựng</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="noiDungTuVung" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Dịch nghĩa</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="dichNghia" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Điểm</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="diem" value="10">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Hình ảnh</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="hinhAnh" name="hinhAnh" value="">
                                        <div class="btn btn-primary btn-block waves-effect" id="uploadHinhAnh">Chọn ảnh</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Âm thanh</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="amThanh" name="amThanh" value="">
                                        <div class="btn btn-primary btn-block waves-effect" id="uploadAmThanh">Chọn âm thanh</div>

                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="btnThemTuVung" class="btn btn-primary btn-block waves-effect">
                                <span>XÁC NHẬN</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách các từ vựng</h3>
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
                                        <th>Mã khóa học</th>
                                        <th>Mã từ vựng</th>
                                        <th>Nội dung từ vựng</th>
                                        <th>Dịch nghĩa</th>
                                        <th>Điểm</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$i = 0;
foreach ($Database->get_list(" SELECT * FROM tuvung A inner join baihoc B on A.MaBaiHoc = B.MaBaiHoc and A.MaKhoaHoc = B.MaKhoaHoc WHERE A.MaKhoaHoc = '" . $row['MaKhoaHoc'] . "' and A.MaBaiHoc = '" . $row['MaBaiHoc'] . "' ORDER BY A.MaTuVung ASC ") as $row) {
    ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$row['MaBaiHoc'];?></td>
                                            <td><?=$row['MaKhoaHoc'];?></td>
                                            <td><?=$row['MaTuVung'];?></td>
                                            <td><?=$row['NoiDungTuVung'];?></td>
                                            <td><?=$row['DichNghia'];?></td>
                                            <td><?=$row['Diem'];?></td>
                                            <td><?=displayStatusAccount($row['TrangThaiTuVung']);?></td>

                                            <td><span class="badge badge-dark px-3"><?=$row['ThoiGianTaoTuVung'];?></span></td>
                                            <td>
                                                <a type="button" href="<?=BASE_URL('admin/courses/' . $row['MaKhoaHoc'] . '/lesson/' . $row['MaBaiHoc'] . '/vocabulary/edit/' . $row['MaTuVung'] . '');?>" class="btn btn-primary"><i class="fas fa-edit"></i>
                                                    <span>Sửa</span></a>
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
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    /**
     * Upload file lên cloudinary
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


        // Khởi tạo widget âm thanh
        let myWidgetAmThanh = cloudinary.createUploadWidget({
            cloudName: 'diih7pze7',
            uploadPreset: 'zdug8flf',
            folder: 'amthanh',
        }, (error, result) => {
            if (!error && result && result.event === "success") {
                console.log('Done! Here is the image info: ', result.info);
                $("#amThanh").val(result.info.url);
            }
        })

        // Thêm sự kiện click cho button uploadAmThanh
        document.getElementById("uploadAmThanh").addEventListener("click", function() {
            console.log(myWidgetAmThanh.open());
        }, false);
    })

    /**
     * Hàm này trả về một chuỗi là thẻ img của ảnh trên cloudinary có id được truyền vào
     *
     * @param {string} id - Id của hình ảnh trên cloudinary
     * @returns {void}
     */
    function processImage(id) {
        var options = {
            client_hints: true,
        };
        return '<img src="' + $.cloudinary.url(id, options) + '" style="width: 100%; height: auto"/>';
    }
</script>


<!-- import components layout -->
<?php
require_once __DIR__ . "/Footer.php";
?>
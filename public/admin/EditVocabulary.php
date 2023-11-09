<?php
require_once __DIR__ . "/../../configs/config.php";
require_once __DIR__ . "/../../configs/function.php";
$title = 'Quản lý từ vựng';
require_once __DIR__ . "/Header.php";
require_once __DIR__ . "/Sidebar.php";
?>
<?php
if (isset($_GET['maBaiHoc']) && isset($_GET['maKhoaHoc']) && isset($_GET['maTuVung'])) {
    $row = $Database->get_row(" SELECT * FROM tuvung WHERE MaKhoaHoc = '" . check_string($_GET['maKhoaHoc']) . "'  and MaBaiHoc = '" . check_string($_GET['maBaiHoc']) . "' and MaTuVung = '" . check_string($_GET['maTuVung']) . "'  ");
    if (!$row) {
        admin_msg_error("Từ vựng này không tồn tại", BASE_URL(''), 500);
    }
} else {
    admin_msg_error("Liên kết không tồn tại", BASE_URL(''), 0);
}
?>


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa từ vựng</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CHỈNH SỬA TỪ VỰNG</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Mã khóa học</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" value="<?=$row['MaKhoaHoc'];?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Mã bài học</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" value="<?=$row['MaBaiHoc'];?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Mã từ vựng</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" value="<?=$row['MaTuVung'];?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nội dung từ vựng</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="noiDungTuVung" value="<?=$row['NoiDungTuVung'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Dịch nghĩa</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="dichNghia" value="<?=$row['DichNghia'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Điểm</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="diem" value="<?=$row['Diem'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Hình ảnh</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="hinhAnh" name="hinhAnh" value="<?=$row['HinhAnh'];?>">
                                        <div class="btn btn-primary btn-block waves-effect" id="uploadHinhAnh">Upload</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Âm thanh</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="amThanh" name="amThanh" value="<?=$row['AmThanh'];?>">
                                        <div class="btn btn-primary btn-block waves-effect" id="uploadAmThanh">Upload</div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" name="trangThaiTuVung">
                                        <option value="<?=$row['TrangThaiTuVung'];?>">
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
                                        <input type="text" class="form-control" id="inputEmail3" value="<?=$row['ThoiGianTaoTuVung'];?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btnSave" class="btn btn-primary btn-block waves-effect">
                                <span>LƯU</span>
                            </button>
                            <a type="button" href="<?=BASE_URL('admin/courses/' . $row["MaKhoaHoc"] . '/lesson/edit/' . $row["MaBaiHoc"] . ' ');?>" class="btn btn-danger btn-block waves-effect">
                                <span>TRỞ LẠI</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">THÊM VÍ DỤ</h3>
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
                                        <select class="custom-select" name="maKhoaHoc" disabled>
                                            <option value="<?=$row['MaKhoaHoc'];?>" selected="selected">
                                                <?=$row["MaKhoaHoc"]?>

                                            </option>
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã bài học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <select class="custom-select" name="maBaiHoc" disabled>
                                            <option value="<?=$row['MaBaiHoc'];?>" selected="selected">
                                                <?=$row["MaBaiHoc"]?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã từ vựng</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <select class="custom-select" name="maTuVung" disabled>
                                            <option value="<?=$row['MaTuVung'];?>" selected="selected">
                                                <?=$row["MaTuVung"]?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
$maViDuMoi = 1;
$getMaViDuMoi = $Database->get_row("select * from vidu where MaKhoaHoc = '" . $row["MaKhoaHoc"] . "' and MaBaiHoc = '" . $row["MaBaiHoc"] . "' and  MaTuVung = '" . $row["MaTuVung"] . "' order by MaViDu desc limit 1");
if ($getMaViDuMoi) {
    $maViDuMoi = $getMaViDuMoi["MaViDu"] + 1;
}
?>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mã ví dụ</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="maViDu" value="<?=$maViDuMoi?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Câu ví dụ</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="cauViDu" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Dịch nghĩa</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="dichNghiaViDu" value="">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="btnThemViDu" class="btn btn-primary btn-block waves-effect">
                                <span>XÁC NHẬN</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách ví dụ</h3>
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
                                        <th>STT</th>
                                        <th>Mã ví dụ</th>

                                        <th>Câu ví dụ</th>
                                        <th>Dịch nghĩa</th>
                                        <th>Trạng thái</th>

                                        <th>Thời gian tạo</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>

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



</script>

<?php
require_once __DIR__ . "/Footer.php";
?>
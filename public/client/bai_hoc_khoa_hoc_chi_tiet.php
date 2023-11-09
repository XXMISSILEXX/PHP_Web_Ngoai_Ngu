<?php
require_once __DIR__ . "/../../configs/config.php";
require_once __DIR__ . "/../../configs/function.php";
$title = 'Bài học chi tiết | ' . $Database->site("TenWeb") . '';
$locationPage = 'khoahoc';
$META_IMAGE = "https://i.imgur.com/3DRA8rf.png";
$META_DESCRIPTION = "Bài học chi tiết";
$META_SITE = BASE_URL("Page/BaiHoc");

require_once __DIR__ . "/../../public/client/header.php";

checkLogin();
if (isset($_GET['maKhoaHoc']) && isset($_GET['maBaiHoc'])) {
    $khoaHoc = $Database->get_row("SELECT * FROM khoahoc A inner join baihoc B on A.MaKhoaHoc = B.MaKhoaHoc and  A.MaKhoaHoc = '" . check_string($_GET['maKhoaHoc']) . "' and A.TrangThaiKhoaHoc = 1 and B.TrangThaiBaiHoc = 1 and B.MaBaiHoc = '" . check_string($_GET['maBaiHoc']) . "'");
    if ($khoaHoc <= 0) {
        return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('') . '" }, 0);
    </script>
    ');
    }
}

$checkDangKy = $Database->get_row("SELECT * FROM dangkykhoahoc WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' AND `MaKhoaHoc` = '" . $khoaHoc["MaKhoaHoc"] . "' ") > 0;
$tongTuVungTheoBaiHoc = $Database->get_row("SELECT COUNT(*) AS SoLuongTuVungBaiHoc FROM tuvung WHERE MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and TrangThaiTuVung = 1 ")["SoLuongTuVungBaiHoc"];
$danhSachTuVungDaHocTheoBaiHoc = $Database->get_row("SELECT COUNT(*) AS SoLuongTuVungDaHoc FROM hoctuvung WHERE MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' AND TaiKhoan = '" . $_SESSION["account"] . "' ")["SoLuongTuVungDaHoc"];
if ($tongTuVungTheoBaiHoc == 0) {
    $tienTrinhHocTap = 0;
} else {

    $tienTrinhHocTap = floor($danhSachTuVungDaHocTheoBaiHoc / $tongTuVungTheoBaiHoc * 100);
}
$soTuVungBoQua = $Database->num_rows("SELECT * FROM boquatuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and A.TaiKhoan = '" . $_SESSION["account"] . "' AND A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "'  AND A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and B.TrangThaiTuVung = 1 ");

?>
<style>
    <?=
include_once __DIR__ . "/../../assets/css/course_page.css";
?>
    <?=
include_once __DIR__ . "/../../assets/css/home_page.css";
?>
</style>
<div id="thongbao"></div>
<div class="grid">
    <div class="row main-page">
        <div class="nav-container">

        </div>
        <div class="main_content-container">
            <div class="list-course">
                <div class="list-course__detail" style="display: block;">
                    <nav class="breadcrumb has-succeeds-separator page__title" aria-label="breadcrumbs">
                        <ul>
                            <li><a href="<?=BASE_URL("Page/KhoaHoc")?>">Khóa học</a></li>
                            <li><a href="<?=BASE_URL("Page/KhoaHoc/") . $khoaHoc["MaKhoaHoc"]?>"><?=$khoaHoc["TenKhoaHoc"]?></a></li>
                            <li class="is-active"><a href="#"><?=$khoaHoc["TenBaiHoc"]?></a></li>

                        </ul>
                    </nav>
                    <div class="course-detail__start" style="display: block;">
                        <div class="start__header card">
                            <div class="start__header-level">
                                <div class="start__header-level-number">Bài học <?=$khoaHoc["MaBaiHoc"]?></div>
                                <img src="<?=BASE_URL("/")?>/assets/img/book_list.svg" alt="" class="start__header-level-img">
                            </div>
                            <div class="start__header-content">
                                <div class="start__header-content-wrap-title">
                                    <div class="start__header-content-title"><?=$khoaHoc["TenBaiHoc"]?></div>

                                </div>
                                <div class="start__header-content-bar">
                                    <div class="start__header-content-bar-value" style="width: <?=$tienTrinhHocTap >= 100 ? 100 : $tienTrinhHocTap?>%;"><span class="start__header-content-bar-percent"><?=$tienTrinhHocTap >= 100 ? 100 : $tienTrinhHocTap?>%</span></div>
                                </div>
                                <div class="start__header-content-separate"></div>
                                <div class="start__header-content-wrap-btn">

                                        <div class="modal" id="modal-register-course-<?=$khoaHoc["MaKhoaHoc"]?>">
                                            <div class="modal-background"></div>
                                            <div class="modal-content">
                                                <div class="modal-content-body">
                                                    <div class="modal-header__text">
                                                        Xác nhận đăng ký khóa học <?=$khoaHoc["TenKhoaHoc"]?> </div>
                                                    <div class="modal-close modal-close-btn is-large" aria-label="close">
                                                    </div>
                                                    <div class="modal-content-body__text">
                                                        Bạn muốn đăng ký khóa học này không?
                                                    </div>

                                                    <div class="btn btn--primary" onclick='handleRegisterCourse(<?=$khoaHoc["MaKhoaHoc"]?>)'>
                                                        Xác nhận
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="start__header-content-btn btn js-modal-trigger" data-target="modal-register-course-<?=$khoaHoc["MaKhoaHoc"]?>">Đăng ký khóa học</div>

                                </div>
                            </div>
                        </div>
                        <div class="start__information_lession">
                            <div class="start__content-item-word-detail"><?=$tongTuVungTheoBaiHoc?> từ</div>
                            <div class="start__content-item-word-detail"> Bỏ qua <?=$soTuVungBoQua?> từ</div>


                        </div>

                        <div class="start__content">

                            <table class="start__content-table">
                                <thead>
                                    <tr>
                                        <td colspan="3">
                                            <div class="table-header">
                                                <?php
if ($checkDangKy) {
    ?>
                                                    <div class="table-header-desc">
                                                        <div class="start__content-item-skipped">
                                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                            <span class="start__content-item-skipped-text">Chưa học</span>
                                                        </div>
                                                        <div class="start__content-item-skipped">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            <span class="start__content-item-skipped-text">Đã học</span>
                                                        </div>
                                                        <div class="start__content-item-ready">
                                                            <i class="fa fa-tint" aria-hidden="true"></i>
                                                            <span class="start__content-item-ready-text">Sẵn sàng ôn tập</span>
                                                        </div>
                                                        <div class="start__content-item-ready">
                                                            <i class="fa fa-bookmark" aria-hidden="true"></i>
                                                            <span class="start__content-item-ready-text">Từ khó</span>
                                                        </div>
                                                    </div>
                                                    <div class="start__content-item-skipp-btn" id="btnBoQua">
                                                        Bỏ qua
                                                    </div>
                                                <?php
}
?>
                                            </div>
                                        </td>
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

        <?php
if ($checkDangKy) {
    // Lấy danh sách các từ vựng đã học đến thời gian ôn tập, nhưng không nằm trong danh sách bỏ qua
    $listDaHocChuaBoQua = $Database->get_list("select * from hoctuvung A left join boquatuvung B on A.MaTuVung = B.MaTuVung and A.MaBaiHoc = B.MaBaiHoc and A.MaKhoaHoc = B.MaKhoaHoc
        and A.TaiKhoan = B.TaiKhoan where A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' and A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and (A.ThoiGianOnTap is NULL or A.ThoiGianOnTap < NOW() - INTERVAL 30 minute) and B.TaiKhoan is NULL and B.MaTuVung is NULL and B.MaBaiHoc is NULL and B.MaKhoaHoc is NULL");

    ?>

            <div class="modal" id="modal-study-course">
                <div class="modal-background"></div>
                <div class="modal-content">
                    <div class="modal-content-body">
                        <div class="modal-header__text">
                            <?=$khoaHoc["TenKhoaHoc"]?> > <?=$khoaHoc["TenBaiHoc"]?>
                            <div class="modal-close modal-close-btn" aria-label="close">
                            </div>
                        </div>
                        <div class="modal-study__suggest">
                            <div class="modal-study__suggest-heading">Đề xuất cho bạn</div>


                                <div class="modal-study__choose-function-wrap">
                                    <a href="<?=BASE_URL("Page/OnTap?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>" class="modal-study__choose-function-btn-link">
                                        <div class="modal-study__choose-function-btn modal-study__choose-function-btn--2">
                                            <img src="<?=BASE_URL("/")?>/assets/img/modal-practice.svg" alt="" class="modal-study__choose-function-btn-img">
                                            <div class="modal-study__notifi-practive"><span class="modal-study__notifi-practive-number"><?=count($listDaHocChuaBoQua)?></span></div>
                                        </div>
                                    </a>
                                    <div class="modal-study__choose-function-text">Ôn tập</div>
                                </div>

                                <div class="modal-study__choose-function-wrap">
                                    <a href="<?=BASE_URL("Page/OnSieuToc?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>" class="modal-study__choose-function-btn-link">
                                        <div class="modal-study__choose-function-btn modal-study__choose-function-btn--3">
                                            <img src="<?=BASE_URL("/")?>/assets/img/modal-watch.svg" alt="" class="modal-study__choose-function-btn-img">
                                        </div>
                                    </a>
                                    <div class="modal-study__choose-function-text">Ôn siêu tốc</div>
                                </div>

                                <div class="modal-study__choose-function-wrap">
                                    <a href="<?=BASE_URL("Page/Study?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>" class="modal-study__choose-function-btn-link">
                                        <div class="modal-study__choose-function-btn">
                                            <img src="<?=BASE_URL("/")?>/assets/img/modal-plus.svg" alt="" class="modal-study__choose-function-btn-img">
                                        </div>
                                    </a>
                                    <div class="modal-study__choose-function-text">Học từ mới</div>
                                </div>


                        </div>
                        <div class="modal-study__choose-function">
                            <div class="modal-study__choose-function__heading">
                                Lựa chọn các mục sau
                            </div>

                            <div class="row">
                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?=BASE_URL("Page/Study?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>" class="modal-study__choose-function-btn-link">
                                            <div class="modal-study__choose-function-btn">
                                                <img src="<?=BASE_URL("/")?>/assets/img/modal-plus.svg" alt="" class="modal-study__choose-function-btn-img">
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Học từ mới</div>
                                    </div>
                                </div>

                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?=BASE_URL("Page/OnTap?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>" class="modal-study__choose-function-btn-link">
                                            <div class="modal-study__choose-function-btn modal-study__choose-function-btn--2">
                                                <img src="<?=BASE_URL("/")?>/assets/img/modal-practice.svg" alt="" class="modal-study__choose-function-btn-img">
                                                <div class="modal-study__notifi-practive"><span class="modal-study__notifi-practive-number"><?=count($listDaHocChuaBoQua)?></span></div>
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Ôn tập</div>
                                    </div>
                                </div>
                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?=BASE_URL("Page/OnSieuToc?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>" class="modal-study__choose-function-btn-link">
                                            <div class="modal-study__choose-function-btn modal-study__choose-function-btn--3">
                                                <img src="<?=BASE_URL("/")?>/assets/img/modal-watch.svg" alt="" class="modal-study__choose-function-btn-img">
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Ôn siêu tốc</div>
                                    </div>
                                </div>
                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?=BASE_URL("Page/OnTuKho?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]")?>">
                                            <div class="modal-study__choose-function-btn modal-study__choose-function-btn--4">
                                                <img src="<?=BASE_URL("/")?>/assets/img/modal-degree.svg" alt="" class="modal-study__choose-function-btn-img">
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Ôn từ khó</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
}
?>

        <script>
            const CURRENT_STATUS_WORDS = [];
            let checkOpenSkipMenu = false;

            function handleRegisterCourse(maKhoaHoc) {
                $.ajax({
                    url: "<?=BASE_URL("assets/ajaxs/Course.php");?>",
                    method: "POST",
                    data: {
                        type: 'registerCourse',
                        maKhoaHoc: maKhoaHoc,
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        $("#thongbao").empty().append(response);
                        setTimeout(() => {

                            window.location.reload();
                        }, 1000)
                    }
                });
            }

            function chonHetBoQuaTuVung() {
                if (checkOpenSkipMenu) {
                    const allWordsOption = document.querySelectorAll(".word .option input[type=checkbox]");
                    allWordsOption.forEach((e) => {
                        e.checked = true;
                    })
                }
            }

            function boChonHetBoQuaTuVung() {
                if (checkOpenSkipMenu) {
                    const allWordsOption = document.querySelectorAll(".word .option input[type=checkbox]");
                    allWordsOption.forEach((e) => {
                        e.checked = false;
                    })
                }
            }

            $(document).ready(function() {
                $("#btnBoQua").click(function() {
                    openSkipMenu();
                })
            });
        </script>

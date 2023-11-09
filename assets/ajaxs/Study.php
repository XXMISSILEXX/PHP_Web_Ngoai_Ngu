<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");

if ($_POST['type'] == 'DanhDauTuKho') {
    $maKhoaHoc = check_string($_POST['maKhoaHoc']);
    $maBaiHoc = check_string($_POST['maBaiHoc']);
    $maTuVung = check_string($_POST['maTuVung']);
    $token = check_string($_POST['token']);
    if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc) || empty($maTuVung)) {
        msg_error2('Vui lòng điền đủ dữ liệu');
    }
    $checkToken = $Database->get_row("SELECT * FROM hoctumoi WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
    if ($checkToken <= 0) {
        msg_error2('Dữ liệu không tồn tại');
    }
    $checkHocTuVung = $Database->get_row("select * from hoctuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and B.TrangThaiTuVung = 1 and A.TaiKhoan = '" . $_SESSION['account'] . "' and A.MaTuVung = '" . $maTuVung . "'  and A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "'");
    if ($checkHocTuVung <= 0) {
        msg_error2('Dữ liệu không tồn tại');
    }
    $getTuVung = $Database->get_row("SELECT * FROM tuvung A inner join khoahoc B on A.MaKhoaHoc = '" . $maKhoaHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc inner join baihoc C on A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaBaiHoc = C.MaBaiHoc and A.MaTuVung = '" . $maTuVung . "'  ");
    if ($checkHocTuVung["TuKho"] == 0) {
        $Study->danhDauTuKho([
            'TaiKhoan' => $_SESSION['account'],
            'MaTuVung' => $checkHocTuVung["MaTuVung"],
            'MaKhoaHoc' => $checkHocTuVung["MaKhoaHoc"],
            'MaBaiHoc' => $checkHocTuVung["MaBaiHoc"],
        ]);
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Đánh dấu từ khó',
            'NoiDung' => 'Đánh dấu từ khó mới: "' . $getTuVung["NoiDungTuVung"] . '" thuộc bài học "' . $getTuVung["TenBaiHoc"] . '" của khóa học "' . $getTuVung["TenKhoaHoc"] . '"',
            'TaiKhoan' => $_SESSION["account"]
        ]);

        msg_success2("Đánh dấu từ khó thành công");
    } else {
        $Study->huyDanhDauTuKho([
            'TaiKhoan' => $_SESSION['account'],
            'MaTuVung' => $checkHocTuVung["MaTuVung"],
            'MaKhoaHoc' => $checkHocTuVung["MaKhoaHoc"],
            'MaBaiHoc' => $checkHocTuVung["MaBaiHoc"],
        ]);
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Hủy đánh dấu từ khó',
            'NoiDung' => 'Hủy đánh dấu từ khó mới: "' . $getTuVung["NoiDungTuVung"] . '" thuộc bài học "' . $getTuVung["TenBaiHoc"] . '" của khóa học "' . $getTuVung["TenKhoaHoc"] . '"',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        msg_success2("Hủy đánh dấu từ khó thành công");
    }
}

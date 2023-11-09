<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");

if ($_POST['type'] == 'updateSkipWord') {
    try {

        $data = ($_POST['data']);
        $maKhoaHoc = check_string($_POST["maKhoaHoc"]);
        $maBaiHoc = check_string($_POST["maBaiHoc"]);
        if (empty($maKhoaHoc) || empty($data) || empty($maBaiHoc)) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $checkBaiHoc = $Database->get_row("SELECT * FROM baihoc A inner join khoahoc B WHERE A.MaKhoaHoc = '" . $maKhoaHoc . "' AND A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc");
        if ($checkBaiHoc <= 0) {
            msg_error2('Bài học không tồn tại');
        }
        $keys = array_keys($data);
        $mangLuuTruTuVungHuyBoQua = array();
        $mangLuuTruTuVungBoQua = array();
        for ($i = 0; $i < count($data); $i++) {
            $maTuVung = '';
            foreach ($data[$keys[$i]] as $key => $value) {
                if ($key == 'maTuVung') {
                    $maTuVung = $value;
                } else if ($key == 'type') {
                    if ($value == 'false') {
                        // hủy bỏ qua từ vựng
                        $checkValid = $Database->get_row("select * from boquatuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaTuVung = '" . $maTuVung . "' and TaiKhoan = '" . $_SESSION['account'] . "' ");
                        if ($checkValid) {
                            $Database->query("DELETE FROM boquatuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaTuVung = '" . $maTuVung . "' and TaiKhoan = '" . $_SESSION['account'] . "' ");
                            $getTuVung = $Database->get_row("select * from tuvung where MaTuVung = '" . $maTuVung . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaKhoaHoc = '" . $maKhoaHoc . "' ");
                            array_push($mangLuuTruTuVungHuyBoQua, $getTuVung["NoiDungTuVung"]);
                        }
                    } else  if ($value == 'true') {
                        $checkValid = $Database->get_row("select * from boquatuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaTuVung = '" . $maTuVung . "' and TaiKhoan = '" . $_SESSION['account'] . "' ");
                        if (!$checkValid) {
                            // bỏ qua từ vựng
                            $Database->insert("boquatuvung", [
                                'MaKhoaHoc' => $maKhoaHoc,
                                'MaBaiHoc' => $maBaiHoc,
                                'MaTuVung' => $maTuVung,
                                'TaiKhoan' => $_SESSION["account"]
                            ]);


                            $getTuVung = $Database->get_row("select * from tuvung where MaTuVung = '" . $maTuVung . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaKhoaHoc = '" . $maKhoaHoc . "' ");
                            array_push($mangLuuTruTuVungBoQua, $getTuVung["NoiDungTuVung"]);
                        }
                    }
                }
            }
        }
        // lưu lại hoạt động
        if (count($mangLuuTruTuVungBoQua) != 0) {
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 2,
                'TenHoatDong' => 'Bỏ qua từ vựng',
                'NoiDung' => 'Bỏ qua từ vựng "' . implode(", ", $mangLuuTruTuVungBoQua) . '" thuộc bài học "' . $checkBaiHoc["TenBaiHoc"] . '" của khóa học "' . $checkBaiHoc["TenKhoaHoc"] . '"',
                'TaiKhoan' => $_SESSION["account"]
            ]);
        }
        if (count($mangLuuTruTuVungHuyBoQua) != 0) {
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 2,
                'TenHoatDong' => 'Hủy bỏ qua từ vựng',
                'NoiDung' => 'Hủy bỏ qua từ vựng "' . implode(", ", $mangLuuTruTuVungHuyBoQua) . '" thuộc bài học "' . $checkBaiHoc["TenBaiHoc"] . '" của khóa học "' . $checkBaiHoc["TenKhoaHoc"] . '"',
                'TaiKhoan' => $_SESSION["account"]
            ]);
        }
        msg_success2("Áp dụng thành công");
    } catch (Exception $err) {
        msg_error2('Có lỗi xảy ra, vui lòng thử lại');
    }
}

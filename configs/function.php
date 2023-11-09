<?php

$config = [
    'project' => 'hocngoaingu',
    'url' => BASE_URL,
    'version' => '1.0.0',
    'ip_server' => '',
];


function checkLogin()
{
    global $Database;
    if (!isset($_SESSION["account"])) {
        return die('<script type="text/javascript">
            setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangNhap') . '" }, 0);
            </script>
            ');
    } else {
        checkAccountExist();
        $row = $Database->get_row("SELECT * FROM `dangkykhoahoc` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "'  ");
        if (!$row) {
            return die('<script type="text/javascript">
                setTimeout(function(){ location.href = "' . BASE_URL('Page/KhoiTaoTaiKhoan') . '" }, 0);
                </script>
                ');
        }
    }
}

function checkAccountExist()
{
    global $Database;
    if (isset($_SESSION["account"])) {
        $row = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "'  ");
        if (!$row) {
            return die('<script type="text/javascript">
                setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 0);
                </script>
                ');
        } else {
            if ($row["TrangThai"] == 0) {
                return die('<script type="text/javascript">toastr.error("Tài khoản đã bị cấm", "Lỗi hệ thống!");
                setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 1000);</script>');
            }
        }
    }
}


function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = '566d7ca789ea4196909d2e703242fef5a00dc85d8fea7bee8733b31b4019f335'; // 32 characters
    $secret_iv = 'ab55b36ebf363980d89d4e72434333cb'; // 16 characters
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes 
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function hashString($plaintext)
{
    $cipher = "aes-256-ctr";
    $key = '566d7ca789ea4196909d2e703242fef5a00dc85d8fea7bee8733b31b4019f335';
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
        return $ciphertext;
    }
    return null;
}
function unHashString($ciphertext)
{
    $cipher = "aes-256-ctr";
    $key = '566d7ca789ea4196909d2e703242fef5a00dc85d8fea7bee8733b31b4019f335';
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv, $tag = null);
        return $original_plaintext;
    }
    return null;
}

function stripUnicode($str)
{
    if (!$str) return false;
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    );
    foreach ($unicode as $nonUnicode => $uni)
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    return $str;
}

function removeItemDuplicate($array, $key)
{
    $found = [];
    foreach ($array as $index => [$key => $ref]) {
        if (!isset($found[$ref])) {
            $found[$ref] = $index;
        } else {
            unset($array[$index], $array[$found[$ref]]);
        }
    }
    return $array;
}
function uniqueMultiArray($array, $key)
{
    // key = MaTuVung
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function checkRequireCapDo($capdo)
{
    $result = 0;
    switch ($capdo) {
        case 2:
            $result = 100;
            break;
        case 3:
            $result = 200;
            break;
        case 4:
            $result = 300;
            break;
        case 5:
            $result = 500;
            break;
        case 6:
            $result = 1000;
            break;
        case 7:
            $result = 2000;
            break;
        case 8:
            $result = 3000;
            break;
        case 9:
            $result = 5000;
            break;
        case 10:
            $result = 10000;
            break;
        case 11:
            $result = 20000;
            break;
        case 12:
            $result = 30000;
            break;
        case 13:
            $result = 100000;
            break;
        case 14:
            $result = 200000;
            break;
        case 15:
            $result = 500000;
            break;
        case 16:
            $result = 1500000;
            break;
        case 17:
            $result = 5000000;
            break;
        case 18:
            $result = 10000000;
            break;
        case 19:
            $result = 30000000;
            break;
        case 20:
            $result = 1000000000;
            break;
        default:
            $result = 0;
    }
    return $result;
}
function formatNumber($number)
{
    return number_format($number, 0, '.', '.');
}
function updateCapDo()
{
    try {

        global $Database;
        if (isset($_SESSION["account"])) {
            $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
            if (!$taikhoan) {
                throw new Exception("Không tồn tại tài khoản trong hệ thống");
            }
            $kinhnghiemhientai = $taikhoan["KinhNghiem"];
            $capdo = $taikhoan["CapDo"];
            while ($kinhnghiemhientai >= checkRequireCapDo($capdo + 1) && $capdo < 20) {
                $Database->query("UPDATE `nguoidung` SET `KinhNghiem` = `KinhNghiem` - '" . checkRequireCapDo($capdo + 1) . "', `CapDo` = `CapDo` + 1  WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
                $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
                $kinhnghiemhientai =  $taikhoan["KinhNghiem"];
                $capdo = $taikhoan["CapDo"];
            }
        }
    } catch (Exception $err) {
        return die('<script type="text/javascript">toastr.error("' . $err->getMessage() . '", "Lỗi hệ thống!");setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 1000);</script>');
    }
}

function sendCSM($mail_nhan, $ten_nhan, $chu_de, $noi_dung, $bcc)
{
    global $Database;
    // PHPMailer Modify
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $Database->site("Email"); // GMAIL STMP
    $mail->Password = $Database->site("PassEmail"); // PASS STMP 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom($Database->site('Email'), $bcc);
    $mail->addAddress($mail_nhan, $ten_nhan);
    $mail->addReplyTo($Database->site('Email'), $bcc);
    $mail->isHTML(true);
    $mail->Subject = $chu_de;
    $mail->Body = $noi_dung;
    $mail->CharSet = 'UTF-8';
    $send = $mail->send();
    return $send;
}
function BASE_URL($url)
{
    global $config;
    $a = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($a == 'http://localhost') {
        $a = BASE_URL;
    }
    if ($url == "/") {
        return $a;
    }
    return $a . '/' . $url;
}
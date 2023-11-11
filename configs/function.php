<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

$config = [
    'project' => 'WebNgoaiNgu',
    'url' => BASE_URL,
    'version' => '1.0.0',
    'ip_server' => '',
];

/**
 * Kiểm tra xem người dùng đã đăng nhập hay chưa.
 *
 * @throws \Exception Hàm có thể ném ra Exception nếu người dùng chưa đăng nhập.
 * @return void
 */
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

/**
 * Kiểm tra xem tài khoản có tồn tại hay không.
 *
 * @throws \Exception Nếu tài khoản không tồn tại.
 */
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

/**
 * Mã hóa hoặc giải mã một chuỗi sử dụng phương pháp mã hóa AES-256-CBC.
 *
 * @param string $action Hành động để thực hiện (mã hóa hoặc giải mã).
 * @param string $string Chuỗi cần mã hóa hoặc giải mã.
 * @return string Chuỗi đã được mã hóa hoặc giải mã.
 */
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC"; //  Advanced Encryption Standard AES-256 Cipher Block Chaining
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

/**
 * Mã hóa một chuỗi văn bản cho trước bằng phương pháp mã hóa AES-256-CTR.
 *
 * @param string $plaintext Chuỗi văn bản cần mã hóa.
 * @throws \Exception Nếu phương pháp mã hóa không được hỗ trợ bởi hệ thống.
 * @return string|null Chuỗi mã hóa hoặc null nếu phương pháp mã hóa không được hỗ trợ.
 */
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

/**
 * Giải mã một chuỗi đã được mã hóa bằng phương pháp AES-256-CTR.
 *
 * @param string $ciphertext Chuỗi đã mã hóa cần giải mã.
 * @throws \Exception Nếu phương pháp mã hóa không được hỗ trợ.
 * @return string|null Chuỗi gốc đã giải mã, hoặc null nếu quá trình giải mã thất bại.
 */
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

/**
 * Loại bỏ các ký tự Unicode từ một chuỗi cho trước.
 *
 * @param string $str Chuỗi cần xử lý.
 * @return string Chuỗi đã được xử lý không chứa ký tự Unicode.
 */
function stripUnicode($str)
{
    if (!$str) {
        return false;
    }

    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    );
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }

    return $str;
}

/**
 * Loại bỏ các phần tử trùng lặp trong một mảng dựa trên một khóa cụ thể.
 *
 * @param array $array Mảng cần loại bỏ các phần tử trùng lặp.
 * @param string $key Khóa để xác định phần tử trùng lặp.
 * @return array Trả về mảng đã loại bỏ các phần tử trùng lặp.
 */
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

/**
 * Trả về một mảng mới chứa các phần tử duy nhất dựa trên khóa đã chỉ định.
 *
 * @param array $array Mảng gốc.
 * @param string $key Khóa để kiểm tra tính duy nhất.
 * @return array Mảng mới chứa các phần tử duy nhất.
 */
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

/**
 * Trả về giá trị tương ứng dựa trên giá trị đầu vào $capdo.
 *
 * @param int $capdo Giá trị cần kiểm tra.
 * @return int Giá trị kết quả tương ứng dựa trên giá trị đầu vào $capdo.
 */
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

/**
 * Định dạng số với dấu phân cách hàng nghìn.
 *
 * @param mixed $number Số cần định dạng.
 * @return string Số đã được định dạng.
 */
function formatNumber($number)
{
    return number_format($number, 0, '.', '.');
}

/**
 * Cập nhật CapDo.
 *
 * @throws Exception nếu tài khoản không tồn tại trong hệ thống
 * @return void
 */
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
                $kinhnghiemhientai = $taikhoan["KinhNghiem"];
                $capdo = $taikhoan["CapDo"];
            }
        }
    } catch (Exception $err) {
        return die('<script type="text/javascript">toastr.error("' . $err->getMessage() . '", "Lỗi hệ thống!");setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 1000);</script>');
    }
}

/**
 * Gửi email CSM.
 *
 * @param string $mail_nhan Địa chỉ email của người nhận.
 * @param string $ten_nhan Tên của người nhận.
 * @param string $chu_de Chủ đề của email.
 * @param string $noi_dung Nội dung của email.
 * @param string $bcc Địa chỉ email bản sao ẩn.
 * @throws \Exception Nếu việc gửi email thất bại.
 * @return bool Trả về true nếu email được gửi thành công.
 */
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

/**
 * Tạo ra URL cơ sở cho đường dẫn URL đã cho.
 *
 * @param string $url Đường dẫn URL để nối với URL cơ sở.
 * @return string URL hoàn chỉnh với URL cơ sở và đường dẫn đã cho.
 */
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

/**
 * Lấy ngày giờ hiện tại theo định dạng 'Y-m-d H:i:s'.
 *
 * @return string Ngày giờ hiện tại.
 */
function getTime()
{
    return date('Y-m-d H:i:s', time());
}

/**
 * Tạo một biểu tượng trạng thái dựa trên dữ liệu đã cho.
 *
 * @param int $data Dữ liệu để xác định biểu tượng trạng thái.
 * @return string Biểu tượng trạng thái được tạo ra.
 */
function displayStatusActiveEmailAccount($data)
{
    if ($data == 0) {
        $show = '<span class="badge badge-danger">Chưa kích hoạt</span>';
    } else if ($data == 1) {
        $show = '<span class="badge badge-success">Đã kích hoạt</span>';
    }
    return $show;
}

/**
 * Kiểm tra xem một URL có tồn tại không.
 *
 * @param string $url URL cần kiểm tra.
 * @return bool Trả về true nếu URL tồn tại, false nếu không tồn tại.
 */
function checkUrlExists($url)
{
    // Use get_headers() function
    $headers = @get_headers($url);
    // Use condition to check the existence of URL
    if ($headers && strpos($headers[0], '200')) {
        $status = true;
    } else {
        $status = false;
    }

    return $status;
}

/**
 * Hiển thị trạng thái tài khoản dựa trên dữ liệu được cung cấp.
 *
 * @param mixed $data Dữ liệu để xác định trạng thái tài khoản.
 * @return string Mã HTML đại diện cho trạng thái tài khoản.
 */
function displayStatusAccount($data)
{
    if ($data == 0) {
        $show = '<span class="badge badge-danger">Banned</span>';
    } else if ($data == 1) {
        $show = '<span class="badge badge-success">Hoạt động</span>';
    }
    return $show;
}

/**
 * Loại bỏ các ký tự đặc biệt từ một chuỗi.
 *
 * @param string $string Chuỗi đầu vào cần loại bỏ các ký tự đặc biệt.
 * @return string Chuỗi đầu vào sau khi loại bỏ các ký tự đặc biệt.
 */
function removeSpecialCharacter($string)
{
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

/**
 * Kiểm tra xem một chuỗi có chứa ký tự đặc biệt hay không.
 *
 * @param string $string Chuỗi đầu vào cần kiểm tra.
 * @return bool Trả về true nếu chuỗi chứa ký tự đặc biệt, ngược lại trả về false.
 */
function checkContainSpecialCharacter($string)
{
    $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
    if (preg_match($pattern, $string)) {
        return true;
    }
    return false;
}

/**
 * Loại bỏ các ký tự đặc biệt và escapes .
 *
 * Hàm này nhận một chuỗi đầu vào và thực hiện các thao tác sau:
 * 1. Loại bỏ các khoảng trắng ở đầu và cuối chuỗi.
 * 2. Escapes các ký tự đặc biệt như <, >, ', ", vv.
 * 3. Thêm dấu gạch chéo trước các ký tự đặc biệt hoặc chuỗi escapes .
 *
 * @param string $data Chuỗi đầu vào cần được loại bỏ ký tự đặc biệt và escapes .
 * @return string Chuỗi đã được loại bỏ ký tự đặc biệt và escapes .
 */
function check_string($data)
{
    return trim(htmlspecialchars(addslashes($data)));
    //return str_replace(array('<',"'",'>','?','/',"\\",'--','eval(','<php'),array('','','','','','','','',''),htmlspecialchars(addslashes(strip_tags($data))));
}

/**
 * Mã hóa một chuỗi bằng phương pháp mã hóa AES-128-CTR.
 *
 * @param string $string Chuỗi cần mã hóa.
 * @throws \Exception Nếu quá trình mã hóa thất bại.
 * @return string Chuỗi đã được mã hóa.
 */
function encryptString($string)
{
    $ciphering = "AES-128-CTR";
    $options = 0;
    $encryption_iv = '3439727867766855';
    $encryption_key = "4236073900";
    $result = openssl_encrypt(
        $string,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );
    return $result;
}

/**
 * Giải mã một chuỗi mã hóa đã cho bằng cách sử dụng mã hóa AES-128-CTR.
 *
 * @param string $encryption Chuỗi mã hóa cần giải mã.
 * @return string Kết quả sau khi giải mã.
 */
function decryptString($encryption)
{
    $ciphering = "AES-128-CTR";
    $options = 0;
    $decryption_iv = '3439727867766855';
    $decryption_key = "4236073900";
    $result = openssl_decrypt(
        $encryption,
        $ciphering,
        $decryption_key,
        $options,
        $decryption_iv
    );
    return $result;
}

/**
 * Gửi một yêu cầu GET tới URL đã chỉ định bằng thư viện cURL và trả về phản hồi.
 *
 * @param string $url URL để gửi yêu cầu tới.
 * @throws \Exception Nếu có lỗi trong quá trình yêu cầu bằng cURL.
 * @return string Dữ liệu phản hồi được trả về bởi yêu cầu.
 */
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);

    curl_close($ch);
    return $data;
}

/**
 * Tạo ra một phản hồi chat bằng cách sử dụng mô hình OpenAI GPT-3.5 Turbo.
 *
 * @param mixed $content Nội dung tin nhắn của người dùng.
 * @throws \Exception Nếu có lỗi xảy ra trong quá trình gọi API.
 * @return mixed Phản hồi được tạo ra bởi mô hình GPT-3.5 Turbo.
 */
function chatGPT($content)
{

    $apiKey = OPENAI_API_KEY;
    $url = 'https://api.openai.com/v1/chat/completions';

    $headers = array(
        "Authorization: Bearer {$apiKey}",
        "OpenAI-Organization: org-uwibhod0YLiCEnlQLEakzgpT",
        "Content-Type: application/json",
    );

    // Define messages
    $messages = array();
    $messages[] = array("role" => "user", "content" => $content);

    // Define data
    $data = array();
    $data["model"] = "gpt-3.5-turbo";
    $data["messages"] = $messages;
    $data["max_tokens"] = 50;

    // init curl
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}

/**
 * Tạo một chuỗi ngẫu nhiên có độ dài xác định.
 *
 * @param string $string Chuỗi ban đầu để tạo chuỗi ngẫu nhiên.
 * @param int $int Độ dài của chuỗi ngẫu nhiên.
 * @return string Chuỗi ngẫu nhiên đã được tạo.
 */
function randomString($string, $int)
{
    return substr(str_shuffle($string), 0, $int);
}

/**
 * Kiểm tra xem tệp hình ảnh đã cho có phần mở rộng hợp lệ hay không.
 *
 * @param array $img Tệp hình ảnh cần kiểm tra.
 * @return bool Trả về true nếu tệp hình ảnh có phần mở rộng hợp lệ, ngược lại trả về false.
 */
function checkImg($img)
{
    $filename = $img['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $valid_ext = array("png", "jpeg", "jpg", "PNG", "JPEG", "JPG");
    if (in_array($ext, $valid_ext)) {
        return true;
    }
    return false;
}

/**
 * Mô tả về toàn bộ hàm PHP.
 *
 * @param string $text Đoạn văn bản sẽ được hiển thị.
 * @return void
 */
function msg_html_success($text)
{
    return die($text);
}

/**
 * Tạo một thông báo lỗi với văn bản cho trước.
 *
 * @param string $text Văn bản cho thông báo lỗi.
 * @return string HTML của thông báo lỗi.
 */
function msg_error3($text)
{
    return '<div class="alert alert-danger alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>';
}

/**
 * Tạo một phần tử HTML thông báo thành công với nút đóng.
 *
 * @param string $text Văn bản được hiển thị bên trong thông báo thành công.
 * @return string Phần tử HTML được tạo ra.
 */
function msg_success3($text)
{
    return '<div class="alert alert-success alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>';
}

/**
 * Hiển thị một thông báo thành công bằng thư viện Toastr.
 *
 * @param string $text Văn bản cần hiển thị trong thông báo thành công.
 * @return void
 */
function msg_success2($text)
{
    return die('<script type="text/javascript">toastr.success("' . $text . '", "Thành công!");</script>');
}

/**
 * Tạo một đoạn mã HTML hiển thị thông báo thành công.
 *
 * @param string $text Đoạn văn bản hiển thị trong thông báo thành công.
 * @return string Đoạn mã HTML cho thông báo thành công.
 */
function getMessageSuccess2($text)
{
    return ('<script type="text/javascript">toastr.success("' . $text . '", "Thành công!");</script>');
}

/**
 * Tạo một thông báo lỗi dưới dạng thẻ script JavaScript với nội dung văn bản đã cho.
 *
 * @param string $text Văn bản thông báo lỗi.
 * @return string Đoạn mã script thông báo lỗi được tạo ra.
 */
function getMessageError2($text)
{
    return ('<script type="text/javascript">toastr.error("' . $text . '", "Lỗi hệ thống!");</script>');
}

/**
 * Hiển thị thông báo lỗi sử dụng thư viện Toastr JavaScript.
 *
 * @param string $text nội dung thông báo lỗi
 * @throws \Exception nếu có lỗi xảy ra trong hệ thống
 * @return void
 */
function msg_error2($text)
{
    return die('<script type="text/javascript">toastr.error("' . $text . '", "Lỗi hệ thống!");</script>');
}

/**
 * Hiển thị một thông báo cảnh báo với nội dung được chỉ định.
 *
 * @param string $text Nội dung của thông báo cảnh báo.
 * @throws \Exception Khi không thể hiển thị thông báo.
 * @return void
 */
function msg_warning2($text)
{
    return die('<div class="alert alert-warning alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>');
}

/**
 * Hiển thị thông báo thành công và chuyển hướng đến một URL sau một khoảng thời gian nhất định.
 *
 * @param string $text Nội dung thông báo thành công để hiển thị.
 * @param string $url URL để chuyển hướng đến.
 * @param int $time Thời gian chờ trước khi chuyển hướng, tính bằng mili giây.
 * @return void
 */
function msg_success($text, $url, $time)
{
    return die('<script type="text/javascript">toastr.success("' . $text . '", "Thành công!");    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}

/**
 * Tạo thông báo lỗi và chuyển hướng người dùng sau một khoảng thời gian nhất định.
 *
 * @param string $text Văn bản thông báo lỗi.
 * @param string $url Đường dẫn URL để chuyển hướng đến.
 * @param int $time Thời gian chờ trước khi chuyển hướng, tính bằng mili giây.
 * @return void
 */
function msg_error($text, $url, $time)
{
    return die('<div class="alert alert-danger alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}

/**
 * Hiển thị một thông báo cảnh báo và chuyển hướng người dùng sau một khoảng thời gian nhất định.
 *
 * @param string $text Nội dung thông báo cảnh báo để hiển thị.
 * @param string $url Đường dẫn URL để chuyển hướng đến.
 * @param int $time Thời gian chờ trước khi chuyển hướng, tính bằng mili giây.
 * @return void
 */
function msg_warning($text, $url, $time)
{
    return die('<div class="alert alert-warning alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}

/**
 * Một hàm để hiển thị thông báo thành công và chuyển hướng người dùng.
 *
 * @param string $text Nội dung của thông báo thành công.
 * @param string $url URL để chuyển hướng người dùng đến.
 * @param int $time Thời gian trễ trước khi chuyển hướng, tính bằng mili giây.
 * @return void
 */

/**
 * Hiển thị một thông báo thành công sử dụng thư viện Swal.fire và chuyển hướng người dùng đến một URL cụ thể sau một khoảng thời gian nhất định.
 *
 * @param string $text Nội dung của thông báo thành công.
 * @param string $url URL để chuyển hướng người dùng đến.
 * @param int $time Thời gian tính bằng mili giây trước khi chuyển hướng người dùng.
 * @return void
 */
function admin_msg_success($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thành Công", "' . $text . '","success");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}

/**
 * Hiển thị thông báo lỗi cho admin và chuyển hướng đến URL được chỉ định sau một khoảng thời gian chờ.
 *
 * @param string $text Nội dung thông báo lỗi cần hiển thị.
 * @param string $url URL cần chuyển hướng đến.
 * @param int $time Thời gian chờ trước khi chuyển hướng (tính bằng mili giây).
 * @return void
 */
function admin_msg_error($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thất Bại", "' . $text . '","error");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}

/**
 * Hiển thị một thông báo cảnh báo cho quản trị viên và chuyển hướng họ đến một URL cụ thể sau một khoảng thời gian nhất định.
 *
 * @param string $text Nội dung của thông báo cảnh báo.
 * @param string $url URL để chuyển hướng quản trị viên đến.
 * @param int $time Thời gian tính bằng mili giây để chờ trước khi chuyển hướng.
 * @return void
 */
function admin_msg_warning($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thông Báo", "' . $text . '","warning");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}

/**
 * Xóa tất cả các khoảng trắng trong văn bản cho trước.
 *
 * @param string $text Văn bản đầu vào.
 * @return string Văn bản đã được chỉnh sửa không có khoảng trắng.
 */
function xoaDauCach($text)
{
    return trim(preg_replace('/\s+/', '', $text));
}

/**
 * Trả về các header HTTP từ yêu cầu hiện tại.
 *
 * @return array Mảng chứa các header HTTP.
 */
function getHeader()
{
    $headers = array();
    $copy_server = array(
        'CONTENT_TYPE' => 'Content-Type',
        'CONTENT_LENGTH' => 'Content-Length',
        'CONTENT_MD5' => 'Content-Md5',
    );
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $key = substr($key, 5);
            if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$key] = $value;
            }
        } elseif (isset($copy_server[$key])) {
            $headers[$copy_server[$key]] = $value;
        }
    }
    if (!isset($headers['Authorization'])) {
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
            $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
        } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
        }
    }
    return $headers;
}

/**
 * Kiểm tra tính hợp lệ của tên người dùng.
 *
 * @param mixed $data Tên người dùng cần kiểm tra.
 * @return bool Trả về true nếu tên người dùng hợp lệ, ngược lại trả về false.
 */
function checkUsername($data)
{
    if (preg_match('/^[a-zA-Z0-9_-]{3,16}$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Kiểm tra xem dữ liệu đã cho có phải là một địa chỉ email hợp lệ hay không.
 *
 * @param mixed $data Dữ liệu đầu vào cần kiểm tra.
 * @return bool Trả về true nếu dữ liệu là một địa chỉ email hợp lệ, ngược lại trả về false.
 */
function checkEmail($data)
{
    if (preg_match('/^.+@.+$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Kiểm tra xem số điện thoại đã cho có hợp lệ hay không.
 *
 * @param mixed $data Số điện thoại cần kiểm tra.
 * @return bool Trả về true nếu số điện thoại hợp lệ, ngược lại trả về false.
 */
function checkPhone($data)
{
    if (preg_match('/^\+?(\d.*){3,}$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Kiểm tra xem một URL cho trước có hợp lệ không bằng cách gửi yêu cầu HEAD.
 *
 * @param string $url URL cần kiểm tra.
 * @throws \Exception Nếu URL không hợp lệ hoặc không thể truy cập được.
 * @return bool Trả về true nếu URL hợp lệ và có thể truy cập được, ngược lại trả về false.
 */
function checkUrl($url)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_HEADER, 1);
    curl_setopt($c, CURLOPT_NOBODY, 1);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
    if (!curl_exec($c)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Kiểm tra xem tệp được cung cấp có phải là tệp ZIP hay không.
 *
 * @param string $file Tên của tệp cần kiểm tra.
 * @return bool Trả về true nếu tệp là tệp ZIP, ngược lại trả về false.
 */
function checkZip($file)
{
    $filename = $_FILES[$file]['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $validExtensions = ["zip", "ZIP"];

    return in_array($extension, $validExtensions);
}

/**
 * Mã hóa một chuỗi mật khẩu được cung cấp bằng thuật toán MD5.
 *
 * @param string $string Chuỗi mật khẩu cần mã hóa.
 * @return string Chuỗi mật khẩu đã được mã hóa.
 */
function encryptPassword($string)
{
    return md5($string);
}

/**
 * Tạo thanh điều hướng phân trang cho một URL, vị trí bắt đầu, tổng số mục và số mục trên mỗi trang đã cho.
 *
 * @param string $url Đường dẫn URL để điều hướng tới.
 * @param int $start Vị trí bắt đầu.
 * @param int $total Tổng số mục.
 * @param int $kmess Số mục trên mỗi trang.
 * @return string Chuỗi HTML của thanh điều hướng phân trang đã tạo.
 */
function phantrang($url, $start, $total, $kmess)
{
    $out[] = '<nav aria-badge="Page navigation example"><ul class="pagination pagination-lg">';
    $neighbors = 2;
    if ($start >= $total) {
        $start = max(0, $total - (($total % $kmess) == 0 ? $kmess : ($total % $kmess)));
    } else {
        $start = max(0, (int) $start - ((int) $start % (int) $kmess));
    }

    $base_link = '<li class="page-item"><a class="page-link" href="' . strtr($url, array('%' => '%%')) . 'page=%d' . '">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, $start / $kmess, '<i class="fas fa-angle-left"></i>');
    if ($start > $kmess * $neighbors) {
        $out[] = sprintf($base_link, 1, '1');
    }

    if ($start > $kmess * ($neighbors + 1)) {
        $out[] = '<li class="page-item"><a class="page-link">...</a></li>';
    }

    for ($nCont = $neighbors; $nCont >= 1; $nCont--) {
        if ($start >= $kmess * $nCont) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    }

    $out[] = '<li class="page-item active"><a class="page-link">' . ($start / $kmess + 1) . '</a></li>';
    $tmpMaxPages = (int) (($total - 1) / $kmess) * $kmess;
    for ($nCont = 1; $nCont <= $neighbors; $nCont++) {
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    }

    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li class="page-item"><a class="page-link">...</a></li>';
    }

    if ($start + $kmess * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    }

    if ($start + $kmess < $total) {
        $display_page = ($start + $kmess) > $total ? $total : ($start / $kmess + 2);
        $out[] = sprintf($base_link, $display_page, '<i class="fas fa-angle-right"></i>');
    }
    $out[] = '</ul></nav>';
    return implode('', $out);
}

/**
 * Trả về địa chỉ IP của người dùng.
 *
 * Hàm này lấy địa chỉ IP của người dùng bằng cách kiểm tra các giá trị trong mảng siêu toàn cục $_SERVER.
 * Đầu tiên, nó kiểm tra xem khóa 'HTTP_CLIENT_IP' có tồn tại trong mảng và gán giá trị của nó cho biến $ip_address.
 * Nếu không, nó kiểm tra xem khóa 'HTTP_X_FORWARDED_FOR' có tồn tại và gán giá trị của nó cho biến $ip_address.
 * Nếu cả hai khóa đều không tồn tại, nó gán giá trị của khóa 'REMOTE_ADDR' cho biến $ip_address.
 *
 * @throws \Exception nếu địa chỉ IP không hợp lệ.
 * @return string địa chỉ IP của người dùng.
 */
function myIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return check_string($ip_address);
}

/**
 * Chuyển đổi một thời gian đã cho thành định dạng thời gian đã trôi qua dễ đọc được.
 *
 * @param string $time_ago Thời gian cần chuyển đổi.
 * @return string Định dạng thời gian đã trôi qua dễ đọc được.
 */
function timeAgo($time_ago)
{
    //$time_ago = date("Y-m-d H:i:s", $time_ago);
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    //Minutes
    else if ($minutes <= 60) {
        return "$minutes phút trước";
    }
    //Hours
    else if ($hours <= 24) {
        return "$hours tiếng trước";
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần trước";
    }
    //Months
    else if ($months <= 12) {
        return "$months tháng trước";
    }
    //Years
    else {
        return "$years năm trước";
    }
}

/**
 * Kiểm tra xem người dùng có phải là admin (quyền admin) hay không?
 *
 * @throws \Exception nếu người dùng chưa đăng nhập hoặc không phải là admin.
 * @return void
 */
function CheckAdmin()
{
    global $Database;
    if (!isset($_SESSION["account"])) {
        // Chưa khởi tạo biến $_SESSION["account"]
        // chuyển hướng người dùng về trang chủ của website: BASE_URL('/')
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);</script>');
    }

    // Lấy thông tin taikhoan từ DB
    $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");

    // Kiểm tra quyền hạn của tài khoản có phải là admin hay không?
    if ($taikhoan["MaQuyenHan"] != 2) {
        // Không phải admin
        // chuyển hướng người dùng về trang chủ của website: BASE_URL('/')
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);</script>');
    }
}

global $Database;
if ($Database->site("BaoTri") == "ON") {
    if (isset($_SESSION["account"])) {
        $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
        if ($taikhoan["MaQuyenHan"] == 1) {
            die($Database->site("NoiDungBaoTri"));
        }
    }
}

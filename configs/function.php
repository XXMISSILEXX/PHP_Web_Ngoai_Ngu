<?php
//
$config = [
    'project' => 'hocngoaingu',
    'url' => BASE_URL,
    'version' => '1.0.0',
    'ip_server' => '',
];

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
function getTime()
{
    return date('Y-m-d H:i:s', time());
}

function xoaDauCach($text)
{
    return trim(preg_replace('/\s+/', '', $text));
}

function checkUsername($data)
{
    if (preg_match('/^[a-zA-Z0-9_-]{3,16}$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}
function checkEmail($data)
{
    if (preg_match('/^.+@.+$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}
function checkPhone($data)
{
    if (preg_match('/^\+?(\d.*){3,}$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}

function encryptPassword($string)
{
    return md5($string);
}
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

function CheckAdmin()
{
    global $Database;
    if (!isset($_SESSION["account"])) {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);</script>');
    }
    $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");

    if ($taikhoan["MaQuyenHan"] != 2) {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);</script>');
    }
}

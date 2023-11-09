<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");


if (empty($_POST['type'])) {
    msg_error2('Dữ liệu không tồn tại');
}

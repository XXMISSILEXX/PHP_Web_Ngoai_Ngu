<style>
    <?= include_once(__DIR__ . "/../../assets/css/login.css");
    ?><?= include_once(__DIR__ . "/../../assets/css/main.css");
        ?>
</style>
<div class="header">
    <div class="grid wide">
        <div class="header_wrap">
            <a href="<?= BASE_URL("/") ?>">
                <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
            </a>
            <div class="nav">
                <a href="" class="nav__course">Các khóa học</a>
                <a href="<?= BASE_URL("Auth/DangNhap") ?>" class="nav__statr btn">Bắt đầu học</a>
            </div>
        </div>
    </div>
</div>
<div class="container" style="
    margin: 150px auto;
">
    <div class="grid wide">
        <form class="form" action="" id="form">
            <div class="form__title">Đăng ký</div>
            <div id="thongbao"></div>
            <input type="text" placeholder="Tên đăng nhập" class="form__account" id="account">
            <div class="form__password">
                <input type="password" class="input_password" placeholder="Mật khẩu" id="password">
                <div id="show">Show</div>
            </div>
            <input type="text" placeholder="Tên hiển thị" class="form__account" id="tenHienThi">
            <button type="submit" id="btnSignup" class="form__login btn">Tạo tài khoản</button>
            <span class="form__separate-text">HOẶC</span>
            <div class="form__separate"></div>
            <div class="wrap-social">
                <a href="<?= $loginFacebookUrl  ?>" class="social__item"><img src="<?= BASE_URL("/") ?>/assets/img/facebook.svg" alt="" class="social__item-img"><span class="social__item-text">FACEBOOK</span></a>
                <a href=<?= $client->createAuthUrl() ?> class="social__item"><img src="<?= BASE_URL("/") ?>/assets/img/google.svg" alt="" class="social__item-img"><span class="social__item-text">GOOGLE</span></a>
            </div>
            <div class="form__not-account">
                Đã có tài khoản? <a href="<?= BASE_URL("Auth/DangNhap")  ?>" class="form__not-account-link">Đăng nhập ngay</a>
            </div>
        </form>
    </div>
</div>
<script src="<?= BASE_URL("/") ?>/assets/javascript/show-password.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#form").submit(function(e) {
            e.preventDefault();
        });
    });
    $("#btnSignup").on("click", function() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Auth.php"); ?>",
            method: "POST",
            data: {
                type: 'signup',
                account: $("#account").val().trim(),
                tenHienThi: $("#tenHienThi").val().trim(),
                password: $("#password").val().trim()
            },
            beforeSend: function() {
                $('#btnSignup').html('Đang xử lý').addClass("disabled");
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                $("#thongbao").html(response);
                $('#btnSignup').html('Tạo tài khoản').removeClass("disabled");
                $('#loading_modal').removeClass("loading--open");
            }
        });
    });
</script>
<?php
require_once(__DIR__ . "/../../public/client/footer.php");
?>
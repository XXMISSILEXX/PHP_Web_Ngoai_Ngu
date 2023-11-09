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
           
        </div>
    </div>
</div>
<div class="container" style="
    margin: 150px auto;
">
    <div class="grid wide">
        <form class="form" action="" id="form">
            <div class="form__title">Đăng nhập</div>
            <div id="thongbao"></div>
            <input type="text" placeholder="Tên đăng nhập" class="form__account" id="account">
            <div class="form__password">
                <input type="password" class="input_password" placeholder="Mật khẩu" id="password" />
                <div id="show">Show</div>
            </div>

            <a href="<?= BASE_URL("Auth/QuenMatKhau") ?>" class="form__forget-password">Quên mật khẩu?</a>
            <button type="submit" id="btnLogin" class="form__login btn">ĐĂNG NHẬP</button>
            <span class="form__separate-text">HOẶC</span>
            <div class="form__separate"></div>
            <div class="wrap-social">
                <a href="<?= $loginFacebookUrl  ?>" class="social__item"><img src="<?= BASE_URL("/") ?>/assets/img/facebook.svg" alt="" class="social__item-img"><span class="social__item-text">FACEBOOK</span></a>
                <a href=<?= $client->createAuthUrl() ?> class="social__item"><img src="<?= BASE_URL("/") ?>/assets/img/google.svg" alt="" class="social__item-img"><span class="social__item-text">GOOGLE</span></a>
            </div>
            <div class="form__not-account">
                Chưa có tài khoản? <a href="<?= BASE_URL("Auth/DangKy") ?>" class="form__not-account-link">Đăng kí ngay</a>
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
    $("#btnLogin").on("click", function() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Auth.php"); ?>",
            method: "POST",
            data: {
                type: 'login',
                account: $("#account").val().trim(),
                password: $("#password").val().trim()
            },
            beforeSend: function() {
                $('#btnLogin').html('Đang xử lý').addClass("disabled");
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                
                $('#loading_modal').removeClass("loading--open");
            }
        });
    });
</script>

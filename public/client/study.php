<div class="header">
    <div class="grid wide">
        <div class="header_wrap">
            <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
            <div class="nav">
                <div class="nav__statr">Khóa học <?= $khoaHoc["TenKhoaHoc"] ?></div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div id="loading"></div>
    <div id="thongbao"></div>
    <div class="grid wide">
        <a href="<?= BASE_URL("Page/Home") ?>" class="return_home_page btn">Trang chủ</a>
        <div class="content">
            <div class="targer-bar">
                <div class="targer-bar-value"></div>
            </div>
            <div id="study_content" style="width: 100%;"></div>
        </div>

    </div>
</div>
<script>
    let audioWord = '';

    function danhDauTuKho(maTuVung, maBaiHoc, maKhoaHoc) {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Study.php"); ?>",
            method: "POST",
            data: {
                type: 'DanhDauTuKho',
                maTuVung: maTuVung,
                maKhoaHoc: maKhoaHoc,
                maBaiHoc: maBaiHoc,
                token: "<?= $_SESSION["thongTinTokenStudy"] ?>"
            },
            beforeSend: function() {
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                $('#loading_modal').removeClass("loading--open");
                $("#thongbao").empty().append(response);

            }
        })


    }



    function setBarValue(value = 0) {
        $(".targer-bar-value").width(`${value}%`);
    }

    function setViDu(data) {
        let str = '';

        data.map((item, index) => {
            str += `
            <div class="example-container">
                <div class="example-for-new-words">${index+1}. ${item.CauViDu}</div>
                <div class="example-for-new-words-sub">${item.DichNghia}</div>
                </div>`
        });
        return str;

    }

    function tiepTuc() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Study.php"); ?>",
            method: "POST",
            data: {
                type: 'GetNewWord',
                maKhoaHoc: <?= $_GET["maKhoaHoc"] ?>,
                maBaiHoc: <?= $_GET["maBaiHoc"] ?>,
                token: "<?= $_SESSION["thongTinTokenStudy"] ?>"
            },
            beforeSend: function() {
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                $('#loading_modal').removeClass("loading--open");
                let json = $.parseJSON(response);
                if (json.status === "complete") {
                    toastr.success("Không còn từ mới để học", "Thành công!");
                    setTimeout(() => {
                        window.history.back();
                    }, 500)
                    return;
                }
                audioWord = new Audio(json.data.data.AmThanh);
                setBarValue(json.data.tienTrinh);
                let strLuuTu = `danhDauTuKho(${json.data.data.MaTuVung},${json.data.data.MaBaiHoc},${json.data.data.MaKhoaHoc})`;
                let str = `     
            <div class="warp-introduce">
                <div class="introduce__new">Bạn ơi, có từ mới nè</div>
                <div class="warp-introduce__ponit">
                    <img src="<?= BASE_URL("/") ?>/assets/img/introcduce-words.svg" alt="" class="introduce__point-img">
                    <span class="introcduce__point-number">${json.data.data.Diem}</span>
                </div>
            </div>

            <div class="conjunction">
            <div class="conjunction_container">
            <div class="new-words">
                <div class="new-words__heading">${json.data.data.NoiDungTuVung}</div>
                <div class="new-words__heading-sub">${json.data.data.DichNghia}</div>
                <div class="new-words__wrap-img">
                    <img src="${json.data.data.HinhAnh}" alt="" class="new-words__img">
                    <div class="new-words__btn" onclick={playSound('${json.data.data.AmThanh}')}><i class="new-words__btn-icon fa-solid fa-volume-high"></i></div>
                </div>
                ${json.data.viDu && json.data.viDu.length > 0 ? ` <div class="example">Ví dụ</div> ${setViDu(json.data.viDu)}` : ``}
             
                          </div>
                          </div>
                          <div class="study_review_button_container"><div onclick={${strLuuTu}} class="btn btn--card study_review_button"><img src="<?= BASE_URL("/") ?>/assets/img/modal-degree.svg" alt=""></div></div>
            ${json.data.tienTrinh === 100 ? `<div onclick={tiepTuc()} class="continue__btn btn btn--primary">Trang chủ</div>` : `<div onclick={tiepTuc()} class="continue__btn btn btn--primary">Tiếp tục</div>`}
            
            </div>
        `;

                $("#study_content").empty().append(str);
            }
        })
    };
    $(document).ready(function() {
        tiepTuc();
    });
</script>
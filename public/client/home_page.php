<div class="grid">
    <div class="row main-page">
        <div class="nav-container">

        </div>

        <div class="main_content-container">
            <div class="my-course">
                <div class="page__title">Khóa học của tôi:</div>


                <div class="my-course__plan card">
                    <img src=<?= $row["LinkAnh"] ?> alt="" class="my-course__plan-img">
                    <div class="my-course__plan-content">
                        <div class="my-course__plan-heading">
                            <div class="my-course__plan-heading-text">
                                <a href="<?= BASE_URL('Page/KhoaHoc/' . $row['MaKhoaHoc'] . '') ?>">
                                    <?= $row["TenKhoaHoc"] ?>
                                </a>
                            </div>
                        </div>
                        <div class="my-course__plan-heading-sub"><span class="my-course__plan-percent">
                                <?= $tienTrinhHoc >= 100 ? 100 : $tienTrinhHoc  ?>%
                            </span><span class="my-course__planned">Đã học
                                <?= $soTuDaHoc ?>/<?= $tongSoTuVung ?>
                            </span></div>
                        <div class="my-course__plan-bar">
                            <div class="my-course__plan-bar-value-english <?= $row['MaKhoaHoc'] ?>" style="width: <?= $tienTrinhHoc >= 100 ? 100 : $tienTrinhHoc ?>%" title="<?= $tienTrinhHoc >= 100 ? 100 : $tienTrinhHoc ?>%"></div>
                        </div>
                        <div class="my-course__plan-tick">
                            <div class="my-course__plan-tick-box" title="Số học viên: <?= $soHocVien ?>">
                                <img src="<?= BASE_URL("/") ?>/assets/img/practice.svg" alt="" class="my-course__plan-tick-img">
                                <span class="my-course__plan-tick-number">
                                    <?= $soHocVien ?>
                                </span>
                            </div>
                            <div class="my-course__plan-tick-box" title="Đã đánh dấu: <?= $tongSoTuVungKho ?> từ khó">

                                <img src="<?= BASE_URL("/") ?>/assets/img/license.svg" alt="" class="my-course__plan-tick-img">
                                <span class="my-course__plan-tick-number">
                                    <?= $tongSoTuVungKho ?>
                                </span>
                            </div>

                            <a href="<?= BASE_URL('Page/KhoaHoc/' . $row['MaKhoaHoc'] . '') ?>" style="margin-left: auto;">
                                <div class="my-course__plan-tick-btn btn">Học tập</div>
                            </a>

                        </div>
                    </div>
                </div>


            </div>
        </div>
        
<style>
    /* Bố cục Full-width tràn lề, phong cách eSports góc cạnh */
    .hero-wrapper { background-color: #111; }

    /* Banner chính bên trái */
    .banner-main {
        background: #1a1a1a;
        padding: 50px;
        height: 450px;
        display: flex;
        align-items: center;
    }

    /* Banner phụ bên phải */
    .banner-side {
        height: 450px;
        position: relative;
        background: url('/LTWNC_BAN_HANG/assets/images/img/header-img.jpg') center/cover no-repeat;
    }

    /* Lớp phủ tối cho banner phụ để làm nổi bật chữ */
    .overlay-side {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 30px;
    }

    .save-badge {
        position: absolute; top: 20px; left: 20px;
        background: #F28B00; color: #fff;
        padding: 6px 16px; font-weight: bold; font-size: 1.1rem;
    }

    /* Định dạng nút bấm màu cam eSports */
    .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; font-weight: bold; }
    .btn-orange:hover { background-color: #d67a00 !important; }
    .text-orange { color: #F28B00 !important; }
</style>

<div class="container-fluid hero-wrapper px-0">
    <div class="row g-0">

        <div class="col-lg-9">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="banner-main">
                            <div class="row w-100 align-items-center">
                               <div class="col-md-6 text-center wow fadeInLeft" data-wow-delay="0.1s">
    <img src="/LTWNC_BAN_HANG/assets/images/img/carousel-1.png" class="img-fluid" alt="Banner Khuyến Mãi">
</div>

<div class="col-md-6 text-white ps-4 wow fadeInRight" data-wow-delay="0.3s">
    <h5 class="text-orange text-uppercase fw-bold" style="letter-spacing: 2px;">Tiết kiệm lên đến 10 Triệu VNĐ</h5>
    <h1 class="fw-bold fs-1 my-3">Cho Các Dòng Laptop, PC & Smartphone Chọn Lọc</h1>
    <p class="text-white-50 mb-4">Áp dụng theo các điều khoản và điều kiện của shop.</p>
    <button class="btn btn-orange rounded-pill px-5 py-3">Mua Ngay Hôm Nay</button>
</div>
                            </div>
                        </div>
                    </div>
                    </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="width: 5%;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="width: 5%;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.5s">
            <div class="banner-side">
                <div class="save-badge">Giảm 2.000.000đ</div>
                <div class="overlay-side text-white text-center text-lg-start">
                    <p class="m-0 text-white-50 fs-5">Điện Thoại Thông Minh</p>
                    <h3 class="fw-bold my-2">Apple iPad Mini G2356</h3>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <del class="text-white-50 me-3 fs-5">15.500.000đ</del>
                        <span class="text-orange fw-bold fs-4">13.500.000đ</span>
                    </div>
                    <button class="btn btn-orange rounded-pill w-100 py-2"><i class="fas fa-shopping-cart me-2"></i> Thêm Vào Giỏ</button>
                </div>
            </div>
        </div>

    </div>
</div>
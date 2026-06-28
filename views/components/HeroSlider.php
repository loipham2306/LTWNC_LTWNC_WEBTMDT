<style>
    /* Bố cục Full-width tràn lề */
    .hero-wrapper { background-color: #111; font-family: 'Segoe UI', sans-serif; overflow: hidden; }

    /* Banner chính bên trái */
    .banner-main {
        background: #151515;
        padding: 0 !important;
        /* Chuyển height cố định thành min-height để mobile có thể đẩy khối chữ xuống dưới */
        min-height: 480px; 
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    /* 1. KHỐI NỀN CAM (Mặc định cho PC) */
    .esport-bg {
        background-color: #F28B00;
        height: 480px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        clip-path: polygon(0 0, 100% 0, 82% 48%, 95% 48%, 78% 100%, 0 100%);
    }

    /* 2. ẢNH SẢN PHẨM (Mặc định cho PC) */
    .hero-img-full {
        width: 100%;
        height: 480px;
        object-fit: cover;
        object-position: top center;
        clip-path: polygon(0 0, 98% 0, 80% 48%, 93% 48%, 76% 100%, 0 100%);
    }

    /* Banner phụ bên phải */
    .banner-side {
        height: 480px;
        position: relative;
        background: url('/LTWNC_LTWNC_WEBTMDT/assets/images/img/Nike Air Jordan 1 Low.jpg') center/cover no-repeat;
    }

    .overlay-side {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.9) 100%);
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 30px;
    }

    .save-badge {
        position: absolute; top: 20px; right: 20px;
        background: #F28B00; color: #fff;
        padding: 6px 16px; font-weight: bold; font-size: 1rem;
        text-transform: uppercase;
        clip-path: polygon(0 0, 100% 0, 90% 100%, 10% 100%);
        z-index: 10;
    }

    .btn-orange { 
        background-color: #F28B00 !important; color: #fff !important; 
        border: none; font-weight: bold; text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-orange:hover { background-color: #d67a00 !important; transform: scale(1.05); }
    .text-orange { color: #F28B00 !important; }

    /* =========================================
       🔥🔥 RESPONSIVE CHO ĐIỆN THOẠI VÀ TABLET
       ========================================= */
    @media (max-width: 991px) {
        /* Bỏ viền cắt xéo trên Mobile để hình tràn viền nhìn rõ hơn */
        .esport-bg {
            height: 350px; /* Thu thấp ảnh xuống cho Mobile */
            clip-path: none;
        }
        .hero-img-full {
            height: 350px;
            clip-path: none;
        }
        /* Căn chỉnh lại khối chữ cho Mobile (Nằm dưới ảnh thay vì bên cạnh) */
        .mobile-text-box {
            padding: 30px 20px !important;
            text-align: center;
        }
        /* Thu nhỏ chữ tiêu đề */
        .mobile-text-box h1 { font-size: 2rem !important; }
        
        /* Chỉnh lại chiều cao banner phụ bên phải cho Mobile (đang bị rớt xuống dưới cùng) */
        .banner-side { height: 350px; }
    }
</style>

<div class="container-fluid hero-wrapper px-0">
    <div class="row g-0">

        <div class="col-lg-9">
            <div id="mainHeroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    
                    <div class="carousel-item active">
                        <div class="banner-main">
                            <div class="row g-0 w-100 align-items-center"> 
                                <div class="col-12 col-md-6 p-0">
                                    <div class="esport-bg">
                                        <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/img/xanh_Adidas Tiro 25 Essentials Training Top - Black _adidas UK .jpg" class="hero-img-full" alt="BST Adidas Originals">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 text-white ps-4 pe-4 mobile-text-box">
                                    <h5 class="text-orange text-uppercase fw-bold" style="letter-spacing: 3px; font-size: 1rem;">CỰC PHẨM STREETWEAR</h5>
                                    <h1 class="fw-bold fs-1 my-3" style="line-height: 1.1;">Trọn Bộ BST Adidas Originals Mới Nhất</h1>
                                    <p class="text-white-50 mb-4 fs-5">Nâng tầm phong cách, bứt phá giới hạn. Giảm ngay 30% cho chủ thẻ thành viên Trạm Hiệu.</p>
                                    <button class="btn btn-orange rounded-0 px-4 px-lg-5 py-3 fs-6 fs-lg-5">KHÁM PHÁ NGAY</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="banner-main">
                            <div class="row g-0 w-100 align-items-center"> 
                                <div class="col-12 col-md-6 p-0">
                                    <div class="esport-bg">
                                        <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/img/Levi's - Áo Sơ Mi Nam Tay Ngắn Relaxed.jpg" class="hero-img-full" alt="BST Uniqlo LifeWear">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 text-white ps-4 pe-4 mobile-text-box">
                                    <h5 class="text-orange text-uppercase fw-bold" style="letter-spacing: 3px; font-size: 1rem;">THỜI TRANG TỐI GIẢN</h5>
                                    <h1 class="fw-bold fs-1 my-3" style="line-height: 1.1;">Công Nghệ LifeWear Từ UNIQLO</h1>
                                    <p class="text-white-50 mb-4 fs-5">Thoải mái mọi lúc, tự tin mọi nơi. Tận hưởng ưu đãi độc quyền 20% cho bộ sưu tập Thu - Đông.</p>
                                    <button class="btn btn-orange rounded-0 px-4 px-lg-5 py-3 fs-6 fs-lg-5">MUA SẮM NGAY</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="prev" style="width: 5%;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="next" style="width: 5%;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="banner-side">
                <div class="save-badge">GIẢM GIÁ -20%</div>
                <div class="overlay-side text-white text-center text-lg-start">
                    <p class="m-0 text-white-50 fs-6 text-uppercase" style="letter-spacing: 1px;">Giày Chạy Bộ Nam</p>
                    <h3 class="fw-bold my-2">Nike Air Jordan 1 Low</h3>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <del class="text-white-50 me-3 fs-5">5.200.000đ</del>
                        <span class="text-orange fw-bold fs-4">4.160.000đ</span>
                    </div>
                    <button class="btn btn-orange rounded-0 w-100 py-3 fs-6"><i class="fas fa-shopping-cart me-2"></i> THÊM VÀO GIỎ</button>
                </div>
            </div>
        </div>

    </div>
</div>
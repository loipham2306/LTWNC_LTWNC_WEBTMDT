import React from 'react';

const HeroSlider = () => {
  return (
    <>
      {/* Carousel Start */}
      <div className="container-fluid carousel bg-light px-0">
        <div className="row g-0 justify-content-end">
          <div className="col-12 col-lg-7 col-xl-9">
            <div className="header-carousel owl-carousel bg-light py-5">
              <div className="row g-0 header-carousel-item align-items-center">
                <div className="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                  <img src="/img/carousel-1.png" className="img-fluid w-100" alt="Banner Khuyến Mãi" />
                </div>
                <div className="col-xl-6 carousel-content p-4">
                  <h4 className="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s" style={{ letterSpacing: '3px' }}>
                    Tiết kiệm lên đến 10 Triệu VNĐ
                  </h4>
                  <h1 className="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">
                    Cho Các Dòng Laptop, PC & Smartphone Chọn Lọc
                  </h1>
                  <p className="text-dark wow fadeInRight" data-wow-delay="0.5s">Áp dụng theo các điều khoản và điều kiện của shop.</p>
                  <a className="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s" href="#">
                    Mua Ngay Hôm Nay
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div className="col-12 col-lg-5 col-xl-3 wow fadeInRight" data-wow-delay="0.1s">
            <div className="carousel-header-banner h-100">
              <img src="/img/header-img.jpg" className="img-fluid w-100 h-100" style={{ objectFit: 'cover' }} alt="Sản phẩm đặc biệt" />
              <div className="carousel-banner-offer">
                <p className="bg-primary text-white rounded fs-5 py-2 px-4 mb-0 me-3">Giảm 2.000.000đ</p>
                <p className="text-primary fs-5 fw-bold mb-0">Ưu Đãi Đặc Biệt</p>
              </div>
              <div className="carousel-banner">
                <div className="carousel-banner-content text-center p-4">
                  <a href="#" className="d-block mb-2">Điện Thoại Thông Minh</a>
                  <a href="#" className="d-block text-white fs-3">Apple iPad Mini <br /> G2356</a>
                  <del className="me-2 text-white fs-5">15.500.000đ</del>
                  <span className="text-primary fs-5">13.500.000đ</span>
                </div>
                <a href="#" className="btn btn-primary rounded-pill py-2 px-4">
                  <i className="fas fa-shopping-cart me-2"></i> Thêm Vào Giỏ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      {/* Carousel End */}
    </>
  );
};

export default HeroSlider;
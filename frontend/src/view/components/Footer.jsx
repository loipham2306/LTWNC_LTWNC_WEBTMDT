import React from 'react';

const Footer = () => {
  return (
    <div className="container-fluid bg-dark text-white-50 footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
      <div className="container py-5">
        <div className="row g-5">
          <div className="col-md-6 col-lg-3">
            <h1 className="fw-bold text-primary mb-4">LuLo<span className="text-white">Shop</span></h1>
            <p className="mb-4">Hệ thống mua sắm trực tuyến hàng đầu, mang đến cho bạn trải nghiệm mua sắm tuyệt vời nhất với nhiều loại mặt hàng chính hãng 100%.</p>
            <div className="d-flex pt-2">
              <a className="btn btn-square btn-outline-primary rounded-circle me-1" href="#"><i className="fab fa-twitter"></i></a>
              <a className="btn btn-square btn-outline-primary rounded-circle me-1" href="#"><i className="fab fa-facebook-f"></i></a>
              <a className="btn btn-square btn-outline-primary rounded-circle me-1" href="#"><i className="fab fa-youtube"></i></a>
              <a className="btn btn-square btn-outline-primary rounded-circle me-0" href="#"><i className="fab fa-linkedin-in"></i></a>
            </div>
          </div>
          <div className="col-md-6 col-lg-3">
            <h4 className="text-light mb-4">Chăm Sóc Khách Hàng</h4>
            <a className="btn btn-link" href="#">Trợ Giúp & Liên Hệ</a>
            <a className="btn btn-link" href="#">Chính Sách Đổi Trả</a>
            <a className="btn btn-link" href="#">Lịch Sử Đơn Hàng</a>
            <a className="btn btn-link" href="#">Theo Dõi Đơn Hàng</a>
          </div>
          <div className="col-md-6 col-lg-3">
            <h4 className="text-light mb-4">Thông Tin</h4>
            <a className="btn btn-link" href="#">Về Chúng Tôi</a>
            <a className="btn btn-link" href="#">Thông Tin Giao Hàng</a>
            <a className="btn btn-link" href="#">Chính Sách Bảo Mật</a>
            <a className="btn btn-link" href="#">Điều Khoản Dịch Vụ</a>
          </div>
          <div className="col-md-6 col-lg-3">
            <h4 className="text-light mb-4">Liên Hệ</h4>
            <p><i className="fa fa-map-marker-alt me-3"></i> 624 Đường Âu Cơ, Phường Bảy Hiền, Thành phố Hồ Chí Minh, Việt Nam</p>
            <p><i className="fa fa-phone-alt me-3"></i> +84 123 456 789</p>
            <p><i className="fa fa-envelope me-3"></i> hotro@electro.vn</p>
          </div>
        </div>
      </div>
      <div className="container border-top border-secondary py-4">
        <div className="row">
          <div className="col-md-6 text-center text-md-start mb-3 mb-md-0">
            &copy; <a className="border-bottom" href="#">LuLoShop</a>. Tất cả quyền được bảo lưu.
          </div>
          <div className="col-md-6 text-center text-md-end">
            Thiết kế bởi <a className="border-bottom" href="#">..</a>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Footer;
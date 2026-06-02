import React from 'react';

const ServiceFeatures = () => {
  return (
    <>
      {/* Services Start */}
      <div className="container-fluid px-0">
        <div className="row g-0">
          <div className="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
            <div className="p-4">
              <div className="d-inline-flex align-items-center">
                <i className="fa fa-sync-alt fa-2x text-primary"></i>
                <div className="ms-4">
                  <h6 className="text-uppercase mb-2">Đổi Trả Miễn Phí</h6>
                  <p className="mb-0">Hoàn tiền trong vòng 30 ngày!</p>
                </div>
              </div>
            </div>
          </div>
          <div className="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.2s">
            <div className="p-4">
              <div className="d-flex align-items-center">
                <i className="fab fa-telegram-plane fa-2x text-primary"></i>
                <div className="ms-4">
                  <h6 className="text-uppercase mb-2">Freeship</h6>
                  <p className="mb-0">Miễn phí vận chuyển mọi đơn hàng</p>
                </div>
              </div>
            </div>
          </div>
          <div className="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.3s">
            <div className="p-4">
              <div className="d-flex align-items-center">
                <i className="fas fa-life-ring fa-2x text-primary"></i>
                <div className="ms-4">
                  <h6 className="text-uppercase mb-2">Hỗ Trợ 24/7</h6>
                  <p className="mb-0">Luôn túc trực giải đáp thắc mắc</p>
                </div>
              </div>
            </div>
          </div>
          <div className="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.4s">
            <div className="p-4">
              <div className="d-flex align-items-center">
                <i className="fas fa-credit-card fa-2x text-primary"></i>
                <div className="ms-4">
                  <h6 className="text-uppercase mb-2">Quà Tặng Khủng</h6>
                  <p className="mb-0">Tặng voucher cho đơn từ 1 triệu</p>
                </div>
              </div>
            </div>
          </div>
          <div className="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.5s">
            <div className="p-4">
              <div className="d-flex align-items-center">
                <i className="fas fa-lock fa-2x text-primary"></i>
                <div className="ms-4">
                  <h6 className="text-uppercase mb-2">Bảo Mật Cao</h6>
                  <p className="mb-0">Thanh toán an toàn tuyệt đối</p>
                </div>
              </div>
            </div>
          </div>
          <div className="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.6s">
            <div className="p-4">
              <div className="d-flex align-items-center">
                <i className="fas fa-blog fa-2x text-primary"></i>
                <div className="ms-4">
                  <h6 className="text-uppercase mb-2">Dịch Vụ Nhanh</h6>
                  <p className="mb-0">Giải quyết khiếu nại trong 24h</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {/* Services End */}
    </>
  );
};

export default ServiceFeatures;
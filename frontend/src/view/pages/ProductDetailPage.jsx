import React, { useState, useEffect } from 'react';
import PageHeader from '../components/PageHeader';

const ProductDetailPage = () => {
  // 1. Quản lý ảnh đang hiển thị (thay cho owl-carousel)
  const [mainImg, setMainImg] = useState('/img/product-4.png');
  
  // 2. Quản lý số lượng sản phẩm
  const [quantity, setQuantity] = useState(1);

  // Kích hoạt hiệu ứng WOW.js nếu có
  useEffect(() => {
    if (window.WOW) { new window.WOW().init(); }
  }, []);

  // Hàm xử lý tăng giảm số lượng
  const handleQuantity = (type) => {
    if (type === 'minus' && quantity > 1) setQuantity(quantity - 1);
    if (type === 'plus') setQuantity(quantity + 1);
  };

  return (
    <div className="container-fluid p-0">
      {/* Kế thừa lại PageHeader đã làm cực chuẩn */}
      <PageHeader title="Chi Tiết Sản Phẩm" breadcrumb="Sản Phẩm" />

      {/* Single Products Start */}
      <div className="container-fluid shop py-5">
        <div className="container py-5">
          <div className="row g-4">
            
            {/* --- SIDEBAR BÊN TRÁI --- */}
            <div className="col-lg-5 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
              <div className="input-group w-100 mx-auto d-flex mb-4">
                <input type="search" className="form-control p-3" placeholder="Tìm kiếm..." />
                <span className="input-group-text p-3"><i className="fa fa-search"></i></span>
              </div>
              
              <div className="product-categories mb-4">
                <h4>Danh Mục</h4>
                <ul className="list-unstyled">
                  <li>
                    <div className="categories-item">
                      <a href="#" className="text-dark"><i className="fas fa-apple-alt text-secondary me-2"></i> Điện Tử & Máy Tính</a>
                      <span>(5)</span>
                    </div>
                  </li>
                  <li>
                    <div className="categories-item">
                      <a href="#" className="text-dark"><i className="fas fa-apple-alt text-secondary me-2"></i> Điện Thoại & Tablet</a>
                      <span>(8)</span>
                    </div>
                  </li>
                </ul>
              </div>

              {/* Banner giảm giá */}
              <a href="#">
                <div className="position-relative">
                  <img src="/img/product-banner-2.jpg" className="img-fluid w-100 rounded" alt="Khuyến mãi" />
                  <div className="text-center position-absolute d-flex flex-column align-items-center justify-content-center rounded p-4"
                    style={{ width: '100%', height: '100%', top: '0', right: '0', background: 'rgba(242, 139, 0, 0.3)' }}>
                    <h5 className="display-6 text-primary">SALE</h5>
                    <h4 className="text-white">Giảm Đến 50%</h4>
                    <button className="btn btn-primary rounded-pill px-4 mt-2">Mua Ngay</button>
                  </div>
                </div>
              </a>
            </div>
            {/* --- KẾT THÚC SIDEBAR --- */}


            {/* --- NỘI DUNG CHI TIẾT SẢN PHẨM BÊN PHẢI --- */}
            <div className="col-lg-7 col-xl-9 wow fadeInUp" data-wow-delay="0.1s">
              <div className="row g-4 single-product">
                
                {/* Cột ảnh sản phẩm */}
                <div className="col-xl-6">
                  {/* Ảnh chính */}
                  <div className="bg-light rounded mb-3 p-4 d-flex justify-content-center align-items-center" style={{height: '400px'}}>
                    <img src={mainImg} className="img-fluid rounded" style={{maxHeight: '100%'}} alt="Sản phẩm chính" />
                  </div>
                  {/* List ảnh nhỏ (Thumbnails) */}
                  <div className="d-flex justify-content-between">
                    {['/img/product-4.png', '/img/product-5.png', '/img/product-6.png', '/img/product-7.png'].map((imgSrc, index) => (
                      <div 
                        key={index} 
                        className="bg-light rounded p-2" 
                        style={{width: '22%', cursor: 'pointer', border: mainImg === imgSrc ? '2px solid #F28B00' : 'none'}}
                        onClick={() => setMainImg(imgSrc)}
                      >
                        <img src={imgSrc} className="img-fluid" alt={`Ảnh ${index}`} />
                      </div>
                    ))}
                  </div>
                </div>

                {/* Cột thông tin sản phẩm */}
                <div className="col-xl-6">
                  <h4 className="fw-bold mb-3">Smart Camera Pro Max</h4>
                  <p className="mb-3">Danh mục: <span className="text-primary">Điện Tử</span></p>
                  <h5 className="fw-bold mb-3 fs-3">3,350,000 VNĐ</h5>
                  
                  <div className="d-flex mb-4">
                    <i className="fa fa-star text-warning"></i>
                    <i className="fa fa-star text-warning"></i>
                    <i className="fa fa-star text-warning"></i>
                    <i className="fa fa-star text-warning"></i>
                    <i className="fa fa-star text-secondary"></i>
                    <span className="ms-2">(15 đánh giá)</span>
                  </div>

                  <div className="d-flex flex-column mb-3">
                    <small>Mã SP: CAM-2026</small>
                    <small>Tình trạng: <strong className="text-primary">Còn 20 sản phẩm</strong></small>
                  </div>
                  
                  <p className="mb-4">
                    Camera thông minh tích hợp AI nhận diện khuôn mặt, đàm thoại 2 chiều rõ nét. Phù hợp cho an ninh gia đình và cửa hàng.
                  </p>

                  {/* Bộ chọn số lượng */}
                  <div className="input-group quantity mb-4" style={{ width: '130px' }}>
                    <div className="input-group-btn">
                      <button className="btn btn-sm btn-minus rounded-circle bg-light border" onClick={() => handleQuantity('minus')}>
                        <i className="fa fa-minus"></i>
                      </button>
                    </div>
                    <input type="text" className="form-control form-control-sm text-center border-0 fw-bold" value={quantity} readOnly />
                    <div className="input-group-btn">
                      <button className="btn btn-sm btn-plus rounded-circle bg-light border" onClick={() => handleQuantity('plus')}>
                        <i className="fa fa-plus"></i>
                      </button>
                    </div>
                  </div>

                  <button className="btn btn-primary border border-secondary rounded-pill px-5 py-3 mb-4 fw-bold">
                    <i className="fa fa-shopping-bag me-2 text-white"></i> Thêm Vào Giỏ
                  </button>
                </div>

                {/* Phần Tabs (Mô tả & Đánh giá) */}
                <div className="col-lg-12 mt-5">
                  <nav>
                    <div className="nav nav-tabs mb-3">
                      <button className="nav-link active border-white border-bottom-0" data-bs-toggle="tab" data-bs-target="#nav-about">Mô Tả Sản Phẩm</button>
                      <button className="nav-link border-white border-bottom-0" data-bs-toggle="tab" data-bs-target="#nav-mission">Đánh Giá (2)</button>
                    </div>
                  </nav>
                  <div className="tab-content mb-5">
                    <div className="tab-pane active" id="nav-about">
                      <p>Sản phẩm chính hãng, bảo hành 12 tháng. Camera hỗ trợ xoay 360 độ, kết nối wifi ổn định và dễ dàng quản lý qua ứng dụng trên điện thoại.</p>
                      <b className="fw-bold">Tính năng nổi bật:</b>
                      <ul className="mt-2">
                        <li>Độ phân giải 2K siêu nét.</li>
                        <li>Phát hiện chuyển động thông minh.</li>
                        <li>Lưu trữ thẻ nhớ lên đến 256GB.</li>
                      </ul>
                    </div>
                    <div className="tab-pane" id="nav-mission">
                      <p>Hiện chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            {/* --- KẾT THÚC NỘI DUNG --- */}

          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetailPage;
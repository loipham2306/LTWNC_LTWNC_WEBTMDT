import React from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import { Link } from 'react-router-dom';
const Header = () => {
  // 2. Lấy thông tin đường dẫn hiện tại
  const location = useLocation();
  const isShopActive = location.pathname.includes('/shop') || location.pathname.includes('/product');

  return (
    <>

      {/* Brand & Search Start */}
      <div className="container-fluid px-5 py-4 d-none d-lg-block">
        <div className="row gx-0 align-items-center text-center">
          <div className="col-md-4 col-lg-3 text-center text-lg-start">
            <div className="d-inline-flex align-items-center">
              <a href="#" className="navbar-brand p-0">
                <h1 className="display-5 text-primary m-0">
                  <img src="../../../public/img/th.png" alt="" class="h-100px" />
                </h1>
              </a>
            </div>
          </div>
          <div className="col-md-4 col-lg-6 text-center">
            <div className="position-relative ps-4">
              <div className="d-flex border rounded-pill">
                <input className="form-control border-0 rounded-pill w-100 py-3" type="text" placeholder="Bạn đang tìm sản phẩm gì?" />
                <button type="button" className="btn btn-primary rounded-pill py-3 px-5" style={{ border: 0 }}>
                  <i className="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
          <div className="col-md-4 col-lg-3 text-center text-lg-end">
            <div className="d-inline-flex align-items-center gap-3">

              <a href="#" className="text-muted d-flex align-items-center justify-content-center">
                <span className="rounded-circle btn-md-square border"><i className="fas fa-heart"></i></span>
              </a>

              {/* Icon Giỏ Hàng*/}
              <Link to="/cart" className="text-muted d-flex align-items-center justify-content-center">
                <span className="rounded-circle btn-md-square border"><i className="fas fa-shopping-cart"></i></span>
              </Link>

              {/* Icon Tài Khoản */}
              <Link to="/profile" className="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style={{ width: '45px', height: '45px' }}>
                <i className="fas fa-user text-white"></i>
              </Link>

            </div>
          </div>
        </div>
      </div>
      {/* Brand & Search End */}


      {/* Navbar Start */}
      <div className="container-fluid nav-bar p-0 mb-0">
        <div className="row gx-0 bg-primary px-5 align-items-center mb-0">
          <div className="col-12 col-lg-12">
            <nav className="navbar navbar-expand-lg navbar-light bg-primary">
              <button className="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span className="fa fa-bars fa-1x"></span>
              </button>
              <div className="collapse navbar-collapse" id="navbarCollapse">
                <div className="navbar-nav ms-auto py-0">
                  <NavLink to="/" className="nav-item nav-link">Trang Chủ</NavLink>
                  <NavLink to="/shop" className={`nav-item nav-link ${isShopActive ? 'active' : ''}`}>Cửa hàng</NavLink>
                  <NavLink to="/cart" className="nav-item nav-link">Giỏ Hàng</NavLink>
                  <NavLink to="/checkout" className="nav-item nav-link">Thanh Toán</NavLink>
                  <NavLink to="/contact" className="nav-item nav-link me-2">Liên Hệ</NavLink>
                </div>

                <a href="tel:0123456789" className="btn btn-secondary rounded-pill py-2 px-4">
                  <i className="fa fa-mobile-alt me-2"></i> 0123 456 789
                </a>
              </div>
            </nav>
          </div>
        </div>
      </div>
      {/* Navbar End */}
    </>
  );
};

export default Header;
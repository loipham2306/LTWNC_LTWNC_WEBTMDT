import React from 'react';
import { Link, Outlet, useLocation, useNavigate } from 'react-router-dom';

const AdminLayout = () => {
  const location = useLocation();
  const navigate = useNavigate();

  // Hàm xử lý đăng xuất
  const handleLogout = () => {
    if (window.confirm('Thoát khỏi trang Quản Trị?')) {
      navigate('/login');
    }
  };

  const isActive = (path) => location.pathname === path;

  return (
    <div className="d-flex" style={{ minHeight: '100vh', backgroundColor: '#111' }}>

      {/* ================= 1. SIDEBAR (THANH ĐIỀU HƯỚNG CỐ ĐỊNH) ================= */}
      <div className="d-flex flex-column flex-shrink-0 p-3 shadow-lg" style={{ width: '260px', backgroundColor: '#1a1a1a', borderRight: '1px solid #333' }}>
        <Link to="/admin" className="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-decoration-none justify-content-center w-100 mt-2">
          <h2 className="fw-bold m-0" style={{ color: '#F28B00' }}>
            <i className="fas fa-user-shield me-2"></i>Admin
          </h2>
        </Link>
        <hr className="text-secondary" />

        <ul className="nav nav-pills flex-column mb-auto gap-2">
          <li className="nav-item">
            <Link
              to="/admin"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${isActive('/admin') ? 'text-white' : 'text-muted'}`}
              style={{ backgroundColor: isActive('/admin') ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-tachometer-alt me-3" style={{ width: '20px' }}></i> Tổng Quan
            </Link>
          </li>
          <li>
            <Link
              to="/admin/category"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${isActive('/admin/category') ? 'text-white' : 'text-muted'}`}
              style={{ backgroundColor: isActive('/admin/category') ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-box me-3" style={{ width: '20px' }}></i> Quản Lý Danh Mục
            </Link>
          </li>
          <li>
            <Link
              to="/admin/products"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${isActive('/admin/products') ? 'text-white' : 'text-muted'}`}
              style={{ backgroundColor: isActive('/admin/products') ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-box me-3" style={{ width: '20px' }}></i> Quản Lý Sản Phẩm
            </Link>
          </li>
          <li>
            <Link
              to="/admin/brands"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 py-2 px-3 ${isActive('/admin/brands') ? 'text-white' : 'text-muted'}`}
              style={{
                backgroundColor: isActive('/admin/brands') ? '#F28B00' : 'transparent',
                borderRadius: '8px', // Thêm bo góc nhẹ cho nút menu khi được kích hoạt nhìn sẽ rất tây
                transition: 'all 0.3s'
              }}
            >
              {/* ĐÃ ĐỔI: Thay 'fa-box' thành 'fa-tag' đại diện cho nhãn hiệu/thương hiệu */}
              <i className="fas fa-tag me-3" style={{ width: '20px', fontSize: '18px' }}></i>
              Quản Lý Thương Hiệu
            </Link>
          </li>
          <li>
            <Link
              to="/admin/orders"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${isActive('/admin/orders') ? 'text-white' : 'text-muted'}`}
              style={{ backgroundColor: isActive('/admin/orders') ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-clipboard-list me-3" style={{ width: '20px' }}></i> Quản Lý Đơn Hàng
            </Link>
          </li>
          <li>
            <Link
              to="/admin/customers"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${isActive('/admin/customers') ? 'text-white' : 'text-muted'}`}
              style={{ backgroundColor: isActive('/admin/customers') ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-users me-3" style={{ width: '20px' }}></i> Quản Lý Khách Hàng
            </Link>
          </li>
          <li>
            <Link
              to="/admin/vouchers"
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${isActive('/admin/vouchers') ? 'text-white' : 'text-muted'}`}
              style={{ backgroundColor: isActive('/admin/vouchers') ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-ticket-alt me-3" style={{ width: '20px' }}></i> Quản Lý Voucher
            </Link>
          </li>
        </ul>

        <hr className="text-secondary" />
        <button onClick={handleLogout} className="btn btn-outline-danger fw-bold w-100 d-flex align-items-center justify-content-center">
          <i className="fas fa-sign-out-alt me-2"></i> Đăng Xuất
        </button>
      </div>

      {/* ================= 2. KHU VỰC NỘI DUNG CHÍNH ================= */}
      <div className="flex-grow-1 d-flex flex-column" style={{ overflowY: 'auto', maxHeight: '100vh' }}>

        {/* Header dùng chung cho mọi trang Admin */}
        <div className="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center" style={{ backgroundColor: '#111' }}>
          <h4 className="text-white fw-bold mb-0">Hệ Thống Quản Trị LuLoShop</h4>
          <div className="d-flex align-items-center gap-3">
            <button className="btn btn-dark border-secondary position-relative">
              <i className="fas fa-bell text-warning"></i>
              <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>
            <div className="d-flex align-items-center gap-2">
              <img src="https://ui-avatars.com/api/?name=Admin&background=F28B00&color=fff" alt="Admin" className="rounded-circle" width="40" height="40" />
              <span className="text-white fw-bold">Quản Trị Viên</span>
            </div>
          </div>
        </div>

        {/* NƠI RENDER COMPONENT CON (Dashboard, Products, v.v.) */}
        <div className="p-4 flex-grow-1" style={{ backgroundColor: '#111' }}>
          <Outlet />
        </div>

      </div>
    </div>
  );
};

export default AdminLayout;
import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

const AdminDashboardPage = () => {
  const navigate = useNavigate();
  // Quản lý menu đang được chọn ('overview' hoặc 'products')
  const [activeMenu, setActiveMenu] = useState('overview');

  // --- DỮ LIỆU TỔNG QUAN ---
  const stats = [
    { title: 'Tổng Doanh Thu', value: '125.400.000 đ', icon: 'fa-wallet', color: 'text-success' },
    { title: 'Đơn Hàng Mới', value: '45', icon: 'fa-shopping-cart', color: 'text-warning' },
    { title: 'Sản Phẩm', value: '128', icon: 'fa-box', color: 'text-primary' },
    { title: 'Khách Hàng', value: '1.204', icon: 'fa-users', color: 'text-info' }
  ];

  const revenueData = [
    { name: 'Tháng 1', revenue: 45000000 }, { name: 'Tháng 2', revenue: 52000000 },
    { name: 'Tháng 3', revenue: 38000000 }, { name: 'Tháng 4', revenue: 65000000 },
    { name: 'Tháng 5', revenue: 85000000 }, { name: 'Tháng 6', revenue: 125400000 },
  ];

  const recentOrders = [
    { id: '#ORD-001', customer: 'Nguyễn Văn A', date: '25/05/2026', total: '3.350.000 đ', status: 'Chờ Duyệt' },
    { id: '#ORD-002', customer: 'Trần Thị B', date: '24/05/2026', total: '10.500.000 đ', status: 'Đang Giao' },
  ];

  // --- DỮ LIỆU SẢN PHẨM ---
  const products = [
    { id: 'SP-101', name: 'Laptop Gaming ASUS ROG', category: 'Điện Tử', price: '25.000.000 đ', stock: 15, status: 'Còn hàng', imgIcon: 'fa-laptop' },
    { id: 'SP-102', name: 'Apple iPad Mini G2356', category: 'Tablet', price: '10.500.000 đ', stock: 0, status: 'Hết hàng', imgIcon: 'fa-tablet-alt' },
    { id: 'SP-103', name: 'Áo Đấu Thể Thao Team Flash', category: 'Quần Áo', price: '450.000 đ', stock: 50, status: 'Còn hàng', imgIcon: 'fa-tshirt' },
    { id: 'SP-104', name: 'Smart Camera Pro Max', category: 'Điện Tử', price: '3.350.000 đ', stock: 8, status: 'Còn hàng', imgIcon: 'fa-camera' },
    { id: 'SP-105', name: 'Tai nghe Bluetooth 5.0', category: 'Âm Thanh', price: '1.250.000 đ', stock: 120, status: 'Còn hàng', imgIcon: 'fa-headphones' },
  ];

  const handleLogout = () => {
    if (window.confirm('Thoát khỏi trang Quản Trị?')) navigate('/login');
  };

  return (
    <div className="d-flex" style={{ minHeight: '100vh', backgroundColor: '#111' }}>

      {/* ================= SIDEBAR ================= */}
      <div className="d-flex flex-column flex-shrink-0 p-3" style={{ width: '260px', backgroundColor: '#1a1a1a', borderRight: '1px solid #333' }}>
        <div className="d-flex align-items-center mb-4 mb-md-0 me-md-auto justify-content-center w-100 mt-2">
          <h2 className="fw-bold m-0" style={{ color: '#F28B00' }}>
            <i className="fas fa-user-shield me-2"></i>Admin
          </h2>
        </div>
        <hr className="text-secondary" />

        <ul className="nav nav-pills flex-column mb-auto gap-2">
          <li className="nav-item">
            <button
              onClick={() => setActiveMenu('overview')}
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${activeMenu === 'overview' ? 'text-white' : 'text-muted bg-transparent'}`}
              style={{ backgroundColor: activeMenu === 'overview' ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-tachometer-alt me-3" style={{ width: '20px' }}></i> Tổng Quan
            </button>
          </li>
          <li>
            <button
              onClick={() => setActiveMenu('products')}
              className={`nav-link fw-bold d-flex align-items-center w-100 text-start border-0 ${activeMenu === 'products' ? 'text-white' : 'text-muted bg-transparent'}`}
              style={{ backgroundColor: activeMenu === 'products' ? '#F28B00' : 'transparent', transition: 'all 0.3s' }}
            >
              <i className="fas fa-box me-3" style={{ width: '20px' }}></i> Quản Lý Sản Phẩm
            </button>
          </li>
          <li>
            <button className="nav-link text-muted fw-bold d-flex align-items-center w-100 text-start bg-transparent border-0">
              <i className="fas fa-clipboard-list me-3" style={{ width: '20px' }}></i> Quản Lý Đơn Hàng
            </button>
          </li>
          <li>
            <button className="nav-link text-muted fw-bold d-flex align-items-center w-100 text-start bg-transparent border-0">
              <i className="fas fa-users me-3" style={{ width: '20px' }}></i> Quản Lý Khách Hàng
            </button>
          </li>
        </ul>

        <hr className="text-secondary" />
        <button onClick={handleLogout} className="btn btn-outline-danger fw-bold w-100 d-flex align-items-center justify-content-center">
          <i className="fas fa-sign-out-alt me-2"></i> Đăng Xuất
        </button>
      </div>

      {/* ================= MAIN CONTENT ================= */}
      <div className="flex-grow-1 p-4" style={{ overflowY: 'auto', maxHeight: '100vh' }}>

        {/* Header Content */}
        <div className="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-4">
          <h3 className="text-white fw-bold mb-0">
            {activeMenu === 'overview' ? 'Dashboard Tổng Quan' : 'Quản Lý Danh Mục Sản Phẩm'}
          </h3>
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

        {/* --- NỘI DUNG TỔNG QUAN --- */}
        {activeMenu === 'overview' && (
          <>
            <div className="row g-4 mb-4">
              {stats.map((stat, index) => (
                <div className="col-md-6 col-xl-3" key={index}>
                  <div className="card border-0 rounded p-4 h-100 shadow-sm" style={{ backgroundColor: '#1a1a1a' }}>
                    <div className="d-flex justify-content-between align-items-center">
                      <div>
                        <p className="text-muted fw-bold mb-1">{stat.title}</p>
                        <h4 className="text-white fw-bold mb-0">{stat.value}</h4>
                      </div>
                      <div className={`rounded-circle d-flex align-items-center justify-content-center bg-dark border border-secondary ${stat.color}`} style={{ width: '50px', height: '50px', fontSize: '20px' }}>
                        <i className={`fas ${stat.icon}`}></i>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <div className="card border-0 rounded p-4 mb-4" style={{ backgroundColor: '#1a1a1a' }}>
              <h5 className="text-white fw-bold mb-4">Biểu Đồ Doanh Thu 6 Tháng Gần Nhất</h5>
              <div style={{ width: '100%', height: 350 }}>
                <ResponsiveContainer>
                  <LineChart data={revenueData} margin={{ top: 10, right: 30, left: 20, bottom: 5 }}>
                    <CartesianGrid strokeDasharray="3 3" stroke="#333" vertical={false} />
                    <XAxis dataKey="name" stroke="#888" tick={{ fill: '#888' }} tickLine={false} axisLine={false} />
                    <YAxis stroke="#888" tick={{ fill: '#888' }} tickLine={false} axisLine={false} tickFormatter={(value) => `${value / 1000000}Tr`} />
                    <Tooltip contentStyle={{ backgroundColor: '#222', borderColor: '#444', borderRadius: '8px', color: '#fff' }} itemStyle={{ color: '#F28B00', fontWeight: 'bold' }} formatter={(value) => [`${value.toLocaleString('vi-VN')} đ`, 'Doanh Thu']} />
                    <Line type="monotone" dataKey="revenue" stroke="#F28B00" strokeWidth={4} dot={{ r: 6, fill: '#1a1a1a', stroke: '#F28B00', strokeWidth: 2 }} activeDot={{ r: 8, fill: '#F28B00', stroke: '#fff' }} />
                  </LineChart>
                </ResponsiveContainer>
              </div>
            </div>
          </>
        )}

        {/* --- NỘI DUNG QUẢN LÝ SẢN PHẨM --- */}
        {activeMenu === 'products' && (
          <div className="card border-0 rounded p-4" style={{ backgroundColor: '#1a1a1a' }}>

            {/* Thanh công cụ: Tìm kiếm & Thêm mới */}
            <div className="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
              <div className="d-flex gap-2 flex-grow-1" style={{ maxWidth: '500px' }}>
                <div className="input-group">
                  <span className="input-group-text bg-dark border-secondary text-muted"><i className="fas fa-search"></i></span>
                  <input type="text" className="form-control bg-dark border-secondary text-white" placeholder="Tìm kiếm tên sản phẩm, mã SP..." />
                </div>
                <select className="form-select bg-dark border-secondary text-white" style={{ maxWidth: '150px' }}>
                  <option value="">Tất cả danh mục</option>
                  <option value="dientu">Điện Tử</option>
                  <option value="quanao">Quần Áo</option>
                  <option value="phukien">Phụ Kiện</option>
                </select>
              </div>
              <button className="btn fw-bold text-white shadow-sm" style={{ backgroundColor: '#F28B00' }}>
                <i className="fas fa-plus me-2"></i> Thêm Sản Phẩm
              </button>
            </div>

            {/* Bảng Danh Sách Sản Phẩm */}
            <div className="table-responsive">
              <table className="table table-dark table-hover align-middle mb-0">
                <thead>
                  <tr style={{ borderBottom: '2px solid #F28B00' }}>
                    <th scope="col" className="py-3 px-3">Mã SP</th>
                    <th scope="col" className="py-3">Hình Ảnh</th>
                    <th scope="col" className="py-3">Tên Sản Phẩm</th>
                    <th scope="col" className="py-3 text-center">Danh Mục</th>
                    <th scope="col" className="py-3 text-end">Giá Bán</th>
                    <th scope="col" className="py-3 text-center">Kho</th>
                    <th scope="col" className="py-3 text-center">Trạng Thái</th>
                    <th scope="col" className="py-3 text-center">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  {products.map((product, index) => (
                    <tr key={index} style={{ borderBottom: '1px solid #333' }}>
                      <td className="fw-bold text-muted py-3 px-3">{product.id}</td>
                      <td className="py-3">
                        <div className="d-flex align-items-center justify-content-center bg-dark rounded border border-secondary" style={{ width: '50px', height: '50px' }}>
                          <i className={`fas ${product.imgIcon} fs-4`} style={{ color: '#F28B00' }}></i>
                        </div>
                      </td>
                      <td className="text-white fw-bold py-3">{product.name}</td>
                      <td className="text-muted py-3 text-center">{product.category}</td>
                      <td className="text-primary fw-bold py-3 text-end">{product.price}</td>
                      <td className="text-white fw-bold py-3 text-center">{product.stock}</td>
                      <td className="py-3 text-center">
                        <span className={`badge rounded-pill px-3 py-2 ${product.stock > 0 ? 'bg-success' : 'bg-danger'}`}>
                          {product.status}
                        </span>
                      </td>
                      <td className="py-3 text-center">
                        <div className="d-flex justify-content-center gap-2">
                          <button className="btn btn-sm btn-outline-info btn-icon" title="Chỉnh sửa">
                            <i className="fas fa-edit"></i>
                          </button>
                          <button className="btn btn-sm btn-outline-danger btn-icon" title="Xóa">
                            <i className="fas fa-trash-alt"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Phân trang (Đã làm ngang và tách đều) */}
            <div className="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
              <span className="text-muted small">Hiển thị 1 - 5 trong tổng số 128 sản phẩm</span>
              <nav>
                <ul className="pagination pagination-sm mb-0 d-flex flex-row gap-2">
                  <li className="page-item disabled">
                    <button className="page-link bg-dark border-secondary text-muted rounded px-3 py-2" style={{ cursor: 'not-allowed' }}>
                      <i className="fas fa-chevron-left me-1"></i> Trước
                    </button>
                  </li>
                  <li className="page-item active">
                    <button className="page-link border-0 text-white rounded px-3 py-2 shadow-sm" style={{ backgroundColor: '#F28B00' }}>
                      1
                    </button>
                  </li>
                  <li className="page-item">
                    <button className="page-link bg-dark border-secondary text-white rounded px-3 py-2">
                      2
                    </button>
                  </li>
                  <li className="page-item">
                    <button className="page-link bg-dark border-secondary text-white rounded px-3 py-2">
                      3
                    </button>
                  </li>
                  <li className="page-item">
                    <button className="page-link bg-dark border-secondary text-white rounded px-3 py-2">
                      Sau <i className="fas fa-chevron-right ms-1"></i>
                    </button>
                  </li>
                </ul>
              </nav>
            </div>

          </div>
        )}

      </div>
    </div>
  );
};

export default AdminDashboardPage;
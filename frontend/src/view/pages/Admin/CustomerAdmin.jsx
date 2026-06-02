import React, { useState } from 'react';

const CustomerAdmin = () => {
  const [showDetailModal, setShowDetailModal] = useState(false);
  const [selectedCustomer, setSelectedCustomer] = useState(null);

  // --- DỮ LIỆU KHÁCH HÀNG GIẢ LẬP ---
  const customers = [
    { id: 'KH-001', name: 'Nguyễn Văn A', email: 'nguyenvana@gmail.com', phone: '0901234567', totalSpent: '15.450.000 đ', ordersCount: 5, joinDate: '10/01/2026', status: 'Hoạt động' },
    { id: 'KH-002', name: 'Trần Thị B', email: 'tranthib_99@yahoo.com', phone: '0912345678', totalSpent: '2.100.000 đ', ordersCount: 1, joinDate: '15/02/2026', status: 'Hoạt động' },
    { id: 'KH-003', name: 'Lê Hoàng C', email: 'hoangle_dev@gmail.com', phone: '0987654321', totalSpent: '850.000 đ', ordersCount: 1, joinDate: '20/03/2026', status: 'Bị khóa' },
    { id: 'KH-004', name: 'Phạm Hữu Lợi', email: 'loipham@gmail.com', phone: '0123456789', totalSpent: '35.000.000 đ', ordersCount: 12, joinDate: '01/11/2025', status: 'VIP' },
    { id: 'KH-005', name: 'Như Ý', email: 'nhuy_oxford@gmail.com', phone: '0933445566', totalSpent: '0 đ', ordersCount: 0, joinDate: '05/05/2026', status: 'Hoạt động' },
  ];

  const handleViewDetail = (customer) => {
    setSelectedCustomer(customer);
    setShowDetailModal(true);
  };

  const getStatusBadge = (status) => {
    switch(status) {
      case 'Hoạt động': return 'bg-success';
      case 'Bị khóa': return 'bg-danger';
      case 'VIP': return 'bg-warning text-dark fw-bold';
      default: return 'bg-secondary';
    }
  };

  return (
    <>
      <h3 className="text-white fw-bold mb-4">Quản Lý Khách Hàng</h3>

      {/* ================= 1. POPUP CHI TIẾT KHÁCH HÀNG ================= */}
      {showDetailModal && selectedCustomer && (
        <div style={{
          position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
          backgroundColor: 'rgba(0,0,0,0.85)', zIndex: 9999,
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          backdropFilter: 'blur(5px)'
        }}>
          <div className="card border-secondary shadow-lg" style={{ 
            width: '100%', maxWidth: '700px', backgroundColor: '#1a1a1a', 
            borderRadius: '15px', overflow: 'hidden' 
          }}>
            {/* Header Popup */}
            <div className="p-3 d-flex justify-content-between align-items-center" style={{ backgroundColor: '#222', borderBottom: '2px solid #F28B00' }}>
              <h5 className="text-white fw-bold mb-0">
                <i className="fas fa-id-card me-2 text-warning"></i> 
                HỒ SƠ KHÁCH HÀNG
              </h5>
              <button onClick={() => setShowDetailModal(false)} className="btn-close btn-close-white"></button>
            </div>

            {/* Body Popup */}
            <div className="p-4">
              <div className="d-flex align-items-center gap-4 border-bottom border-secondary pb-4 mb-4">
                <img 
                  src={`https://ui-avatars.com/api/?name=${selectedCustomer.name}&background=F28B00&color=fff&size=100`} 
                  alt="Avatar" 
                  className="rounded-circle border border-2 border-secondary"
                />
                <div>
                  <h4 className="text-white fw-bold mb-1">{selectedCustomer.name}</h4>
                  <p className="text-muted mb-2"><i className="fas fa-envelope me-2"></i>{selectedCustomer.email}</p>
                  <span className={`badge rounded-pill px-3 py-1 ${getStatusBadge(selectedCustomer.status)}`}>
                    {selectedCustomer.status}
                  </span>
                </div>
              </div>

              <div className="row g-4 text-center">
                <div className="col-4">
                  <div className="p-3 bg-dark rounded border border-secondary h-100">
                    <p className="text-muted small mb-1 fw-bold">TỔNG ĐƠN HÀNG</p>
                    <h4 className="text-white fw-bold mb-0">{selectedCustomer.ordersCount}</h4>
                  </div>
                </div>
                <div className="col-4">
                  <div className="p-3 bg-dark rounded border border-secondary h-100">
                    <p className="text-muted small mb-1 fw-bold">TỔNG CHI TIÊU</p>
                    <h4 className="text-primary fw-bold mb-0">{selectedCustomer.totalSpent}</h4>
                  </div>
                </div>
                <div className="col-4">
                  <div className="p-3 bg-dark rounded border border-secondary h-100">
                    <p className="text-muted small mb-1 fw-bold">NGÀY THAM GIA</p>
                    <h5 className="text-white fw-bold mb-0 mt-2">{selectedCustomer.joinDate}</h5>
                  </div>
                </div>
              </div>
            </div>

            {/* Footer Popup - Các nút hành động */}
            <div className="p-3 bg-dark d-flex justify-content-between border-top border-secondary">
              {selectedCustomer.status === 'Bị khóa' ? (
                <button className="btn btn-outline-success fw-bold rounded-pill"><i className="fas fa-unlock me-2"></i>Mở Khóa Tài Khoản</button>
              ) : (
                <button className="btn btn-outline-danger fw-bold rounded-pill"><i className="fas fa-ban me-2"></i>Khóa Tài Khoản</button>
              )}
              
              <div className="d-flex gap-2">
                <button className="btn btn-outline-info fw-bold rounded-pill"><i className="fas fa-history me-2"></i>Lịch Sử Mua</button>
                <button onClick={() => setShowDetailModal(false)} className="btn fw-bold text-white rounded-pill px-4" style={{ backgroundColor: '#333' }}>Đóng</button>
              </div>
            </div>
          </div>
        </div>
      )}


      {/* ================= 2. KHU VỰC BẢNG KHÁCH HÀNG ================= */}
      <div className="card border-0 rounded p-4" style={{ backgroundColor: '#1a1a1a' }}>
        
        {/* Thanh công cụ: Tìm kiếm & Bộ lọc */}
        <div className="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
          <div className="d-flex gap-2 flex-grow-1" style={{ maxWidth: '500px' }}>
            <div className="input-group">
              <span className="input-group-text bg-dark border-secondary text-muted"><i className="fas fa-search"></i></span>
              <input type="text" className="form-control bg-dark border-secondary text-white" placeholder="Tìm theo tên, email, SĐT..." />
            </div>
            <select className="form-select bg-dark border-secondary text-white" style={{ maxWidth: '160px' }}>
              <option value="">Tất cả trạng thái</option>
              <option value="hoatdong">Hoạt động</option>
              <option value="vip">Khách VIP</option>
              <option value="khoa">Bị khóa</option>
            </select>
          </div>
          <button className="btn fw-bold text-white shadow-sm" style={{ backgroundColor: '#F28B00' }}>
            <i className="fas fa-user-plus me-2"></i> Thêm Khách Hàng
          </button>
        </div>

        {/* Bảng Danh Sách Khách Hàng */}
        <div className="table-responsive">
          <table className="table table-dark table-hover align-middle mb-0">
            <thead>
              <tr style={{ borderBottom: '2px solid #F28B00' }}>
                <th scope="col" className="py-3 px-3">Khách Hàng</th>
                <th scope="col" className="py-3">Liên Hệ</th>
                <th scope="col" className="py-3 text-center">Ngày Tham Gia</th>
                <th scope="col" className="py-3 text-center">Số Đơn</th>
                <th scope="col" className="py-3 text-end">Chi Tiêu</th>
                <th scope="col" className="py-3 text-center">Trạng Thái</th>
                <th scope="col" className="py-3 text-center">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              {customers.map((customer, index) => (
                <tr key={index} style={{ borderBottom: '1px solid #333' }}>
                  <td className="py-3 px-3">
                    <div className="d-flex align-items-center gap-3">
                      <img src={`https://ui-avatars.com/api/?name=${customer.name}&background=random&color=fff`} className="rounded-circle" width="40" height="40" alt="avatar"/>
                      <div>
                        <div className="fw-bold text-white">{customer.name}</div>
                        <div className="small text-muted">{customer.id}</div>
                      </div>
                    </div>
                  </td>
                  <td className="py-3">
                    <div className="text-muted small"><i className="fas fa-envelope me-1"></i> {customer.email}</div>
                    <div className="text-muted small"><i className="fas fa-phone me-1"></i> {customer.phone}</div>
                  </td>
                  <td className="text-muted py-3 text-center">{customer.joinDate}</td>
                  <td className="text-white fw-bold py-3 text-center">{customer.ordersCount}</td>
                  <td className="text-primary fw-bold py-3 text-end">{customer.totalSpent}</td>
                  <td className="py-3 text-center">
                    <span className={`badge rounded-pill px-3 py-2 ${getStatusBadge(customer.status)}`}>
                      {customer.status}
                    </span>
                  </td>
                  <td className="py-3 text-center">
                    <button 
                      onClick={() => handleViewDetail(customer)} 
                      className="btn btn-sm btn-outline-info rounded-pill px-3"
                    >
                      <i className="fas fa-eye me-1"></i> Xem
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        {/* Phân trang ngang */}
        <div className="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
          <span className="text-muted small">Hiển thị 1 - 5 trong tổng số 1.204 khách hàng</span>
          <nav>
            <ul className="pagination pagination-sm mb-0 d-flex flex-row gap-2">
              <li className="page-item disabled">
                <button className="page-link bg-dark border-secondary text-muted rounded px-3 py-2" style={{ cursor: 'not-allowed' }}>
                  <i className="fas fa-chevron-left me-1"></i> Trước
                </button>
              </li>
              <li className="page-item active">
                <button className="page-link border-0 text-white rounded px-3 py-2 shadow-sm" style={{ backgroundColor: '#F28B00' }}>1</button>
              </li>
              <li className="page-item">
                <button className="page-link bg-dark border-secondary text-white rounded px-3 py-2">2</button>
              </li>
              <li className="page-item">
                <button className="page-link bg-dark border-secondary text-white rounded px-3 py-2">3</button>
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
    </>
  );
};

export default CustomerAdmin;
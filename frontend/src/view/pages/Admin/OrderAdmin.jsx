import React, { useState } from 'react';

const OrderAdmin = () => {
  // Trạng thái mở popup chi tiết đơn hàng
  const [showDetailModal, setShowDetailModal] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState(null);

  // --- DỮ LIỆU ĐƠN HÀNG GIẢ LẬP ---
  const orders = [
    { id: '#ORD-001', customer: 'Nguyễn Văn A', phone: '0901234567', address: 'Quận 1, TP. HCM', date: '25/05/2026', total: '3.350.000 đ', payment: 'COD', status: 'Chờ Duyệt' },
    { id: '#ORD-002', customer: 'Trần Thị B', phone: '0912345678', address: 'Cầu Giấy, Hà Nội', date: '24/05/2026', total: '10.500.000 đ', payment: 'Chuyển Khoản', status: 'Đang Giao' },
    { id: '#ORD-003', customer: 'Lê Hoàng C', phone: '0987654321', address: 'Hải Châu, Đà Nẵng', date: '23/05/2026', total: '850.000 đ', payment: 'Momo', status: 'Hoàn Thành' },
    { id: '#ORD-004', customer: 'Phạm D', phone: '0922334455', address: 'Ninh Kiều, Cần Thơ', date: '22/05/2026', total: '1.250.000 đ', payment: 'COD', status: 'Đã Hủy' },
    { id: '#ORD-005', customer: 'Vũ Đức E', phone: '0933445566', address: 'Quận 7, TP. HCM', date: '21/05/2026', total: '2.500.000 đ', payment: 'Chuyển Khoản', status: 'Chờ Duyệt' },
  ];

  // Hàm mở popup và truyền dữ liệu đơn hàng vào
  const handleViewDetail = (order) => {
    setSelectedOrder(order);
    setShowDetailModal(true);
  };

  // Hàm render màu sắc badge trạng thái
  const getStatusBadge = (status) => {
    switch(status) {
      case 'Chờ Duyệt': return 'bg-info text-dark';
      case 'Đang Giao': return 'bg-warning text-dark';
      case 'Hoàn Thành': return 'bg-success';
      case 'Đã Hủy': return 'bg-danger';
      default: return 'bg-secondary';
    }
  };

  return (
    <>
      <h3 className="text-white fw-bold mb-4">Quản Lý Đơn Hàng</h3>

      {/* ================= 1. POPUP CHI TIẾT ĐƠN HÀNG ================= */}
      {showDetailModal && selectedOrder && (
        <div style={{
          position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
          backgroundColor: 'rgba(0,0,0,0.85)', zIndex: 9999,
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          backdropFilter: 'blur(5px)'
        }}>
          <div className="card border-secondary shadow-lg" style={{ 
            width: '100%', maxWidth: '800px', backgroundColor: '#1a1a1a', 
            borderRadius: '15px', overflow: 'hidden' 
          }}>
            {/* Header Popup */}
            <div className="p-3 d-flex justify-content-between align-items-center" style={{ backgroundColor: '#222', borderBottom: '2px solid #F28B00' }}>
              <h5 className="text-white fw-bold mb-0">
                <i className="fas fa-file-invoice me-2 text-warning"></i> 
                CHI TIẾT ĐƠN HÀNG: <span className="text-primary">{selectedOrder.id}</span>
              </h5>
              <button onClick={() => setShowDetailModal(false)} className="btn-close btn-close-white"></button>
            </div>

            {/* Body Popup */}
            <div className="p-4" style={{ maxHeight: '75vh', overflowY: 'auto' }}>
              <div className="row g-4">
                {/* Thông tin khách hàng */}
                <div className="col-md-6">
                  <div className="p-3 rounded bg-dark border border-secondary h-100">
                    <h6 className="text-warning fw-bold border-bottom border-secondary pb-2 mb-3">Thông Tin Người Nhận</h6>
                    <p className="text-white mb-2"><i className="fas fa-user me-2 text-muted"></i>{selectedOrder.customer}</p>
                    <p className="text-white mb-2"><i className="fas fa-phone me-2 text-muted"></i>{selectedOrder.phone}</p>
                    <p className="text-white mb-0"><i className="fas fa-map-marker-alt me-2 text-muted"></i>{selectedOrder.address}</p>
                  </div>
                </div>

                {/* Thông tin giao dịch */}
                <div className="col-md-6">
                  <div className="p-3 rounded bg-dark border border-secondary h-100">
                    <h6 className="text-warning fw-bold border-bottom border-secondary pb-2 mb-3">Thông Tin Giao Dịch</h6>
                    <p className="text-white mb-2"><i className="fas fa-calendar-alt me-2 text-muted"></i>Ngày đặt: {selectedOrder.date}</p>
                    <p className="text-white mb-2"><i className="fas fa-credit-card me-2 text-muted"></i>Thanh toán: <strong className="text-info">{selectedOrder.payment}</strong></p>
                    <p className="text-white mb-0">
                      <i className="fas fa-info-circle me-2 text-muted"></i>Trạng thái: 
                      <span className={`badge rounded-pill px-2 py-1 ms-2 ${getStatusBadge(selectedOrder.status)}`}>{selectedOrder.status}</span>
                    </p>
                  </div>
                </div>

                {/* Danh sách sản phẩm mua (Giả lập) */}
                <div className="col-12">
                  <h6 className="text-white fw-bold mb-3 mt-2">Sản Phẩm Đã Đặt</h6>
                  <table className="table table-dark table-bordered border-secondary text-center mb-0">
                    <thead style={{ backgroundColor: '#222' }}>
                      <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>SL</th>
                        <th>Thành tiền</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td className="text-start text-white">Bàn phím cơ Razer BlackWidow</td>
                        <td className="text-muted">3.350.000 đ</td>
                        <td>1</td>
                        <td className="text-primary fw-bold">3.350.000 đ</td>
                      </tr>
                    </tbody>
                  </table>
                  <div className="d-flex justify-content-end mt-3">
                    <h5 className="text-white fw-bold">Tổng thanh toán: <span className="text-primary fs-4 ms-2">{selectedOrder.total}</span></h5>
                  </div>
                </div>
              </div>
            </div>

            {/* Footer Popup - Các nút chuyển trạng thái */}
            <div className="p-3 bg-dark d-flex justify-content-between border-top border-secondary">
              <button className="btn btn-outline-danger fw-bold rounded-pill"><i className="fas fa-times me-2"></i>Hủy Đơn</button>
              <div className="d-flex gap-2">
                <button className="btn btn-outline-info fw-bold rounded-pill"><i className="fas fa-check me-2"></i>Duyệt Đơn</button>
                <button className="btn btn-outline-warning fw-bold rounded-pill"><i className="fas fa-truck me-2"></i>Giao Hàng</button>
                <button className="btn fw-bold text-white rounded-pill" style={{ backgroundColor: '#28a745' }}><i className="fas fa-check-double me-2"></i>Hoàn Thành</button>
              </div>
            </div>
          </div>
        </div>
      )}


      {/* ================= 2. KHU VỰC BẢNG ĐƠN HÀNG ================= */}
      <div className="card border-0 rounded p-4" style={{ backgroundColor: '#1a1a1a' }}>
        
        {/* Thanh công cụ: Tìm kiếm & Bộ lọc */}
        <div className="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
          <div className="d-flex gap-2 flex-grow-1" style={{ maxWidth: '600px' }}>
            <div className="input-group">
              <span className="input-group-text bg-dark border-secondary text-muted"><i className="fas fa-search"></i></span>
              <input type="text" className="form-control bg-dark border-secondary text-white" placeholder="Tìm mã đơn, tên khách, số điện thoại..." />
            </div>
            <select className="form-select bg-dark border-secondary text-white" style={{ maxWidth: '180px' }}>
              <option value="">Tất cả trạng thái</option>
              <option value="choduyet">Chờ Duyệt</option>
              <option value="danggiao">Đang Giao</option>
              <option value="hoanthanh">Hoàn Thành</option>
              <option value="dahuy">Đã Hủy</option>
            </select>
          </div>
          <button className="btn btn-outline-success fw-bold shadow-sm">
            <i className="fas fa-file-excel me-2"></i> Xuất Excel
          </button>
        </div>

        {/* Bảng Danh Sách Đơn Hàng */}
        <div className="table-responsive">
          <table className="table table-dark table-hover align-middle mb-0">
            <thead>
              <tr style={{ borderBottom: '2px solid #F28B00' }}>
                <th scope="col" className="py-3 px-3">Mã Đơn</th>
                <th scope="col" className="py-3">Khách Hàng</th>
                <th scope="col" className="py-3">Ngày Đặt</th>
                <th scope="col" className="py-3 text-end">Tổng Tiền</th>
                <th scope="col" className="py-3 text-center">Thanh Toán</th>
                <th scope="col" className="py-3 text-center">Trạng Thái</th>
                <th scope="col" className="py-3 text-center">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              {orders.map((order, index) => (
                <tr key={index} style={{ borderBottom: '1px solid #333' }}>
                  <td className="fw-bold text-primary py-3 px-3">{order.id}</td>
                  <td className="text-white py-3">
                    <div className="fw-bold">{order.customer}</div>
                    <div className="small text-muted">{order.phone}</div>
                  </td>
                  <td className="text-muted py-3">{order.date}</td>
                  <td className="text-white fw-bold py-3 text-end">{order.total}</td>
                  <td className="py-3 text-center">
                    <span className="badge bg-dark border border-secondary text-info">{order.payment}</span>
                  </td>
                  <td className="py-3 text-center">
                    <span className={`badge rounded-pill px-3 py-2 ${getStatusBadge(order.status)}`}>
                      {order.status}
                    </span>
                  </td>
                  <td className="py-3 text-center">
                    <button 
                      onClick={() => handleViewDetail(order)} 
                      className="btn btn-sm btn-outline-warning rounded-pill px-3"
                    >
                      Chi tiết
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        {/* Phân trang ngang */}
        <div className="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
          <span className="text-muted small">Hiển thị 1 - 5 trong tổng số 45 đơn hàng</span>
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

export default OrderAdmin;
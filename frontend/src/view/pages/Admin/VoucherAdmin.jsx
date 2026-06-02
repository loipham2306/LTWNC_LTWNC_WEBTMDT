import React, { useState } from 'react';

const VoucherAdmin = () => {
  const [showAddModal, setShowAddModal] = useState(false);

  // --- DỮ LIỆU VOUCHER GIẢ LẬP ---
  const vouchers = [
    { id: 1, code: 'STORE100K', type: 'Giảm tiền', value: '100.000 đ', minSpend: '1.000.000 đ', used: 45, limit: 100, expiry: '30/12/2026', status: 'Đang chạy' },
    { id: 2, code: 'GAMING20', type: 'Giảm %', value: '20%', minSpend: '500.000 đ', used: 100, limit: 100, expiry: '01/05/2026', status: 'Hết lượt' },
    { id: 3, code: 'HELLOSUMMER', type: 'Giảm %', value: '10%', minSpend: '0 đ', used: 12, limit: 200, expiry: '15/08/2026', status: 'Đang chạy' },
    { id: 4, code: 'NEWUSER50', type: 'Giảm tiền', value: '50.000 đ', minSpend: '200.000 đ', used: 0, limit: 50, expiry: '01/01/2027', status: 'Chưa diễn ra' },
  ];

  const getStatusBadge = (status) => {
    switch(status) {
      case 'Đang chạy': return 'bg-success';
      case 'Hết lượt': return 'bg-danger';
      case 'Chưa diễn ra': return 'bg-info text-dark';
      default: return 'bg-secondary';
    }
  };

  return (
    <>
      <h3 className="text-white fw-bold mb-4">Quản Lý Voucher & Khuyến Mãi</h3>

      {/* ================= 1. POPUP THÊM VOUCHER MỚI ================= */}
      {showAddModal && (
        <div style={{
          position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
          backgroundColor: 'rgba(0,0,0,0.85)', zIndex: 9999,
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          backdropFilter: 'blur(5px)'
        }}>
          <div className="card border-secondary shadow-lg" style={{ 
            width: '100%', maxWidth: '650px', backgroundColor: '#1a1a1a', 
            borderRadius: '15px', overflow: 'hidden' 
          }}>
            <div className="p-3 d-flex justify-content-between align-items-center" style={{ backgroundColor: '#222', borderBottom: '2px solid #F28B00' }}>
              <h5 className="text-white fw-bold mb-0"><i className="fas fa-ticket-alt me-2 text-warning"></i>TẠO MÃ GIẢM GIÁ MỚI</h5>
              <button onClick={() => setShowAddModal(false)} className="btn-close btn-close-white"></button>
            </div>

            <div className="p-4">
              <form>
                <div className="row g-3">
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">MÃ VOUCHER</label>
                    <input type="text" className="form-control bg-dark border-secondary text-white fw-bold text-uppercase" placeholder="VD: GiamGia50" />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">LOẠI GIẢM GIÁ</label>
                    <select className="form-select bg-dark border-secondary text-white">
                      <option>Giảm theo số tiền cố định</option>
                      <option>Giảm theo phần trăm (%)</option>
                    </select>
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">MỨC GIẢM</label>
                    <input type="number" className="form-control bg-dark border-secondary text-white" placeholder="VD: 100000 hoặc 20" />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">ĐƠN HÀNG TỐI THIỂU</label>
                    <input type="number" className="form-control bg-dark border-secondary text-white" placeholder="VD: 500000" />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">NGÀY BẮT ĐẦU</label>
                    <input type="date" className="form-control bg-dark border-secondary text-white" />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">NGÀY KẾT THÚC</label>
                    <input type="date" className="form-control bg-dark border-secondary text-white" />
                  </div>
                  <div className="col-md-12">
                    <label className="form-label text-muted fw-bold small">GIỚI HẠN LƯỢT DÙNG</label>
                    <input type="number" className="form-control bg-dark border-secondary text-white" placeholder="VD: 100" />
                  </div>
                </div>
              </form>
            </div>

            <div className="p-3 bg-dark d-flex justify-content-end gap-2 border-top border-secondary">
              <button onClick={() => setShowAddModal(false)} className="btn btn-outline-secondary px-4 fw-bold rounded-pill">HỦY</button>
              <button onClick={() => setShowAddModal(false)} className="btn px-4 fw-bold text-white rounded-pill" style={{ backgroundColor: '#F28B00' }}>TẠO VOUCHER</button>
            </div>
          </div>
        </div>
      )}

      {/* ================= 2. BẢNG DANH SÁCH VOUCHER ================= */}
      <div className="card border-0 rounded p-4" style={{ backgroundColor: '#1a1a1a' }}>
        <div className="d-flex justify-content-between align-items-center mb-4">
          <div className="input-group w-50">
            <span className="input-group-text bg-dark border-secondary text-muted"><i className="fas fa-search"></i></span>
            <input type="text" className="form-control bg-dark border-secondary text-white" placeholder="Tìm theo mã voucher..." />
          </div>
          <button onClick={() => setShowAddModal(true)} className="btn fw-bold text-white px-4 rounded-pill" style={{ backgroundColor: '#F28B00' }}>
            <i className="fas fa-plus me-2"></i> Tạo Voucher
          </button>
        </div>

        <div className="table-responsive">
          <table className="table table-dark table-hover align-middle">
            <thead>
              <tr style={{ borderBottom: '2px solid #F28B00' }}>
                <th className="py-3 px-3">Mã Voucher</th>
                <th className="py-3">Loại / Mức Giảm</th>
                <th className="py-3 text-center">Đơn tối thiểu</th>
                <th className="py-3 text-center">Đã dùng</th>
                <th className="py-3 text-center">Hết hạn</th>
                <th className="py-3 text-center">Trạng Thái</th>
                <th className="py-3 text-center">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              {vouchers.map((v) => (
                <tr key={v.id} style={{ borderBottom: '1px solid #333' }}>
                  <td className="py-3 px-3"><span className="badge bg-dark border border-warning text-warning fs-6">{v.code}</span></td>
                  <td className="py-3">
                    <div className="text-white fw-bold">{v.value}</div>
                    <div className="small text-muted">{v.type}</div>
                  </td>
                  <td className="py-3 text-center text-primary fw-bold">{v.minSpend}</td>
                  <td className="py-3 text-center text-white">
                    <div className="fw-bold">{v.used} / {v.limit}</div>
                    <div className="progress mt-1" style={{ height: '5px', backgroundColor: '#333' }}>
                      <div className="progress-bar bg-warning" style={{ width: `${(v.used/v.limit)*100}%` }}></div>
                    </div>
                  </td>
                  <td className="py-3 text-center text-muted">{v.expiry}</td>
                  <td className="py-3 text-center">
                    <span className={`badge rounded-pill px-3 py-2 ${getStatusBadge(v.status)}`}>{v.status}</span>
                  </td>
                  <td className="py-3 text-center">
                    <button className="btn btn-sm btn-outline-info me-2"><i className="fas fa-edit"></i></button>
                    <button className="btn btn-sm btn-outline-danger"><i className="fas fa-trash"></i></button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </>
  );
};

export default VoucherAdmin;
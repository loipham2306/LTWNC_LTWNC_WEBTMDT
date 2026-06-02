import React, { useState, useEffect } from 'react';
import PageHeader from '../components/PageHeader';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

const UserProfilePage = () => {
  const [activeTab, setActiveTab] = useState('info');
  const navigate = useNavigate();

  // State lưu trữ thông tin user thực tế lấy từ localStorage
  const [userData, setUserData] = useState({
    id_tai_khoan: '',
    ten_dang_nhap: '',
    vai_tro: '',
    ho_ten_dem: '',
    ten: '',
    so_dien_thoai: '',
    dia_chi: '',
    hang_thanh_vien: ''
  });

  // 1. useEffect lấy dữ liệu từ localStorage khi component render
  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      try {
        const parsedUser = JSON.parse(storedUser);
        setUserData(parsedUser);
      } catch (error) {
        console.error("Lỗi parse dữ liệu user:", error);
      }
    } else {
      // Nếu chưa đăng nhập mà cố tình vào trang này thì đá về trang login
      toast.warning("Vui lòng đăng nhập để xem thông tin!");
      navigate('/login');
    }
  }, [navigate]);

  // Demo danh sách đơn hàng (sau này bạn viết API lấy theo id_tai_khoan tương tự)
  const orders = [
    { id: '#LK2034', date: '25/05/2026', total: '3.350.000 đ', status: 'Đang Giao' },
    { id: '#LK1988', date: '12/05/2026', total: '1.250.000 đ', status: 'Hoàn Thành' },
  ];

  // 2. Hàm xử lý Đăng xuất thực tế (Xóa dữ liệu cũ)
  const handleLogout = () => {
    if (window.confirm('Bạn có chắc chắn muốn đăng xuất?')) {
      localStorage.removeItem('user'); // Xóa sạch dấu vết đăng nhập
      toast.info("Đăng xuất thành công!");
      navigate('/login');
    }
  };

  // Hàm xử lý cập nhật thông tin cá nhân trên Form giao diện
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setUserData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleUpdateInfo = (e) => {
    e.preventDefault();
    // Sau này bạn bắn API PUT/POST lên backend cập nhật bảng khach_hang ở đây
    // Tạm thời lưu lại vào localStorage để đồng bộ giao diện
    localStorage.setItem('user', JSON.stringify(userData));
    toast.success('🎉 Cập nhật thông tin thành công!');
  };

  // Hàm xử lý đổi mật khẩu demo
  const handleChangePassword = (e) => {
    e.preventDefault();
    toast.success('🎉 Cập nhật mật khẩu thành công!', {
      style: { borderLeft: '4px solid #F28B00' }
    });
    e.target.reset();
  };

  // Tạo tên đầy đủ để hiển thị
  const fullName = `${userData.ho_ten_dem} ${userData.ten}`.trim() || userData.ten_dang_nhap || 'Khách Hàng';

  return (
    <div className="container-fluid p-0" style={{ backgroundColor: '#111', minHeight: '80vh', paddingBottom: '50px' }}>
      <PageHeader title="Tài Khoản Của Tôi" breadcrumb="Tài Khoản" />

      <div className="container py-5">
        <div className="row g-4">

          {/* CỘT TRÁI: MENU QUẢN LÝ */}
          <div className="col-lg-3">
            <div className="card border-0 rounded p-4 text-center mb-4" style={{ backgroundColor: '#1a1a1a' }}>
              <div className="d-flex justify-content-center mb-3">
                <div className="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                  style={{ width: '80px', height: '80px', fontSize: '30px', fontWeight: 'bold', textTransform: 'uppercase' }}>
                  {userData.ten ? userData.ten.charAt(0) : 'U'}
                </div>
              </div>
              <h5 className="text-white fw-bold">{fullName}</h5>
              <p className="text-muted small mb-0">Hạng: {userData.hang_thanh_vien || 'Thành Viên Mới'}</p>
            </div>

            <div className="card border-0 rounded overflow-hidden" style={{ backgroundColor: '#1a1a1a' }}>
              <div className="list-group list-group-flush">
                <button
                  className={`list-group-item list-group-item-action py-3 fw-bold ${activeTab === 'info' ? 'bg-primary text-white' : 'bg-transparent text-muted'}`}
                  style={{ borderBottom: '1px solid #333' }}
                  onClick={() => setActiveTab('info')}
                >
                  <i className="fas fa-user me-3"></i>Thông Tin Cá Nhân
                </button>
                <button
                  className={`list-group-item list-group-item-action py-3 fw-bold ${activeTab === 'orders' ? 'bg-primary text-white' : 'bg-transparent text-muted'}`}
                  style={{ borderBottom: '1px solid #333' }}
                  onClick={() => setActiveTab('orders')}
                >
                  <i className="fas fa-shopping-bag me-3"></i>Lịch Sử Đơn Hàng
                </button>
                <button
                  className={`list-group-item list-group-item-action py-3 fw-bold ${activeTab === 'password' ? 'bg-primary text-white' : 'bg-transparent text-muted'}`}
                  style={{ borderBottom: '1px solid #333' }}
                  onClick={() => setActiveTab('password')}
                >
                  <i className="fas fa-lock me-3"></i>Đổi Mật Khẩu
                </button>

                <button
                  className="list-group-item list-group-item-action py-3 fw-bold bg-transparent text-danger"
                  onClick={handleLogout}
                >
                  <i className="fas fa-sign-out-alt me-3"></i>Đăng Xuất
                </button>
              </div>
            </div>
          </div>

          {/* CỘT PHẢI: NỘI DUNG CHI TIẾT */}
          <div className="col-lg-9">
            <div className="card border-0 rounded p-4 h-100" style={{ backgroundColor: '#1a1a1a' }}>

              {/* TAB THÔNG TIN CÁ NHÂN */}
              {activeTab === 'info' && (
                <div>
                  <h4 className="text-white fw-bold border-bottom border-secondary pb-3 mb-4">Hồ Sơ Của Tôi</h4>
                  <form onSubmit={handleUpdateInfo}>
                    <div className="row g-4">
                      <div className="col-md-6">
                        <label className="form-label text-muted fw-bold">Họ & Tên Đệm</label>
                        <input type="text" name="ho_ten_dem" className="form-control bg-dark border-secondary text-white py-2" value={userData.ho_ten_dem || ''} onChange={handleInputChange} />
                      </div>
                      <div className="col-md-6">
                        <label className="form-label text-muted fw-bold">Tên</label>
                        <input type="text" name="ten" className="form-control bg-dark border-secondary text-white py-2" value={userData.ten || ''} onChange={handleInputChange} />
                      </div>
                      <div className="col-md-6">
                        <label className="form-label text-muted fw-bold">Số Điện Thoại</label>
                        <input type="text" name="so_dien_thoai" className="form-control bg-dark border-secondary text-white py-2" value={userData.so_dien_thoai || ''} onChange={handleInputChange} />
                      </div>
                      <div className="col-md-6">
                        <label className="form-label text-muted fw-bold">Tên đăng nhập / Email</label>
                        <input type="text" className="form-control bg-dark border-secondary text-muted py-2" value={userData.ten_dang_nhap || ''} disabled />
                      </div>
                      <div className="col-md-12">
                        <label className="form-label text-muted fw-bold">Địa Chỉ Giao Hàng</label>
                        <textarea name="dia_chi" className="form-control bg-dark border-secondary text-white py-2" rows="3" value={userData.dia_chi || ''} onChange={handleInputChange}></textarea>
                      </div>
                      <div className="col-12 mt-4">
                        <button type="submit" className="btn btn-primary px-5 py-2 fw-bold rounded-pill">
                          Lưu Thay Đổi
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              )}

              {/* TAB LỊCH SỬ ĐƠN HÀNG */}
              {activeTab === 'orders' && (
                <div>
                  <h4 className="text-white fw-bold border-bottom border-secondary pb-3 mb-4">Đơn Hàng Gần Đây</h4>
                  <div className="table-responsive">
                    <table className="table table-dark table-hover align-middle text-center">
                      <thead>
                        <tr style={{ borderBottom: '2px solid #F28B00' }}>
                          <th scope="col" className="py-3">Mã Đơn</th>
                          <th scope="col" className="py-3">Ngày Mua</th>
                          <th scope="col" className="py-3">Tổng Tiền</th>
                          <th scope="col" className="py-3">Trạng Thái</th>
                          <th scope="col" className="py-3">Thao Tác</th>
                        </tr>
                      </thead>
                      <tbody>
                        {orders.map((order, index) => (
                          <tr key={index} style={{ borderBottom: '1px solid #333' }}>
                            <td className="fw-bold text-white py-3">{order.id}</td>
                            <td className="text-muted py-3">{order.date}</td>
                            <td className="text-primary fw-bold py-3">{order.total}</td>
                            <td className="py-3">
                              <span className={`badge rounded-pill px-3 py-2 ${order.status === 'Đang Giao' ? 'bg-warning text-dark' : 'bg-success'}`}>
                                {order.status}
                              </span>
                            </td>
                            <td className="py-3">
                              <button className="btn btn-sm btn-outline-primary rounded-pill px-3">Xem</button>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              )}

              {/* TAB ĐỔI MẬT KHẨU */}
              {activeTab === 'password' && (
                <div>
                  <h4 className="text-white fw-bold border-bottom border-secondary pb-3 mb-4">Đổi Mật Khẩu</h4>
                  <form onSubmit={handleChangePassword}>
                    <div className="row g-4" style={{ maxWidth: '600px' }}>
                      <div className="col-12">
                        <label className="form-label text-muted fw-bold">Mật khẩu hiện tại</label>
                        <input type="password" name="oldPassword" className="form-control bg-dark border-secondary text-white py-2" placeholder="Nhập mật khẩu cũ..." required />
                      </div>
                      <div className="col-12">
                        <label className="form-label text-muted fw-bold">Mật khẩu mới</label>
                        <input type="password" name="newPassword" className="form-control bg-dark border-secondary text-white py-2" placeholder="Nhập mật khẩu mới..." required />
                      </div>
                      <div className="col-12">
                        <label className="form-label text-muted fw-bold">Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirmPassword" className="form-control bg-dark border-secondary text-white py-2" placeholder="Nhập lại mật khẩu mới..." required />
                      </div>
                      <div className="col-12 mt-4">
                        <button type="submit" className="btn btn-primary px-5 py-2 fw-bold rounded-pill">
                          Cập Nhật Mật Khẩu
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              )}

            </div>
          </div>

        </div>
      </div>
    </div>
  );
};

export default UserProfilePage;
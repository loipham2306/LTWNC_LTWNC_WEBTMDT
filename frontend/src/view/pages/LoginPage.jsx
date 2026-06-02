import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import '../../assets/css/AuthPages.css';
import axios from 'axios';

const LoginPage = () => {
  const navigate = useNavigate();

  // Giữ lại state role để giao diện nút chuyển đổi quyền không bị crash
  const [role, setRole] = useState('user');
  const [formData, setFormData] = useState({ username: '', password: '' });
  const [loading, setLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  // ĐÃ SỬA CHÍNH XÁC: Thêm "async" ở đây để sửa triệt để lỗi hiển thị trên Vite Terminal
  const handleLogin = async (e) => {
    e.preventDefault();
    setErrorMessage('');
    setLoading(true);

    try {
      // ĐÃ SỬA: Điền đúng URL kết nối trực tiếp đến API PHP trong XAMPP của bạn
      const response = await axios.post('http://localhost/LTWNC_BAN_HANG/backend/controllers/DangNhapController.php', {
        username_or_email: formData.username,
        password: formData.password
      });

      // Kiểm tra nếu API trả về kết quả thành công
      if (response.data.success) {
        const userData = response.data.user;

        // Lưu dữ liệu đăng nhập vào localStorage
        localStorage.setItem('user', JSON.stringify(userData));

        // Phân quyền điều hướng tự động dựa trên vai trò thực tế lưu trong Database
        if (userData.vai_tro === 'admin') {
          navigate('/admin');
        } else if (userData.vai_tro === 'nhanvien') {
          navigate('/employee/orders');
        } else {
          navigate('/'); // Về trang chủ khách hàng
        }
      }
    } catch (err) {
      if (err.response && err.response.data && err.response.data.message) {
        setErrorMessage(err.response.data.message);
      } else {
        setErrorMessage("Không thể kết nối đến máy chủ!");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container-fluid d-flex auth-page-container">
      <div className="card p-4 rounded border auth-card login-card pb-0">

        <div className="text-center mb-4">
          <h1 className="fw-bold m-0 lkstore-logo-text">
            <i className="fas fa-shopping-bag me-2"></i>LuLoShop
          </h1>
          <p className="text-muted mt-2">Chào mừng bạn quay trở lại!</p>
        </div>

        <div className="d-flex justify-content-center mb-4 p-1 rounded role-switcher-container border border-secondary">
          <button
            type="button"
            className={`btn w-50 rounded-pill fw-bold py-2 role-switcher-btn transition-all ${role === 'user' ? 'active' : ''}`}
            onClick={() => setRole('user')}
          >
            <i className="fas fa-user me-2"></i>Khách Hàng
          </button>
          <button
            type="button"
            className={`btn w-50 rounded-pill fw-bold py-2 role-switcher-btn transition-all ${role === 'admin' ? 'active' : ''}`}
            onClick={() => setRole('admin')}
          >
            <i className="fas fa-user-shield me-2"></i>Admin
          </button>
        </div>

        <form onSubmit={handleLogin}>
          <div className="mb-3 auth-input-group">
            <label className="form-label fw-bold text-white">Tên đăng nhập hoặc Email</label>
            <div className="input-group">
              <span className="input-group-text"><i className="fa fa-envelope"></i></span>
              <input
                type="text"
                name="username"
                className="form-control"
                placeholder="Nhập tài khoản..."
                required
                value={formData.username}
                onChange={handleInputChange}
              />
            </div>
          </div>

          <div className="mb-4 auth-input-group">
            <div className="d-flex justify-content-between">
              <label className="form-label fw-bold text-white">Mật khẩu</label>

            </div>
            <div className="input-group">
              <span className="input-group-text"><i className="fa fa-lock"></i></span>
              <input
                type="password"
                name="password"
                className="form-control"
                placeholder="Nhập mật khẩu..."
                required
                value={formData.password}
                onChange={handleInputChange}
              />
            </div>
          </div>
          {errorMessage && (
            <div className="alert alert-danger text-center my-3 fw-bold py-2 rounded"
              style={{ color: '#ff4d4f', backgroundColor: '#fff1f0', border: '1px solid #ffa39e', fontSize: '14px' }}>
              ❌ {errorMessage}
            </div>
          )}
          <button
            type="submit"
            className="btn w-100 py-2 text-uppercase fw-bold auth-submit-btn rounded-pill shadow-sm transition-all mb-3"
          >
            Đăng Nhập với quyền {role === 'admin' ? 'Admin' : 'Khách Hàng'}
          </button>

          <div className="text-center mt-3">
            <span className="text-muted">Chưa có tài khoản? </span>
            <Link to="/register" className="auth-switch-link fw-bold">Đăng ký ngay</Link>
            <a className="padding " href="#" className="text-decoration-none small text-warning">Quên mật khẩu?</a>
          </div>
        </form>

      </div>
    </div>
  );
};

export default LoginPage;
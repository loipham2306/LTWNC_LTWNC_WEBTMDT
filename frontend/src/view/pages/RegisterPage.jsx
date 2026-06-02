import React, { useState } from 'react';
import { Link } from 'react-router-dom';
// Đường dẫn đã trỏ đúng vào thư mục assets/css của bạn
import '../../assets/css/AuthPages.css'; 

const RegisterPage = () => {
  const [role, setRole] = useState('user');
  const [formData, setFormData] = useState({
    fullName: '',
    email: '',
    password: '',
    confirmPassword: ''
  });

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleRegister = (e) => {
    e.preventDefault();
    if(formData.password !== formData.confirmPassword) {
      alert('❌ Mật khẩu xác nhận không khớp!');
      return;
    }
    alert(`🎉 Demo UI: Đăng ký thành công tài khoản [${role.toUpperCase()}]!\n👤 Họ tên: ${formData.fullName}`);
  };

  return (
    <div className="container-fluid d-flex auth-page-container">
      <div className="card p-4 rounded border auth-card register-card pb-0">
        
        <div className="text-center mb-4">
          <h1 className="fw-bold m-0 lkstore-logo-text">
            <i className="fas fa-shopping-bag me-2"></i>LuLoShop
          </h1>
          <h5 className="fw-bold text-white m-0 text-uppercase mt-2">Tạo Tài Khoản</h5>
          <p className="text-muted mt-2">Đăng ký thành viên để nhận ưu đãi</p>
        </div>

        <div className="mb-3 auth-input-group">
          <label className="form-label fw-bold text-white d-block text-center mb-3">Bạn muốn đăng ký loại tài khoản nào?</label>
          <div className="d-flex justify-content-center flex-column gap-2 mb-3">
            <div className="form-check border border-secondary p-2 rounded px-3 bg-dark d-flex align-items-center mb-0" style={{ cursor: 'pointer' }}>
              <input 
                className="form-check-input mt-0" 
                type="radio" 
                name="regRole" 
                id="regUser" 
                checked={role === 'user'} 
                onChange={() => setRole('user')}
                style={{ cursor: 'pointer' }}
              />
              <label className="form-check-label text-white fw-bold ms-2 mb-0" htmlFor="regUser" style={{ cursor: 'pointer' }}>Tài khoản Mua Hàng</label>
            </div>
            <div className="form-check border border-secondary p-2 rounded px-3 bg-dark d-flex align-items-center mb-0" style={{ cursor: 'pointer' }}>
              <input 
                className="form-check-input mt-0" 
                type="radio" 
                name="regRole" 
                id="regAdmin" 
                checked={role === 'admin'} 
                onChange={() => setRole('admin')}
                style={{ cursor: 'pointer' }}
              />
              <label className="form-check-label text-white fw-bold ms-2 mb-0" htmlFor="regAdmin" style={{ cursor: 'pointer' }}>Yêu cầu quyền Admin</label>
            </div>
          </div>
        </div>

        <form onSubmit={handleRegister}>
          <div className="mb-3 auth-input-group">
            <label className="form-label fw-bold text-white">Họ và tên</label>
            <input 
              type="text" 
              name="fullName"
              className="form-control py-2" 
              placeholder="Nhập họ và tên..." 
              required
              value={formData.fullName}
              onChange={handleInputChange}
            />
          </div>

          <div className="mb-3 auth-input-group">
            <label className="form-label fw-bold text-white">Địa chỉ Email</label>
            <input 
              type="email" 
              name="email"
              className="form-control py-2" 
              placeholder="example@gmail.com" 
              required
              value={formData.email}
              onChange={handleInputChange}
            />
          </div>

          <div className="mb-3 auth-input-group">
            <label className="form-label fw-bold text-white">Mật khẩu</label>
            <input 
              type="password" 
              name="password"
              className="form-control py-2" 
              placeholder="Tối thiểu 6 ký tự" 
              required
              value={formData.password}
              onChange={handleInputChange}
            />
          </div>

          <div className="mb-4 auth-input-group">
            <label className="form-label fw-bold text-white">Xác nhận mật khẩu</label>
            <input 
              type="password" 
              name="confirmPassword"
              className="form-control py-2" 
              placeholder="Nhập lại mật khẩu..." 
              required
              value={formData.confirmPassword}
              onChange={handleInputChange}
            />
          </div>

          <button 
            type="submit" 
            className="btn w-100 py-2 text-uppercase fw-bold text-white rounded-pill shadow-sm auth-submit-btn"
          >
            Đăng Ký {role === 'admin' ? 'Quyền Admin' : 'Thành Viên'}
          </button>

          <div className="text-center mt-3">
            <span className="text-muted">Đã có tài khoản? </span>
            <Link to="/login" className="auth-switch-link fw-bold">Đăng nhập</Link>
          </div>
        </form>

      </div>
    </div>
  );
};

export default RegisterPage;
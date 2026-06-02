import React, { useState } from 'react';
import PageHeader from '../components/PageHeader';

const ContactPage = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  });

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    alert(`Cảm ơn ${formData.name}!\nTin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại qua email ${formData.email} trong thời gian sớm nhất.`);
    setFormData({ name: '', email: '', subject: '', message: '' });
  };

  return (
    <div className="container-fluid p-0">
      <PageHeader title="Liên Hệ Với Chúng Tôi" breadcrumb="Liên Hệ" />

      <div className="container-fluid contact py-5">
        <div className="container py-5">
          <div className="p-5 bg-light rounded border">
            <div className="row g-4">
              
              <div className="col-12 text-center mb-4">
                <h1 className="fw-bold text-uppercase">Để Lại Lời Nhắn</h1>
                <p className="text-muted">Bạn có thắc mắc về sản phẩm hay cần hỗ trợ bảo hành? Hãy điền vào form bên dưới, đội ngũ hỗ trợ của chúng tôi sẽ giải đáp ngay!</p>
              </div>

              {/*FORM ĐIỀN THÔNG TIN */}
              <div className="col-lg-7">
                <form onSubmit={handleSubmit}>
                  <div className="row g-3">
                    <div className="col-md-6">
                      <input 
                        type="text" 
                        name="name"
                        className="form-control p-3 rounded bg-white" 
                        placeholder="Tên của bạn" 
                        required 
                        value={formData.name}
                        onChange={handleInputChange}
                      />
                    </div>
                    <div className="col-md-6">
                      <input 
                        type="email" 
                        name="email"
                        className="form-control p-3 rounded bg-white" 
                        placeholder="Email liên hệ" 
                        required 
                        value={formData.email}
                        onChange={handleInputChange}
                      />
                    </div>
                    <div className="col-12">
                      <input 
                        type="text" 
                        name="subject"
                        className="form-control p-3 rounded bg-white" 
                        placeholder="Tiêu đề (VD: Hỗ trợ kỹ thuật, Hủy đơn...)" 
                        required 
                        value={formData.subject}
                        onChange={handleInputChange}
                      />
                    </div>
                    <div className="col-12">
                      <textarea 
                        name="message"
                        className="form-control p-3 rounded bg-white" 
                        rows="6" 
                        placeholder="Nội dung chi tiết..." 
                        required
                        value={formData.message}
                        onChange={handleInputChange}
                      ></textarea>
                    </div>
                    <div className="col-12">
                      <button className="btn btn-primary w-100 p-3 rounded-pill fw-bold text-uppercase shadow-sm" type="submit">
                        <i className="fa fa-paper-plane me-2"></i> Gửi Tin Nhắn
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div className="col-lg-5">
                <div className="d-flex flex-column h-100">
                  
                  <div className="mb-4">
                    <div className="d-flex align-items-center mb-4">
                      <div className="bg-white d-flex align-items-center justify-content-center rounded-circle border shadow-sm" style={{ width: '60px', height: '60px', flexShrink: 0 }}>
                        <i className="fa fa-map-marker-alt fa-2x text-primary"></i>
                      </div>
                      <div className="ms-4">
                        <h5 className="fw-bold mb-1">Địa chỉ</h5>
                        {/* Cập nhật chữ hiển thị địa chỉ */}
                        <p className="mb-0 text-muted">624 Đường Âu Cơ, Phường Bảy Hiền, TP. Hồ Chí Minh</p>
                      </div>
                    </div>
                    
                    <div className="d-flex align-items-center mb-4">
                      <div className="bg-white d-flex align-items-center justify-content-center rounded-circle border shadow-sm" style={{ width: '60px', height: '60px', flexShrink: 0 }}>
                        <i className="fa fa-envelope fa-2x text-primary"></i>
                      </div>
                      <div className="ms-4">
                        <h5 className="fw-bold mb-1">Email Hỗ Trợ</h5>
                        <p className="mb-0 text-muted">support@lkstore.vn</p>
                      </div>
                    </div>

                    <div className="d-flex align-items-center mb-4">
                      <div className="bg-white d-flex align-items-center justify-content-center rounded-circle border shadow-sm" style={{ width: '60px', height: '60px', flexShrink: 0 }}>
                        <i className="fa fa-phone-alt fa-2x text-primary"></i>
                      </div>
                      <div className="ms-4">
                        <h5 className="fw-bold mb-1">Hotline</h5>
                        <p className="mb-0 text-muted">(+84) 123 456 789</p>
                      </div>
                    </div>
                  </div>

                  <div className="rounded overflow-hidden flex-fill shadow-sm border" style={{ minHeight: '280px' }}>
                    <iframe 
                      className="w-100 h-100" 
                      style={{ border: 0, display: 'block' }} 
                      src="https://maps.google.com/maps?q=624%20Đường%20Âu%20Cơ,%20Phường%20Bảy%20Hiền,%20TP.%20Hồ%20Chí%20Minh&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                      allowFullScreen="" 
                      loading="lazy" 
                      referrerPolicy="no-referrer-when-downgrade">
                    </iframe>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ContactPage;
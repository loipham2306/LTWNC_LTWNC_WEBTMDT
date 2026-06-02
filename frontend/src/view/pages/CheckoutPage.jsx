import React, { useState } from 'react';
import PageHeader from '../components/PageHeader';
// 1. Thêm useLocation và Link từ react-router-dom
import { useLocation, Link } from 'react-router-dom'; 

const CheckoutPage = () => {
  // 2. Nhận danh sách sản phẩm được truyền từ Giỏ Hàng qua Route State
  const location = useLocation();
  const checkoutItems = location.state?.checkoutItems || []; // Nếu vào trực tiếp, mảng này sẽ trống []

  const [formData, setFormData] = useState({
    fullName: '',
    phone: '',
    email: '',
    address: '',
    paymentMethod: 'cod',
    note: ''
  });

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  // 3. Hàm tính tổng tiền động dựa trên các mặt hàng thực tế mang sang
  const calculateTotal = () => {
    return checkoutItems.reduce((total, item) => total + (item.price * item.quantity), 0);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (checkoutItems.length === 0) {
      alert('⚠️ Không có sản phẩm nào để xử lý thanh toán!');
      return;
    }
    alert(`🎉 Đặt hàng thành công!\n👤 Khách hàng: ${formData.fullName}\n💳 Tổng tiền: ${calculateTotal().toLocaleString('vi-VN')} đ`);
  };

  return (
    <div className="container-fluid p-0">
      <PageHeader title="Thanh Toán Đơn Hàng" breadcrumb="Thanh Toán" />

      <div className="container-fluid py-5">
        <div className="container py-5">
          
          {/* 4. ĐIỀU KIỆN KIỂM TRA: Nếu không có sản phẩm nào thì hiện trang trống */}
          {checkoutItems.length === 0 ? (
            <div className="text-center py-5 bg-light rounded border">
              <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty" style={{ width: '100px', opacity: 0.5 }} className="mb-3" />
              <h4 className="text-muted mb-3">Không có mặt hàng nào đang chờ thanh toán!</h4>
              <p className="text-muted mb-4">Vui lòng quay lại Giỏ hàng để lựa chọn các mặt hàng bạn muốn mua.</p>
              <Link to="/cart" className="btn btn-primary rounded-pill px-5 py-3 fw-bold">
                Quay lại Giỏ Hàng
              </Link>
            </div>
          ) : (
            
            // NẾU CÓ SẢN PHẨM THÌ MỚI HIỆN FORM VÀ BẢNG THANH TOÁN
            <form onSubmit={handleSubmit}>
              <div className="row g-5">
                
                {/* CỘT BÊN TRÁI: THÔNG TIN GIAO HÀNG */}
                <div className="col-md-12 col-lg-7">
                  <h3 className="mb-4 fw-bold text-uppercase">Thông Tin Nhận Hàng</h3>
                  <div className="row g-3">
                    <div className="col-12">
                      <label className="form-label fw-bold text-dark">Họ và tên người nhận <span className="text-danger">*</span></label>
                      <input type="text" name="fullName" className="form-control py-3 rounded border" placeholder="Nhập đầy đủ họ và tên" required value={formData.fullName} onChange={handleInputChange} />
                    </div>
                    <div className="col-md-6">
                      <label className="form-label fw-bold text-dark">Số điện thoại <span className="text-danger">*</span></label>
                      <input type="tel" name="phone" className="form-control py-3 rounded border" placeholder="Số điện thoại nhận hàng" required value={formData.phone} onChange={handleInputChange} />
                    </div>
                    <div className="col-md-6">
                      <label className="form-label fw-bold text-dark">Địa chỉ Email (Nếu có)</label>
                      <input type="email" name="email" className="form-control py-3 rounded border" placeholder="example@gmail.com" value={formData.email} onChange={handleInputChange} />
                    </div>
                    <div className="col-12">
                      <label className="form-label fw-bold text-dark">Địa chỉ giao hàng chính xác <span className="text-danger">*</span></label>
                      <input type="text" name="address" className="form-control py-3 rounded border" placeholder="Số nhà, tên đường, phường/xã..." required value={formData.address} onChange={handleInputChange} />
                    </div>
                    <div className="col-12 mt-4">
                      <label className="form-label fw-bold text-dark">Ghi chú đơn hàng (Tùy chọn)</label>
                      <textarea name="note" className="form-control p-3 rounded border" rows="4" placeholder="Ghi chú thêm về đơn hàng..." value={formData.note} onChange={handleInputChange}></textarea>
                    </div>
                  </div>
                </div>

                <div className="col-md-12 col-lg-5">
                  <div className="bg-light rounded p-4 border">
                    <h3 className="mb-4 fw-bold text-uppercase text-center">Đơn Hàng Của Bạn</h3>
                    
                    <div className="table-responsive mb-4">
                      <table className="table table-borderless align-middle">
                        <thead>
                          <tr className="border-bottom">
                            <th className="ps-0 fw-bold text-dark fs-5">Sản phẩm</th>
                            <th className="text-end pe-0 fw-bold text-dark fs-5">Tạm tính</th>
                          </tr>
                        </thead>
                        <tbody>
                          {/* 5. VÒNG LẶP MAP ĐỂ IN RA CÁC MẶT HÀNG THỰC TẾ ĐÃ CHỌN */}
                          {checkoutItems.map((item) => (
                            <tr key={item.id}>
                              <td className="ps-0 text-muted">
                                {item.name} <strong className="text-dark">x {item.quantity}</strong>
                              </td>
                              <td className="text-end pe-0 fw-bold">
                                {(item.price * item.quantity).toLocaleString('vi-VN')} đ
                              </td>
                            </tr>
                          ))}
                          
                          <tr className="border-bottom">
                            <td className="ps-0 text-muted">Phí vận chuyển</td>
                            <td className="text-end pe-0 text-success fw-bold">Miễn phí</td>
                          </tr>
                          <tr>
                            <td className="ps-0 fs-5 fw-bold text-dark">Tổng tiền thanh toán</td>
                            <td className="text-end pe-0 fs-4 fw-bold text-primary">
                              {calculateTotal().toLocaleString('vi-VN')} đ
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <h4 className="mb-3 fw-bold text-uppercase mt-4">Phương Thức Thanh Toán</h4>
                    <div className="form-check mb-3">
                      <input className="form-check-input" type="radio" name="paymentMethod" id="paymentCOD" value="cod" checked={formData.paymentMethod === 'cod'} onChange={handleInputChange} style={{ cursor: 'pointer', width: '18px', height: '18px' }} />
                      <label className="form-check-label fw-bold text-dark ms-2" htmlFor="paymentCOD" style={{ cursor: 'pointer' }}>Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <div className="form-check mb-4">
                      <input className="form-check-input" type="radio" name="paymentMethod" id="paymentBank" value="bank" checked={formData.paymentMethod === 'bank'} onChange={handleInputChange} style={{ cursor: 'pointer', width: '18px', height: '18px' }} />
                      <label className="form-check-label fw-bold text-dark ms-2" htmlFor="paymentBank" style={{ cursor: 'pointer' }}>Chuyển khoản qua ngân hàng (Mã QR)</label>
                    </div>

                    <button type="submit" className="btn btn-primary w-100 py-3 text-uppercase fw-bold rounded-pill fs-5 shadow-sm mt-2">
                      Xác Nhận Đặt Hàng
                    </button>
                  </div>
                </div>

              </div>
            </form>
          )}

        </div>
      </div>
    </div>
  );
};

export default CheckoutPage;
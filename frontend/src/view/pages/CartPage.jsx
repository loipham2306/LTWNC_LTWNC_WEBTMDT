import React, { useState, useEffect } from 'react';
import PageHeader from '../components/PageHeader';
import { Link } from 'react-router-dom';

const CartPage = () => {
  // Lấy dữ liệu từ localStorage khi trang vừa load
  const [cartItems, setCartItems] = useState(() => {
    const savedCart = localStorage.getItem('cart');
    return savedCart ? JSON.parse(savedCart) : [];
  });

  // THEO DÕI: Mỗi khi cartItems bị thay đổi (tăng, giảm, xóa), tự động lưu đè lên localStorage
  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cartItems));
  }, [cartItems]);

  const toggleSelection = (id) => {
    setCartItems(cartItems.map(item => 
      item.id === id ? { ...item, selected: !item.selected } : item
    ));
  };

  const toggleSelectAll = (e) => {
    const isChecked = e.target.checked;
    setCartItems(cartItems.map(item => ({ ...item, selected: isChecked })));
  };

  const handleRemoveSelected = () => {
    if(window.confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) {
      setCartItems(cartItems.filter(item => !item.selected));
    }
  };

  const handleRemove = (id) => {
    setCartItems(cartItems.filter(item => item.id !== id));
  };

  const handleQuantity = (id, type) => {
    const updatedCart = cartItems.map(item => {
      if (item.id === id) {
        let newQuantity = item.quantity;
        if (type === 'plus') newQuantity += 1;
        if (type === 'minus' && newQuantity > 1) newQuantity -= 1;
        return { ...item, quantity: newQuantity };
      }
      return item;
    });
    setCartItems(updatedCart);
  };

  const calculateTotal = () => {
    return cartItems
      .filter(item => item.selected)
      .reduce((total, item) => total + (item.price * item.quantity), 0);
  };

  const selectedCount = cartItems.filter(item => item.selected).length;

  return (
    <div className="container-fluid p-0">
      <PageHeader title="Giỏ Hàng" breadcrumb="Giỏ Hàng" />

      <div className="container-fluid py-5">
        <div className="container py-5">
          
          <div className="table-responsive">
            <table className="table align-middle text-center">
              <thead>
                <tr>
                  <th scope="col">Chọn</th>
                  <th scope="col">Hình Ảnh</th>
                  <th scope="col" className="text-start">Tên Sản Phẩm</th>
                  <th scope="col">Đơn Giá</th>
                  <th scope="col">Số Lượng</th>
                  <th scope="col">Thành Tiền</th>
                  <th scope="col">Xóa</th>
                </tr>
              </thead>
              <tbody>
                {cartItems.map((item) => (
                  <tr key={item.id} className={item.selected ? "bg-light text-dark" : ""}>
                    <td>
                      <input 
                        className="form-check-input" 
                        type="checkbox" 
                        style={{ width: '20px', height: '20px', cursor: 'pointer' }}
                        checked={item.selected}
                        onChange={() => toggleSelection(item.id)}
                      />
                    </td>
                    <td>
                      <img src={item.img} className="img-fluid rounded" style={{ width: '80px', height: '80px', objectFit: 'cover' }} alt={item.name} />
                    </td>
                    <td className="text-start">
                      <p className="mb-0 fw-bold">{item.name}</p>
                    </td>
                    <td>
                      <p className="mb-0">{item.price.toLocaleString('vi-VN')} đ</p>
                    </td>
                    <td>
                      <div className="input-group quantity mx-auto" style={{ width: '120px' }}>
                        <div className="input-group-btn">
                          <button onClick={() => handleQuantity(item.id, 'minus')} className="btn btn-sm btn-minus rounded-circle bg-white border">
                            <i className="fa fa-minus"></i>
                          </button>
                        </div>
                        <input type="text" className="form-control form-control-sm text-center border-0 fw-bold bg-transparent" value={item.quantity} readOnly />
                        <div className="input-group-btn">
                          <button onClick={() => handleQuantity(item.id, 'plus')} className="btn btn-sm btn-plus rounded-circle bg-white border">
                            <i className="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p className="mb-0 fw-bold text-primary">
                        {(item.price * item.quantity).toLocaleString('vi-VN')} đ
                      </p>
                    </td>
                    <td>
                      <button onClick={() => handleRemove(item.id)} className="btn btn-sm text-danger bg-transparent border-0 fs-5">
                        <i className="fa fa-times"></i>
                      </button>
                    </td>
                  </tr>
                ))}

                {cartItems.length === 0 && (
                  <tr>
                    <td colSpan="7" className="text-center py-5">
                      <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" style={{width: '100px', opacity: 0.5}} className="mb-3"/>
                      <h5 className="text-muted mb-3">Giỏ hàng của bạn đang trống!</h5>
                      <Link to="/shop" className="btn btn-primary rounded-pill px-4">Quay lại Cửa Hàng</Link>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {/* THANH THAO TÁC (CONTROL BAR) BÊN DƯỚI */}
          {cartItems.length > 0 && (
            <div className="d-flex flex-wrap justify-content-between align-items-center mt-2 bg-light p-4 rounded border">
              
              <div className="form-check d-flex align-items-center mb-3 mb-md-0">
                <input 
                  className="form-check-input me-3" 
                  type="checkbox" 
                  id="selectAllBottom"
                  style={{ width: '22px', height: '22px', cursor: 'pointer' }}
                  onChange={toggleSelectAll}
                  checked={cartItems.length > 0 && selectedCount === cartItems.length}
                />
                <label className="form-check-label fw-bold text-dark fs-5" htmlFor="selectAllBottom" style={{ cursor: 'pointer' }}>
                  Chọn tất cả ({cartItems.length})
                </label>
              </div>
              
              <div className="d-flex align-items-center gap-3">
                <button 
                  className="btn btn-outline-danger px-4 py-3 fw-bold rounded-pill" 
                  onClick={handleRemoveSelected}
                  disabled={selectedCount === 0}
                >
                  <i className="fa fa-trash me-2"></i> Xóa Đã Chọn
                </button>

                {/* Nút Thanh toán tích hợp tổng tiền */}
             <Link 
  to="/checkout" 
  state={{ checkoutItems: cartItems.filter(item => item.selected) }} 
  className={`btn px-5 py-3 text-uppercase fw-bold rounded-pill ${selectedCount > 0 ? 'btn-primary' : 'btn-secondary disabled'}`}
  style={{ pointerEvents: selectedCount > 0 ? 'auto' : 'none' }}
>
  Thanh Toán {selectedCount > 0 ? `(${calculateTotal().toLocaleString('vi-VN')} đ)` : ''}
</Link>
              </div>

            </div>
          )}

        </div>
      </div>
    </div>
  );
};

export default CartPage;
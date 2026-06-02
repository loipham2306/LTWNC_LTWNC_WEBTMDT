import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { toast } from 'react-toastify';
const ProductCard = ({ product }) => {
  const [isHovered, setIsHovered] = useState(false);

  const handleAddToCart = () => {
    // 1. Lấy giỏ hàng cũ từ bộ nhớ, nếu chưa có thì tạo mảng rỗng []
    const currentCart = JSON.parse(localStorage.getItem('cart')) || [];

    // 2. Kiểm tra món này đã có trong giỏ chưa
    const existingItemIndex = currentCart.findIndex(item => item.id === product.id);

    if (existingItemIndex !== -1) {
      // Có rồi thì cộng dồn số lượng
      currentCart[existingItemIndex].quantity += 1;
    } else {
      // Chưa có thì thêm mới vào (mặc định cho tích chọn sẵn selected: true)
      currentCart.push({ ...product, quantity: 1, selected: true });
    }

    // 3. Lưu ngược lại vào bộ nhớ trình duyệt
    localStorage.setItem('cart', JSON.stringify(currentCart));
    
    toast.success(`Đã thêm ${product.name} vào giỏ!`, {
      style: { borderLeft: '4px solid #F28B00' } 
    });
  };

  return (
    <div className="col-md-6 col-lg-4 col-xl-3 mb-4">
      <div 
        className="card h-100 border rounded shadow-sm bg-white"
        style={{ 
          transform: isHovered ? 'translateY(-8px)' : 'none',
          boxShadow: isHovered ? '0 15px 30px rgba(242,139,0,0.15)' : 'none', 
          transition: 'all 0.3s ease',
          overflow: 'hidden'
        }}
        onMouseEnter={() => setIsHovered(true)}
        onMouseLeave={() => setIsHovered(false)}
      >
        
        {/* HÌNH ẢNH SẢN PHẨM */}
        <div className="position-relative overflow-hidden bg-light" style={{ height: '240px' }}>
          <img src={product.img} className="img-fluid w-100 h-100" style={{ objectFit: 'cover' }} alt={product.name} />
          
          <div 
            className="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style={{
              background: 'rgba(0, 0, 0, 0.3)',
              opacity: isHovered ? 1 : 0,
              transition: 'opacity 0.3s ease',
            }}
          >
            <Link to="/product" className="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow" style={{ width: '50px', height: '50px', transform: isHovered ? 'scale(1)' : 'scale(0.5)', transition: 'transform 0.3s ease' }}>
              <i className="fa fa-eye text-primary fs-5"></i>
            </Link>
          </div>
        </div>

        {/* THÔNG TIN SẢN PHẨM */}
        <div className="card-body text-center d-flex flex-column p-4">
          <p className="text-muted small mb-1">{product.category}</p>
          <Link to="/product" className="card-title h6 fw-bold mb-2 text-dark text-decoration-none d-block">
            {product.name}
          </Link>
          
          <div className="d-flex justify-content-center align-items-center">
            <del className="me-2 text-muted small">{product.oldPrice?.toLocaleString('vi-VN')} đ</del>
            <span className="text-primary fs-5 fw-bold">{product.price.toLocaleString('vi-VN')} đ</span>
          </div>

          {/* KHỐI ẨN: SAO ĐÁNH GIÁ & NÚT THÊM (SẼ TRƯỢT XUỐNG KHI HOVER) */}
          <div 
            style={{
              maxHeight: isHovered ? '150px' : '0px',
              opacity: isHovered ? 1 : 0,
              overflow: 'hidden',
              transition: 'all 0.4s ease',
              marginTop: isHovered ? '15px' : '0px'
            }}
          >
            <div className="d-flex justify-content-center mb-3">
              <i className="fas fa-star text-warning"></i>
              <i className="fas fa-star text-warning"></i>
              <i className="fas fa-star text-warning"></i>
              <i className="fas fa-star text-warning"></i>
              <i className="fas fa-star text-muted"></i>
            </div>
            
            <button 
              onClick={handleAddToCart}
              className="btn btn-primary w-100 rounded-pill py-2 fw-bold text-white shadow-sm"
            >
              <i className="fas fa-cart-plus me-2"></i> Thêm Giỏ Hàng
            </button>
          </div>

        </div>
      </div>
    </div>
  );
};

export default ProductCard;
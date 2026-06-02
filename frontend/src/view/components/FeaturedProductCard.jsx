import React, { useState } from 'react';
import { toast } from 'react-toastify';
const FeaturedProductCard = ({ product }) => {
  //hover
  const [isHovered, setIsHovered] = useState(false);

  const handleAddToCart = () => {
    const currentCart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItemIndex = currentCart.findIndex(item => item.id === product.id);

    if (existingItemIndex !== -1) {
      currentCart[existingItemIndex].quantity += 1;
    } else {
      currentCart.push({ ...product, quantity: 1, selected: true });
    }

    localStorage.setItem('cart', JSON.stringify(currentCart));
    toast.success(`Đã thêm ${product.name} vào giỏ!`, {
      style: { borderLeft: '4px solid #F28B00' } 
    });
  };

  return (
    <div className="col-md-6 col-lg-4 col-xl-3 mb-4">
      <div 
        className="card h-100 rounded"
        style={{ 
          backgroundColor: '#1a1a1a',
          border: isHovered ? '1px solid #F28B00' : '1px solid #333', 
          transform: isHovered ? 'translateY(-10px)' : 'none', // Nảy lên
          boxShadow: isHovered ? '0 10px 25px rgba(242, 139, 0, 0.2)' : 'none', 
          transition: 'all 0.3s ease',
          position: 'relative'
        }}
        onMouseEnter={() => setIsHovered(true)}
        onMouseLeave={() => setIsHovered(false)}
      >
        
        {/* Tag HOT */}
        <div 
          className="position-absolute top-0 start-0 px-3 py-1 rounded-end mt-3 fw-bold" 
          style={{ backgroundColor: '#F28B00', color: '#fff', zIndex: 2 }}
        >
          Hot
        </div>
        <div 
          className="d-flex justify-content-center align-items-center" 
          style={{ height: '220px', borderBottom: '1px solid #333' }}
        >
          <i 
            className={`fas ${product.imgIcon || 'fa-box'} fa-6x`} 
            style={{ 
              color: '#F28B00',
              transform: isHovered ? 'scale(1.1)' : 'scale(1)', // Phóng to icon nhẹ khi hover
              transition: 'transform 0.3s ease' 
            }}
          ></i>
        </div>

        {/* Khối Thông tin */}
        <div className="card-body text-center p-4 d-flex flex-column">
          <h6 className="text-white mb-3 flex-grow-1" style={{ minHeight: '45px', fontSize: '1.1rem' }}>
            {product.name}
          </h6>
          
          <div className="d-flex justify-content-center mb-3">
            <i className="fas fa-star" style={{ color: '#F28B00' }}></i>
            <i className="fas fa-star" style={{ color: '#F28B00' }}></i>
            <i className="fas fa-star" style={{ color: '#F28B00' }}></i>
            <i className="fas fa-star" style={{ color: '#F28B00' }}></i>
            <i className="fas fa-star" style={{ color: '#F28B00' }}></i>
          </div>

          <h5 className="fw-bold mb-1" style={{ color: '#e0e0e0' }}>{product.price.toLocaleString('vi-VN')}đ</h5>
          <del className="small mb-4 d-block" style={{ color: '#666' }}>{product.oldPrice?.toLocaleString('vi-VN')}đ</del>

          {/* Nút bấm (Tự động sáng lên khi hover card) */}
          <button 
            onClick={handleAddToCart}
            className="btn w-100 rounded-pill fw-bold text-white mt-auto"
            style={{ 
              backgroundColor: isHovered ? '#ff9d1a' : '#F28B00',
              transition: 'background-color 0.3s'
            }}
          >
            <i className="fas fa-shopping-bag me-2"></i> Thêm Vào Giỏ
          </button>
        </div>

      </div>
    </div>
  );
};

export default FeaturedProductCard;
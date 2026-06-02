import React, { useState } from 'react';
import FeaturedProductCard from './FeaturedProductCard'; 

const FeaturedProducts = () => {
  const [activeTab, setActiveTab] = useState('Tất Cả');

  
  const products = [
    { id: 101, name: 'Laptop Gaming ASUS ROG', category: 'Điện Tử', price: 25000000, oldPrice: 28000000, imgIcon: 'fa-laptop' },
    { id: 102, name: 'Điện Thoại iPhone 15 Pro', category: 'Điện Tử', price: 28500000, oldPrice: 30000000, imgIcon: 'fa-mobile-alt' },
    { id: 103, name: 'Áo Đấu Thể Thao Điện Tử Đen', category: 'Quần Áo', price: 450000, oldPrice: 600000, imgIcon: 'fa-tshirt' },
    { id: 104, name: 'Áo Khoác Bomber Thời Trang', category: 'Quần Áo', price: 550000, oldPrice: 700000, imgIcon: 'fa-tshirt' }
  ];

  const tabs = ['Tất Cả', 'Điện Tử', 'Quần Áo', 'Giày Dép', 'Phụ Kiện'];

  const filteredProducts = activeTab === 'Tất Cả' 
    ? products 
    : products.filter(p => p.category === activeTab);

  return (
    <div className="container-fluid py-5" style={{ backgroundColor: '#1a1a1a' }}>
      <div className="container py-5">
        
        <div className="d-flex justify-content-center flex-wrap mb-5 gap-3">
          {tabs.map(tab => (
            <button
              key={tab}
              className={`btn rounded-pill px-4 py-2 fw-bold ${activeTab === tab ? 'text-white' : 'text-warning'}`}
              style={{ 
                border: '1px solid #F28B00', 
                backgroundColor: activeTab === tab ? '#F28B00' : 'transparent',
                transition: 'all 0.3s ease'
              }}
              onClick={() => setActiveTab(tab)}
            >
              {tab}
            </button>
          ))}
        </div>
        <div className="row g-4 justify-content-center">
          {filteredProducts.length > 0 ? (
            filteredProducts.map(product => (
              <FeaturedProductCard key={product.id} product={product} />
            ))
          ) : (
            <div className="text-center text-white mt-4">
              <h5>Đang cập nhật sản phẩm...</h5>
            </div>
          )}
        </div>

      </div>
    </div>
  );
};

export default FeaturedProducts;
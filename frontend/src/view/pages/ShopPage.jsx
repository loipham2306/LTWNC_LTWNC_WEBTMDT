import React, { useState, useEffect } from 'react';
import ServiceFeatures from '../components/ServiceFeatures'; 
import PageHeader from '../components/PageHeader';
import ProductCard from '../components/ProductCard';
const ShopPage = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');

  useEffect(() => {
    if (window.WOW) { new window.WOW().init(); }
  }, []);

  const products = [
    { id: 1, name: 'Smart Camera Pro Max', category: 'Điện Tử', price: 3350000, oldPrice: 4110000, img: '/img/product-4.png' },
    { id: 2, name: 'Apple iPad Mini G2356', category: 'Tablet', price: 10500000, oldPrice: 12500000, img: '/img/product-3.png' },
    { id: 3, name: 'Microphone Đa Hướng', category: 'Phụ Kiện', price: 850000, oldPrice: 1000000, img: '/img/product-5.png' },
    { id: 4, name: 'Tai nghe Bluetooth 5.0', category: 'Âm Thanh', price: 1250000, oldPrice: 1500000, img: '/img/product-6.png' }
  ];

  const filteredProducts = products.filter(p => 
    (selectedCategory === 'all' || p.category === selectedCategory) &&
    (p.name.toLowerCase().includes(searchQuery.toLowerCase()))
  );

  const categoryNames = {
    all: 'Tất Cả',
    'dien-tu': 'Điện Tử',
    'quan-ao': 'Quần Áo',
    'giay-dep': 'Giày Dép',
    'phu-kien': 'Phụ Kiện'
  };

  return (
    <div className="container-fluid p-0">
      
      <PageHeader title="Trang cửa hàng"/>
      <div className="mb-5">
        <ServiceFeatures />
      </div>

      <div className="container py-5">
        <h1 className="mb-4">Cửa Hàng Của Chúng Tôi</h1>
        <div className="row g-4">
          
          <div className="col-lg-3">
             <div className="mb-3">
                <h4>Danh Mục</h4>
                <ul className="list-unstyled">
                  {['all', 'dien-tu', 'quan-ao', 'giay-dep', 'phu-kien'].map(cat => (
                    <li key={cat} className="mb-2">
                      <button 
                        className={`btn btn-link p-0 text-decoration-none ${selectedCategory === cat ? 'fw-bold text-primary' : 'text-dark'}`}
                        onClick={() => setSelectedCategory(cat)}
                      >
                        {categoryNames[cat]}
                      </button>
                    </li>
                  ))}
                </ul>
              </div>
          </div>

   {/* LƯỚI SẢN PHẨM */}
          <div className="col-lg-9">
            <div className="row g-4 justify-content-center">
              {filteredProducts.length > 0 ? (
                filteredProducts.map(product => (
                  <ProductCard key={product.id} product={product} />
                ))
              ) : (
                <div className="text-center mt-5">
                  <h3>Không tìm thấy sản phẩm phù hợp 😅</h3>
                </div>
              )}
            </div>
          </div>

        </div> 
      </div> 
    </div> 
  ); 
};

export default ShopPage;
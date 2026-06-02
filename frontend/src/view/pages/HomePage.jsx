import React, { useEffect } from 'react';
import HeroSlider from '../components/HeroSlider';
import ServiceFeatures from '../components/ServiceFeatures';
import FeaturedProducts from '../components/FeaturedProducts';
import ProductCard from '../components/ProductCard';

const HomePage = () => {
  // KHAI BÁO MẢNG DỮ LIỆU SẢN PHẨM Ở ĐÂY ĐỂ TRÁNH LỖI "NOT DEFINED"
  const products = [
    { id: 1, name: 'Smart Camera Pro Max', category: 'Điện Tử', price: 3350000, oldPrice: 4110000, img: '/img/product-4.png' },
    { id: 2, name: 'Apple iPad Mini G2356', category: 'Tablet', price: 10500000, oldPrice: 12500000, img: '/img/product-3.png' },
    { id: 3, name: 'Microphone Đa Hướng', category: 'Phụ Kiện', price: 850000, oldPrice: 1000000, img: '/img/product-5.png' },
    { id: 4, name: 'Tai nghe Bluetooth 5.0', category: 'Âm Thanh', price: 1250000, oldPrice: 1500000, img: '/img/product-6.png' }
  ];

  useEffect(() => {
   
    const timer = setTimeout(() => {
    
      if (window.WOW) {
        console.log("🟢 Đã tìm thấy thư viện WOW.js, đang kích hoạt hiệu ứng!");
        new window.WOW({
          boxClass: 'wow',
          animateClass: 'animated',
          offset: 0,
          mobile: true,
          live: false
        }).init();
      } else {
        console.error("🔴 Không tìm thấy WOW.js. Trình duyệt chưa đọc được file script!");
      }
    }, 500);

    return () => clearTimeout(timer);
  }, []);

  return (
    <>
      <HeroSlider />
      <ServiceFeatures />
      <FeaturedProducts />
      
      <div className="container py-5">
        <div className="text-center mx-auto mb-5" style={{ maxWidth: '700px' }}>
          <h1 className="fw-bold">Sản Phẩm Nổi Bật</h1>
        </div>
        
        <div className="row g-4">
          {products.slice(0, 4).map(product => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      </div>
    </>
  );
};

export default HomePage;
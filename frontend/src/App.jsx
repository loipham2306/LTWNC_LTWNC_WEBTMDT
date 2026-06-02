import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
// Bổ sung thêm useLocation
import { BrowserRouter, Routes, Route, useLocation } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Header from './view/components/Header.jsx';
import Footer from './view/components/Footer.jsx';
import HomePage from './view/pages/HomePage.jsx';
import ShopPage from './view/pages/ShopPage.jsx';
import ProductDetailPage from './view/pages/ProductDetailPage';
import CartPage from './view/pages/CartPage';
import CheckoutPage from './view/pages/CheckoutPage';
import ContactPage from './view/pages/ContactPage';
import LoginPage from './view/pages/LoginPage';
import RegisterPage from './view/pages/RegisterPage';
import UserProfilePage from './view/pages/UserProfilePage';
import AdminDashboardPage from './view/pages/AdminDashboardPage';
import AdminLayout from './view/pages/admin/AdminLayout';
import Dashboard from './view/pages/admin/Dashboard';
import ProductAdmin from './view/pages/admin/ProductAdmin';
import OrderAdmin from './view/pages/admin/OrderAdmin';
import CustomerAdmin from './view/pages/admin/CustomerAdmin';
import VoucherAdmin from './view/pages/admin/VoucherAdmin';
import BrandManagement from './view/pages/admin/BrandManagementPage.jsx'
import CategoryManagement from './view/pages/Admin/CategoryManagement.jsx';
// Tách phần giao diện chính ra một Component con để dùng được hook useLocation
const AppContent = () => {
  const location = useLocation();

  // Kiểm tra xem đường dẫn hiện tại có phải là login hoặc register không
  const isAuthPage = location.pathname === '/login' || location.pathname === '/register';
  const isAdminPage = location.pathname.startsWith('/admin');

  const hideHeaderFooter = isAuthPage || isAdminPage;
  return (
    <>
      {!hideHeaderFooter && <Header />}

      <main style={{ minHeight: '60vh' }}>
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/shop" element={<ShopPage />} />
          <Route path="/product" element={<ProductDetailPage />} />
          <Route path="/cart" element={<CartPage />} />
          <Route path="/checkout" element={<CheckoutPage />} />
          <Route path="/contact" element={<ContactPage />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/profile" element={<UserProfilePage />} />
          <Route path="/admin" element={<AdminDashboardPage />} />
          <Route path="/admin" element={<AdminLayout />}>
            <Route index element={<Dashboard />} />
            <Route path="products" element={<ProductAdmin />} />
            <Route path="orders" element={<OrderAdmin />} />
            <Route path="customers" element={<CustomerAdmin />} />
            <Route path="vouchers" element={<VoucherAdmin />} />
            <Route path="brands" element={<BrandManagement />} />
            <Route path="category" element={<CategoryManagement />} />
          </Route>
        </Routes>
      </main>
      {!hideHeaderFooter && <Footer />}
    </>
  );
};

function App() {
  return (
    <>
      <ToastContainer
        position="bottom-right"
        autoClose={2000}
        theme="dark"
      />

      <BrowserRouter>
        <AppContent />
      </BrowserRouter>
    </>
  );
}

export default App;
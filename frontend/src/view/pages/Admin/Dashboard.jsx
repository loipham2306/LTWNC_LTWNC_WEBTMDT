import React from 'react';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

const Dashboard = () => {

  // --- DỮ LIỆU TỔNG QUAN ---
  const stats = [
    { title: 'Tổng Doanh Thu', value: '125.400.000 đ', icon: 'fa-wallet', color: 'text-success' },
    { title: 'Đơn Hàng Mới', value: '45', icon: 'fa-shopping-cart', color: 'text-warning' },
    { title: 'Sản Phẩm', value: '128', icon: 'fa-box', color: 'text-primary' },
    { title: 'Khách Hàng', value: '1.204', icon: 'fa-users', color: 'text-info' }
  ];

  const revenueData = [
    { name: 'Tháng 1', revenue: 45000000 },
    { name: 'Tháng 2', revenue: 52000000 },
    { name: 'Tháng 3', revenue: 38000000 },
    { name: 'Tháng 4', revenue: 65000000 },
    { name: 'Tháng 5', revenue: 85000000 },
    { name: 'Tháng 6', revenue: 125400000 },
  ];

  const recentOrders = [
    { id: '#ORD-001', customer: 'Nguyễn Văn A', date: '25/05/2026', total: '3.350.000 đ', status: 'Chờ Duyệt' },
    { id: '#ORD-002', customer: 'Trần Thị B', date: '24/05/2026', total: '10.500.000 đ', status: 'Đang Giao' },
    { id: '#ORD-003', customer: 'Lê Hoàng C', date: '23/05/2026', total: '850.000 đ', status: 'Hoàn Thành' },
    { id: '#ORD-004', customer: 'Phạm D', date: '22/05/2026', total: '1.250.000 đ', status: 'Đã Hủy' },
  ];

  return (
    <>
      <h3 className="text-white fw-bold mb-4">Dashboard Tổng Quan</h3>

      {/* 4 Thẻ Thống Kê */}
      <div className="row g-4 mb-4">
        {stats.map((stat, index) => (
          <div className="col-md-6 col-xl-3" key={index}>
            <div className="card border-0 rounded p-4 h-100 shadow-sm" style={{ backgroundColor: '#1a1a1a' }}>
              <div className="d-flex justify-content-between align-items-center">
                <div>
                  <p className="text-muted fw-bold mb-1">{stat.title}</p>
                  <h4 className="text-white fw-bold mb-0">{stat.value}</h4>
                </div>
                <div className={`rounded-circle d-flex align-items-center justify-content-center bg-dark border border-secondary ${stat.color}`} style={{ width: '50px', height: '50px', fontSize: '20px' }}>
                  <i className={`fas ${stat.icon}`}></i>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Biểu đồ doanh thu */}
      <div className="card border-0 rounded p-4 mb-4" style={{ backgroundColor: '#1a1a1a' }}>
        <h5 className="text-white fw-bold mb-4">Biểu Đồ Doanh Thu 6 Tháng Gần Nhất</h5>
        <div style={{ width: '100%', height: 350 }}>
          <ResponsiveContainer>
            <LineChart data={revenueData} margin={{ top: 10, right: 30, left: 20, bottom: 5 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="#333" vertical={false} />
              <XAxis dataKey="name" stroke="#888" tick={{ fill: '#888' }} tickLine={false} axisLine={false} />
              <YAxis stroke="#888" tick={{ fill: '#888' }} tickLine={false} axisLine={false} tickFormatter={(value) => `${value / 1000000}Tr`} />
              <Tooltip contentStyle={{ backgroundColor: '#222', borderColor: '#444', borderRadius: '8px', color: '#fff' }} itemStyle={{ color: '#F28B00', fontWeight: 'bold' }} formatter={(value) => [`${value.toLocaleString('vi-VN')} đ`, 'Doanh Thu']} />
              <Line type="monotone" dataKey="revenue" stroke="#F28B00" strokeWidth={4} dot={{ r: 6, fill: '#1a1a1a', stroke: '#F28B00', strokeWidth: 2 }} activeDot={{ r: 8, fill: '#F28B00', stroke: '#fff' }} />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Bảng đơn hàng mới nhất */}
      <div className="card border-0 rounded p-4" style={{ backgroundColor: '#1a1a1a' }}>
        <div className="d-flex justify-content-between align-items-center mb-4">
          <h5 className="text-white fw-bold mb-0">Đơn Hàng Vừa Đặt</h5>
          <button className="btn btn-sm btn-outline-warning">Xem Tất Cả</button>
        </div>
        
        <div className="table-responsive">
          <table className="table table-dark table-hover align-middle text-center mb-0">
            <thead>
              <tr style={{ borderBottom: '2px solid #F28B00' }}>
                <th scope="col" className="py-3">Mã Đơn</th>
                <th scope="col" className="py-3 text-start">Khách Hàng</th>
                <th scope="col" className="py-3">Ngày Đặt</th>
                <th scope="col" className="py-3">Tổng Tiền</th>
                <th scope="col" className="py-3">Trạng Thái</th>
                <th scope="col" className="py-3">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              {recentOrders.map((order, index) => (
                <tr key={index} style={{ borderBottom: '1px solid #333' }}>
                  <td className="fw-bold text-white py-3">{order.id}</td>
                  <td className="text-muted py-3 text-start">{order.customer}</td>
                  <td className="text-muted py-3">{order.date}</td>
                  <td className="text-primary fw-bold py-3">{order.total}</td>
                  <td className="py-3">
                    <span className={`badge rounded-pill px-3 py-2 
                      ${order.status === 'Chờ Duyệt' ? 'bg-info text-dark' : 
                        order.status === 'Đang Giao' ? 'bg-warning text-dark' : 
                        order.status === 'Đã Hủy' ? 'bg-danger' : 'bg-success'}`}>
                      {order.status}
                    </span>
                  </td>
                  <td className="py-3">
                    <button className="btn btn-sm btn-primary rounded-pill px-3">Chi tiết</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </>
  );
};

export default Dashboard; 
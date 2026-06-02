import React, { useState, useEffect } from 'react';
import axios from 'axios';
// Định nghĩa URL API
const API_URL = 'http://localhost/LTWNC_BAN_HANG/backend/controllers/SanPhamController.php';
const API_URL_DANHMUC = 'http://localhost/LTWNC_BAN_HANG/backend/controllers/DanhMuc.php';
const API_URL_THUONGHIEU = 'http://localhost/LTWNC_BAN_HANG/backend/controllers/ThuongHieuController.php';
const ProductAdmin = () => {
  const [products, setProducts] = useState([]);
  const [showAddModal, setShowAddModal] = useState(false);
  const [categories, setCategories] = useState([]);
  const [brands, setBrands] = useState([]);
  const [formData, setFormData] = useState({
    ten_san_pham: '',
    gia: '',
    id_danh_muc: '',
    id_thuong_hieu: '',
    mo_ta: '',
    hinh_anh: ''
  });
  const [currentPage, setCurrentPage] = useState(1);
  const productsPerPage = 5; // Số sản phẩm hiển thị mỗi trang

  // Tính toán chỉ số của sản phẩm để cắt mảng (pagination logic)
  const indexOfLastProduct = currentPage * productsPerPage;
  const indexOfFirstProduct = indexOfLastProduct - productsPerPage;
  const currentProducts = products.slice(indexOfFirstProduct, indexOfLastProduct);
  const totalPages = Math.ceil(products.length / productsPerPage);
  // Tải danh sách sản phẩm
  const fetchProducts = async () => {
    try {
      const res = await axios.get(API_URL);
      setProducts(res.data);
    } catch (err) { console.error(err); }
  };

  const fetchMetadata = async () => {
    try {
      // Đảm bảo đường dẫn này trỏ tới file API đã thiết lập header CORS
      const [catRes, brandRes] = await Promise.all([
        axios.get(API_URL_DANHMUC),
        axios.get(API_URL_THUONGHIEU)
      ]);
      setCategories(catRes.data);
      setBrands(brandRes.data);
    } catch (error) {
      console.error("Lỗi lấy metadata:", error);
    }
  };

  useEffect(() => {
    fetchProducts();
    fetchMetadata();
  }, []);

  const handleSaveProduct = async () => {
    try {
      // Gửi formData lên server
      await axios.post(API_URL, formData);
      setShowAddModal(false);
      fetchProducts(); // Tải lại bảng sau khi thêm thành công
      // Reset form
      setFormData({ ten_san_pham: '', gia: '', id_danh_muc: '', id_thuong_hieu: '', mo_ta: '', hinh_anh: '' });
    } catch (error) {
      alert("Lỗi khi thêm sản phẩm!");
    }
  };
  return (
    <>
      <h3 className="text-white fw-bold mb-4">Quản Lý Danh Mục Sản Phẩm</h3>

      {/* ================= 1. POPUP THÊM SẢN PHẨM ================= */}
      {showAddModal && (
        <div style={{
          position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
          backgroundColor: 'rgba(0,0,0,0.85)', zIndex: 9999,
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          backdropFilter: 'blur(5px)' // Hiệu ứng làm mờ nền
        }}>
          <div className="card border-secondary shadow-lg" style={{
            width: '100%', maxWidth: '700px', backgroundColor: '#1a1a1a',
            borderRadius: '15px', overflow: 'hidden'
          }}>
            {/* Header Popup */}
            <div className="p-3 d-flex justify-content-between align-items-center" style={{ backgroundColor: '#222', borderBottom: '2px solid #F28B00' }}>
              <h5 className="text-white fw-bold mb-0"><i className="fas fa-plus-circle me-2 text-warning"></i>THÊM SẢN PHẨM MỚI</h5>
              <button onClick={() => setShowAddModal(false)} className="btn-close btn-close-white"></button>
            </div>

            {/* Body Popup (Form) */}
            <div className="p-4" style={{ maxHeight: '80vh', overflowY: 'auto' }}>
              <form>
                <div className="row g-3">
                  <div className="col-12">
                    <label className="form-label text-muted fw-bold small">TÊN SẢN PHẨM</label>
                    <input type="text" className="form-control bg-dark border-secondary text-white py-2" placeholder="Ví dụ: ÁO NIKE" />
                  </div>

                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">DANH MỤC</label>
                    <select
                      className="form-select bg-dark border-secondary text-white"
                      onChange={(e) => setFormData({ ...formData, id_danh_muc: e.target.value })}
                    >
                      <option value="">-- Chọn Danh Mục --</option>
                      {categories.map((cat) => (
                        <option key={cat.id_danh_muc} value={cat.id_danh_muc}>
                          {cat.ten_danh_muc}
                        </option>
                      ))}
                    </select>
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">GIÁ BÁN (VNĐ)</label>
                    <input type="number" className="form-control bg-dark border-secondary text-white" placeholder="0" />
                  </div>

                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">SỐ LƯỢNG KHO</label>
                    <input type="number" className="form-control bg-dark border-secondary text-white" placeholder="0" />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label text-muted fw-bold small">THƯƠNG HIỆU</label>
                    <select
                      className="form-select bg-dark border-secondary text-white"
                      onChange={(e) => setFormData({ ...formData, id_thuong_hieu: e.target.value })}
                    >
                      <option value="">-- Chọn Thương Hiệu --</option>
                      {brands.map((brand) => (
                        <option key={brand.id_thuong_hieu} value={brand.id_thuong_hieu}>
                          {brand.ten_thuong_hieu}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div className="col-12">
                    <label className="form-label text-muted fw-bold small">MÔ TẢ CHI TIẾT</label>
                    <textarea className="form-control bg-dark border-secondary text-white" rows="4" placeholder="Nhập đặc điểm nổi bật..."></textarea>
                  </div>

                  <div className="col-12 mt-3">
                    <div className="border border-secondary border-dashed rounded p-4 text-center" style={{ borderStyle: 'dashed', backgroundColor: '#111' }}>
                      <i className="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                      <p className="text-muted small mb-0">Kéo thả hoặc Click để tải ảnh sản phẩm</p>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            {/* Footer Popup */}
            <div className="p-3 bg-dark d-flex justify-content-end gap-2 border-top border-secondary">
              <button onClick={() => setShowAddModal(false)} className="btn btn-outline-secondary px-4 fw-bold rounded-pill">HỦY</button>
              <button onClick={() => { alert('Đã lưu UI sản phẩm!'); setShowAddModal(false); }} className="btn px-4 fw-bold text-white rounded-pill" style={{ backgroundColor: '#F28B00' }}>LƯU SẢN PHẨM</button>
            </div>
          </div>
        </div>
      )}


      {/* ================= 2. KHU VỰC BẢNG SẢN PHẨM ================= */}
      <div className="card border-0 rounded p-4" style={{ backgroundColor: '#1a1a1a' }}>

        {/* Thanh công cụ: Tìm kiếm & Thêm mới */}
        <div className="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
          <div className="d-flex gap-2 flex-grow-1" style={{ maxWidth: '500px' }}>
            <div className="input-group">
              <span className="input-group-text bg-dark border-secondary text-muted"><i className="fas fa-search"></i></span>
              <input type="text" className="form-control bg-dark border-secondary text-white" placeholder="Tìm kiếm tên sản phẩm, mã SP..." />
            </div>
            <select className="form-select bg-dark border-secondary text-white" style={{ maxWidth: '150px' }}>
              <option value="">Tất cả</option>
              <option value="dientu">Điện Tử</option>
              <option value="quanao">Quần Áo</option>
              <option value="phukien">Phụ Kiện</option>
            </select>
          </div>

          <button onClick={() => setShowAddModal(true)} className="btn fw-bold text-white shadow-sm" style={{ backgroundColor: '#F28B00' }}>
            <i className="fas fa-plus me-2"></i> Thêm Sản Phẩm
          </button>
        </div>

        {/* Bảng Danh Sách Sản Phẩm */}
        <div className="table-responsive">
          <table className="table table-dark table-hover align-middle mb-0">
            <thead>
              <tr style={{ borderBottom: '2px solid #F28B00' }}>
                <th scope="col" className="py-3 px-3">Mã SP</th>
                <th scope="col" className="py-3">Hình Ảnh</th>
                <th scope="col" className="py-3">Tên Sản Phẩm</th>
                <th scope="col" className="py-3 text-center">Danh Mục</th>
                <th scope="col" className="py-3 text-end">Giá Bán</th>
                <th scope="col" className="py-3 text-center">Kho</th>
                <th scope="col" className="py-3 text-center">Trạng Thái</th>
                <th scope="col" className="py-3 text-center">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              {currentProducts.map((product, index) => (
                <tr key={index} style={{ borderBottom: '1px solid #333' }}>
                  <td className="fw-bold text-muted py-3 px-3">{product.id}</td>
                  <td className="py-3">
                    <div className="d-flex align-items-center justify-content-center bg-dark rounded border border-secondary" style={{ width: '50px', height: '50px' }}>
                      <i className={`fas ${product.imgIcon} fs-4`} style={{ color: '#F28B00' }}></i>
                    </div>
                  </td>
                  <td className="text-white fw-bold py-3">{product.name}</td>
                  <td className="text-muted py-3 text-center">{product.category}</td>
                  <td className="text-primary fw-bold py-3 text-end">{product.price}</td>
                  <td className="text-white fw-bold py-3 text-center">{product.stock}</td>
                  <td className="py-3 text-center">
                    <span className={`badge rounded-pill px-3 py-2 ${product.stock > 0 ? 'bg-success' : 'bg-danger'}`}>
                      {product.status}
                    </span>
                  </td>
                  <td className="py-3 text-center">
                    <div className="d-flex justify-content-center gap-2">
                      <button className="btn btn-sm btn-outline-info btn-icon" title="Chỉnh sửa">
                        <i className="fas fa-edit"></i>
                      </button>
                      <button className="btn btn-sm btn-outline-danger btn-icon" title="Xóa">
                        <i className="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        {/* Phân trang động */}
        <div className="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
          <span className="text-muted small">
            Hiển thị {indexOfFirstProduct + 1} - {Math.min(indexOfLastProduct, products.length)} trong tổng số {products.length} sản phẩm
          </span>
          <nav>
            <ul className="pagination pagination-sm mb-0 d-flex flex-row gap-2">
              {/* Nút Trước */}
              <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                <button className="page-link bg-dark border-secondary text-white rounded" onClick={() => setCurrentPage(currentPage - 1)}>
                  <i className="fas fa-chevron-left"></i> Trước
                </button>
              </li>

              {/* Các số trang */}
              {[...Array(totalPages)].map((_, i) => (
                <li key={i} className={`page-item ${currentPage === i + 1 ? 'active' : ''}`}>
                  <button
                    className="page-link border-0 rounded px-3"
                    style={currentPage === i + 1 ? { backgroundColor: '#F28B00', color: 'white' } : { backgroundColor: '#212529', color: '#fff' }}
                    onClick={() => setCurrentPage(i + 1)}
                  >
                    {i + 1}
                  </button>
                </li>
              ))}

              {/* Nút Sau */}
              <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                <button className="page-link bg-dark border-secondary text-white rounded" onClick={() => setCurrentPage(currentPage + 1)}>
                  Sau <i className="fas fa-chevron-right"></i>
                </button>
              </li>
            </ul>
          </nav>
        </div>

      </div>
    </>
  );
};

export default ProductAdmin;
import React, { useState, useEffect } from 'react';
import { toast } from 'react-toastify';
import axios from 'axios';

const API_URL = 'http://localhost/LTWNC_BAN_HANG/backend/controllers/ThuongHieuController.php';
const IMAGE_BASE_URL = 'http://localhost/LTWNC_BAN_HANG/backend/assets/images/brands/';
const BrandManagementPage = () => {
    // 1. Khởi tạo mảng rỗng [] thay vì chuỗi rỗng '' để tránh lỗi hàm .filter() và .map()
    const [brands, setBrands] = useState([]);

    // Các trạng thái Form và Tìm kiếm
    const [searchTerm, setSearchTerm] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [viewingDesc, setViewingDesc] = useState({ isOpen: false, name: '', content: '' });
    const [currentBrand, setCurrentBrand] = useState({
        id_thuong_hieu: '',
        ten_thuong_hieu: '',
        mo_ta: '',
        hinh_anh_logo: '',
        trang_thai: 'active'
    });

    // Lấy danh sách thương hiệu (Đã tối ưu hóa ép kiểu dữ liệu)
    const fetchBrands = async () => {
        try {
            const response = await axios.get(API_URL);

            // Trường hợp 1: Nếu response.data là chuỗi (do PHP dính text lạ), ta cố gắng parse nó
            let dataData = response.data;
            if (typeof dataData === 'string') {
                try {
                    // Tìm vị trí mảng JSON bắt đầu bằng [ và kết thúc bằng ]
                    const jsonStart = dataData.indexOf('[');
                    const jsonEnd = dataData.lastIndexOf(']') + 1;
                    if (jsonStart !== -1 && jsonEnd !== -1) {
                        dataData = JSON.parse(dataData.slice(jsonStart, jsonEnd));
                    }
                } catch (e) {
                    console.error("Không thể tự động ép kiểu chuỗi sang JSON:", e);
                }
            }

            // Trường hợp 2: Đảm bảo cuối cùng dữ liệu nạp vào setBrands phải là mảng
            if (Array.isArray(dataData)) {
                setBrands(dataData);
            } else {
                console.warn("Dữ liệu nhận được không đúng định dạng mảng:", response.data);
                setBrands([]);
            }
        } catch (error) {
            console.error("Lỗi kết nối API Thương Hiệu: ", error);
            toast.error('Không thể kết nối với backend');
        }
    };

    useEffect(() => {
        fetchBrands();
    }, []);

    // Tìm kiếm thương hiệu an toàn (Kiểm tra brands có phải mảng hay không)
    const filteredBrands = Array.isArray(brands)
        ? brands.filter(brand =>
            brand.ten_thuong_hieu?.toLowerCase().includes(searchTerm.toLowerCase())
        )
        : [];

    // Mở modal để Thêm mới
    const handleOpenAddModal = () => {
        setIsEditing(false);
        setCurrentBrand({ id_thuong_hieu: '', ten_thuong_hieu: '', mo_ta: '', hinh_anh_logo: '', trang_thai: 1 });
        setShowModal(true);
    };

    // Mở modal để Chỉnh sửa
    const handleOpenEditModal = (brand) => {
        setIsEditing(true);
        setCurrentBrand(brand);
        setShowModal(true);
    };

    // Hàm lưu thương hiệu lên database
    const handleSaveBrand = async (e) => {
        e.preventDefault();
        if (!currentBrand.ten_thuong_hieu?.trim()) {
            toast.error('Vui lòng nhập tên thương hiệu!');
            return;
        }

        try {
            if (isEditing) {
                // SỬA: Gửi phương thức PUT kèm id_thuong_hieu
                const response = await axios.put(API_URL, currentBrand);
                if (response.status === 200) {
                    toast.success(`Cập nhật thương hiệu thành công!`);
                }
            } else {
                // Trường hợp THÊM MỚI: Gửi phương thức POST
                const response = await axios.post(API_URL, currentBrand);
                if (response.status === 201 || response.status === 200) {
                    toast.success(`Đã thêm thương hiệu mới thành công!`);
                }
            }
            setShowModal(false); // Đóng popup form
            fetchBrands();       // Cập nhật lại bảng danh sách
        } catch (error) {
            console.error("Lỗi lưu dữ liệu:", error);
            toast.error(error.response?.data?.message || 'Có lỗi xảy ra khi lưu dữ liệu!');
        }
    };

    // Hàm DELETE: Xóa thương hiệu
    const handleDeleteBrand = async (id, name) => {
        if (window.confirm(`Bạn có chắc chắn muốn xóa hoàn toàn thương hiệu "${name}" không?`)) {
            try {
                const response = await axios.delete(API_URL, {
                    data: { id_thuong_hieu: id }
                });
                if (response.status === 200) {
                    toast.success(`❌ Đã xóa thương hiệu ${name}`);
                    fetchBrands();
                }
            } catch (error) {
                console.error("Lỗi xóa dữ liệu:", error);
                toast.error('Không thể xóa thương hiệu này!');
            }
        }
    };

    return (
        <div className="container-fluid p-4" style={{ backgroundColor: '#111', minHeight: '100vh', color: '#fff' }}>

            {/* TIÊU ĐỀ TRANG VÀ NÚT THÊM */}
            <div className="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                <div>
                    <h3 className="fw-bold text-white mb-1">Quản Lý Thương Hiệu</h3>
                    <p className="text-muted small mb-0">Hệ thống danh mục đối tác chiến lược của Sàn Hiệu</p>
                </div>
                <button className="btn btn-primary px-4 py-2 rounded-pill fw-bold" onClick={handleOpenAddModal}>
                    <i className="fas fa-plus me-2"></i>Thêm Thương Hiệu
                </button>
            </div>

            {/* THANH TÌM KIẾM */}
            <div className="card border-0 mb-4 p-3" style={{ backgroundColor: '#1a1a1a' }}>
                <div className="row">
                    <div className="col-md-4">
                        <div className="input-group">
                            <span className="input-group-text bg-dark border-secondary text-muted">
                                <i className="fas fa-search"></i>
                            </span>
                            <input
                                type="text"
                                className="form-control bg-dark border-secondary text-white"
                                placeholder="Tìm tên thương hiệu..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* BẢNG DANH SÁCH THƯƠNG HIỆU */}
            <div className="card border-0 rounded overflow-hidden" style={{ backgroundColor: '#1a1a1a' }}>
                <div className="table-responsive">
                    <table className="table table-dark table-hover align-middle mb-0 text-center">
                        <thead>
                            <tr style={{ borderBottom: '2px solid #F28B00' }}>
                                <th scope="col" className="py-3 text-start ps-4">Thương Hiệu</th>
                                <th scope="col" className="py-3">Mô Tả</th>
                                <th scope="col" className="py-3">Số Sản Phẩm</th>
                                <th scope="col" className="py-3">Trạng Thái</th>
                                <th scope="col" className="py-3 pe-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filteredBrands.length > 0 ? (
                                filteredBrands.map((brand) => (
                                    <tr key={brand.id_thuong_hieu} style={{ borderBottom: '1px solid #2a2a2a' }}>
                                        {/* Tên & Logo */}
                                        <td className="py-3 text-start ps-4">
                                            <div className="d-flex align-items-center gap-3">
                                                <div className="bg-white rounded p-1 d-flex align-items-center justify-content-center" style={{ width: '50px', height: '50px', overflow: 'hidden' }}>
                                                    {brand.hinh_anh_logo ? (
                                                        <img
                                                            src={`${IMAGE_BASE_URL}${brand.hinh_anh_logo}`}
                                                            alt={brand.ten_thuong_hieu}
                                                            style={{ width: '100%', height: '100%', objectFit: 'contain' }}
                                                        />
                                                    ) : (
                                                        <span className="text-dark fw-bold" style={{ fontSize: '18px' }}>{brand.ten_thuong_hieu?.charAt(0)}</span>
                                                    )}
                                                </div>
                                                <span className="fw-bold text-white">{brand.ten_thuong_hieu}</span>
                                            </div>
                                        </td>

                                        {/* Mô tả - Chỉ hiện 1 dòng, có dấu ... và icon xem chi tiết */}
                                        <td className="py-3 text-muted text-start" style={{ maxWidth: '300px' }}>
                                            <div className="d-flex align-items-center justify-content-between gap-2">
                                                {/* Phần text cắt chữ */}
                                                <span
                                                    style={{
                                                        fontSize: '14px',
                                                        whiteSpace: 'nowrap',
                                                        overflow: 'hidden',
                                                        textOverflow: 'ellipsis',
                                                        flex: 1
                                                    }}
                                                >
                                                    {brand.mo_ta || <em className="text-secondary">Chưa có mô tả</em>}
                                                </span>

                                                {/* Icon bấm xem chi tiết (Chỉ hiện nếu có mô tả) */}
                                                {brand.mo_ta && (
                                                    <button
                                                        type="button"
                                                        className="btn btn-link text-warning p-0 border-0 ms-1"
                                                        style={{ fontSize: '14px', textDecoration: 'none' }}
                                                        title="Xem chi tiết mô tả"
                                                        onClick={() => setViewingDesc({ isOpen: true, name: brand.ten_thuong_hieu, content: brand.mo_ta })}
                                                    >
                                                        <i className="fas fa-search-plus"></i>
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                        {/* Số sản phẩm */}
                                        <td className="py-3 text-primary fw-bold" >
                                            {brand.count || 0} sản phẩm
                                        </td>

                                        {/* Trạng thái - Đọc theo chuẩn số 1 và 0 */}
                                        <td className="py-3">
                                            {Number(brand.trang_thai) === 1 ? (
                                                <span className="badge rounded-pill px-3 py-2 bg-success">
                                                    Đang hoạt động
                                                </span>
                                            ) : (
                                                <span className="badge rounded-pill px-3 py-2 bg-secondary text-dark">
                                                    Tạm dừng
                                                </span>
                                            )}
                                        </td>

                                        {/* Nút sửa/xóa */}
                                        <td className="py-3 pe-4">
                                            <div className="d-flex justify-content-center gap-2">
                                                <button className="btn btn-sm btn-outline-warning rounded-circle" style={{ width: '35px', height: '35px' }} onClick={() => handleOpenEditModal(brand)}>
                                                    <i className="fas fa-edit"></i>
                                                </button>
                                                <button className="btn btn-sm btn-outline-danger rounded-circle" style={{ width: '35px', height: '35px' }} onClick={() => handleDeleteBrand(brand.id_thuong_hieu, brand.ten_thuong_hieu)}>
                                                    <i className="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="5" className="py-4 text-muted">Không tìm thấy thương hiệu nào hoặc danh sách trống.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div >

            {/* MODAL THÊM / SỬA */}
            {
                showModal && (
                    <div className="modal d-block" style={{ backgroundColor: 'rgba(0,0,0,0.7)', backdropFilter: 'blur(4px)' }}>
                        <div className="modal-dialog modal-dialog-centered">
                            <div className="modal-content text-white border-0" style={{ backgroundColor: '#1a1a1a' }}>
                                <div className="modal-header border-secondary">
                                    <h5 className="modal-title fw-bold text-primary">
                                        {isEditing ? 'Cập Nhật Thương Hiệu' : 'Thêm Thương Hiệu Mới'}
                                    </h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setShowModal(false)}></button>
                                </div>
                                <form onSubmit={handleSaveBrand}>
                                    <div className="modal-body">
                                        <div className="mb-3">
                                            <label className="form-label text-muted fw-bold">Tên Thương Hiệu</label>
                                            <input
                                                type="text"
                                                className="form-control bg-dark border-secondary text-white py-2"
                                                placeholder="Ví dụ: Nike, Adidas..."
                                                required
                                                value={currentBrand.ten_thuong_hieu || ''}
                                                onChange={(e) => setCurrentBrand({ ...currentBrand, ten_thuong_hieu: e.target.value })}
                                            />
                                        </div>

                                        <div className="mb-3">
                                            <label className="form-label text-muted fw-bold">Đường Dẫn Hình Ảnh Logo (URL)</label>
                                            <input
                                                type="text"
                                                className="form-control bg-dark border-secondary text-white py-2"
                                                placeholder="Nhập link ảnh logo"
                                                value={currentBrand.hinh_anh_logo || ''}
                                                onChange={(e) => setCurrentBrand({ ...currentBrand, hinh_anh_logo: e.target.value })}
                                            />
                                            {/* Chỗ xem trước ảnh Preview trong Modal Form khi nhập tên file */}
                                            {currentBrand.hinh_anh_logo && (
                                                <div className="mt-2 p-2 bg-white rounded d-inline-block" style={{ maxWidth: '100px' }}>
                                                    <img
                                                        src={`${IMAGE_BASE_URL}${currentBrand.hinh_anh_logo}`}
                                                        alt="Preview Logo"
                                                        style={{ width: '100%', height: '40px', objectFit: 'contain' }}
                                                        onError={(e) => { e.target.style.display = 'none'; }}
                                                    />
                                                </div>
                                            )}
                                        </div>

                                        <div className="mb-3">
                                            <label className="form-label text-muted fw-bold">Mô Tả Thương Hiệu</label>
                                            <textarea
                                                className="form-control bg-dark border-secondary text-white py-2"
                                                rows="3"
                                                placeholder="Nhập giới thiệu ngắn..."
                                                value={currentBrand.mo_ta || ''}
                                                onChange={(e) => setCurrentBrand({ ...currentBrand, mo_ta: e.target.value })}
                                            ></textarea>
                                        </div>

                                        <div className="mb-3">
                                            <label className="form-label text-muted fw-bold">Trạng Thái Hoạt Động</label>
                                            <select
                                                className="form-select bg-dark border-secondary text-white py-2"
                                                value={currentBrand.trang_thai || 'active'}
                                                onChange={(e) => setCurrentBrand({ ...currentBrand, trang_thai: e.target.value })}
                                            >
                                                <option value={1}>Đang hoạt động</option>
                                                <option value={0}>Tạm dừng</option>
                                            </select>
                                        </div>

                                        {currentBrand.id_thuong_hieu && (
                                            <div className="row g-2 pt-2 border-top border-secondary mt-2">
                                                <div className="col-6">
                                                    <small className="text-secondary d-block">Mã số hãng (ID):</small>
                                                    <small className="text-warning fw-bold">#{currentBrand.id_thuong_hieu}</small>
                                                </div>
                                                <div className="col-6">
                                                    <small className="text-secondary d-block">Ngày khởi tạo:</small>
                                                    <small className="text-white-50">{currentBrand.ngay_tao || 'Vừa xong'}</small>
                                                </div>
                                            </div>
                                        )}
                                    </div>

                                    <div className="modal-footer border-secondary">
                                        <button type="button" className="btn btn-outline-secondary px-4 py-2 rounded-pill text-white" onClick={() => setShowModal(false)}>
                                            Hủy
                                        </button>
                                        <button type="submit" className="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                                            Lưu Thông Tin
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                )
            }
            {/* MODAL XEM CHI TIẾT MÔ TẢ */}
            {viewingDesc.isOpen && (
                <div className="modal d-block" style={{ backgroundColor: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(3px)', zIndex: 1060 }}>
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content text-white border-0 shadow-lg" style={{ backgroundColor: '#222', borderRadius: '15px' }}>
                            <div className="modal-header border-secondary" style={{ borderBottom: '1px solid #333' }}>
                                <h5 className="modal-title fw-bold text-warning">
                                    <i className="fas fa-info-circle me-2"></i>Mô Tả Hãng: {viewingDesc.name}
                                </h5>
                                <button
                                    type="button"
                                    className="btn-close btn-close-white"
                                    onClick={() => setViewingDesc({ isOpen: false, name: '', content: '' })}
                                ></button>
                            </div>
                            <div className="modal-body py-4" style={{ fontSize: '15px', lineHeight: '1.6', color: '#ddd', whiteSpace: 'pre-line' }}>
                                {viewingDesc.content}
                            </div>
                            <div className="modal-footer border-0 justify-content-end pt-0">
                                <button
                                    type="button"
                                    className="btn btn-secondary px-4 py-1.5 rounded-pill fw-bold"
                                    onClick={() => setViewingDesc({ isOpen: false, name: '', content: '' })}
                                >
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div >
    );
};

export default BrandManagementPage;
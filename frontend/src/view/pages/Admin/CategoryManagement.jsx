import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {
    Plus, Search, Pencil, Trash2, Eye, FolderOpen,
    Image as ImageIcon, CalendarDays, CheckCircle2, EyeOff
} from 'lucide-react';

const API_URL = 'http://localhost/LTWNC_BAN_HANG/backend/controllers/DanhMuc.php';
const IMAGE_BASE_URL = 'http://localhost/LTWNC_BAN_HANG/backend/assets/images/categorys/';

const CategoryManager = () => {
    const [categories, setCategories] = useState([]);
    const [searchTerm, setSearchTerm] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [editingCategory, setEditingCategory] = useState(null);
    const initialCategory = {
        ten_danh_muc: '', mo_ta: '', trang_thai: 1,
        ngay_tao: new Date().toISOString().split('T')[0]
    };
    const [currentCategory, setCurrentCategory] = useState(initialCategory);

    const fetchCategories = async () => {
        try {
            const res = await axios.get(API_URL);
            setCategories(Array.isArray(res.data) ? res.data : []);
        } catch (err) { console.error("Lỗi tải dữ liệu:", err); }
    };

    useEffect(() => { fetchCategories(); }, []);

    const handleAddCategory = async (e) => {
        e.preventDefault();
        const { ten_danh_muc, mo_ta, trang_thai } = currentCategory;
        const payload = { ten_danh_muc, mo_ta, trang_thai };

        try {
            await axios.post(API_URL, payload);
            fetchCategories();
            setShowModal(false);
            setCurrentCategory(initialCategory);
        } catch (err) {
            console.error("Lỗi khi thêm:", err);
            alert("Có lỗi xảy ra, vui lòng kiểm tra lại dữ liệu!");
        }
    };
    const handleDeleteCategory = async (id) => {
        if (!window.confirm("Bạn có chắc chắn muốn xóa danh mục này?")) return;

        try {
            await axios.delete(API_URL, {
                data: { id_danh_muc: id }
            });
            setCategories(categories.filter(c => c.id_danh_muc !== id));
        } catch (err) {
            console.error("Lỗi xóa:", err);
            alert("Có lỗi xảy ra!");
        }
    };
    // 1. Hàm mở modal để THÊM mới
    const handleOpenAddModal = () => {
        setEditingCategory(null); // Đảm bảo không ở chế độ sửa
        setCurrentCategory(initialCategory); // Reset form
        setShowModal(true);
    };

    // 2. Hàm mở modal để SỬA (kích hoạt khi bấm nút Pencil)
    const handleOpenEditModal = (item) => {
        setEditingCategory(item); // Lưu item đang chọn vào state
        setCurrentCategory(item); // Nạp thông tin vào form
        setShowModal(true);
    };

    const handleSave = async (e) => {
        e.preventDefault();
        try {
            if (editingCategory) {
                // SỬA: Dùng axios.put
                await axios.put(API_URL, {
                    ...currentCategory,
                    id_danh_muc: editingCategory.id_danh_muc
                });
            } else {
                // THÊM: Dùng axios.post
                await axios.post(API_URL, currentCategory);
            }

            // RESET SAU KHI XONG
            await fetchCategories();
            setShowModal(false);
            setEditingCategory(null); // QUAN TRỌNG: Phải set về null
            setCurrentCategory(initialCategory);
        } catch (err) {
            alert("Lỗi: " + (err.response?.data?.message || err.message));
        }
    };
    const filteredCategories = categories.filter((item) =>
        item.ten_danh_muc.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.mo_ta?.toLowerCase().includes(searchTerm.toLowerCase())
    );
    const filtered = categories.filter(c =>
        c.ten_danh_muc?.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const stats = {
        total: categories.length,
        active: categories.filter(c => parseInt(c.trang_thai) === 1).length,
        hidden: categories.filter(c => parseInt(c.trang_thai) === 0).length
    };

    return (
        <div style={{ background: '#09090b', minHeight: '100vh', padding: '24px', color: 'white', fontFamily: 'sans-serif' }}>
            {/* HEADER */}
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
                <div>
                    <h2 style={{ fontSize: '28px', fontWeight: '700', marginBottom: '4px' }}>Quản Lý Danh Mục</h2>
                    <p style={{ color: '#71717a', fontSize: '14px', margin: 0 }}>Hệ thống quản lý của TRẠM HIỆU</p>
                </div>
                <button onClick={() => setShowModal(true)} style={{ background: '#f59e0b', border: 'none', padding: '10px 20px', borderRadius: '12px', display: 'flex', alignItems: 'center', gap: '8px', fontWeight: '600', cursor: 'pointer' }}>
                    <Plus size={18} /> Thêm Danh Mục
                </button>
            </div>

            {/* STATS */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: '16px', marginBottom: '24px' }}>
                {[
                    { title: 'Tổng', value: stats.total, icon: <FolderOpen size={20} />, color: '#60a5fa' },
                    { title: 'Hiển thị', value: stats.active, icon: <CheckCircle2 size={20} />, color: '#4ade80' },
                    { title: 'Đang Ẩn', value: stats.hidden, icon: <EyeOff size={20} />, color: '#f87171' },
                    { title: 'Sản Phẩm', value: '1,250', icon: <ImageIcon size={20} />, color: '#fb923c' }
                ].map((item, i) => (
                    <div key={i} style={{ background: '#18181b', border: '1px solid #27272a', borderRadius: '16px', padding: '20px', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <div>
                            <p style={{ color: '#a1a1aa', fontSize: '12px', margin: 0 }}>{item.title}</p>
                            <h3 style={{ fontSize: '24px', fontWeight: '800', margin: '4px 0 0' }}>{item.value}</h3>
                        </div>
                        <div style={{ color: item.color }}>{item.icon}</div>
                    </div>
                ))}
            </div>
            <div style={{ marginBottom: '24px', position: 'relative' }}>
                <Search size={20} style={{ position: 'absolute', left: '16px', top: '14px', color: '#71717a' }} />
                <input
                    type="text"
                    placeholder="Tìm kiếm theo tên hoặc mô tả..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    style={{
                        width: '100%',
                        padding: '12px 12px 12px 48px',
                        borderRadius: '12px',
                        background: '#18181b',
                        border: '1px solid #27272a',
                        color: 'white',
                        outline: 'none'
                    }}
                />
            </div>
            {/* TABLE */}
            <div style={{ background: '#18181b', borderRadius: '16px', border: '1px solid #27272a', overflow: 'hidden' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead style={{ background: '#111827' }}>
                        <tr>
                            {['ID', 'Danh Mục', 'Mô Tả', 'Trạng Thái', 'Ngày Tạo', 'Ngày Cập Nhật'].map(h => (
                                <th key={h} style={thStyle}>{h}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {filtered.map((item) => (
                            <tr key={item.id_danh_muc} style={{ borderBottom: '1px solid #27272a', cursor: 'pointer' }} onMouseEnter={e => e.currentTarget.style.background = '#202023'} onMouseLeave={e => e.currentTarget.style.background = 'transparent'}>
                                <td style={tdStyle}>#{item.id_danh_muc}</td>
                                <td style={{ ...tdStyle, fontWeight: '600', whiteSpace: 'nowrap' }}>{item.ten_danh_muc}</td>
                                <td style={{ ...tdStyle, color: '#a1a1aa', maxWidth: '200px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{item.mo_ta || '---'}</td>

                                <td style={tdStyle}>
                                    <span style={{ padding: '4px 12px', borderRadius: '99px', fontSize: '12px', background: item.trang_thai == 1 ? '#064e3b' : '#7f1d1d', color: item.trang_thai == 1 ? '#34d399' : '#f87171' }}>
                                        {item.trang_thai == 1 ? 'Hiển thị' : 'Đã ẩn'}
                                    </span>
                                </td>
                                <td style={{ ...tdStyle, fontSize: '13px', color: '#a1a1aa' }}>{item.ngay_tao}</td>
                                <td style={tdStyle}>
                                    <div style={{ display: 'flex', gap: '8px' }}>
                                        <button
                                            onClick={() => handleOpenEditModal(item)}
                                            style={btnAction('#1e3a8a', '#60a5fa')}
                                        >
                                            <Pencil size={16} />
                                        </button>
                                        <button
                                            onClick={() => handleDeleteCategory(item.id_danh_muc)}
                                            style={btnAction('#7f1d1d', '#f87171')}
                                        >
                                            <Trash2 size={16} />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {showModal && (
                <div className="modal d-block" style={{
                    position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
                    backgroundColor: 'rgba(0,0,0,0.7)', backdropFilter: 'blur(4px)',
                    display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000
                }}>
                    <div className="modal-dialog modal-dialog-centered" style={{ width: '400px', margin: 'auto' }}>
                        {/* Modal Content với style dark & border cam */}
                        <div className="modal-content text-white border-0" style={{
                            backgroundColor: '#1a1a1a',
                            padding: '24px',
                            borderRadius: '16px',
                            border: '1px solid #f59e0b' // Viền cam nổi bật
                        }}>
                            <div className="modal-header border-0 mb-3 p-0">
                                <h5 className="modal-title fw-bold" style={{ color: '#f59e0b' }}>
                                    {editingCategory ? "Cập Nhật Danh Mục" : "Thêm Danh Mục Mới"}
                                </h5>
                            </div>

                            <form onSubmit={handleSave}>
                                <div className="modal-body p-0">
                                    {/* Tên danh mục */}
                                    <div className="mb-3">
                                        <label className="form-label fw-bold" style={{ color: '#a1a1aa', fontSize: '14px' }}>Tên danh mục</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Ví dụ: Đồ điện tử, Thời trang..."
                                            value={currentCategory.ten_danh_muc || ''}
                                            onChange={e => setCurrentCategory({ ...currentCategory, ten_danh_muc: e.target.value })}
                                            style={{
                                                width: '100%', padding: '12px', background: '#27272a',
                                                border: '1px solid #444', borderRadius: '8px', color: 'white'
                                            }}
                                            required
                                        />
                                    </div>

                                    {/* Mô tả */}
                                    <div className="mb-3">
                                        <label className="form-label fw-bold" style={{ color: '#a1a1aa', fontSize: '14px' }}>Mô tả</label>
                                        <textarea
                                            className="form-control"
                                            placeholder="Nhập mô tả ngắn..."
                                            value={currentCategory.mo_ta || ''}
                                            onChange={e => setCurrentCategory({ ...currentCategory, mo_ta: e.target.value })}
                                            style={{
                                                width: '100%', padding: '12px', background: '#27272a',
                                                border: '1px solid #444', borderRadius: '8px', color: 'white', minHeight: '100px'
                                            }}
                                        />
                                    </div>

                                    {/* Trạng thái */}
                                    <div className="mb-4">
                                        <label className="form-label fw-bold" style={{ color: '#a1a1aa', fontSize: '14px' }}>Trạng thái hoạt động</label>
                                        <select
                                            value={currentCategory.trang_thai}
                                            onChange={(e) => setCurrentCategory({ ...currentCategory, trang_thai: parseInt(e.target.value) })}
                                            style={{
                                                width: '100%', padding: '12px', background: '#27272a',
                                                border: '1px solid #444', borderRadius: '8px', color: 'white'
                                            }}
                                        >
                                            <option value={1}>Đang hoạt động</option>
                                            <option value={0}>Tạm dừng</option>
                                        </select>
                                    </div>
                                </div>

                                <div className="modal-footer border-0 p-0 d-flex justify-content-end gap-2">
                                    <button type="button" onClick={() => setShowModal(false)} style={{
                                        padding: '10px 24px', background: 'transparent', border: '1px solid #555',
                                        borderRadius: '8px', color: 'white', cursor: 'pointer'
                                    }}>
                                        Hủy
                                    </button>
                                    <button type="submit" style={{
                                        padding: '10px 24px', background: '#f59e0b', border: 'none',
                                        borderRadius: '8px', color: '#000', cursor: 'pointer', fontWeight: 'bold'
                                    }}>
                                        Lưu Thông Tin
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </div>

    );
};

// Styles
const thStyle = { padding: '16px 20px', color: '#9ca3af', fontSize: '11px', textTransform: 'uppercase', textAlign: 'left' };
const tdStyle = { padding: '14px 20px', verticalAlign: 'middle' };
const btnAction = (bg, color) => ({ width: '36px', height: '36px', border: 'none', borderRadius: '10px', background: bg, color: color, display: 'flex', alignItems: 'center', justifyContent: 'center' });

export default CategoryManager;
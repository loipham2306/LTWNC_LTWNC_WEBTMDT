// Hàm lấy class màu cho trạng thái trong Popup
function getJsStatusBadge(status) {
    switch (status) {
        case 'Hoạt động': return 'bg-success';
        case 'Bị khóa': return 'bg-danger';
        case 'VIP': return 'bg-warning text-dark fw-bold';
        default: return 'bg-secondary';
    }
}
// 1. Hàm Lọc: Kiểm tra tất cả điều kiện
function filterCustomers() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const hangFilter = document.getElementById('hangFilter').value;

    const rows = document.getElementsByClassName('customer-row');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];

        const name =
            row.querySelector('.customer-name')?.innerText.toLowerCase() || '';

        const phone =
            row.querySelector('.customer-phone')?.innerText.toLowerCase() || '';

        const status = row.getAttribute('data-status');
        const hang = (row.getAttribute('data-hang') || '').toLowerCase();

        const matchesSearch =
            name.includes(input) || phone.includes(input);

        const matchesStatus =
            statusFilter === '' || status === statusFilter;

        const matchesHang =
            hangFilter === 'all' || hang === hangFilter.toLowerCase();

        row.style.display =
            (matchesSearch && matchesStatus && matchesHang)
                ? ''
                : 'none';
    }
}

// 2. Hàm Xem chi tiết
function viewCustomerDetail(customer) {
    const fullName = customer.ho_ten_dem + ' ' + customer.ten;
    document.getElementById('modalAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=F28B00&color=fff&size=100`;
    document.getElementById('modalName').innerText = fullName;
    document.getElementById('modalEmail').innerText = customer.so_dien_thoai;

    let isHoatDong = (customer.trang_thai == 1);
    let statusEl = document.getElementById('modalStatus');
    statusEl.innerText = isHoatDong ? 'Hoạt động' : 'Bị khóa';
    statusEl.className = `badge rounded-pill px-3 py-1 ${isHoatDong ? 'bg-success' : 'bg-danger'}`;

    document.getElementById('modalOrders').innerText = customer.so_don || 0;
    document.getElementById('modalSpent').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(customer.tong_chi_tieu || 0);
    document.getElementById('modalDate').innerText = customer.ngay_tao ? customer.ngay_tao.split(' ')[0] : 'N/A';

    let lockBtnContainer = document.getElementById('lockBtnContainer');
    lockBtnContainer.innerHTML = isHoatDong
        ? `<a href="index.php?act=toggleStatus&id_tai_khoan=${customer.id_tai_khoan}&status=0" class="btn btn-outline-danger fw-bold rounded-pill" onclick="return confirm('Bạn có chắc muốn khóa TK này?')"><i class="fas fa-ban me-2"></i>Khóa TK</a>`
        : `<a href="index.php?act=toggleStatus&id_tai_khoan=${customer.id_tai_khoan}&status=1" class="btn btn-outline-success fw-bold rounded-pill" onclick="return confirm('Bạn có chắc muốn mở khóa TK này?')"><i class="fas fa-unlock me-2"></i>Mở Khóa TK</a>`;

    document.getElementById('detailModal').style.display = 'flex';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

// Đóng Popup
function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

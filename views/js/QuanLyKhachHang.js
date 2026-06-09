// Hàm lấy class màu cho trạng thái trong Popup
function getJsStatusBadge(status) {
    switch (status) {
        case 'Hoạt động': return 'bg-success';
        case 'Bị khóa': return 'bg-danger';
        case 'VIP': return 'bg-warning text-dark fw-bold';
        default: return 'bg-secondary';
    }
}

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
    // Dùng thẻ <a> để điều hướng thẳng về PHP
    if (isHoatDong) {
        lockBtnContainer.innerHTML = `<a href="index.php?act=xuLyKhoaMo&id=${customer.id_tai_khoan}&status=0" class="btn btn-outline-danger fw-bold rounded-pill" onclick="return confirm('Bạn có chắc muốn khóa TK này?')"><i class="fas fa-ban me-2"></i>Khóa TK</a>`;
    } else {
        lockBtnContainer.innerHTML = `<a href="index.php?act=xuLyKhoaMo&id=${customer.id_tai_khoan}&status=1" class="btn btn-outline-success fw-bold rounded-pill" onclick="return confirm('Bạn có chắc muốn mở khóa TK này?')"><i class="fas fa-unlock me-2"></i>Mở Khóa TK</a>`;
    }

    document.getElementById('detailModal').style.display = 'flex';
}

// Đóng Popup
function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

// Lọc khách hàng trực tiếp bằng JS
function filterCustomers() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let rows = document.getElementsByClassName('customer-row');

    for (let i = 0; i < rows.length; i++) {
        let name = rows[i].querySelector('.customer-name').innerText.toLowerCase();
        let email = rows[i].querySelector('.customer-email').innerText.toLowerCase();
        let phone = rows[i].querySelector('.customer-phone').innerText.toLowerCase();

        if (name.includes(input) || email.includes(input) || phone.includes(input)) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}
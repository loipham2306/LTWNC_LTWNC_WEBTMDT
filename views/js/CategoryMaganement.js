// Dùng biến để lưu trữ instance của Modal
let catModalInstance;

function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Thêm Danh Mục Mới';
    document.getElementById('formAction').value = 'themDM';
    document.getElementById('catId').value = '';
    document.getElementById('catName').value = '';
    document.getElementById('catDesc').value = '';
    document.getElementById('catStatus').value = '1';

    // Khởi tạo và hiển thị
    catModalInstance = new bootstrap.Modal(document.getElementById('categoryModal'));
    catModalInstance.show();
}

function openEditModal(button) {
    const dataString = button.getAttribute('data-item');
    const category = JSON.parse(dataString);

    document.getElementById('modalTitle').innerText = 'Cập Nhật Danh Mục';
    document.getElementById('formAction').value = 'suaDM';
    document.getElementById('catId').value = category.id_danh_muc;
    document.getElementById('catName').value = category.ten_danh_muc;
    document.getElementById('catDesc').value = category.mo_ta;
    document.getElementById('catStatus').value = category.trang_thai;

    // Khởi tạo và hiển thị
    catModalInstance = new bootstrap.Modal(document.getElementById('categoryModal'));
    catModalInstance.show();
}
// Lọc danh sách trực tiếp trên Front-end
function filterCategories() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let rows = document.getElementsByClassName('cat-row');

    for (let i = 0; i < rows.length; i++) {
        let name = rows[i].querySelector('.cat-name').innerText.toLowerCase();
        let desc = rows[i].querySelector('.cat-desc').innerText.toLowerCase();

        if (name.includes(input) || desc.includes(input)) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}
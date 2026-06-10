let catModalInstance;

// Thêm tham số isRoot để phân biệt
function openAddModal(isRoot = true) {
    document.getElementById('modalTitle').innerText = isRoot ? 'Thêm Danh Mục Gốc' : 'Thêm Danh Mục Con';
    document.getElementById('formAction').value = 'themDM';
    document.getElementById('catId').value = '';
    document.getElementById('catName').value = '';
    document.getElementById('catDesc').value = '';
    document.getElementById('catStatus').value = '1';

    // Xử lý ẩn/hiện ô chọn danh mục cha
    const parentField = document.getElementById('parentCategoryField');
    const selectElement = document.querySelector('select[name="id_danh_muc_cha"]');
    selectElement.value = "";
    if (parentField) {
        parentField.style.display = isRoot ? 'none' : 'block';
    }

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

    // Hiện ô chọn cha khi sửa để admin có thể thay đổi cha cho danh mục
    const parentField = document.getElementById('parentCategoryField');
    if (parentField) {
        parentField.style.display = 'block';
        // Chọn giá trị cha hiện tại (nếu có)
        const selectElement = document.querySelector('select[name="id_danh_muc_cha"]');
        selectElement.value = category.id_danh_muc_cha || "";
    }

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
function viewCategoryDesc(title, desc) {

    document.getElementById("descTitle").innerText = title;

    document.getElementById("descContent").innerText =
        desc || "Chưa có mô tả";

    const modal = new bootstrap.Modal(
        document.getElementById("descModal")
    );

    modal.show();
}
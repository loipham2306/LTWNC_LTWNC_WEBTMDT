function openAddModal() {
    // 1. Cập nhật Tiêu đề & Action
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2 text-warning"></i> THÊM SẢN PHẨM MỚI';
    document.getElementById('formAction').value = 'themSP';

    // 2. Reset các trường thông tin chung (Check tồn tại trước khi set)
    document.getElementById('sanphamId').value = '';
    document.getElementById('prodName').value = '';
    document.getElementById('prodCat').value = '';
    document.getElementById('prodBrand').value = '';
    document.getElementById('prodDesc').value = '';
    document.getElementById('prodStatus').value = '1';
    const basePrice = document.getElementById('prodBasePrice').value || 0;
    // Nếu có trường giảm giá
    if (document.getElementById('prodDiscount')) {
        document.getElementById('prodDiscount').value = '';
    }

    // Ẩn ảnh cũ khi thêm mới
    document.getElementById('prodCurrentImage').style.display = 'none';
    document.getElementById('prodOldImage').value = '';

    // 4. Xử lý các trường không còn tồn tại ở tab 1
    // (Bỏ qua hoặc xóa các lệnh gọi prodPrice/prodStock ở đây)

    // 5. Hiển thị modal
    document.getElementById('productModal').style.display = 'flex';
}

function openEditModal(product) {
    // 1. Cập nhật Tiêu đề và Action
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2 text-info"></i> CẬP NHẬT SẢN PHẨM';
    document.getElementById('formAction').value = 'suaSP';

    // 2. Điền thông tin vào các trường input
    document.getElementById('sanphamId').value = product.id_san_pham;
    document.getElementById('prodName').value = product.ten_san_pham;
    document.getElementById('prodCat').value = product.id_danh_muc;
    document.getElementById('prodBasePrice').value = product.gia_co_ban;
    document.getElementById('prodBrand').value = product.id_thuong_hieu;
    document.getElementById('prodDesc').value = product.mo_ta;
    document.getElementById('prodStatus').value = product.trang_thai;

    // 3. Xử lý hiển thị ảnh (Khai báo 1 lần duy nhất)
    const imgElement = document.getElementById('prodCurrentImage');
    const oldImageInput = document.getElementById('prodOldImage');

    if (product.hinh_anh) {
        imgElement.src = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' + product.hinh_anh;
        imgElement.style.display = 'block';
        if (oldImageInput) oldImageInput.value = product.hinh_anh;
    } else {
        imgElement.style.display = 'none';
        if (oldImageInput) oldImageInput.value = '';
    }

    // 4. Reset về Tab 1
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
    document.getElementById('infoTab').classList.add('show', 'active');

    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
    document.querySelector('[data-bs-target="#infoTab"]').classList.add('active');

    /// Thay vì .json() hãy dùng .text()
    fetch('index.php?act=layBienThe&id=' + product.id_san_pham)
        .then(response => response.text()) // Nhận về chuỗi HTML
        .then(html => {
            const container = document.getElementById('variantsContainer');
            container.innerHTML = html; // Đổ trực tiếp HTML vào tbody

            // Hiển thị modal
            document.getElementById('productModal').style.display = 'flex';
        })
        .catch(err => {
            console.error("Lỗi:", err);
        });
}
function loadVariants(id_sp, ten_sp) {
    // 1. Cập nhật tiêu đề modal
    document.getElementById('modalTitle').innerText = 'Biến thể sản phẩm: ' + ten_sp;

    // 2. Gửi AJAX lên Controller (act=layBienThe)
    fetch('index.php?act=layBienThe&id=' + id_sp)
        .then(response => response.text())
        .then(html => {
            // Đổ HTML trả về vào tbody
            document.getElementById('variantsTableBody').innerHTML = html;
            // Hiển thị modal
            new bootstrap.Modal(document.getElementById('modalVariants')).show();
        });
}
function toggleEditMode(btn) {
    // Sử dụng 'let' hoặc 'const' hợp lý, tránh khai báo lại
    const row = btn.closest('tr');
    if (!row) return;

    const inputs = row.querySelectorAll('.variant-input');
    const isReadonly = inputs[0].hasAttribute('readonly');

    inputs.forEach(input => {
        input.readOnly = !isReadonly;
        input.classList.toggle('border-0', !input.readOnly);
        input.classList.toggle('bg-transparent', !input.readOnly);
    });

    const fileInput = row.querySelector('input[type="file"]');
    if (fileInput) fileInput.style.display = isReadonly ? 'block' : 'none';

    btn.innerHTML = isReadonly ? '<i class="fas fa-save"></i>' : '<i class="fas fa-edit"></i>';
    btn.className = isReadonly ? 'btn btn-success btn-sm' : 'btn btn-warning btn-sm';
}
function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}
// Lọc bảng ngay trên Front-end
function filterProducts() {
    // 1. Lấy giá trị từ các bộ lọc
    let searchInput = document.getElementById('searchInput').value.toLowerCase();
    let catInput = document.getElementById('categoryFilter').value.toLowerCase();
    let statusInput = document.getElementById('statusFilter').value; // Trả về '1', '0' hoặc ''
    let sortInput = document.getElementById('sortOrder').value; // 'newest', 'price_asc', 'price_desc'

    let table = document.querySelector('table tbody');
    let rows = Array.from(document.getElementsByClassName('product-row'));

    // 2. Lọc dữ liệu
    rows.forEach(row => {
        let name = row.querySelector('.product-name').innerText.toLowerCase();
        let id = row.querySelector('.product-id').innerText.toLowerCase();
        let cat = row.querySelector('.product-category').innerText.toLowerCase();
        // Lấy trạng thái từ class badge hoặc thuộc tính ẩn nếu bạn có
        let status = row.querySelector('.badge').innerText.includes('Đang Bán') ? '1' : '0';

        let matchText = name.includes(searchInput) || id.includes(searchInput);
        let matchCat = (catInput === "") || (cat === catInput);
        let matchStatus = (statusInput === "") || (status === statusInput);

        row.style.display = (matchText && matchCat && matchStatus) ? '' : 'none';
    });

    // 3. Sắp xếp (Chỉ sắp xếp các hàng đang hiển thị)
    if (sortInput !== "newest") {
        let visibleRows = rows.filter(r => r.style.display !== 'none');

        visibleRows.sort((a, b) => {
            // Lấy giá từ thẻ, loại bỏ chữ "đ", "." và khoảng trắng
            let priceA = parseInt(a.querySelector('.text-primary').innerText.replace(/\D/g, ''));
            let priceB = parseInt(b.querySelector('.text-primary').innerText.replace(/\D/g, ''));

            return sortInput === 'price_asc' ? priceA - priceB : priceB - priceA;
        });

        // Vẽ lại bảng theo thứ tự mới
        visibleRows.forEach(row => table.appendChild(row));
    }
}
function addVariantRow(data = null, defaultPrice = 0) {
    const container = document.getElementById('variantsContainer');
    const row = document.createElement('tr');

    const stockValue = data ? data.so_luong_ton : '0';

    row.innerHTML = `
        <td><input type="text" name="size[]" class="form-control form-control-sm bg-dark text-white border-secondary" value="${data ? data.kich_co : ''}" required></td>
        <td><input type="text" name="mau[]" class="form-control form-control-sm bg-dark text-white border-secondary" value="${data ? data.mau_sac : ''}" required></td>
        <td><input type="number" name="gia_ban[]" class="form-control form-control-sm bg-dark text-white border-secondary" value="${data ? data.gia_ban : defaultPrice}" required></td>
        <td>
            <input type="number" name="ton_kho[]" class="form-control form-control-sm bg-dark text-white border-secondary" value="${stockValue}" required>
        </td>        
        <td><input type="file" name="anh_bien_the[]" class="form-control form-control-sm bg-dark text-white border-secondary"></td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    container.appendChild(row);
}
// GLOBAL VARIABLES CHO MODALS
let brandModal, descModal, imageBaseUrl;

document.addEventListener('DOMContentLoaded', function () {

    imageBaseUrl = '<?= $IMAGE_BASE_URL ?>';

    // ❗ INIT BOOTSTRAP MODAL SAU KHI DOM READY
    brandModal = new bootstrap.Modal(
        document.getElementById('brandModal')
    );

    descModal = new bootstrap.Modal(
        document.getElementById('descModal')
    );

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('logoPreview');
    const previewContainer = document.getElementById('logoPreviewContainer');

    // CLICK FILE
    dropZone.addEventListener('click', () => fileInput.click());

    // DRAG OVER
    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.style.borderColor = '#ffc107';
    });

    // DROP
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        handleFile(file);
    });

    // CHANGE FILE
    fileInput.addEventListener('change', e => {
        handleFile(e.target.files[0]);
    });

    function handleFile(file) {

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Chỉ được chọn file ảnh!');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ảnh tối đa 2MB!');
            return;
        }

        const reader = new FileReader();

        reader.onload = e => {
            preview.src = e.target.result;
            previewContainer.classList.remove('d-none');
        };

        reader.readAsDataURL(file);

        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
    }

    window.openAddModal = function () {
        // 1. Kiểm tra từng ID một cách an toàn
        const modalTitle = document.getElementById('modalTitle');
        if (modalTitle) modalTitle.innerText = 'Thêm Thương Hiệu Mới';

        const formAction = document.getElementById('formAction');
        if (formAction) formAction.value = 'themTH';

        const brandName = document.getElementById('brandName');
        if (brandName) brandName.value = '';

        const brandDesc = document.getElementById('brandDesc');
        if (brandDesc) brandDesc.value = '';

        const previewContainer = document.getElementById('logoPreviewContainer');
        if (previewContainer) previewContainer.classList.add('d-none');

        // 2. Mở Modal an toàn
        var modalEl = document.getElementById('brandModal');
        if (modalEl) {
            var myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            myModal.show();
        } else {
            console.error("Không tìm thấy phần tử #brandModal");
        }
    };

    window.openEditModal = function (brand) {
        // 1. Cấu hình giao diện
        document.getElementById('modalTitle').innerText = "Cập Nhật Thương Hiệu";
        document.getElementById('formAction').value = "suaTH";

        // 2. Điền dữ liệu
        document.getElementById('brandId').value = brand.id_thuong_hieu;
        document.getElementById('brandName').value = brand.ten_thuong_hieu;
        document.getElementById('brandDesc').value = brand.mo_ta;
        document.getElementById('brandStatus').value = brand.trang_thai;
        document.getElementById('oldLogo').value = brand.hinh_anh_logo || '';

        // 3. Hiển thị ảnh
        const previewContainer = document.getElementById('logoPreviewContainer');
        const previewImg = document.getElementById('logoPreview');
        if (brand.hinh_anh_logo) {
            previewImg.src = "/LTWNC_LTWNC_WEBTMDT/assets/images/brands/" + brand.hinh_anh_logo;
            previewContainer.classList.remove('d-none');
        } else {
            previewContainer.classList.add('d-none');
        }

        // 4. Meta data
        document.getElementById('editMeta').classList.remove('d-none');
        document.getElementById('displayBrandId').innerText = brand.id_thuong_hieu;

        // 5. MỞ MODAL ĐÚNG CÁCH (Không tạo mới instance chồng chéo)
        var modalEl = document.getElementById('brandModal');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    };

    // HÀM XEM CHI TIẾT MÔ TẢ
    window.viewDesc = function (brandName, description) {
        // 1. Cập nhật nội dung
        document.getElementById('descTitle').innerText = brandName;
        document.getElementById('descContent').innerText = description;

        // 2. Khởi tạo và show modal (Dùng getOrCreateInstance để tránh lỗi trùng lặp)
        var modalEl = document.getElementById('descModal');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    };

    // HÀM LỌC/TÌM KIẾM THƯƠNG HIỆU
    window.filterBrands = function () {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const tableRows = document.querySelectorAll('.brand-row');

        tableRows.forEach(row => {
            const brandName = row.querySelector('.brand-name').innerText.toLowerCase();
            if (brandName.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    };

});
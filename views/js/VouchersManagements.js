function openVoucherModal(mode, data = null) {
    const modal = document.getElementById('voucherModal');
    const form = document.getElementById('voucherForm');
    const title = document.getElementById('modalTitle');
    const formAction = document.getElementById('formAction');
    const btnSubmit = document.getElementById('btnSubmit');

    modal.style.display = 'flex';

    if (mode === 'edit') {
        formAction.value = 'CapNhatVoucher';
        title.innerHTML = '<i class="fas fa-edit me-2 text-warning"></i>CHỈNH SỬA VOUCHER';
        btnSubmit.innerHTML = 'CẬP NHẬT VOUCHER';

        document.getElementById('input_id').value = data.id_voucher;
        document.getElementById('input_ma').value = data.ma_voucher;
        document.getElementById('input_loai').value = data.loai_giam_gia;
        document.getElementById('input_giatri').value = data.gia_tri_giam;
        document.getElementById('input_min').value = data.don_toi_thieu;
        document.getElementById('input_limit').value = data.so_luong_ma;

        // Cải tiến: Xử lý chuỗi ngày tháng để input datetime-local nhận diện đúng
        // Chỉ lấy 16 ký tự đầu: YYYY-MM-DDTHH:MM
        let dateValue = data.ngay_het_han.replace(' ', 'T').substring(0, 16);
        document.getElementById('input_ngay').value = dateValue;
    } else {
        formAction.value = 'ThemVoucher';
        title.innerHTML = '<i class="fas fa-ticket-alt me-2 text-warning"></i>TẠO MÃ GIẢM GIÁ MỚI';
        btnSubmit.innerHTML = 'TẠO VOUCHER';
        form.reset();
    }
}
function filterTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toUpperCase();
    let table = document.querySelector(".table"); // Chọn bảng của bạn
    let tr = table.getElementsByTagName("tr");

    // Lặp qua tất cả các hàng, ẩn những hàng không khớp
    for (let i = 1; i < tr.length; i++) { // Bắt đầu từ 1 để bỏ qua thẻ <thead>
        let td = tr[i].getElementsByTagName("td")[0]; // Tìm theo cột 1 (Mã Voucher)
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
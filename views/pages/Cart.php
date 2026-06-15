

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        /* Reset nền tảng web sang màu Đen eSports */
        body { background-color: #111; color: #fff; margin: 0; padding: 0; overflow-x: hidden; }

        /* CSS của trang Giỏ Hàng */
        .cart-wrapper { background-color: #1a1a1a; min-height: 50vh; }
        .table-dark-custom { color: #fff; border-color: #333; }
        .table-dark-custom thead th { background-color: #222; border-bottom: 2px solid #F28B00; color: #F28B00; text-transform: uppercase; }
        .table-dark-custom tbody td { background-color: #1a1a1a; border-bottom: 1px solid #333; vertical-align: middle; }
        
        .btn-qty { background: #333; border: 1px solid #444; color: #fff; transition: 0.3s; }
        .btn-qty:hover { background: #F28B00; color: #fff; border-color: #F28B00; }
        .input-qty { background: transparent; color: #fff; border: none; font-weight: bold; text-align: center; width: 40px; }
        
        .custom-checkbox { width: 22px; height: 22px; cursor: pointer; accent-color: #F28B00; }
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
        .control-bar { background-color: #222; border: 1px solid #333; }
        /* Làm nổi bật số tiền */
.price-highlight { color: #F28B00 !important; font-weight: bold; }
/* Đảm bảo chữ trong bảng rõ ràng */
.table-dark-custom td { color: #ccc !important; }
    </style>
</head>
<body>

    <?php
    // Cấu hình thông tin cho PageHeader
    $pageTitle = "Giỏ Hàng Của Bạn";
    $pageBreadcrumb = "Giỏ Hàng";

    // Gọi Component Header & PageHeader
    include_once __DIR__ . '/../components/Header.php';
    include_once __DIR__ . '/../components/PageHeader.php';
    ?>

    <div class="container-fluid py-5 cart-wrapper">
        <div class="container py-5">
            
            <div id="empty-state" class="text-center py-5" style="display: <?= empty($_SESSION['cart']) ? 'block' : 'none' ?>;">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" style="width: 120px; opacity: 0.5;" class="mb-4"/>
                <h4 class="text-white-50 mb-4">Giỏ hàng của bạn đang trống!</h4>
                <a href="/LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=Shop" class="btn btn-orange rounded-pill px-5 py-3 fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại Cửa Hàng
                </a>
            </div>

            <div id="cart-content" style="display: <?= !empty($_SESSION['cart']) ? 'block' : 'none' ?>;">
                <div class="table-responsive">
                    <table class="table table-dark-custom text-center align-middle wow fadeInUp" data-wow-delay="0.1s">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">Chọn</th>
                                <th scope="col" style="width: 15%;">Hình Ảnh</th>
                                <th scope="col" class="text-start" style="width: 25%;">Tên Sản Phẩm</th>
                                <th scope="col" style="width: 15%;">Đơn Giá</th>
                                <th scope="col" style="width: 15%;">Số Lượng</th>
                                <th scope="col" style="width: 15%;">Thành Tiền</th>
                                <th scope="col" style="width: 10%;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody">
                            <?php if (empty($_SESSION['cart'])): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">Giỏ hàng của bạn đang trống!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($_SESSION['cart'] as $key => $item): 
                        
                                    ?>
                                    <tr>
                                        <td>
                                            <input
                                                class="form-check-input custom-checkbox product-checkbox"
                                                type="checkbox"
                                                value="<?= $key ?>"
                                                data-unit-price="<?= $item['gia'] ?>"
                                                data-price="<?= $item['gia'] * $item['so_luong'] ?>"
                                                checked
                                                onchange="updateTotal()"
                                            />
                                        </td>
                                        <td><img src="/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products/<?= $item['hinh_anh'] ?>" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;"></td>
                                        <td class="text-start">
                                            <p class="mb-0 fw-bold text-white"><?= $item['ten_san_pham'] ?></p>
                                            <small class="text-white-50">
                                                Size: <?= $item['size'] ?> | 
                                                Màu: <?= (!empty($item['ten_mau'])) ? $item['ten_mau'] : getColorName($item['mau']) ?>
                                            </small>
                                        </td>
                                        <td><?= number_format($item['gia'], 0, ',', '.') ?> đ</td>
                                       <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-sm btn-qty" onclick="updateQty('<?= $key ?>', 'minus')">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                
                                                <input type="text" class="input-qty mx-2" id="qty-<?= $key ?>" value="<?= $item['so_luong'] ?>" readonly>
                                                
                                                <button type="button" class="btn btn-sm btn-qty" onclick="updateQty('<?= $key ?>', 'plus')">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['gia'] * $item['so_luong'], 0, ',', '.') ?> đ</td>
                                        <td>
                                            <button type="button" 
                                                    onclick="xoaSanPham('<?= $key ?>')" 
                                                    class="btn btn-sm btn-outline-danger border-0">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>                                    
                                         </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 p-4 rounded control-bar">
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input custom-checkbox me-3" type="checkbox" id="selectAllBottom" onclick="toggleAll(this)">
                        <label class="form-check-label fw-bold text-white" for="selectAllBottom">Chọn tất cả</label>
                    </div>
                    
                    <div class="text-end">
                        <span class="text-white-50">Tổng thanh toán: </span>
                        <span class="fs-3 fw-bold text-orange" id="total-price" style="color: #F28B00;">
                            <?php 
                            $tong = 0;
                            foreach ($_SESSION['cart'] as $item) $tong += ($item['gia'] * $item['so_luong']);
                            echo number_format($tong, 0, ',', '.') . ' đ';
                            ?>
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="button" 
                                onclick="xoaDaChon()" 
                                class="btn btn-outline-danger px-4 py-3 fw-bold rounded-pill">
                            Xóa đã chọn
                        </button>
                        <button
                            type="button"
                            class="btn btn-orange px-5 py-3 fw-bold rounded-pill"
                            onclick="goToCheckout()">
                            Thanh Toán
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include_once __DIR__. '/../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        function updateQty(id, type) {
            let input = document.getElementById(`qty-${id}`);
            let checkbox = document.querySelector(`.product-checkbox[value="${id}"]`);

            let unitPrice = parseFloat(checkbox.dataset.unitPrice);

            let currentQty = parseInt(input.value);
            let newQty = (type === 'plus') ? currentQty + 1 : Math.max(1, currentQty - 1);

            fetch(`index.php?act=CapNhatSoLuong&id=${id}&type=${type}`)
            .then(res => res.text())
            .then(() => {

                // update UI
                input.value = newQty;

                let newTotalRow = newQty * unitPrice;

                checkbox.dataset.price = newTotalRow;

                let row = checkbox.closest('tr');
                row.querySelectorAll('td')[5].innerText =
                    newTotalRow.toLocaleString('vi-VN') + ' đ';

                updateTotal();
            });
        }
        function updateTotal() {
            let total = 0;
            // Lấy tất cả checkbox sản phẩm đang được tích
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            
            checkedBoxes.forEach(function(box) {
                // Cộng giá trị data-price vào tổng
                total += parseFloat(box.getAttribute('data-price'));
            });

            // Định dạng lại tiền tệ (ví dụ: 1.000.000 đ)
            document.getElementById('total-price').innerText = 
                total.toLocaleString('vi-VN') + ' đ';
        }

        // Cập nhật hàm toggleAll để gọi luôn hàm tính tổng
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = source.checked;
            });
            updateTotal(); // Gọi lại hàm tính tổng sau khi chọn tất cả
        }
        function selectColor(colorName, element) {
            // 1. Cập nhật dòng chữ hiển thị
            document.getElementById('selected-color-text').innerText = "Đã chọn: " + colorName;
            
            // 2. Hiệu ứng: Thêm viền nổi bật cho ô màu được chọn
            const options = document.querySelectorAll('.color-option');
            options.forEach(opt => opt.style.borderColor = '#666'); // Reset tất cả về màu cũ
            element.style.borderColor = '#F28B00'; // Đổi màu viền ô được chọn sang cam
        }
        function goToCheckout() {
            let selected = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(item => {
                selected.push(item.value);
            });

            if(selected.length === 0) {
                alert("Vui lòng chọn ít nhất 1 sản phẩm!");
                return;
            }

            let formData = new FormData();
            // Đảm bảo dữ liệu gửi đi đúng định dạng mảng
            selected.forEach(id => {
                formData.append('selected_products[]', id);
            });

            fetch('index.php?act=LuuSanPhamThanhToan', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.href = 'index.php?act=ThanhToan';
                } else {
                    alert(data.message || "Có lỗi xảy ra khi lưu đơn hàng!");
                }
            })
            .catch(err => console.error(err));
        }
        // Trong file Cart.php, phía Client
        function guiDuLieuThanhToan() {
            // Lấy tất cả checkbox đã chọn
            let selected = [];
            document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                selected.push(cb.value);
            });

            if (selected.length === 0) {
                alert("Vui lòng chọn ít nhất 1 sản phẩm!");
                return;
            }

            // Gửi bằng AJAX
            fetch('index.php?act=LuuSanPhamThanhToan', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'selected_products[]=' + selected.join('&selected_products[]=')
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Chuyển hướng sang trang thanh toán sau khi lưu session thành công
                    window.location.href = 'index.php?act=ThanhToan';
                } else {
                    alert(data.message);
                }
            });
        }
        function xoaSanPham(id) {
            if(!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

            fetch('index.php?act=XoaGioHang&id=' + id)
            .then(response => {
                // Sau khi xóa xong, đơn giản nhất là reload lại trang 
                // HOẶC gọi hàm để cập nhật lại giao diện (đề xuất reload để đảm bảo khớp dữ liệu session)
                window.location.reload(); 
            });
        }
        function xoaDaChon() {
            // 1. Lấy danh sách ID các checkbox đã tích
            let selected = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(item => {
                selected.push(item.value);
            });

            if(selected.length === 0) {
                alert("Vui lòng chọn ít nhất một sản phẩm để xóa!");
                return;
            }

            if(!confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) return;

            // 2. Gửi dữ liệu qua AJAX
            let formData = new FormData();
            selected.forEach(id => formData.append('selected_products[]', id));

            fetch('index.php?act=XoaDaChon', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // 3. Xóa thành công thì tải lại trang để cập nhật giao diện
                window.location.reload();
            })
            .catch(error => console.error('Lỗi:', error));
        }
    </script>
</body>
</html>
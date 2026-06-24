<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản Của Tôi - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        /* Tông nền tối eSports vững chãi */
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .profile-wrapper { background-color: #111; min-height: 80vh; padding-bottom: 50px; }
        .profile-card { background-color: #1a1a1a; border: 1px solid #333; }
        
        /* Menu quản lý bên trái */
        .list-group-custom .list-group-item {
            background-color: transparent;
            color: #aaa;
            border: none;
            border-bottom: 1px solid #2a2a2a;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .list-group-custom .list-group-item:hover { background-color: #222; color: #F28B00; }
        .list-group-custom .list-group-item.active { background-color: #F28B00 !important; color: #fff !important; }
        
        /* Định dạng Form Input tối màu cứng cáp */
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .form-control:disabled { background-color: #222 !important; color: #666 !important; border-color: #333 !important; }
        
        /* Bảng lịch sử đơn hàng */
        .table-profile th { background-color: #222; border-bottom: 2px solid #F28B00; color: #F28B00; text-transform: uppercase; }
        .table-profile td { background-color: #1a1a1a; border-bottom: 1px solid #2a2a2a; color: #eee; vertical-align: middle; }
        
        /* Thành phần màu sắc Cam thương hiệu */
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
        .btn-outline-orange { border: 1px solid #F28B00; color: #F28B00; background: transparent; }
        .btn-outline-orange:hover { background-color: #F28B00; color: #fff; }
        .text-orange { color: #F28B00 !important; }
        .avatar-circle { background-color: #F28B00 !important; color: #fff; font-weight: bold; text-transform: uppercase; }
        /* Nền tối của card */
/* Tổng thể thẻ */
.voucher-card { 
    background: #1a1a1a; 
    border: 1px solid #333; 
    padding: 15px; 
    border-radius: 8px; 
    position: relative; 
    transition: all 0.3s ease;
    color: #fff;
}

/* Phần Mã Voucher - Làm nổi bật */
.voucher-code {
    color: #FFD700 !important; /* Vàng Gold rực rỡ */
    font-weight: 800 !important;
    font-size: 1.1rem;
    text-transform: uppercase;
    text-shadow: 0 0 5px rgba(255, 215, 0, 0.3);
}

/* Phần trạng thái */
.voucher-status.text-success { color: #28a745 !important; font-weight: bold; }

/* Phần giảm giá */
.voucher-discount { color: #fff; margin-bottom: 8px; font-weight: 600; }
.voucher-discount span { color: #F28B00; font-size: 1.3rem; font-weight: 900; }

/* Thông tin chi tiết */
.voucher-condition, .voucher-exp {
    color: #ffffff; /* Đổi thành màu trắng hoàn toàn để hết bị chìm */
    font-size: 0.9rem;
    margin-bottom: 4px;
    font-weight: 500;
}

/* Sửa nút bấm bị xấu */
.btn-copy { 
    width: 100%; 
    margin-top: 15px; 
    padding: 10px; 
    background-color: #F28B00 !important; 
    color: #fff !important; 
    border: none !important; 
    border-radius: 4px; 
    font-weight: bold;
    cursor: pointer;
    text-transform: uppercase;
}
.btn-copy:hover { background-color: #d67a00 !important; }
/* Style cho Rank Badge */
.rank-badge {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #fff;
    margin-top: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

/* Màu sắc theo từng hạng */
.rank-diamond { background: linear-gradient(45deg, #00d4ff, #0055ff); }
.rank-gold    { background: linear-gradient(45deg, #FFD700, #F28B00); }
.rank-silver  { background: linear-gradient(45deg, #adb5bd, #6c757d); }
.rank-member  { background: #444; }
    </style>
</head>
<body>

    <?php
    // Gán dữ liệu cấu hình cho PageHeader cha
    $pageTitle = "Tài Khoản Của Tôi";
    $pageBreadcrumb = "Tài Khoản";
    include __DIR__ . '/../components/Header.php'; 
    include __DIR__ . '/../components/PageHeader.php';
    ?>

    <div class="container-fluid profile-wrapper py-5">
        <div class="container py-5">

            <div class="row g-4">
                <?php
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']); // Xóa sau khi đã hiển thị
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']); // Xóa sau khi đã hiển thị
                    }
                    ?>
                <div class="col-lg-3 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="card profile-card rounded p-4 text-center mb-4">
                        <div class="d-flex justify-content-center mb-3">
                            <div id="userAvatar" class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; font-size: 30px;">U</div>
                        </div>
                        <h5 id="userSidebarName" class="text-white fw-bold mb-1">Khách Hàng</h5>
                        <div id="userRankBadge" class="rank-badge">Thành Viên</div> 
                    </div>

                    <div class="card profile-card rounded overflow-hidden">
                        <div class="list-group list-group-flush list-group-custom" id="profileTabs">
                            <button class="list-group-item py-3 fw-bold active" onclick="switchTab('info', this)">
                                <i class="fas fa-user me-3"></i>Thông Tin Cá Nhân
                            </button>
                            <button class="list-group-item py-3 fw-bold" onclick="switchTab('vouchers', this)">
                                <i class="fas fa-ticket-alt me-3"></i>Ví Voucher
                            </button>
                            <button class="list-group-item py-3 fw-bold" onclick="switchTab('orders', this)">
                                <i class="fas fa-shopping-bag me-3"></i>Lịch Sử Đơn Hàng
                            </button>
                            <button class="list-group-item py-3 fw-bold" onclick="switchTab('password', this)">
                                <i class="fas fa-lock me-3"></i>Đổi Mật Khẩu
                            </button>
                            <button class="list-group-item py-3 fw-bold text-danger" onclick="handleLogout()">
                                <i class="fas fa-sign-out-alt me-3"></i>Đăng Xuất
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="card profile-card rounded p-4 h-100">
                        <div id="tabContent-info" class="tab-pane-custom">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Hồ Sơ Của Tôi</h4>
                            <form action="index.php?act=updateProfile" method="POST" id="infoForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Họ & Tên Đệm</label>
                                        <input type="text" name="ho_ten_dem" id="ho_ten_dem" class="form-control py-2" placeholder="Nhập họ và tên đệm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Tên</label>
                                        <input type="text" name="ten"  id="ten" class="form-control py-2" placeholder="Nhập tên">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Số Điện Thoại</label>
                                        <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control py-2" placeholder="Nhập số điện thoại">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Tên đăng nhập / Email</label>
                                        <input type="text" id="ten_dang_nhap" class="form-control py-2" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label text-white-50 fw-bold">Địa Chỉ Giao Hàng</label>
                                        <textarea name="dia_chi" id="dia_chi" class="form-control py-2" rows="3" placeholder="Nhập số nhà, tên đường, quận/huyện..."></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-orange px-5 py-2 fw-bold rounded-pill">
                                            Lưu Thay Đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tabContent-orders" class="tab-pane-custom" style="display: none;">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Đơn Hàng Gần Đây</h4>
                            <div class="table-responsive">
                                <table class="table table-profile text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3">Mã Đơn</th>
                                            <th scope="col" class="py-3">Ngày Mua</th>
                                            <th scope="col" class="py-3">Tổng Tiền</th>
                                            <th scope="col" class="py-3">Trạng Thái</th>
                                            <th scope="col" class="py-3">Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ordersTableBody">
                                        </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div id="tabContent-vouchers" class="tab-pane-custom" style="display: none;">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Ví Voucher</h4>
                            <div class="row g-3" id="voucherContainer">
                                <?php 
                            $danhSachVoucherCuaToi = $_SESSION['user']['vouchers'] ?? [];
                            
                            if (!empty($danhSachVoucherCuaToi)) : 
                                foreach ($danhSachVoucherCuaToi as $vc) : 
                                    $isUsed = ($vc['da_su_dung'] == 1);
                            ?>
                                <div class="col-12 col-md-6">
                                    <div class="voucher-card <?= $isUsed ? 'opacity-50' : '' ?>">
                                        <div class="voucher-top">
                                            <span class="voucher-code">🎟 <?= htmlspecialchars($vc['ma_voucher']) ?></span>
                                            <span class="voucher-status <?= $isUsed ? 'text-danger' : 'text-success' ?>">
                                                <?= $isUsed ? 'Đã sử dụng' : 'Chưa sử dụng' ?>
                                            </span>
                                        </div>

                                        <div class="voucher-body">
                                            <div class="voucher-discount">
                                                Giảm <span><?= $vc['loai_giam_gia'] == 'percent' ? $vc['gia_tri_giam'] . '%' : number_format($vc['gia_tri_giam']) . 'đ' ?></span>
                                            </div>
                                            <div class="voucher-condition">
                                                🛒 Đơn tối thiểu: <?= number_format($vc['don_toi_thieu']) ?>đ
                                            </div>
                                            <div class="voucher-exp">
                                                ⏳ HSD: <?= htmlspecialchars($vc['ngay_het_han']) ?>
                                            </div>
                                        </div>
                                        
                                        <?php if($isUsed): ?>
                                            <button class="btn-copy btn-disabled" disabled>Đã dùng</button>
                                        <?php else: ?>
                                            <button class="btn-copy" onclick="window.location.href='index.php?act=Shop'">Sử dụng ngay</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; 
                            else : ?>
                                <p class="text-white-50 p-3">Hiện tại bạn chưa có voucher nào trong ví.</p>
                            <?php endif; ?>
                            </div>
                        </div>
                        <div id="tabContent-password" class="tab-pane-custom" style="display: none;">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Đổi Mật Khẩu</h4>
                            <form action="index.php?act=changePassword" method="POST" id="passwordForm">
                                <div class="row g-4" style="max-width: 600px;">
                                    <div class="col-12">
                                        <label class="form-label text-white-50 fw-bold">Mật khẩu hiện tại</label>
                                        <input type="password" name="old_password" id="oldPassword" class="form-control" required>                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white-50 fw-bold">Mật khẩu mới</label>
                                        <input type="password" name="new_password" id="newPassword" class="form-control" required>                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white-50 fw-bold">Xác nhận mật khẩu mới</label>
                                        <input type="password" name="confirm_password" id="confirmPassword" class="form-control" required>                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-orange px-5 py-2 fw-bold rounded-pill">
                                            Cập Nhật Mật Khẩu
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

  <?php include __DIR__ . '/../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
       const orders = <?= json_encode($danhSachDonHang ?? []); ?>; 
       console.log("ORDERS FULL:", orders);
        console.log("Dữ liệu đơn hàng:", orders);
        new WOW().init();

        // Khai báo biến toàn cục quản lý dữ liệu User
        let userData = {
            id_tai_khoan: '', ten_dang_nhap: '', vai_tro: '', ho_ten_dem: '',
            ten: '', so_dien_thoai: '', dia_chi: '', hang_thanh_vien: ''
        };



        document.addEventListener("DOMContentLoaded", function() {
            renderOrders();
            // Gọi AJAX để lấy dữ liệu từ Server/Controller
            // Giả sử ID được lấy từ session PHP, bạn có thể truyền thẳng vào đây
            const taiKhoanId = "<?= $_SESSION['user']['id_tai_khoan'] ?? 0 ?>";

            if (taiKhoanId > 0) {
                fetch(`index.php?act=layChiTiet&id_tai_khoan=${taiKhoanId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data && !data.error) { // Kiểm tra nếu data tồn tại và không phải là thông báo lỗi
                            userData = data;
                            fillUserData();
                        } else {
                            console.warn('Không tìm thấy dữ liệu người dùng');
                        }
                    })
                    .catch(error => console.error('Lỗi khi lấy thông tin:', error));
            }
        });
        // Đổ dữ liệu từ Object vào các trường form input và sidebar hiển thị
        function fillUserData() {
            document.getElementById('ho_ten_dem').value = userData.ho_ten_dem || '';
            document.getElementById('ten').value = userData.ten || '';
            document.getElementById('so_dien_thoai').value = userData.so_dien_thoai || '';
            document.getElementById('ten_dang_nhap').value = userData.ten_dang_nhap || '';
            document.getElementById('dia_chi').value = userData.dia_chi || '';
            
            // Render tên hiển thị đầy đủ (Fullname logic)
            let rawFullName = `${userData.ho_ten_dem || ''} ${userData.ten || ''}`.trim();
            let finalName = rawFullName || userData.ten_dang_nhap || 'Khách Hàng';
            document.getElementById('userSidebarName').innerText = finalName;
            
            // Render chữ cái đầu avatar
            document.getElementById('userAvatar').innerText = userData.ten ? userData.ten.charAt(0) : 'U';
            
            // Hạng thành viên
            const rankElement = document.getElementById('userRankBadge'); // Đảm bảo ID đúng trong HTML
            const rankRaw = (userData.hang_thanh_vien || 'member').toLowerCase();
            
            // Reset classes
            rankElement.className = 'rank-badge'; 
            
            // Gán class màu sắc và text
            if (rankRaw.includes('diamond')) {
                rankElement.classList.add('rank-diamond');
                rankElement.innerText = '💎 Kim Cương';
            } else if (rankRaw.includes('gold')) {
                rankElement.classList.add('rank-gold');
                rankElement.innerText = '👑 Vàng';
            } else if (rankRaw.includes('silver')) {
                rankElement.classList.add('rank-silver');
                rankElement.innerText = '🥈 Bạc';
            } else {
                rankElement.classList.add('rank-member');
                rankElement.innerText = '👤 Thành Viên';
            }
        }

        function renderOrders() {
            const tbody = document.getElementById('ordersTableBody');
            if (!tbody) return;

            // Kiểm tra nếu orders không phải là mảng hoặc rỗng
            if (!Array.isArray(orders) || orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-white-50 text-center">Chưa có đơn hàng nào.</td></tr>';
                return;
            }
            
            let html = '';
            orders.forEach(order => {
                // Dựa trên object bạn cung cấp:
                const id = order.id_don_hang; 
                const date = order.ngay_dat;
                
                // Định dạng tiền tệ từ 8800000.00
                const total = Number(order.tong_tien).toLocaleString('vi-VN') + ' đ';
                
                const status = order.trang_thai_don_hang;

               let badgeClass = 'bg-secondary';
               let actionButton = `
                    <button
                        class="btn btn-sm btn-outline-orange"
                        onclick="viewOrderDetail(${order.id_don_hang})">
                        Chi tiết
                    </button>
                `;
                switch (order.trang_thai_don_hang) {
                    case 'Đã giao':
                        badgeClass = 'bg-success';
                        break;

                    case 'Chờ duyệt':
                        badgeClass = 'bg-warning text-dark';
                        break;

                    case 'Đang giao':
                        badgeClass = 'bg-info text-dark';
                        break;

                    case 'Hủy':
                    case 'Đã hủy':
                        badgeClass = 'bg-danger';
                        break;
                }
                
                html += `
                    <tr>
                        <td class="fw-bold text-white py-3">#${id}</td>
                        <td class="text-white-50 py-3">${date}</td>
                        <td class="text-orange fw-bold py-3">${total}</td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3 py-2 ${badgeClass}">
                                ${status}
                            </span>
                        </td>
                       <td class="py-3">${actionButton}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }
        function viewOrderDetail(orderId) {
            const order = orders.find(o => String(o.id_don_hang) === String(orderId));

            if (!order) return;

            const items = Array.isArray(order.items) ? order.items : [];

            let itemsHtml = '';

            if (items.length > 0) {
                itemsHtml = `
                <table class="table table-dark table-bordered align-middle mt-3">
                    <thead>
                        <tr class="text-orange">
                            <th>Sản phẩm</th>
                            <th>Size</th>
                            <th>Màu</th>
                            <th>SL</th>
                            <th>Giá</th>
                            <th>Tạm tính</th>
                            <th>Đánh giá</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                items.forEach(sp => {
                    const price = Number(sp.gia_luc_mua || 0);
                    const qty = Number(sp.so_luong || 0);
                    const total = price * qty;
                    const reviewBtn =
                        order.trang_thai_don_hang === 'Đã giao'
                        ? `
                            <button
                                class="btn btn-warning btn-sm mt-2"
                                onclick="openReviewModal(${order.id_don_hang}, ${sp.id_bien_the})">
                                ⭐ Đánh giá
                            </button>
                        `
                        : '';    
                    itemsHtml += `
                        <tr>
                            <td>${sp.ten_san_pham}</td>
                            <td>${sp.kich_co ?? '-'}</td>
                            <td>
                                <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:${sp.mau_sac}"></span>
                                ${sp.mau_sac ?? '-'}
                            </td>
                            <td>${qty}</td>
                            <td>${price.toLocaleString('vi-VN')} đ</td>
                            <td class="text-warning fw-bold">
                                ${total.toLocaleString('vi-VN')} đ
                            </td>
                            <td>
                                <div>${sp.ten_san_pham}</div>
                                ${reviewBtn}
                            </td>
                        </tr>
                    `;
                });

                itemsHtml += `</tbody></table>`;
            } else {
                itemsHtml = `<p class="text-white-50">Không có sản phẩm</p>`;
            }

            let html = `
                <div>
                    <h5 class="text-orange">#${order.id_don_hang}</h5>
                    <p class="text-white-50">Ngày đặt: ${order.ngay_dat}</p>
                    <p class="text-white-50">Trạng thái: <b>${order.trang_thai_don_hang}</b></p>
                    <p class="text-white-50">Địa chỉ: ${order.dia_chi_giao_hang ?? '-'}</p>
                    <p class="text-white-50">Người nhận: ${order.ten_nguoi_nhan ?? '-'}</p>
                    <p class="text-white-50">SĐT: ${order.sdt_nguoi_nhan ?? '-'}</p>
                </div>

                <hr class="border-secondary">

                <h6 class="text-orange">Sản phẩm đã mua</h6>
                ${itemsHtml}

                <div class="mt-3 text-end">
                    <h5>Tổng tiền:
                        <span class="text-orange">
                            ${Number(order.tong_tien || 0).toLocaleString('vi-VN')} đ
                        </span>
                    </h5>
                </div>
            `;

            document.getElementById('orderDetailBody').innerHTML = html;

            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
        }
        // ĐIỀU KHIỂN CHUYỂN TAB MƯỢT MÀ (Thay thế useState activeTab)
        function switchTab(tabName, element) {
            // Ẩn toàn bộ các ô nội dung tab
            document.querySelectorAll('.tab-pane-custom').forEach(pane => pane.style.display = 'none');
            // Gỡ bỏ trạng thái active ở các nút bấm cũ
            document.querySelectorAll('#profileTabs button').forEach(btn => btn.classList.remove('active'));
            
            // Hiển thị tab được chọn và gán viền sáng active
            document.getElementById(`tabContent-${tabName}`).style.display = 'block';
            element.classList.add('active');
        }

        // XỬ LÝ HÀM CẬP NHẬT THÔNG TIN HỒ SƠ
        document.getElementById('infoForm').addEventListener('submit', function(e) {
            userData.ho_ten_dem = document.getElementById('ho_ten_dem').value;
            userData.ten = document.getElementById('ten').value;
            userData.so_dien_thoai = document.getElementById('so_dien_thoai').value;
            userData.dia_chi = document.getElementById('dia_chi').value;
            
            // Ghi đè cập nhật ngược lại vào localStorage
            localStorage.setItem('user', JSON.stringify(userData));
            fillUserData(); // Cập nhật lại UI lập tức
            
            alert('🎉 Cập nhật thông tin thành công!');
        });

        // XỬ LÝ ĐỔI MẬT KHẨU
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;
            
            // Chỉ chặn gửi form NẾU mật khẩu không khớp
            if (newPass !== confirmPass) {
                e.preventDefault(); 
                alert('⚠️ Xác nhận mật khẩu mới không trùng khớp!');
                return false;
            }
        });

        function handleLogout() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                // 1. Xóa dữ liệu tạm ở trình duyệt (nếu bạn vẫn muốn dùng)
                localStorage.removeItem('user');
                
                // 2. Chuyển hướng đến hành động logout của Controller PHP
                // Việc này sẽ gọi case 'logout' trong switch-case của index.php
                window.location.href = 'index.php?act=logout';
            }
        }
        // Thêm hàm lấy dữ liệu Voucher từ Server
        function loadVouchers() {
            const container = document.getElementById('voucherContainer');
            // Giả sử bạn đã có action 'layVoucherCuaToi' trong Controller
            fetch('index.php?act=layVoucherCuaToi')
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        container.innerHTML = '<p class="text-white-50">Bạn chưa có voucher nào.</p>';
                        return;
                    }
                    
                    let html = '';
                    data.forEach(vc => {
                        html += `
                        <div class="col-md-6">
                            <div class="card p-3" style="background: #222; border-left: 5px solid #F28B00;">
                                <h6 class="text-orange fw-bold">${vc.ma_voucher}</h6>
                                <p class="text-white mb-1">Giảm: ${vc.gia_tri_giam} ${vc.loai_giam_gia == 1 ? '%' : 'đ'}</p>
                                <small class="text-white-50">Hạn: ${vc.ngay_het_han}</small>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                })
                .catch(err => {
                    container.innerHTML = '<p class="text-danger">Không thể tải voucher.</p>';
                });
        }
        function openReviewModal(orderId, detailId) {

            const order = orders.find(
                o => o.id_don_hang == orderId
            );

            if (!order) return;
             console.log("ORDER:", order);
            console.log("DETAIL ID:", detailId);
            console.table(order.items);           
            const item = order.items.find(
                i => i.id_bien_the == detailId
            );

            if (!item) return;

            document.getElementById('review_order_id').innerText =
                order.id_don_hang;

            document.getElementById('review_order_date').innerText =
                order.ngay_dat;

            document.getElementById('review_product_name').innerText =
                item.ten_san_pham;

            document.getElementById('review_product_size').innerText =
                item.kich_co || '-';

            document.getElementById('review_product_color').innerText =
                item.mau_sac || '-';

            document.getElementById('review_product_qty').innerText =
                item.so_luong;
            document.getElementById('review_product_image').src =
                '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' + item.hinh_anh_san_pham;

            document.getElementById('review_id_san_pham').value =
                item.id_san_pham;

            document.getElementById('review_id_bien_the').value =
        item.id_bien_the;

            new bootstrap.Modal(
                document.getElementById('reviewModal')
            ).show();
        }
        
    </script>
    
    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-secondary">

            <div class="modal-header border-secondary">
                <h5 class="modal-title text-orange">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="orderDetailBody"></div>

            </div>
        </div>
    </div>
   <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-secondary">

                <form action="index.php?act=guiDanhGia" method="POST">

                    <div class="modal-header border-secondary">
                        <h5 class="modal-title text-orange">
                            ⭐ Đánh Giá Sản Phẩm
                        </h5>

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_san_pham" id="review_id_san_pham">
                        <input type="hidden" name="id_bien_the" id="review_id_bien_the">
                        <!-- Thông tin đơn hàng -->
                        <div class="mb-3 p-3 border rounded bg-secondary bg-opacity-10">
                            <div class="small text-white-50">
                                Đơn hàng #
                                <span id="review_order_id"></span>
                            </div>

                            <div class="small text-white-50">
                                Ngày mua:
                                <span id="review_order_date"></span>
                            </div>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="d-flex gap-3 mb-4 align-items-center">

                            <img
                                id="review_product_image"
                                src=""
                                width="100"
                                height="100"
                                class="rounded border"
                                style="object-fit:cover"
                            >

                            <div>
                                <h5 id="review_product_name" class="fw-bold mb-2"></h5>

                                <div class="text-white-50">
                                    Size:
                                    <span id="review_product_size"></span>
                                </div>

                                <div class="text-white-50">
                                    Màu:
                                    <span id="review_product_color"></span>
                                </div>

                                <div class="text-white-50">
                                    Số lượng:
                                    <span id="review_product_qty"></span>
                                </div>
                            </div>

                        </div>

                        <!-- Đánh giá -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Số sao đánh giá
                            </label>

                            <select
                                name="so_sao"
                                class="form-control"
                                required>

                                <option value="5">⭐⭐⭐⭐⭐ Rất hài lòng</option>
                                <option value="4">⭐⭐⭐⭐ Hài lòng</option>
                                <option value="3">⭐⭐⭐ Bình thường</option>
                                <option value="2">⭐⭐ Chưa hài lòng</option>
                                <option value="1">⭐ Tệ</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Nhận xét của bạn
                            </label>

                            <textarea
                                name="noi_dung"
                                rows="4"
                                class="form-control"
                                placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Đóng
                        </button>
                        <button
                            type="submit"
                            class="btn btn-orange">
                            Gửi Đánh Giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
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

                <div class="col-lg-3 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="card profile-card rounded p-4 text-center mb-4">
                        <div class="d-flex justify-content-center mb-3">
                            <div id="userAvatar" class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-size: 30px;">
                                U
                            </div>
                        </div>
                        <h5 id="userSidebarName" class="text-white fw-bold mb-1">Khách Hàng</h5>
                        <p id="userRank" class="text-white-50 small mb-0">Hạng: Thành Viên Mới</p>
                    </div>

                    <div class="card profile-card rounded overflow-hidden">
                        <div class="list-group list-group-flush list-group-custom" id="profileTabs">
                            <button class="list-group-item py-3 fw-bold active" onclick="switchTab('info', this)">
                                <i class="fas fa-user me-3"></i>Thông Tin Cá Nhân
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
                            <form id="infoForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Họ & Tên Đệm</label>
                                        <input type="text" id="ho_ten_dem" class="form-control py-2" placeholder="Nhập họ và tên đệm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Tên</label>
                                        <input type="text" id="ten" class="form-control py-2" placeholder="Nhập tên">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Số Điện Thoại</label>
                                        <input type="text" id="so_dien_thoai" class="form-control py-2" placeholder="Nhập số điện thoại">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Tên đăng nhập / Email</label>
                                        <input type="text" id="ten_dang_nhap" class="form-control py-2" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label text-white-50 fw-bold">Địa Chỉ Giao Hàng</label>
                                        <textarea id="dia_chi" class="form-control py-2" rows="3" placeholder="Nhập số nhà, tên đường, quận/huyện..."></textarea>
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

                        <div id="tabContent-password" class="tab-pane-custom" style="display: none;">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Đổi Mật Khẩu</h4>
                            <form id="passwordForm">
                                <div class="row g-4" style="max-width: 600px;">
                                    <div class="col-12">
                                        <label class="form-label text-white-50 fw-bold">Mật khẩu hiện tại</label>
                                        <input type="password" id="oldPassword" class="form-control py-2" placeholder="Nhập mật khẩu cũ..." required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white-50 fw-bold">Mật khẩu mới</label>
                                        <input type="password" id="newPassword" class="form-control py-2" placeholder="Nhập mật khẩu mới..." required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white-50 fw-bold">Xác nhận mật khẩu mới</label>
                                        <input type="password" id="confirmPassword" class="form-control py-2" placeholder="Nhập lại mật khẩu mới..." required>
                                    </div>
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
        new WOW().init();

        // Khai báo biến toàn cục quản lý dữ liệu User
        let userData = {
            id_tai_khoan: '', ten_dang_nhap: '', vai_tro: '', ho_ten_dem: '',
            ten: '', so_dien_thoai: '', dia_chi: '', hang_thanh_vien: ''
        };

        // Mảng dữ liệu mockup Đơn hàng giống React gốc
        const orders = [
            { id: '#LK2034', date: '25/05/2026', total: '3.350.000 đ', status: 'Đang Giao' },
            { id: '#LK1988', date: '12/05/2026', total: '1.250.000 đ', status: 'Hoàn Thành' },
        ];

        document.addEventListener("DOMContentLoaded", function() {
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
            document.getElementById('userRank').innerText = 'Hạng: ' + (userData.hang_thanh_vien || 'Thành Viên Mới');
        }

        // Vẽ danh sách lịch sử mua hàng vào bảng
        function renderOrders() {
            const tbody = document.getElementById('ordersTableBody');
            let html = '';
            
            orders.forEach(order => {
                const badgeClass = order.status === 'Đang Giao' ? 'bg-warning text-dark' : 'bg-success';
                html += `
                    <tr>
                        <td class="fw-bold text-white py-3">${order.id}</td>
                        <td class="text-white-50 py-3">${order.date}</td>
                        <td class="text-orange fw-bold py-3">${order.total}</td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3 py-2 ${badgeClass}">
                                ${order.status}
                            </span>
                        </td>
                        <td class="py-3">
                            <button class="btn btn-sm btn-outline-orange rounded-pill px-3">Xem</button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
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
            e.preventDefault();
            
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
            e.preventDefault();
            
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;
            
            if (newPass !== confirmPass) {
                alert('⚠️ Xác nhận mật khẩu mới không trùng khớp!');
                return;
            }
            
            alert('🎉 Cập nhật mật khẩu thành công!');
            this.reset();
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
    </script>
</body>
</html>
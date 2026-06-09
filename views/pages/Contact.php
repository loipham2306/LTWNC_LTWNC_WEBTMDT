<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        /* Phong cách eSports Đen - Cam chủ đạo */
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .contact-wrapper { background-color: #1a1a1a; min-height: 60vh; }
        .contact-box { background-color: #222; border: 1px solid #333; }
        
        /* Định dạng Form Input tối màu cứng cáp */
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .form-control::placeholder { color: #666 !important; }
        
        /* Hộp tròn chứa Icon bên phải */
        .icon-box { background-color: #111; border: 1px solid #444; width: 60px; height: 60px; flex-shrink: 0; transition: 0.3s; }
        .icon-box:hover { background-color: #F28B00; border-color: #F28B00; }
        .icon-box:hover i { color: #fff !important; }
        
        /* Màu sắc thương hiệu */
        .text-orange { color: #F28B00 !important; }
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
    </style>
</head>
<body>

    <?php
    // Khai báo cấu hình "props" cho PageHeader
    $pageTitle = "Liên Hệ Với Chúng Tôi";
    $pageBreadcrumb = "Liên Hệ";

    // Gọi đúng các Component viết hoa chữ cái đầu
    include '../components/Header.php';
    include '../components/PageHeader.php';
    ?>

    <div class="container-fluid contact-wrapper py-5">
        <div class="container py-5">
            <div class="p-5 contact-box rounded shadow-lg wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-5">
                    
                    <div class="col-12 text-center mb-2">
                        <h1 class="fw-bold text-uppercase text-orange">Để Lại Lời Nhắn</h1>
                        <p class="text-white-50">Bạn có thắc mắc về sản phẩm hay cần hỗ trợ bảo hành? Hãy điền vào form bên dưới, đội ngũ hỗ trợ của chúng tôi sẽ giải đáp ngay!</p>
                    </div>

                    <div class="col-lg-7">
                        <form id="contactForm">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <input type="text" id="name" class="form-control p-3 rounded" placeholder="Tên của bạn" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" id="email" class="form-control p-3 rounded" placeholder="Email liên hệ" required>
                                </div>
                                <div class="col-12">
                                    <input type="text" id="subject" class="form-control p-3 rounded" placeholder="Tiêu đề (VD: Hỗ trợ kỹ thuật, Hủy đơn...)" required>
                                </div>
                                <div class="col-12">
                                    <textarea id="message" class="form-control p-3 rounded" rows="6" placeholder="Nội dung chi tiết..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-orange w-100 p-3 rounded-pill fw-bold text-uppercase shadow-sm" type="submit">
                                        <i class="fa fa-paper-plane me-2"></i> Gửi Tin Nhắn
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5">
                        <div class="d-flex flex-column h-100">
                            
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-box d-flex align-items-center justify-content-center rounded-circle shadow-sm">
                                        <i class="fa fa-map-marker-alt fa-2x text-orange" style="transition: 0.3s;"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="fw-bold mb-1 text-white">Địa chỉ</h5>
                                        <p class="mb-0 text-white-50">624 Đường Âu Cơ, P. Bảy Hiền, TP. HCM</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-box d-flex align-items-center justify-content-center rounded-circle shadow-sm">
                                        <i class="fa fa-envelope fa-2x text-orange" style="transition: 0.3s;"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="fw-bold mb-1 text-white">Email Hỗ Trợ</h5>
                                        <p class="mb-0 text-white-50">support@lkstore.vn</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-box d-flex align-items-center justify-content-center rounded-circle shadow-sm">
                                        <i class="fa fa-phone-alt fa-2x text-orange" style="transition: 0.3s;"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="fw-bold mb-1 text-white">Hotline</h5>
                                        <p class="mb-0 text-white-50">(+84) 123 456 789</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded overflow-hidden flex-fill shadow-sm border border-secondary" style="min-height: 280px;">
    <iframe 
        class="w-100 h-100" 
        style="border: 0; display: block;" 
        src="https://maps.google.com/maps?q=624+%C3%82u+C%C6%A1,+Ph%C6%B0%E1%BB%9Dng+10,+T%C3%A2n+B%C3%ACnh,+H%E1%BB%93+Ch%C3%AD+Minh&z=16&output=embed" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>
</div>
      
</div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include '../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        // Kích hoạt hiệu ứng cuộn WOW.js
        new WOW().init();

        // Xử lý gửi Form liên hệ bằng Javascript thuần
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn trang bị load lại
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            
            // Hiện thông báo thành công
            alert(`Cảm ơn ${name}!\nTin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại qua email ${email} trong thời gian sớm nhất.`);
            
            // Reset toàn bộ form về trống
            this.reset();
        });
    </script>
</body>
</html>
<?php
// Kiểm tra nếu biến chưa được khai báo ở trang cha thì gán giá trị mặc định để tránh lỗi
$pageTitle = isset($pageTitle) ? $pageTitle : 'Tiêu Đề Trang';
$pageBreadcrumb = isset($pageBreadcrumb) ? $pageBreadcrumb : 'Tên Trang';
?>

<div class="container-fluid page-header py-5 mb-5" style="
    /* Làm tối ảnh nền bằng gradient đen rgba(17,17,17) để chữ trắng và cam nổi bật hơn */
    background: linear-gradient(rgba(17, 17, 17, 0.8), rgba(17, 17, 17, 0.8)), url('/LTWNC_BAN_HANG/assets/images/img/carousel-1.png');
    background-size: cover;
    background-position: center;
    border-bottom: 2px solid #333;
">
    <div class="container text-center py-5">
        <h1 class="text-white display-4 fw-bold wow fadeInUp" data-wow-delay="0.1s">
            <?= htmlspecialchars($pageTitle) ?>
        </h1>
        
        <nav aria-label="breadcrumb" class="wow fadeInUp" data-wow-delay="0.3s">
            <ol class="breadcrumb justify-content-center mb-0 mt-3 fs-5">
                <li class="breadcrumb-item">
                    <a href="/LTWNC_BAN_HANG/index.php" class="text-decoration-none fw-bold" style="color: #F28B00;">
                        Trang Chủ
                    </a>
                </li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">
                    <?= htmlspecialchars($pageBreadcrumb) ?>
                </li>
            </ol>
        </nav>
    </div>
</div>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
$Products=$Products??[];
$danhMucList = $danhMucList ?? [];

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa Hàng - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .shop-wrapper { background-color: #1a1a1a; min-height: 80vh; }
        
        .sidebar-box { background-color: #222; border: 1px solid #333; padding: 25px; border-radius: 12px; }
        
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .form-control::placeholder { color: #555 !important; }
        .input-group-text { background-color: #333; border: 1px solid #444; color: #F28B00; }

        .btn-category { 
            display: block; width: 100%; text-align: left; padding: 10px 15px; 
            background: transparent; border: none; color: #ccc; 
            border-radius: 6px; transition: all 0.3s ease; text-decoration: none;
        }
        .btn-category:hover { background-color: #2a2a2a; color: #F28B00; padding-left: 20px; }
        .btn-category.active { background-color: #F28B00; color: #fff !important; font-weight: bold; }

        .text-orange { color: #F28B00 !important; }
            /* Tăng chiều cao và khoảng cách nội dung */
.product-card-anim {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid #333 !important;
    min-height: 400px; /* Tăng chiều cao tối thiểu cho card */
    display: flex;
    flex-direction: column;
}

/* Ảnh sản phẩm: dùng object-fit để ảnh luôn lấp đầy nhưng không bị méo */
.product-img-wrapper {
    height: 250px !important; /* Tăng chiều cao khu vực chứa ảnh */
    background-color: #111;
}

/* Tên sản phẩm: Không cắt bằng ... nữa mà cho xuống dòng */
.product-title {
    font-size: 1.1rem;
    height: 3em; /* Chiều cao cố định cho 2 dòng */
    line-height: 1.5em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Hiển thị tối đa 2 dòng */
    -webkit-box-orient: vertical;
    margin-bottom: 10px !important;
}
        /* Hiệu ứng Product Card */
        .product-card-anim:hover {
             transform: translateY(-5px); 
             border-color: #F28B00 !important; 
             min-height: 400px;
             box-shadow: 0 10px 20px rgba(242, 139, 0, 0.2); }
        .btn-orange { background-color: #F28B00; color: #fff; }
        .btn-orange:hover { background-color: #d67a00; color: #fff; }
        /* Container Sidebar */
        .sidebar-box {
            background-color: #1a1a1a; /* Đồng bộ màu nền */
            border: 1px solid #333;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        /* Tiêu đề mục */
        .sidebar-box h5 {
            color: #fff;
            margin-bottom: 20px !important;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }

        /* Các nút danh mục */
        .btn-category {
            display: block;
            width: 100%;
            text-align: left;
            padding: 12px 15px;
            background: transparent;
            border: 1px solid transparent;
            color: #bbb;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-category:hover {
            background-color: #222;
            color: #F28B00;
            border-color: #333;
            padding-left: 20px; /* Hiệu ứng đẩy nhẹ khi hover */
        }

        .btn-category.active {
            background-color: #F28B00;
            color: #fff !important;
            font-weight: 600;
        }

        /* Nhóm danh mục cha */
        .category-parent {
            padding: 10px 15px;
            transition: color 0.3s;
            font-size: 0.95rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-parent:hover {
            color: #fff !important;
        }

        .category-child-list {
            background: #111;
            border-radius: 8px;
            margin: 5px 0;
            padding: 5px 0;
        }
    </style>
</head>
<body>

    <?php
    $pageTitle = "Cửa Hàng Của Chúng Tôi";
    $pageBreadcrumb = "Cửa Hàng";
    include __DIR__ . '/../components/Header.php';
    include __DIR__ . '/../components/PageHeader.php';
    ?>

    <div class="mb-5">
        <?php include __DIR__ . '/../components/ServiceFeatures.php'; ?>
    </div>

    <div class="container-fluid shop-wrapper py-5">
        <div class="container py-4">
            <div class="row g-4">
                
                <div class="col-lg-3 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="sidebar-box">
                        <h5 class="fw-bold text-white text-uppercase mb-3" style="font-size: 0.95rem; letter-spacing: 1px;">Tìm Kiếm</h5>
                        <div class="input-group mb-4">
                            <input type="text" id="searchInput" class="form-control p-3" placeholder="Nhập tên sản phẩm..." oninput="handleSearch(this)">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>

                        <h5 class="fw-bold text-white text-uppercase mb-3 mt-2" style="font-size: 0.95rem; letter-spacing: 1px;">Danh Mục</h5>
                        <div class="d-flex flex-column gap-1" id="categoryGrid">
                            <a href="javascript:void(0)" onclick="changeCategory('all')" class="btn-category <?= !isset($_GET['cat_id']) ? 'active' : '' ?>">
                                <i class="fas fa-chevron-right me-2 small text-orange"></i> Tất Cả Sản Phẩm
                            </a>

                            <?php foreach ($danhMucList as $cat): 
                                if (empty($cat['id_danh_muc_cha'])): ?>
                                <div class="category-group">
                                    <div class="fw-bold text-orange mt-3 mb-1 category-parent" style="cursor: pointer;">
                                        <?= $cat['ten_danh_muc'] ?> <i class="fas fa-chevron-down small"></i>
                                    </div>
                                    <div class="category-child-list" style="display: none;">
                                        <?php foreach ($danhMucList as $sub): 
                                            if ($sub['id_danh_muc_cha'] == $cat['id_danh_muc']): ?>
                                            <a href="javascript:void(0)" onclick="changeCategory('<?= addslashes($sub['ten_danh_muc']) ?>')" 
                                            class="btn-category ps-4">
                                                <?= $sub['ten_danh_muc'] ?>
                                            </a>
                                        <?php endif; endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="row g-4 justify-content-start" id="productsContainer">
                        <?php foreach ($Products as $product): ?>
                            <div class="col-md-6 col-lg-6 col-xl-4 mb-2">
                                <div class="card h-100 product-card-anim" style="background-color: #222; border: 1px solid #333; border-radius: 12px; overflow: hidden;">
                                    <div class="position-relative overflow-hidden" style="height: 220px;">
                                        <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/products/<?= $product['hinh_anh'] ?? 'default.png' ?>" 
                                            class="img-fluid w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    
                                    <div class="card-body text-center d-flex flex-column p-4">
                                        <p class="text-white-50 small mb-1"><?= $product['ten_danh_muc'] ?></p>
                                        <a href="/LTWNC_LTWNC_WEBTMDT/views/pages/ProductDetail.php?id=<?= $product['id_san_pham'] ?>" 
                                        class="card-title h6 fw-bold mb-2 text-white text-decoration-none">
                                            <?= $product['ten_san_pham'] ?>
                                        </a>
                                        <span style="color: #F28B00;" class="fs-5 fw-bold"><?= number_format($product['gia_co_ban'], 0, ',', '.') ?> đ</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div> 
        </div> 
    </div> 

    <?php include __DIR__ . '/../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        new WOW().init();
                            
        // 1. NHẬN DỮ LIỆU TỪ PHP
        const rawDbProducts = <?= json_encode($Products) ?>;
        console.log("Dữ liệu gốc:", rawDbProducts);
        const products = rawDbProducts.map(p => ({
            id: p.id_san_pham,
            name: p.ten_san_pham,
            category: p.ten_danh_muc || 'Khác',
            price: Number(p.gia_co_ban) || 0,
            oldPrice: 0, 
            img: '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' + (p.hinh_anh || 'default.png'),
            so_luong_kho: Number(p.so_luong_kho) || 0, 
            ngay_tao: p.ngay_tao,
            is_sale: false // Nếu DB chưa có cột này thì luôn là false
        }));

        console.log("Mảng đã map:", products);

        // 3. GỌI HÀM NÀY SAU KHI CÓ DỮ LIỆU
        document.addEventListener('DOMContentLoaded', function() {
            renderProducts(); 
        });
        let searchQuery = '';
        let selectedCategory = 'all';

       document.querySelectorAll('.category-parent').forEach(parent => {
            parent.addEventListener('click', function() {
                const childList = this.nextElementSibling;
                const icon = this.querySelector('i');

                if (childList.style.display === "none" || childList.style.display === "") {
                    childList.style.display = "block";
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                } else {
                    childList.style.display = "none";
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                }
            });
        });

        function renderProducts() {
            const container = document.getElementById('productsContainer');
            if (!container) return;

            const filtered = products.filter(p => {
                // Lấy tên danh mục từ object sản phẩm
                const dbCategory = (p.category || '').trim().toLowerCase();
                // Lấy tên danh mục người dùng vừa chọn
                const btnCategory = selectedCategory.trim().toLowerCase();
                
                // Kiểm tra logic lọc
                const matchCategory = (selectedCategory === 'all' || btnCategory === dbCategory);
                const matchSearch = (p.name || '').toLowerCase().includes(searchQuery.toLowerCase());
                
                return matchCategory && matchSearch;
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <h4 class="text-white-50">Không tìm thấy sản phẩm nào</h4>
                    </div>`;
                return;
            }

            let html = '';
            const now = new Date();

            filtered.forEach(product => {
                // 1. Tính toán trạng thái
                const kho = Number(product.so_luong_kho) || 0;
                const isOutOfStock = kho <= 0;
                const ngayTao = new Date(product.ngay_tao);
                const isNew = (now - ngayTao) < (7 * 24 * 60 * 60 * 1000); // Mới trong vòng 7 ngày
                const isSale = !!product.is_sale;

                // 2. Tạo chuỗi Tags
                let tagsHtml = '';
                if (isOutOfStock) {
                    tagsHtml = `<span class="badge position-absolute top-0 start-0 m-2" style="background: #333; z-index: 2;">Hết hàng</span>`;
                } else {
                    if (isSale) tagsHtml += `<span class="badge position-absolute top-0 start-0 m-2" style="background: #dc3545; z-index: 2;">Khuyến mãi</span>`;
                    if (isNew) tagsHtml += `<span class="badge position-absolute top-0 end-0 m-2" style="background: #F28B00; z-index: 2;">Mới</span>`;
                }

                // 3. Xử lý giá
                const formattedPrice = Number(product.price).toLocaleString('vi-VN') + ' đ';
                const formattedOldPrice = (product.oldPrice && product.oldPrice > product.price) 
                    ? `<del class="me-2 text-white-50 small">${Number(product.oldPrice).toLocaleString('vi-VN')} đ</del>` 
                    : '';

                // 4. Mã hóa JSON an toàn để tránh lỗi cú pháp
                const productData = { ...product };
                const productJson = JSON.stringify(productData).replace(/"/g, '&quot;');

                html += `
                    <div class="col-md-6 col-lg-6 col-xl-4 mb-4">
                        <div class="card h-100 product-card-anim" style="background-color: #222; border: 1px solid #333; border-radius: 12px; overflow: hidden; ${isOutOfStock ? 'opacity: 0.7;' : ''}">
                            <div class="position-relative overflow-hidden" style="height: 250px; background-color: #111;">
                                ${tagsHtml}
                                <img src="${product.img}" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="${product.name}">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s;" onmouseenter="this.style.opacity=1" onmouseleave="this.style.opacity=0">
                                    <a href="index.php?act=ProductDetail&id=${product.id}" class="btn rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #1a1a1a; color: #F28B00; border: 1px solid #F28B00;">
                                        <i class="fa fa-eye fs-5"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body text-center p-4">
                                <p class="text-white-50 small mb-1">${product.category}</p>
                                <a href="/LTWNC_LTWNC_WEBTMDT/views/pages/ProductDetail.php?id=${product.id}" class="product-title-custom fw-bold text-white text-decoration-none d-block">
                                    ${product.name}
                                </a>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    ${formattedOldPrice}
                                    <span style="color: #F28B00;" class="fs-5 fw-bold">${formattedPrice}</span>
                                </div>
                                <button ${isOutOfStock ? 'disabled' : ''} onclick='handleAddToCartFromShop(${productJson})' class="btn ${isOutOfStock ? 'btn-secondary' : 'btn-orange'} w-100 rounded-pill py-2 fw-bold shadow-sm">
                                    ${isOutOfStock ? 'Liên Hệ Đặt Hàng' : '<i class="fas fa-cart-plus me-2"></i> Thêm Giỏ Hàng'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function changeCategory(catName) {
            selectedCategory = catName; // Cập nhật danh mục đang chọn
            
            // 1. Cập nhật class 'active' cho nút được bấm
            document.querySelectorAll('.btn-category').forEach(btn => {
                btn.classList.remove('active');
                // Kiểm tra nếu innerText chứa tên danh mục thì thêm class active
                if (btn.innerText.trim() === catName.trim()) {
                    btn.classList.add('active');
                }
            });

            // 2. Render lại sản phẩm theo danh mục mới
            renderProducts();
        }

        function handleSearch(inputEl) {
            searchQuery = inputEl.value;
            renderProducts();
        }

        function handleAddToCartFromShop(product) {
            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            let existingItemIndex = currentCart.findIndex(item => item.id === product.id);

            if (existingItemIndex !== -1) {
                currentCart[existingItemIndex].quantity += 1;
            } else {
                product.quantity = 1;
                product.selected = true;
                currentCart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(currentCart));
            if (typeof updateCartBadge === 'function') updateCartBadge();
            alert(`Thành công! Đã thêm ${product.name} vào giỏ hàng.`);
        }

    </script>
</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 
$products = $products ?? [];
$categories = $categories ?? [];
$brands = $brands ?? [];
$totalProducts = $totalProducts ?? 0;
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$offset = $offset ?? 0;
$limit = $limit ?? 10;
$IMAGE_BASE_URL = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/';
$IMAGE_BIEN_THE_BASE_URL = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products';

?>

<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h2 class="text-white fw-bold mb-1">
                <i class="fas fa-box-open text-warning me-2"></i> Quản Lý Sản Phẩm
            </h2>
            <p class="text-secondary mb-0 small">
                Quản lý kho hàng và thông tin sản phẩm chi tiết trong hệ thống
            </p>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">Sản phẩm</li>
            </ol>
        </nav>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success fw-bold border-0 shadow-sm" style="background-color: #064e3b; color: #34d399;">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger fw-bold border-0 shadow-sm" style="background-color: #7f1d1d; color: #f87171;">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">

        <div class="row g-3 mb-4">
            <?php 
            $dang_ban = count(array_filter($products, fn($p) => $p['trang_thai'] == 1));
            $ngung_ban = count(array_filter($products, fn($p) => $p['trang_thai'] == 0));
            ?>
            <div class="col-md-3">
                <div class="card bg-dark border-0 p-3 shadow-sm text-center">
                    <p class="text-secondary small mb-1">TỔNG SẢN PHẨM</p>
                    <h3 class="text-white fw-bold mb-0"><?= $totalProducts ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark border-0 p-3 shadow-sm text-center">
                    <p class="text-secondary small mb-1">ĐANG BÁN</p>
                    <h3 class="text-success fw-bold mb-0"><?= $dang_ban ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark border-0 p-3 shadow-sm text-center">
                    <p class="text-secondary small mb-1">NGỪNG BÁN</p>
                    <h3 class="text-danger fw-bold mb-0"><?= $ngung_ban ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark border-0 p-3 shadow-sm text-center">
                    <p class="text-secondary small mb-1">DANH MỤC</p>
                    <h3 class="text-warning fw-bold mb-0"><?= count($categories) ?></h3>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center mb-4 gap-3">
            <div class="input-group" style="max-width: 350px;">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" onkeyup="filterProducts()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm kiếm...">
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <select id="categoryFilter" onchange="filterProducts()" class="form-select bg-dark border-secondary text-white" style="width: auto;">
                    <option value="">Tất cả DM ▼</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['ten_danh_muc']) ?>"><?= htmlspecialchars($cat['ten_danh_muc']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="statusFilter" onchange="filterProducts()" class="form-select bg-dark border-secondary text-white" style="width: auto;">
                    <option value="">Tất cả TT ▼</option>
                    <option value="1">Đang bán</option>
                    <option value="0">Ngừng bán</option>
                </select>

                <select id="sortOrder" onchange="filterProducts()" class="form-select bg-dark border-secondary text-white" style="width: auto;">
                    <option value="newest">Mới nhất ▼</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                </select>
            </div>

            <div class="ms-auto">
                <button onclick="openAddModal()" class="btn fw-bold text-white shadow-sm" style="background-color: #F28B00;">
                    <i class="fas fa-plus me-2"></i> Thêm Sản Phẩm
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th class="text-center"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <th>Sản Phẩm</th>
                        <th class="text-center">Danh Mục</th>
                        <th class="text-end">Giá Bán</th>
                        <th class="text-center">Tồn Kho</th>
                        <th class="text-center">Trạng Thái</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $stock = $product['so_luong_kho'] ?? 0;
                        if ($stock == 0) { $stockBadge = '<span class="badge bg-danger">Hết hàng</span>'; }
                        elseif ($stock < 10) { $stockBadge = '<span class="badge bg-warning text-dark">Sắp hết (' . $stock . ')</span>'; }
                        else { $stockBadge = '<span class="badge bg-success">Còn hàng (' . $stock . ')</span>'; }
                    ?>
                    <tr class="product-row">
                        <td class="text-center"><input type="checkbox" class="form-check-input product-checkbox" value="<?= $product['id_san_pham'] ?>"></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= $IMAGE_BASE_URL . $product['hinh_anh'] ?>" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-white product-name"><?= htmlspecialchars($product['ten_san_pham']) ?></div>
                                    <small class="text-muted">SKU: #<?= $product['id_san_pham'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center product-category"><?= htmlspecialchars($product['ten_danh_muc']) ?></td>
                        <td class="text-end fw-bold text-primary">
                            <?= number_format($product['gia_co_ban'] ?? 0, 0, ',', '.') ?>đ
                        </td>
                        <td class="text-center"><?= $stockBadge ?></td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?= ($product['trang_thai']??1)==1 ? 'bg-info' : 'bg-secondary' ?>">
                                <?= ($product['trang_thai']??1)==1 ? 'Đang bán' : 'Ngừng bán' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" 
                                    class="btn btn-sm btn-info" 
                                    onclick="loadVariants(<?= $product['id_san_pham'] ?>, '<?= htmlspecialchars($product['ten_san_pham']) ?>')">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button type="button" 
                                    class="btn btn-sm btn-warning me-1" 
                                    onclick="openEditModal(<?= htmlspecialchars(json_encode($product)) ?>)" 
                                    title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="index.php?act=xoaSP" method="POST" style="display:inline;">
                                <input type="hidden" name="id_san_pham" value="<?= $product['id_san_pham'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
            <span class="text-muted small">
                Hiển thị <?= $totalProducts > 0 ? $offset + 1 : 0 ?> - <?= min($offset + $limit, $totalProducts) ?> trong tổng số <?= $totalProducts ?> sản phẩm
            </span>
            <nav>
                <ul class="pagination pagination-sm mb-0 d-flex flex-row gap-2">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>" class="page-link bg-dark border-secondary <?= $page <= 1 ? 'text-muted' : 'text-white' ?> rounded">
                            <i class="fas fa-chevron-left"></i> Trước
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a href="?page=<?= $i ?>" class="page-link border-0 rounded px-3" style="background-color: <?= $page == $i ? '#F28B00' : '#212529' ?>; color: #fff;">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a href="?page=<?= $page + 1 ?>" class="page-link bg-dark border-secondary <?= $page >= $totalPages ? 'text-muted' : 'text-white' ?> rounded">
                            Sau <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>

    <div id="productModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div class="card border-secondary shadow-lg" style="width: 100%; max-width: 800px; background-color: #1a1a1a; border-radius: 15px; overflow: hidden; max-height: 90vh;">
            
            <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #222; border-bottom: 2px solid #F28B00;">
                <h5 class="text-white fw-bold mb-0" id="modalTitle"><i class="fas fa-box me-2 text-warning"></i>QUẢN LÝ SẢN PHẨM</h5>
                <button onclick="closeModal()" class="btn-close btn-close-white"></button>
            </div>

            <ul class="nav nav-tabs border-0 px-3 pt-3" id="productTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active text-white" data-bs-toggle="tab" data-bs-target="#infoTab">Thông tin chung</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" data-bs-toggle="tab" data-bs-target="#variantsTab">Biến thể (Size/Màu)</button>
                </li>
            </ul>

            <form id="SanPhamForm" action="index.php" method="POST" enctype="multipart/form-data" class="p-4" novalidate style="overflow-y: auto;">
                <input type="hidden" name="act" id="formAction" value="themSP">
                <input type="hidden" name="id_san_pham" id="sanphamId">
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="infoTab">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold small">TÊN SẢN PHẨM</label>
                                <input type="text" name="ten_san_pham" id="prodName" class="form-control bg-dark border-secondary text-white" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold small">DANH MỤC</label>
                                <select name="id_danh_muc" id="prodCat" class="form-select bg-dark border-secondary text-white" required>
                                    <option value="">-- Chọn Danh Mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id_danh_muc'] ?>"><?= htmlspecialchars($cat['ten_danh_muc']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold small">THƯƠNG HIỆU</label>
                                <select name="id_thuong_hieu" id="prodBrand" class="form-select bg-dark border-secondary text-white">
                                    <option value="">-- Chọn Thương Hiệu --</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id_thuong_hieu'] ?>"><?= htmlspecialchars($brand['ten_thuong_hieu']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Tab 1: Thay thế trường giảm giá cũ -->
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold small">GIÁ KHỞI ĐIỂM (VND)</label>
                                <input type="number" name="gia_co_ban" id="prodBasePrice" class="form-control bg-dark border-secondary text-white" placeholder="Ví dụ: 250000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold small">TRẠNG THÁI</label>
                                <select name="trang_thai" id="prodStatus" class="form-select bg-dark border-secondary text-white">
                                    <option value="1">Đang Bán</option>
                                    <option value="0">Ngừng Bán</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold small">MÔ TẢ CHI TIẾT</label>
                                <textarea name="mo_ta" id="prodDesc" class="form-control bg-dark border-secondary text-white" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted fw-bold small">ẢNH ĐẠI DIỆN</label>
                                <div class="mb-2">
                                    <img id="prodCurrentImage" src="" alt="Ảnh sản phẩm" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover; display: none;">
                                </div>
                                <input type="file" name="hinh_anh_file" class="form-control bg-dark border-secondary text-white">
                                <input type="hidden" name="hinh_anh_cu" id="prodOldImage">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="variantsTab">
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-1"></i> Lưu ý: Bạn cần lưu thông tin sản phẩm trước khi thêm hoặc chỉnh sửa danh sách biến thể.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>SIZE</th>
                                        <th>MÀU</th>
                                        <th>GIÁ BÁN</th>
                                        <th>TỒN KHO</th>
                                        <th>ẢNH</th>
                                        <th></th> </tr>
                                </thead>
                                <tbody id="variantsContainer">
                                    </tbody>
                            </table>
                        </div>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning" 
                                        onclick="addVariantRow(null, document.getElementById('prodBasePrice').value)">
                                    + Thêm biến thể mới
                                </button>                   
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                    <button type="button" onclick="closeModal()" class="btn btn-outline-secondary px-4">HỦY</button>
                    <button type="submit" class="btn px-4 text-white" style="background-color: #F28B00;">LƯU SẢN PHẨM</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalVariants" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Biến thể sản phẩm</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark table-bordered">
                        <thead>
                            <tr><th>Kích cỡ</th><th>Màu sắc</th><th>Giá bán</th><th>Tồn kho</th><th>Hình ảnh</th></tr>
                        </thead>
                        <tbody id="variantsTableBody">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>                           
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/LTWNC_LTWNC_WEBTMDT/views/js/QuanLySanPham.js"></script>


<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>
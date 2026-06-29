<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
$categories  = $categories ?? [];
$total       = $total ?? 0;
$active      = $active ?? 0;
$hidden      = $hidden ?? 0;
$total_parent = $total_parent ?? 0;
$danh_muc_goc = $danh_muc_goc ?? []; 
$danh_muc_con = $danh_muc_con ?? [];

// Helper render dòng (Giữ nguyên logic của bạn, dọn dẹp lại HTML/CSS)
function renderRow($item) {
    ob_start(); ?>
    <tr class="cat-row align-middle" style="border-bottom: 1px solid #333; transition: background 0.2s;">
        <td class="py-3 px-3 fw-bold text-muted">#<?= $item['id_danh_muc'] ?></td>
        <td class="cat-name fw-bold text-white py-3 px-3"><?= htmlspecialchars($item['ten_danh_muc']) ?></td>
        <td class="py-3 px-3" style="max-width: 250px;">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="text-truncate text-muted" style="max-width: 180px;">
                    <?= !empty($item['mo_ta']) 
                        ? htmlspecialchars($item['mo_ta']) 
                        : '<em style="color:#71717a;">Chưa có mô tả</em>' ?>
                </span>
                <?php if (!empty($item['mo_ta'])): ?>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-circle p-1 d-flex align-items-center justify-content-center btn-action" 
                            style="width: 25px; height: 25px; flex-shrink: 0;"
                            onclick='viewCategoryDesc(<?= json_encode(htmlspecialchars($item["ten_danh_muc"], ENT_QUOTES)) ?>, <?= json_encode(htmlspecialchars($item["mo_ta"], ENT_QUOTES)) ?>)'>
                        <i class="fas fa-search-plus" style="font-size: 11px;"></i>
                    </button>
                <?php endif; ?>
            </div>
        </td>
        <td class="py-3 px-3 text-center">
            <?php if ($item['trang_thai'] == 1): ?>
                <span class="badge bg-success rounded-pill px-3 py-2">Hiển thị</span>
            <?php else: ?>
                <span class="badge bg-danger rounded-pill px-3 py-2">Đã ẩn</span>
            <?php endif; ?>
        </td>
        <td class="py-3 px-3 text-muted small text-center">
            <?= isset($item['ngay_tao']) ? date('d/m/Y', strtotime($item['ngay_tao'])) : '---' ?>
        </td>
        <td class="py-3 px-3 text-center">
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-warning rounded-circle btn-action" 
                        style="width: 35px; height: 35px;" 
                        data-item='<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>' 
                        onclick="openEditModal(this)">
                    <i class="fas fa-edit"></i>
                </button>
                <form action="index.php?act=xoaDM" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                    <input type="hidden" name="id_danh_muc" value="<?= $item['id_danh_muc'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger rounded-circle btn-action" style="width: 35px; height: 35px;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    <?php return ob_get_clean();
}
?>

<style>
    /* Làm gọn thanh cuộn cho bảng */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
    
    /* Hiệu ứng thẻ Card */
    .stat-card { transition: all 0.3s ease; border-left: 4px solid transparent; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3) !important; border-left-color: #F28B00; background-color: #222 !important; }
    
    /* Hover Row Bảng */
    .cat-row:hover { background-color: #222 !important; }
    .btn-action:hover { transform: scale(1.1); }

    /* Tùy chỉnh Tabs Bootstrap cho giao diện tối */
    .custom-tabs .nav-link { color: #a1a1aa; font-weight: bold; border: none; border-bottom: 2px solid transparent; padding: 10px 20px; transition: 0.3s; }
    .custom-tabs .nav-link:hover { color: #F28B00; border-bottom-color: rgba(242, 139, 0, 0.5); }
    .custom-tabs .nav-link.active { color: #F28B00; background: transparent; border-bottom: 2px solid #F28B00; }
</style>

<div class="container-fluid px-0">
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show fw-bold border-0 shadow-sm" style="background-color: #064e3b; color: #34d399;">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show fw-bold border-0 shadow-sm" style="background-color: #7f1d1d; color: #f87171;">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="text-white fw-bold mb-1"><i class="fas fa-layer-group text-warning me-2"></i>Quản Lý Danh Mục</h4>
            <p class="text-muted small m-0">Hệ thống quản lý của Trạm Hiệu</p>
        </div>

        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
            <button onclick="openAddModal(true)" class="btn btn-warning fw-bold d-flex align-items-center flex-grow-1 flex-md-grow-0 justify-content-center">
                <i class="fas fa-plus me-2"></i> DM Cha
            </button>
            <button onclick="openAddModal(false)" class="btn btn-outline-warning fw-bold d-flex align-items-center flex-grow-1 flex-md-grow-0 justify-content-center">
                <i class="fas fa-plus me-2"></i> DM Con
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 rounded p-3 h-100 shadow-sm" style="background-color: #1a1a1a;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted fw-bold mb-1 fs-6">Tổng danh mục</p>
                        <h3 class="text-white fw-bold mb-0"><?= $total ?></h3>
                    </div>
                    <div class="text-info fs-1"><i class="fas fa-folder-open"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 rounded p-3 h-100 shadow-sm" style="background-color: #1a1a1a;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted fw-bold mb-1 fs-6">Đang hiển thị</p>
                        <h3 class="text-white fw-bold mb-0"><?= $active ?></h3>
                    </div>
                    <div class="text-success fs-1"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 rounded p-3 h-100 shadow-sm" style="background-color: #1a1a1a;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted fw-bold mb-1 fs-6">Đang ẩn</p>
                        <h3 class="text-white fw-bold mb-0"><?= $hidden ?></h3>
                    </div>
                    <div class="text-danger fs-1"><i class="fas fa-eye-slash"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card border-0 rounded p-3 h-100 shadow-sm" style="background-color: #1a1a1a;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted fw-bold mb-1 fs-6">Danh mục gốc</p>
                        <h3 class="text-white fw-bold mb-0"><?= $total_parent ?></h3>
                    </div>
                    <div class="text-warning fs-1"><i class="fas fa-sitemap"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 rounded p-3 p-md-4 shadow-sm" style="background-color: #1a1a1a;">
        
        <div class="input-group mb-4" style="max-width: 500px;">
            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" onkeyup="filterCategories()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm kiếm tên danh mục...">
        </div>

        <ul class="nav nav-tabs custom-tabs mb-3 border-secondary" id="catTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-goc">
                    Danh Mục Gốc (<?= $total_parent ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-con">
                    Danh Mục Con (<?= count($danh_muc_con) ?>)
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-goc">
                <div class="table-responsive border border-secondary rounded">
                    <table class="table table-dark table-hover mb-0" style="min-width: 800px;">
                        <thead style="background-color: #222;">
                            <tr style="border-bottom: 2px solid #F28B00;">
                                <th class="py-3 px-3">ID</th>
                                <th class="py-3 px-3">Danh Mục</th>
                                <th class="py-3 px-3">Mô Tả</th>
                                <th class="py-3 px-3 text-center">Trạng Thái</th>
                                <th class="py-3 px-3 text-center">Ngày Tạo</th>
                                <th class="py-3 px-3 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBodyGoc">
                            <?php if (!empty($danh_muc_goc)): foreach ($danh_muc_goc as $item): ?>
                                <?= renderRow($item) ?>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="py-5 text-center text-muted"><i class="fas fa-folder-open fa-2x mb-2"></i><br>Chưa có danh mục gốc.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-con">
                <div class="table-responsive border border-secondary rounded">
                    <table class="table table-dark table-hover mb-0" style="min-width: 800px;">
                        <thead style="background-color: #222;">
                            <tr style="border-bottom: 2px solid #F28B00;">
                                <th class="py-3 px-3">ID</th>
                                <th class="py-3 px-3">Danh Mục Con</th>
                                <th class="py-3 px-3">Mô Tả</th>
                                <th class="py-3 px-3 text-center">Trạng Thái</th>
                                <th class="py-3 px-3 text-center">Ngày Tạo</th>
                                <th class="py-3 px-3 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBodyCon">
                            <?php if (!empty($danh_muc_con)): foreach ($danh_muc_con as $item): ?>
                                <?= renderRow($item) ?>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="py-5 text-center text-muted"><i class="fas fa-folder-open fa-2x mb-2"></i><br>Chưa có danh mục con.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary shadow-lg">            
            <div class="modal-header border-secondary" style="border-bottom: 2px solid #F28B00; background-color: #222;">
                <h5 id="modalTitle" class="modal-title fw-bold text-warning"><i class="fas fa-edit me-2"></i>Thêm Danh Mục Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4" style="background-color: #1a1a1a;">
                <form id="brandForm" action="index.php" method="POST">
                    <input type="hidden" name="act" id="formAction" value="themDM"> 
                    <input type="hidden" name="id_danh_muc" id="catId">

                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Tên danh mục</label>
                        <input type="text" name="ten_danh_muc" id="catName" class="form-control bg-dark border-secondary text-white" placeholder="Ví dụ: Đồ điện tử..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Mô tả</label>
                        <textarea name="mo_ta" id="catDesc" class="form-control bg-dark border-secondary text-white" rows="3" placeholder="Nhập mô tả ngắn..."></textarea>
                    </div>

                    <div class="mb-3" id="parentCategoryField">
                        <label class="form-label text-muted fw-bold">Chọn danh mục cha</label>
                        <select name="id_danh_muc_cha" class="form-select bg-dark border-secondary text-white">
                            <option value="">-- Không có (Lưu làm gốc) --</option>
                            <?php foreach ($categories as $cat): ?>
                                <?php if (is_null($cat['id_danh_muc_cha'])): ?>
                                    <option value="<?= $cat['id_danh_muc'] ?>">
                                        <?= htmlspecialchars($cat['ten_danh_muc']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold">Trạng thái hoạt động</label>
                        <select name="trang_thai" id="catStatus" class="form-select bg-dark border-secondary text-white">
                            <option value="1">Đang hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Lưu Thông Tin</button>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

<div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary shadow-lg">
            <div class="modal-header border-secondary" style="border-bottom: 2px solid #F28B00; background-color: #222;">
                <h5 class="modal-title fw-bold text-warning">
                    <i class="fas fa-folder-open me-2"></i>Mô Tả: <span id="descTitle" class="text-white"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="descContent" style="font-size: 15px; line-height: 1.7; color: #ddd; white-space: pre-line; background-color: #1a1a1a;">
            </div>
            <div class="modal-footer border-secondary" style="background-color: #222;">
                <button type="button" class="btn btn-outline-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div> 

<script src="/LTWNC_LTWNC_WEBTMDT/views/js/CategoryMaganement.js"></script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>
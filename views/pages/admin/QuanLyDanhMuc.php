<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
$categories = isset($categories) ? $categories : [];
$categories = $categories ?? [];
$total      = $total ?? 0;
$active     = $active ?? 0;
$hidden     = $hidden ?? 0;
?>

<div style="background: #09090b; min-height: 100vh; padding: 24px; color: white; font-family: sans-serif;">
    
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

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">Quản Lý Danh Mục</h2>
            <p style="color: #71717a; font-size: 14px; margin: 0;">Hệ thống quản lý của SÀN HIỆU</p>
        </div>
        <button onclick="openAddModal()" style="background: #f59e0b; border: none; padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer; color: #000;">
            <i class="fas fa-plus" style="font-size: 16px;"></i> Thêm Danh Mục
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div style="background: #18181b; border: 1px solid #27272a; border-radius: 16px; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #a1a1aa; font-size: 12px; margin: 0;">Tổng</p>
                    <h3 style="font-size: 24px; font-weight: 800; margin: 4px 0 0;"><?= $total ?></h3>
                </div>
                <div style="color: #60a5fa; font-size: 24px;"><i class="fas fa-folder-open"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: #18181b; border: 1px solid #27272a; border-radius: 16px; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #a1a1aa; font-size: 12px; margin: 0;">Hiển thị</p>
                    <h3 style="font-size: 24px; font-weight: 800; margin: 4px 0 0;"><?= $active ?></h3>
                </div>
                <div style="color: #4ade80; font-size: 24px;"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: #18181b; border: 1px solid #27272a; border-radius: 16px; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #a1a1aa; font-size: 12px; margin: 0;">Đang Ẩn</p>
                    <h3 style="font-size: 24px; font-weight: 800; margin: 4px 0 0;"><?= $hidden ?></h3>
                </div>
                <div style="color: #f87171; font-size: 24px;"><i class="fas fa-eye-slash"></i></div>
            </div>
        </div>
        
    </div>

    <div style="margin-bottom: 24px; position: relative;">
        <i class="fas fa-search" style="position: absolute; left: 16px; top: 16px; color: #71717a;"></i>
        <input type="text" id="searchInput" onkeyup="filterCategories()" placeholder="Tìm kiếm theo tên hoặc mô tả..." style="width: 100%; padding: 12px 12px 12px 48px; border-radius: 12px; background: #18181b; border: 1px solid #27272a; color: white; outline: none;">
    </div>

    <div style="background: #18181b; border-radius: 16px; border: 1px solid #27272a; overflow: hidden;" class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead style="background: #111827;">
                <tr>
                    <th style="padding: 16px 20px; color: #9ca3af; font-size: 11px; text-transform: uppercase; text-align: left;">ID</th>
                    <th style="padding: 16px 20px; color: #9ca3af; font-size: 11px; text-transform: uppercase; text-align: left;">Danh Mục</th>
                    <th style="padding: 16px 20px; color: #9ca3af; font-size: 11px; text-transform: uppercase; text-align: left;">Mô Tả</th>
                    <th style="padding: 16px 20px; color: #9ca3af; font-size: 11px; text-transform: uppercase; text-align: left;">Trạng Thái</th>
                    <th style="padding: 16px 20px; color: #9ca3af; font-size: 11px; text-transform: uppercase; text-align: left;">Ngày Tạo</th>
                    <th style="padding: 16px 20px; color: #9ca3af; font-size: 11px; text-transform: uppercase; text-align: left;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $item): ?>
                        <tr class="cat-row" style="border-bottom: 1px solid #27272a; cursor: pointer; transition: background 0.2s;" onmouseenter="this.style.background='#202023'" onmouseleave="this.style.background='transparent'">
                            <td style="padding: 14px 20px; vertical-align: middle;">#<?= $item['id_danh_muc'] ?></td>
                            <td class="cat-name" style="padding: 14px 20px; vertical-align: middle; font-weight: 600; white-space: nowrap;"><?= htmlspecialchars($item['ten_danh_muc']) ?></td>
                            <td class="cat-desc" style="padding: 14px 20px; vertical-align: middle; color: #a1a1aa; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?= !empty($item['mo_ta']) ? htmlspecialchars($item['mo_ta']) : '---' ?>
                            </td>
                            <td style="padding: 14px 20px; vertical-align: middle;">
                                <?php if ($item['trang_thai'] == 1): ?>
                                    <span style="padding: 4px 12px; border-radius: 99px; font-size: 12px; background: #064e3b; color: #34d399;">Hiển thị</span>
                                <?php else: ?>
                                    <span style="padding: 4px 12px; border-radius: 99px; font-size: 12px; background: #7f1d1d; color: #f87171;">Đã ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 14px 20px; vertical-align: middle; font-size: 13px; color: #a1a1aa;">
                                <?= isset($item['ngay_tao']) ? date('d/m/Y', strtotime($item['ngay_tao'])) : '---' ?>
                            </td>
                            <td style="padding: 14px 20px; vertical-align: middle;">
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning rounded-circle" 
                                            style="width: 35px; height: 35px;"
                                            data-item='<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>'
                                            onclick="openEditModal(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="index.php?act=xoaDM" method="POST" style="margin: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                        <input type="hidden" name="action">
                                        <input type="hidden" name="id_danh_muc" value="<?= $item['id_danh_muc'] ?>">
                                        <button type="submit" style="width: 36px; height: 36px; border: none; border-radius: 10px; background: #7f1d1d; color: #f87171; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #71717a;">Chưa có danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
<div class="modal-content" style="background-color: #1a1a1a; border: 1px solid #f59e0b; border-radius: 16px; overflow: hidden;">            
            <div class="modal-header border-0">
                <h5 id="modalTitle" style="color: #f59e0b; font-weight: bold; margin: 0; font-size: 20px;">Thêm Danh Mục Mới</h5>
            </div>

            <div class="modal-body" style="padding: 0 24px 24px 24px;">
                <form id="brandForm" action="index.php" method="POST">
                    <input type="hidden" name="act" id="formAction" value="themDM"> 
                    <input type="hidden" name="id_danh_muc" id="catId">

                    <div style="margin-bottom: 16px;">
                        <label style="color: #a1a1aa; font-size: 14px; font-weight: bold; margin-bottom: 8px; display: block;">Tên danh mục</label>
                        <input type="text" name="ten_danh_muc" id="catName" placeholder="Ví dụ: Đồ điện tử, Thời trang..." required style="width: 100%; padding: 12px; background: #27272a; border: 1px solid #444; border-radius: 8px; color: white; outline: none;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="color: #a1a1aa; font-size: 14px; font-weight: bold; margin-bottom: 8px; display: block;">Mô tả</label>
                        <textarea name="mo_ta" id="catDesc" placeholder="Nhập mô tả ngắn..." style="width: 100%; padding: 12px; background: #27272a; border: 1px solid #444; border-radius: 8px; color: white; min-height: 100px; outline: none;"></textarea>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="color: #a1a1aa; font-size: 14px; font-weight: bold; margin-bottom: 8px; display: block;">Trạng thái hoạt động</label>
                        <select name="trang_thai" id="catStatus" style="width: 100%; padding: 12px; background: #27272a; border: 1px solid #444; border-radius: 8px; color: white; outline: none; cursor: pointer;">
                            <option value="1">Đang hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="button" data-bs-dismiss="modal" style="padding: 10px 24px; background: transparent; border: 1px solid #555; border-radius: 8px; color: white; cursor: pointer;">
                            Hủy
                        </button>
                        <button type="submit" style="padding: 10px 24px; background: #f59e0b; border: none; border-radius: 8px; color: #000; cursor: pointer; font-weight: bold;">
                            Lưu Thông Tin
                        </button>
                    </div>
                </form>
            </div> 
            </div>
    </div>
</div>

</div>

<script src="/LTWNC_LTWNC_WEBTMDT/views/js/CategoryMaganement.js"></script>

<?php
// Trả nội dung vào Layout
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>
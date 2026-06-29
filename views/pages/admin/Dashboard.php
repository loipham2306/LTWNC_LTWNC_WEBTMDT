<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

$data = $data ?? [];

$total_revenue   = $data['total_revenue'] ?? 0;
$new_orders      = $data['new_orders'] ?? 0;
$total_orders    = $data['total_orders'] ?? 0;
$shipping_orders = $data['shipping_orders'] ?? 0;
$cancel_orders   = $data['cancel_orders'] ?? 0;

$total_products  = $data['total_products'] ?? 0;
$total_users     = $data['total_users'] ?? 0;
$total_brand     = $data['total_brand']   ?? 0;
$recentOrders    = $data['recent_orders'] ?? [];

$stats = [
    ['title' => 'Tổng Doanh Thu', 'value' => number_format($total_revenue) . ' đ', 'icon' => 'fa-wallet', 'color' => 'text-success'],
    ['title' => 'Đơn Mới', 'value' => $new_orders, 'icon' => 'fa-bell', 'color' => 'text-warning'],
    ['title' => 'Tổng Đơn', 'value' => $total_orders, 'icon' => 'fa-shopping-cart', 'color' => 'text-primary'],
    ['title' => 'Đang Giao', 'value' => $shipping_orders, 'icon' => 'fa-truck', 'color' => 'text-info'],
    ['title' => 'Đã Hủy', 'value' => $cancel_orders, 'icon' => 'fa-times-circle', 'color' => 'text-danger'],
    ['title' => 'Sản Phẩm', 'value' => $total_products, 'icon' => 'fa-box', 'color' => 'text-primary'],
    ['title' => 'Khách Hàng', 'value' => $total_users, 'icon' => 'fa-users', 'color' => 'text-info'],
    ['title' => 'Thương Hiệu', 'value' => $total_brand , 'icon' => 'fa-tag', 'color' => 'text-light'],
];
?>

<style>
    /* Hiệu ứng nổi cho thẻ thống kê */
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.5) !important;
        border-left-color: #F28B00;
        background-color: #222 !important;
    }
    
    /* Làm gọn thanh cuộn cho bảng */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
</style>

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h4 class="text-white fw-bold m-0">Dashboard Tổng Quan</h4>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach ($stats as $stat): ?>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card border-0 rounded p-3 p-md-4 h-100 shadow-sm" style="background-color: #1a1a1a;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted fw-bold mb-1 fs-6"><?= $stat['title'] ?></p>
                            <h4 class="text-white fw-bold mb-0 text-truncate" style="max-width: 150px;"><?= $stat['value'] ?></h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark border border-secondary <?= $stat['color'] ?>" style="width: 50px; height: 50px; font-size: 20px; flex-shrink: 0;">
                            <i class="fas <?= $stat['icon'] ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 rounded p-3 p-md-4" style="background-color: #1a1a1a;">
                <h5 class="text-white fw-bold mb-4">Biểu Đồ Doanh Thu Tổng</h5>
                <div style="position: relative; width: 100%; height: 350px;">
                    <canvas id="revenueLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 rounded p-3 p-md-4 mb-4" style="background-color: #1a1a1a;">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="text-white fw-bold mb-0">Đơn Hàng Vừa Đặt</h5>
            <button class="btn btn-sm btn-outline-warning fw-bold px-3">Xem Tất Cả</button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle text-center mb-0" style="min-width: 800px;">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th scope="col" class="py-3">Mã Đơn</th>
                        <th scope="col" class="py-3 text-start">Khách Hàng</th>
                        <th scope="col" class="py-3">Ngày Đặt</th>
                        <th scope="col" class="py-3 text-end">Tổng Tiền</th>
                        <th scope="col" class="py-3">Trạng Thái</th>
                        <th scope="col" class="py-3">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentOrders)): ?>
                        <?php foreach ($recentOrders as $order): 
                            $badgeClass = '';
                            if ($order['status'] === 'Chờ Duyệt') {
                                $badgeClass = 'bg-info text-dark';
                            } elseif ($order['status'] === 'Đang Giao') {
                                $badgeClass = 'bg-warning text-dark';
                            } elseif ($order['status'] === 'Đã Hủy') {
                                $badgeClass = 'bg-danger';
                            } else {
                                $badgeClass = 'bg-success';
                            }
                        ?>
                            <tr style="border-bottom: 1px solid #333;">
                                <td class="fw-bold text-white py-3">#<?= htmlspecialchars($order['id']) ?></td>
                                <td class="text-muted py-3 text-start"><?= htmlspecialchars($order['customer']) ?></td>
                                <td class="text-muted py-3"><?= htmlspecialchars($order['date']) ?></td>
                                <td class="text-orange fw-bold py-3 text-end"><?= htmlspecialchars($order['total']) ?></td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-3 py-2 <?= $badgeClass ?>">
                                        <?= htmlspecialchars($order['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <button class="btn btn-sm btn-secondary rounded-pill px-3 hover-orange"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-muted py-4">Chưa có đơn hàng nào gần đây.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div> <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const totalRevenue = <?= (float)$total_revenue ?>; 
    const target = 20000000; // Mục tiêu doanh thu

    const ctx = document.getElementById('revenueLineChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Tháng trước', 'Hiện tại'],
                datasets: [
                    {
                        label: 'Doanh thu thực tế',
                        data: [0, totalRevenue], 
                        borderColor: '#F28B00',
                        backgroundColor: 'rgba(242, 139, 0, 0.2)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointBackgroundColor: '#F28B00'
                    },
                    {
                        label: 'Mục tiêu',
                        data: [target, target], 
                        borderColor: '#555',
                        borderDash: [5, 5],
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#333' },
                        ticks: { color: '#aaa' }
                    },
                    x: { 
                        grid: { color: '#333' },
                        ticks: { color: '#aaa' } 
                    }
                },
                plugins: {
                    legend: { labels: { color: '#fff', font: { family: 'Segoe UI' } } }
                }
            }
        });
    }
});
</script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>
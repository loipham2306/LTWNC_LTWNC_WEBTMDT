
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
$brand_stats     = $data['brand_stats'] ?? [];
$total_brand     = $data['total_brand']   ??[];
$revenueData     = $data['revenue_data'] ?? [];
$recentOrders    = $data['recent_orders'] ?? [];
$revenueData = $data['revenue_data'] ?? [];
?>
<?php
    $stats = [
        ['title' => 'Tổng Doanh Thu', 'value' => number_format($total_revenue) . ' đ', 'icon' => 'fa-wallet', 'color' => 'text-success'],

        ['title' => 'Đơn Mới', 'value' => $new_orders, 'icon' => 'fa-bell', 'color' => 'text-warning'],

        ['title' => 'Tổng Đơn', 'value' => $total_orders, 'icon' => 'fa-shopping-cart', 'color' => 'text-primary'],

        ['title' => 'Đang Giao', 'value' => $shipping_orders, 'icon' => 'fa-truck', 'color' => 'text-info'],

        ['title' => 'Đã Hủy', 'value' => $cancel_orders, 'icon' => 'fa-times-circle', 'color' => 'text-danger'],

        ['title' => 'Sản Phẩm', 'value' => $total_products, 'icon' => 'fa-box', 'color' => 'text-primary'],

        ['title' => 'Khách Hàng', 'value' => $total_users, 'icon' => 'fa-users', 'color' => 'text-info'],
        ['title' => 'Số Thương Hiệu', 'value' => $total_brand , 'icon' => 'fa-users', 'color' => 'text-info'],
    ];
?>

<h3 class="text-white fw-bold mb-4">Dashboard Tổng Quan</h3>

<div class="row g-4 mb-4">
    <?php foreach ($stats as $stat): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 rounded p-4 h-100 shadow-sm" style="background-color: #1a1a1a;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted fw-bold mb-1"><?= $stat['title'] ?></p>
                        <h4 class="text-white fw-bold mb-0"><?= $stat['value'] ?></h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark border border-secondary <?= $stat['color'] ?>" style="width: 50px; height: 50px; font-size: 20px;">
                        <i class="fas <?= $stat['icon'] ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php foreach ($stats as $stat): ?>
        <div class="col-md-6 col-xl-3">
            </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 rounded p-4 mb-4" style="background-color: #1a1a1a;">
    <h5 class="text-white fw-bold mb-4">Biểu Đồ Doanh Thu Tổng</h5>
    <div style="width: 100%; height: 350px;">
        <canvas id="revenueLineChart"></canvas>
    </div>
</div>
</div>

<div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="text-white fw-bold mb-0">Đơn Hàng Vừa Đặt</h5>
        <button class="btn btn-sm btn-outline-warning">Xem Tất Cả</button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle text-center mb-0">
            <thead>
                <tr style="border-bottom: 2px solid #F28B00;">
                    <th scope="col" class="py-3">Mã Đơn</th>
                    <th scope="col" class="py-3 text-start">Khách Hàng</th>
                    <th scope="col" class="py-3">Ngày Đặt</th>
                    <th scope="col" class="py-3">Tổng Tiền</th>
                    <th scope="col" class="py-3">Trạng Thái</th>
                    <th scope="col" class="py-3">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): 
                    // Xác định màu Badge dựa trên trạng thái
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
                        <td class="fw-bold text-white py-3"><?= $order['id'] ?></td>
                        <td class="text-muted py-3 text-start"><?= $order['customer'] ?></td>
                        <td class="text-muted py-3"><?= $order['date'] ?></td>
                        <td class="text-primary fw-bold py-3"><?= $order['total'] ?></td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3 py-2 <?= $badgeClass ?>">
                                <?= $order['status'] ?>
                            </span>
                        </td>
                        <td class="py-3">
                            <button class="btn btn-sm btn-primary rounded-pill px-3">Chi tiết</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
 
document.addEventListener("DOMContentLoaded", function () {
    const totalRevenue = <?= (float)$total_revenue ?>; 
    const target = 20000000; // Mục tiêu doanh thu

    const ctx = document.getElementById('revenueLineChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Tháng trước', 'Hiện tại'],
            datasets: [
                {
                    label: 'Doanh thu thực tế',
                    data: [0, totalRevenue], // Tăng dần từ 0 đến hiện tại
                    borderColor: '#F28B00',
                    backgroundColor: 'rgba(242, 139, 0, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6
                },
                {
                    label: 'Mục tiêu',
                    data: [target, target], // Đường thẳng mục tiêu
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
                    ticks: { color: '#fff' }
                },
                x: { ticks: { color: '#fff' } }
            },
            plugins: {
                legend: { labels: { color: '#fff' } }
            }
        }
    });
});
</script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>
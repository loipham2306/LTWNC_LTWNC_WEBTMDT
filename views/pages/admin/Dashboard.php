<?php
session_start();
ob_start(); 

// --- DỮ LIỆU TỔNG QUAN (Giả lập) ---
$stats = [
    ['title' => 'Tổng Doanh Thu', 'value' => '125.400.000 đ', 'icon' => 'fa-wallet', 'color' => 'text-success'],
    ['title' => 'Đơn Hàng Mới', 'value' => '45', 'icon' => 'fa-shopping-cart', 'color' => 'text-warning'],
    ['title' => 'Sản Phẩm', 'value' => '128', 'icon' => 'fa-box', 'color' => 'text-primary'],
    ['title' => 'Khách Hàng', 'value' => '1.204', 'icon' => 'fa-users', 'color' => 'text-info']
];

$revenueData = [
    ['name' => 'Tháng 1', 'revenue' => 45000000],
    ['name' => 'Tháng 2', 'revenue' => 52000000],
    ['name' => 'Tháng 3', 'revenue' => 38000000],
    ['name' => 'Tháng 4', 'revenue' => 65000000],
    ['name' => 'Tháng 5', 'revenue' => 85000000],
    ['name' => 'Tháng 6', 'revenue' => 125400000],
];

$recentOrders = [
    ['id' => '#ORD-001', 'customer' => 'Nguyễn Văn A', 'date' => '25/05/2026', 'total' => '3.350.000 đ', 'status' => 'Chờ Duyệt'],
    ['id' => '#ORD-002', 'customer' => 'Trần Thị B', 'date' => '24/05/2026', 'total' => '10.500.000 đ', 'status' => 'Đang Giao'],
    ['id' => '#ORD-003', 'customer' => 'Lê Hoàng C', 'date' => '23/05/2026', 'total' => '850.000 đ', 'status' => 'Hoàn Thành'],
    ['id' => '#ORD-004', 'customer' => 'Phạm D', 'date' => '22/05/2026', 'total' => '1.250.000 đ', 'status' => 'Đã Hủy'],
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
</div>

<div class="card border-0 rounded p-4 mb-4" style="background-color: #1a1a1a;">
    <h5 class="text-white fw-bold mb-4">Biểu Đồ Doanh Thu 6 Tháng Gần Nhất</h5>
    <div style="width: 100%; height: 350px; position: relative;">
        <canvas id="revenueChart"></canvas>
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
    document.addEventListener("DOMContentLoaded", function() {
        // Lấy dữ liệu từ PHP chuyển sang mảng JS
        const revenueData = <?= json_encode($revenueData) ?>;
        
        const labels = revenueData.map(item => item.name);
        const data = revenueData.map(item => item.revenue);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh Thu',
                    data: data,
                    borderColor: '#F28B00',
                    backgroundColor: 'rgba(242, 139, 0, 0.1)',
                    borderWidth: 4,
                    pointBackgroundColor: '#1a1a1a',
                    pointBorderColor: '#F28B00',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: false,
                    tension: 0.1 // 0.1 cho đường kẻ thẳng gấp khúc, tương tự type="monotone" của recharts
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Ẩn chú thích giống Recharts
                    },
                    tooltip: {
                        backgroundColor: '#222',
                        borderColor: '#444',
                        borderWidth: 1,
                        titleColor: '#fff',
                        bodyColor: '#F28B00',
                        bodyFont: { weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false, // Ẩn đường kẻ dọc giống vertical={false}
                            drawBorder: false
                        },
                        ticks: {
                            color: '#888'
                        }
                    },
                    y: {
                        grid: {
                            color: '#333',
                            borderDash: [3, 3], // Đường kẻ ngang nét đứt giống strokeDasharray="3 3"
                            drawBorder: false
                        },
                        ticks: {
                            color: '#888',
                            callback: function(value) {
                                return (value / 1000000) + 'Tr'; // tickFormatter
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>
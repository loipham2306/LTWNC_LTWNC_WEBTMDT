<div class="container-fluid">
    <h3 class="text-white mb-4"><i class="fas fa-chart-pie me-2 text-warning"></i>Báo cáo Doanh Thu</h3>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 shadow" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="text-muted">Tổng doanh thu (5 tuần gần nhất)</h6>
                    <h4 class="fw-bold text-warning">
                        <?= number_format(array_sum($valuesTuan), 0, ',', '.') ?>đ
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-dark border-0 shadow" style="border-radius: 15px;">
        <div class="card-body">
            <canvas id="revenueChart" style="max-height: 400px;"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu được đẩy từ PHP sang JS
    const labelsTuan = <?= json_encode($labelsTuan) ?>;
    const valuesTuan = <?= json_encode($valuesTuan) ?>;

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsTuan, // Nhãn động (Tuần/Năm)
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: valuesTuan, // Dữ liệu doanh thu động
                borderColor: '#F28B00',
                backgroundColor: 'rgba(242, 139, 0, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#fff' } } },
            scales: {
                x: { ticks: { color: '#aaa' }, grid: { color: '#333' } },
                y: { ticks: { color: '#aaa' }, grid: { color: '#333' }, beginAtZero: true }
            }
        }
    });
</script>
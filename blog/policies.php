<?php
$breadcrumbs = [
    ["label" => "Trang chủ", "url" => "/techzone/index.php"],
    ["label" => "Chính sách"]
];
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../breadcrumb.php';
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="assets/images/icon_logo.png">
<link rel="stylesheet" href="../assets/css/account.css">
<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="../assets/css/footer.css">
<style>




/* ===== POLICY MENU ===== */
.policy-menu {
    background: #fff;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 6px 20px rgba(0,0,0,.06);
}


.policy-menu .nav-link {
    cursor: pointer;
    border-radius: 8px;
    font-weight: 500;
    color: #1A3D64;
    padding: 12px 14px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all .25s ease;
    position: relative;
}

/* icon giả bằng before */
.policy-menu .nav-link::before {
    content: "›";
    font-size: 18px;
    color: #c1c7d0;
    transition: .25s;
}

/* hover */
.policy-menu .nav-link:hover {
    background: #f3f6fa;
    transform: translateX(4px);
}

.policy-menu .nav-link:hover::before {
    color: #1A3D64;
}

/* active */
.policy-menu .nav-link.active {
    background: linear-gradient(90deg, #1A3D64, #274f85);
    color: #fff;
    box-shadow: 0 6px 15px rgba(26,61,100,.25);
}

.policy-menu .nav-link.active::before {
    color: #fff;
}

/* ===== POLICY CONTENT ===== */
.policy-content {
    display: none;
    animation: fadeSlide .35s ease;
}

.policy-content.active {
    display: block;
}

@keyframes fadeSlide {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.policy-title {
    color: #1A3D64;
    font-weight: 600;
}


/* ===== POLICY CARD ===== */
.policy-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,.06);
}

/* spacing chung */
.policy-card h5 {
    margin-top: 28px;
    font-weight: 600;
}

.policy-card h6 {
    margin-top: 18px;
    font-weight: 600;
    color: #1A3D64;
}

.policy-card p,
.policy-card li {
    line-height: 1.7;
}
</style>
</head>
<div class="container my-5">
    <div class="row">
        <!-- Sidebar menu -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="nav flex-column nav-pills policy-menu" id="policyTab" role="tablist">
                <a class="nav-link active" href="#warranty" onclick="showPolicy('warranty', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
                    <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                    <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                    </svg>
                Chính sách bảo hành</a>
                <a class="nav-link" href="#return" onclick="showPolicy('return', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-repeat" viewBox="0 0 16 16">
                    <path d="M11 5.466V4H5a4 4 0 0 0-3.584 5.777.5.5 0 1 1-.896.446A5 5 0 0 1 5 3h6V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192m3.81.086a.5.5 0 0 1 .67.225A5 5 0 0 1 11 13H5v1.466a.25.25 0 0 1-.41.192l-2.36-1.966a.25.25 0 0 1 0-.384l2.36-1.966a.25.25 0 0 1 .41.192V12h6a4 4 0 0 0 3.585-5.777.5.5 0 0 1 .225-.67Z"/>
                    </svg>
                Chính sách đổi trả</a>
                <a class="nav-link" href="#shipping" onclick="showPolicy('shipping', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                    <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
                    </svg>
                Chính sách vận chuyển</a>
                <a class="nav-link" href="#payment" onclick="showPolicy('payment', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                    </svg>
                Chính sách thanh toán</a>
            </div>
        </div>

        <!-- Policy content -->
        <div class="col-md-8 col-lg-9">
            <!-- CHÍNH SÁCH BẢO HÀNH -->
                <div id="policy-warranty" class="policy-content active">
                     <div class="policy-card">
                    <h3 class="policy-title mb-3">Chính sách bảo hành</h3>

                    <h5 class="mt-4">1. Đổi mới 30 ngày miễn phí</h5>
                    <p>
                        Khi mua sản phẩm tại <strong>TechZone</strong>, khách hàng được áp dụng chính sách
                        <strong>đổi mới miễn phí lên đến 30 ngày</strong> đối với các lỗi phần cứng do nhà sản xuất.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Loại sản phẩm</th>
                                    <th>Thời gian đổi mới</th>
                                    <th>Quy định nhập / trả lại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Điện thoại / Tablet / Macbook / Apple Watch</td>
                                    <td>30 ngày</td>
                                    <td>
                                        Trong 30 ngày: trừ 20% giá hiện tại (hoặc giá mua nếu thấp hơn).<br>
                                        Sau 30 ngày: nhập lại theo giá thỏa thuận.
                                    </td>
                                </tr>
                                <tr>
                                    <td>Samsung Watch</td>
                                    <td>30 ngày</td>
                                    <td>
                                        Trong 30 ngày: trừ 30% giá hiện tại.<br>
                                        Sau 30 ngày: nhập lại theo giá thỏa thuận.
                                    </td>
                                </tr>
                                <tr>
                                    <td>Laptop</td>
                                    <td>30 ngày</td>
                                    <td>
                                        Trong 30 ngày: trừ 20% giá hiện tại.<br>
                                        Sau 30 ngày: không áp dụng nhập lại.
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phụ kiện &lt; 1 triệu</td>
                                    <td>1 năm (mới) / 1 tháng (cũ)</td>
                                    <td>Không áp dụng nhập lại</td>
                                </tr>
                                <tr>
                                    <td>Phụ kiện &gt; 1 triệu</td>
                                    <td>15 ngày</td>
                                    <td>Không áp dụng nhập lại</td>
                                </tr>
                                <tr>
                                    <td>AirPods</td>
                                    <td>30 ngày</td>
                                    <td>
                                        Trong 30 ngày: trừ 20% giá hiện tại.<br>
                                        Sau 30 ngày: nhập theo giá thỏa thuận.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mt-4">Điều kiện đổi trả</h6>
                    <ul>
                        <li>Máy như mới, không trầy xước, không dán decal.</li>
                        <li>Hộp còn nguyên vẹn, serial/IMEI trùng với máy.</li>
                        <li>Phụ kiện, quà tặng đầy đủ, không hư hỏng.</li>
                        <li>Thiết bị đã đăng xuất khỏi iCloud, Google, Mi Account…</li>
                    </ul>

                    <p class="text-muted">
                        (*) Lỗi nhà sản xuất bao gồm: lỗi nguồn, mainboard, màn hình, ổ cứng, linh kiện phần cứng.
                    </p>

                    <hr>

                    <h5 class="mt-4">2. Bảo hành tiêu chuẩn</h5>

                    <h6 class="mt-3">2.1. Điện thoại & Laptop</h6>
                    <ul>
                        <li>Sản phẩm mới: bảo hành 12 tháng hoặc theo chính sách hãng.</li>
                        <li>Sản phẩm đã kích hoạt: bảo hành theo thời gian còn lại của hãng.</li>
                        <li>Máy cũ: bảo hành 6 tháng tại hệ thống TechZone.</li>
                        <li>Trong thời gian chờ bảo hành, khách hàng được hỗ trợ máy sử dụng tạm thời.</li>
                    </ul>

                    <h6 class="mt-3">2.2. Phụ kiện</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Loại phụ kiện</th>
                                    <th>Thời gian bảo hành</th>
                                    <th>Quyền lợi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Phụ kiện mới</td>
                                    <td>12 tháng</td>
                                    <td>1 đổi 1 mọi lỗi</td>
                                </tr>
                                <tr>
                                    <td>Phụ kiện cũ</td>
                                    <td>30 ngày</td>
                                    <td>1 đổi 1</td>
                                </tr>
                                <tr>
                                    <td>Dán màn hình / Cường lực</td>
                                    <td>30 ngày</td>
                                    <td>1 đổi 1, giảm 30% lần dán tiếp theo</td>
                                </tr>
                                <tr>
                                    <td>Thẻ nhớ, USB</td>
                                    <td>24 tháng</td>
                                    <td>Sửa chữa / đổi mới</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>

            <!-- CHÍNH SÁCH ĐỔI TRẢ -->
            <div id="policy-return" class="policy-content">
                <div class="policy-card">
                <h3 class="policy-title mb-3">Chính sách đổi – trả</h3>

                <h5>1. Thời gian đổi trả</h5>
                <p>
                    Thời gian đổi trả được tính từ ngày khách hàng nhận hàng (đối với đơn online).
                </p>

                <ul>
                    <li>Điện thoại / Tablet / Macbook: 30 ngày</li>
                    <li>Apple Watch / Samsung Watch: 30 ngày</li>
                    <li>Laptop: 30 ngày (không áp dụng sau thời gian tiêu chuẩn)</li>
                    <li>Phụ kiện &lt; 1 triệu: tối đa 1 năm</li>
                    <li>Phụ kiện &gt; 1 triệu: 15 ngày</li>
                </ul>

                <h5 class="mt-4">2. Điều kiện đổi trả</h5>
                <ul>
                    <li>Sản phẩm như mới, không trầy xước, móp méo.</li>
                    <li>Hộp, phụ kiện, quà tặng còn đầy đủ.</li>
                    <li>Thiết bị đã đăng xuất khỏi các tài khoản cá nhân.</li>
                </ul>

                <h5 class="mt-4">3. Hình thức đổi trả</h5>
                <ul>
                    <li>Đổi trả trực tiếp tại cửa hàng TechZone.</li>
                    <li>Đổi trả qua đơn vị vận chuyển theo hướng dẫn của TechZone.</li>
                </ul>

                <h5 class="mt-4">4. Hoàn tiền</h5>
                <ul>
                    <li>Tiền mặt: hoàn ngay tại cửa hàng.</li>
                    <li>Chuyển khoản: 2 ngày làm việc.</li>
                    <li>Thẻ ATM: 7 – 10 ngày.</li>
                    <li>Visa / Master / JCB: 7 – 15 ngày.</li>
                    <li>Ví điện tử: 3 – 8 ngày.</li>
                </ul>
                </div>
            </div>

            <!-- CHÍNH SÁCH VẬN CHUYỂN -->
            <div id="policy-shipping" class="policy-content">
                <div class="policy-card">
                <h3 class="policy-title mb-3">Chính sách vận chuyển</h3>

                <h5>1. Phạm vi áp dụng</h5>
                <p>Áp dụng trên toàn quốc tại các khu vực có hệ thống TechZone.</p>

                <h5 class="mt-4">2. Thời gian giao hàng</h5>
                <ul>
                    <li>Giao nhanh: 1 – 2 giờ (bán kính ≤ 20km).</li>
                    <li>Giao tiêu chuẩn: trong ngày.</li>
                    <li>Nội tỉnh – liên tỉnh: 2 – 6 ngày.</li>
                </ul>

                <h5 class="mt-4">3. Phí giao hàng</h5>
                <ul>
                    <li>Đơn ≥ 500.000đ: miễn phí 10km đầu.</li>
                    <li>Đơn &lt; 500.000đ: phí 20.000đ / 10km.</li>
                    <li>Mỗi km tiếp theo: 5.000đ/km.</li>
                </ul>

                <p class="text-muted">
                    Lưu ý: Giao siêu nhanh có thể phát sinh phụ phí.
                </p>
                </div>
            </div>

            <!-- CHÍNH SÁCH THANH TOÁN -->
            <div id="policy-payment" class="policy-content">
                <div class="policy-card">
                <h3 class="policy-title mb-3">Chính sách thanh toán</h3>

                <h5>1. Quy trình mua hàng</h5>
                <ol>
                    <li>Chọn sản phẩm và đồng ý điều khoản mua hàng.</li>
                    <li>Chọn hình thức mua: mua ngay / trả góp.</li>
                    <li>Nhập thông tin giao hàng và thanh toán.</li>
                    <li>TechZone xác nhận đơn hàng qua điện thoại.</li>
                    <li>Giao hàng hoặc nhận tại cửa hàng.</li>
                </ol>

                <h5 class="mt-4">2. Hình thức thanh toán</h5>
                <ul>
                    <li>COD – Thanh toán khi nhận hàng.</li>
                    <li>Chuyển khoản ngân hàng.</li>
                    <li>Thẻ ATM, Visa, MasterCard, JCB.</li>
                    <li>Ví điện tử (VNPay, MoMo…).</li>
                </ul>

                <p class="text-muted">
                    Với đơn hàng giá trị cao, TechZone có thể yêu cầu xác minh chủ thẻ trước khi giao hàng.
                </p>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
// JS để chuyển nội dung mà không reload
function showPolicy(id, el) {
    // ẩn hiện content
    document.querySelectorAll('.policy-content').forEach(div => {
        div.classList.remove('active');
    });

    document.getElementById('policy-' + id).classList.add('active');

    // update active menu
    document.querySelectorAll('.policy-menu .nav-link').forEach(btn => {
        btn.classList.remove('active');
    });
    el.classList.add('active');
}
</script>
<script>
function showPolicy(id, el = null) {
    // ẩn tất cả nội dung
    document.querySelectorAll('.policy-content').forEach(div => {
        div.classList.remove('active');
    });

    const target = document.getElementById('policy-' + id);
    if (target) target.classList.add('active');

    // active menu
    document.querySelectorAll('.policy-menu .nav-link').forEach(btn => {
        btn.classList.remove('active');
    });

    if (el) {
        el.classList.add('active');
    } else {
        // active theo hash
        const link = document.querySelector('.policy-menu a[href="#' + id + '"]');
        if (link) link.classList.add('active');
    }

    // update URL hash (không reload)
    history.replaceState(null, '', '#' + id);
}

// 🔥 TỰ ĐỘNG MỞ POLICY THEO HASH
document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        showPolicy(hash);
    }
});
</script>
<?php include __DIR__ . '/../partials/footer.php'; ?>

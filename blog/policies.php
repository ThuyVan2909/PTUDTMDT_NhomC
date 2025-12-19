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
<link rel="stylesheet" href="../assets/css/account.css">
<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="../assets/css/footer.css">
<style>
.policy-menu .nav-link {
    cursor: pointer;
    border-radius: 0;
    font-weight: 500;
}
.policy-menu .nav-link.active {
    background-color: #1A3D64;
    color: #fff;
}
.policy-content {
    display: none;
}
.policy-content.active {
    display: block;
}
.policy-title {
    color: #1A3D64;
    font-weight: 600;
}
</style>
</head>
<div class="container my-5">
    <div class="row">
        <!-- Sidebar menu -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="nav flex-column nav-pills policy-menu" id="policyTab" role="tablist">
                <a class="nav-link active" onclick="showPolicy('warranty', this)">Chính sách bảo hành</a>
                <a class="nav-link" onclick="showPolicy('return', this)">Chính sách đổi trả</a>
                <a class="nav-link" onclick="showPolicy('shipping', this)">Chính sách vận chuyển</a>
                <a class="nav-link" onclick="showPolicy('payment', this)">Chính sách thanh toán</a>
            </div>
        </div>

        <!-- Policy content -->
        <div class="col-md-8 col-lg-9">
            <!-- CHÍNH SÁCH BẢO HÀNH -->
                <div id="policy-warranty" class="policy-content active">
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

            <!-- CHÍNH SÁCH ĐỔI TRẢ -->
            <div id="policy-return" class="policy-content">
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

            <!-- CHÍNH SÁCH VẬN CHUYỂN -->
            <div id="policy-shipping" class="policy-content">
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

            <!-- CHÍNH SÁCH THANH TOÁN -->
            <div id="policy-payment" class="policy-content">
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

<?php include __DIR__ . '/../partials/footer.php'; ?>

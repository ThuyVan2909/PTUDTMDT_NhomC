<?php
$conn = new mysqli("localhost","root","","lendly_db");
session_start();

$catId = intval($_GET['cat'] ?? 0);

// Lấy info category
$category = null;
if ($catId > 0) {
    $rs = $conn->query("SELECT * FROM categories WHERE id = $catId");
    $category = $rs->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($category['name'] ?? 'Danh mục') ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
.price-range-box input[type=range]{width:100%}
.product-card{border:1px solid #eee;border-radius:8px;overflow:hidden}
.product-img{width:100%;height:180px;object-fit:cover}
</style>
</head>

<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg border-bottom bg-white">
  <div class="container">

    <a class="navbar-brand fw-bold" href="index.php">
      <img src="assets/images/logo.png" height="36">
    </a>

    <!-- MOBILE TOGGLE -->
    <button class="navbar-toggler d-lg-none" type="button" id="mobileMenuToggle">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- CART -->
    <a href="cart.php" class="btn btn-outline-success position-relative ms-2">
      🛒
      <span id="cartCount"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
    </a>
  </div>
</nav>

<!-- MOBILE MENU OVERLAY -->
<div id="mobileMenuDropdown"
     class="position-fixed top-0 start-0 w-100 h-100 bg-white d-lg-none"
     style="z-index:1050;display:none">
  <div class="p-3 border-bottom d-flex justify-content-between">
    <strong>Menu</strong>
    <button class="btn btn-sm btn-outline-secondary" id="closeMobileMenu">✕</button>
  </div>
  <ul class="navbar-nav p-3 gap-2">
    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
    <li class="nav-item"><a class="nav-link" href="category.php?cat=<?= $catId ?>">Danh mục</a></li>
    <li class="nav-item"><a class="nav-link" href="contact.php">Liên hệ</a></li>
  </ul>
</div>

<!-- CONTENT -->
<div class="container py-4">
  <div class="row">

    <!-- FILTER -->
    <div class="col-lg-3 mb-4">
      <div class="bg-white p-3 rounded shadow-sm">
        <h5 class="fw-bold mb-3">Lọc theo giá</h5>

        <div class="price-range-box">
          <input type="range" id="minPrice" min="0" max="50000000" step="500000" value="0">
          <input type="range" id="maxPrice" min="0" max="50000000" step="500000" value="50000000">

          <div class="d-flex justify-content-between small mt-2">
            <span id="minPriceText">0₫</span>
            <span id="maxPriceText">50.000.000₫</span>
          </div>
        </div>

        <button class="btn btn-primary w-100 mt-3" id="applyFilter">
          Áp dụng
        </button>
      </div>
    </div>

    <!-- PRODUCTS -->
    <div class="col-lg-9">
      <h3 class="fw-bold mb-3">
        <?= htmlspecialchars($category['name'] ?? 'Danh mục') ?>
      </h3>

      <div id="productList" class="row g-3"></div>
    </div>

  </div>
</div>

<script>
function formatVND(v){
  return Number(v).toLocaleString("vi-VN")+"₫";
}

function loadProducts(){
  $.post("load_category_products.php",{
    cat: <?= $catId ?>,
    min: $("#minPrice").val(),
    max: $("#maxPrice").val()
  },function(html){
    $("#productList").html(html);
  });
}

$("#minPrice,#maxPrice").on("input",function(){
  let min=parseInt($("#minPrice").val());
  let max=parseInt($("#maxPrice").val());
  if(min>max){$("#minPrice").val(max);min=max;}
  $("#minPriceText").text(formatVND(min));
  $("#maxPriceText").text(formatVND(max));
});

$("#applyFilter").click(loadProducts);

// mobile menu
$("#mobileMenuToggle").click(()=>$("#mobileMenuDropdown").show());
$("#closeMobileMenu").click(()=>$("#mobileMenuDropdown").hide());

// load lần đầu
loadProducts();
</script>

</body>
</html>

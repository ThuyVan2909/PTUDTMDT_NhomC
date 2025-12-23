<?php
$conn = new mysqli("localhost","root","","lendly_db");
$conn->set_charset("utf8mb4");
session_start();

$catId = intval($_GET['cat'] ?? 0);

// Lấy info category
$category = null;
if ($catId > 0) {
    $rs = $conn->query("SELECT * FROM categories WHERE id = $catId");
    $category = $rs->fetch_assoc();
}


?>


<?php $breadcrumbs = [
    ["label" => "Trang chủ", "url" => "index.php"]
];

if (!empty($category['name'])) {
    $breadcrumbs[] = [
        "label" => $category['name']
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($category['name'] ?? 'Danh mục') ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
/* ===== GLOBAL ===== */
body{
  background:#f4f6f8;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* ===== CARD ===== */
.product-card{
  background:#fff;
  border-radius:14px;
  border:1px solid #eaeaea;
  transition:all .25s ease;
  height:100%;
  overflow:hidden;
}
.product-card:hover{
  transform:translateY(-4px);
  box-shadow:0 12px 28px rgba(0,0,0,.12);
}

/* IMAGE */
.product-img{
  width:100%;
  height:200px;
  object-fit:contain;
  background:#fafafa;
  padding:12px;
}

/* INFO */
.product-name{
  font-size:15px;
  font-weight:600;
  line-height:1.4;
  height:42px;
  overflow:hidden;
}

.price{
  font-weight:700;
  color:#e53935;
  font-size:16px;
}
.old-price{
  text-decoration:line-through;
  font-size:13px;
  color:#9e9e9e;
}

/* BUTTON */
.btn-buy{
  border-radius:10px;
  font-size:14px;
  padding:6px 10px;
}

/* ===== FILTER BOX ===== */
.filter-box{
  background:#fff;
  border-radius:14px;
  box-shadow:0 6px 20px rgba(0,0,0,.06);
}

/* RANGE */
input[type=range]::-webkit-slider-thumb{
  background:#0d6efd;
}
input[type=range]::-moz-range-thumb{
  background:#0d6efd;
}

/* DROPDOWN */
.dropdown-menu{
  border-radius:12px;
  box-shadow:0 10px 30px rgba(0,0,0,.12);
  border:none;
}
.dropdown-item{
  font-size:14px;
}
.dropdown-item:hover{
  background:#f1f3f5;
}

/* ===== NAVBAR ===== */
.navbar{
  box-shadow:0 2px 10px rgba(0,0,0,.05);
}
.nav-link{
  font-weight:500;
}

/* ===== MOBILE ===== */
@media (max-width: 576px){
  .product-img{
    height:160px;
  }
  h4{
    font-size:18px;
  }
}

</style>
</head>

<body>

<!-- ===== HEADER (copy phong cách index) ===== -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">

    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
      <img src="assets/images/logo.png" height="36">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU DESKTOP -->
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="index.php">Trang chủ</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            Danh mục
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="category.php?cat=1">Điện thoại</a></li>
            <li><a class="dropdown-item" href="category.php?cat=2">Laptop</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="contact.php">Liên hệ</a>
        </li>

      </ul>

      <!-- CART -->
      <a href="cart.php" class="btn btn-outline-success position-relative">
        🛒
        <span id="cartCount"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          0
        </span>
      </a>
    </div>

  </div>
</nav>

<div class="container mt-3">
    <?php include "breadcrumb.php"; ?>
</div>
<!-- MOBILE MENU -->
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



<!-- ===== CONTENT ===== -->
<div class="container my-4">
  <div class="row">

    <!-- FILTER -->
<div class="col-lg-3 mb-4">
  <div class="bg-white p-3 rounded shadow-sm">

    <h5 class="fw-bold mb-3">Lọc sản phẩm</h5>

    <!-- PRICE RANGE (1 BAR) -->
    <label class="fw-semibold mb-2">Khoảng giá</label>

    <div class="position-relative mb-2">
      <input type="range" id="priceRange"
             min="0" max="50000000" step="500000" value="50000000"
             class="form-range">
      <div class="d-flex justify-content-between small">
        <span id="minPriceText">0₫</span>
        <span id="maxPriceText">50.000.000₫</span>
      </div>
    </div>

    <!-- BRAND DROPDOWN -->
    <div class="dropdown mb-2">
      <button class="btn btn-outline-secondary w-100 dropdown-toggle text-start"
              data-bs-toggle="dropdown">
        Thương hiệu
      </button>
      <ul class="dropdown-menu w-100">
        <li><a class="dropdown-item brand-item" data-brand="">Tất cả</a></li>
        <li><a class="dropdown-item brand-item" data-brand="Apple">Apple</a></li>
        <li><a class="dropdown-item brand-item" data-brand="Samsung">Samsung</a></li>
        <li><a class="dropdown-item brand-item" data-brand="Dell">Dell</a></li>
        <li><a class="dropdown-item brand-item" data-brand="Asus">Asus</a></li>
      </ul>
    </div>

    <!-- TYPE DROPDOWN -->
    <div class="dropdown mb-3">
      <button class="btn btn-outline-secondary w-100 dropdown-toggle text-start"
              data-bs-toggle="dropdown">
        Loại sản phẩm
      </button>
      <ul class="dropdown-menu w-100">
        <li><a class="dropdown-item type-item" data-type="">Tất cả</a></li>
        <li><a class="dropdown-item type-item" data-type="phone">Điện thoại</a></li>
        <li><a class="dropdown-item type-item" data-type="laptop">Laptop</a></li>
      </ul>
    </div>

    <button class="btn btn-primary w-100" id="applyFilter">
      Áp dụng
    </button>

  </div>
</div>


    <!-- PRODUCTS -->
    <div class="col-lg-9">
      <h4 class="fw-bold mb-3">
        <?= htmlspecialchars($category['name'] ?? 'Danh mục') ?>
      </h4>

      <div id="productList" class="row g-3">
        <!-- ajax load -->
      </div>
    </div>

  </div>
</div>


<script>
/* ===== GLOBAL FILTER STATE ===== */
let minPrice = 0;
let maxPrice = 50000000;
let selectedBrand = "";
let selectedType = "";

/* ===== FORMAT ===== */
function formatVND(v){
  return Number(v).toLocaleString("vi-VN") + "₫";
}

/* ===== LOAD PRODUCTS ===== */
function loadProducts(){
  $.post("load_category_products.php",{
    cat: <?= $catId ?>,
    min: minPrice,
    max: maxPrice,
    brand: selectedBrand,
    type: selectedType
  },function(html){
    $("#productList").html(html);
  });
}

/* ===== PRICE RANGE (1 BAR) ===== */
$("#priceRange").on("input", function () {
  maxPrice = parseInt(this.value);
  $("#minPriceText").text(formatVND(minPrice));
  $("#maxPriceText").text(formatVND(maxPrice));
});

/* ===== BRAND FILTER ===== */
$(".brand-item").on("click", function () {
  selectedBrand = $(this).data("brand");
  $(this).closest(".dropdown")
         .find("button")
         .text($(this).text());
});

/* ===== TYPE FILTER ===== */
$(".type-item").on("click", function () {
  selectedType = $(this).data("type");
  $(this).closest(".dropdown")
         .find("button")
         .text($(this).text());
});

/* ===== APPLY ===== */
$("#applyFilter").on("click", function () {
  loadProducts();
});

/* ===== MOBILE MENU ===== */
$("#mobileMenuToggle").on("click", function(){
  $("#mobileMenuDropdown").show();
});
$("#closeMobileMenu").on("click", function(){
  $("#mobileMenuDropdown").hide();
});

/* ===== FIRST LOAD ===== */
$(document).ready(function(){
  $("#minPriceText").text(formatVND(minPrice));
  $("#maxPriceText").text(formatVND(maxPrice));
  loadProducts();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

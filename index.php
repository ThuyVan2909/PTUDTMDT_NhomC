<?php
$conn = new mysqli("localhost","root","","lendly_db");

// Laptop
$laptop_categories = $conn->query("SELECT * FROM categories WHERE parent_id = 2");
$laptop_brands = $conn->query("SELECT DISTINCT brand FROM spu WHERE category_id IN (SELECT id FROM categories WHERE parent_id=2)");

// Điện thoại
$phone_categories = $conn->query("SELECT * FROM categories WHERE parent_id = 1");


// Smartwatch
$watch_categories = $conn->query("SELECT * FROM categories WHERE parent_id = 3");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Lendly Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .category-btn, .brand-btn { cursor: pointer; }
        .active-filter { background: #0d6efd !important; color: #fff !important; }
        /* Login modal */
        .login-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); justify-content:center; align-items:center; z-index:2000; }
        .login-box { background:#fff; padding:25px; width:350px; border-radius:8px; position:relative; }
        .login-box input { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:5px; }
        .login-submit { width:100%; padding:10px; background:#135071; color:#fff; border:none; border-radius:6px; cursor:pointer; }
        .close-btn { position:absolute; right:15px; top:10px; cursor:pointer; font-size:20px; }
    </style>
</head>
<body class="bg-light">

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Lendly Store</a>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary" onclick="openLogin()">Đăng nhập</button>
      <a href="#" class="btn btn-outline-success">Giỏ hàng</a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- LAPTOP -->
<section id="laptop-section">
    <h2 class="fw-bold mb-3">Laptop</h2>
    <div class="d-flex gap-2 mb-2">
        <button class="btn btn-outline-primary laptop-cat active-filter" data-id="">Tất cả</button>
        <?php while($c=$laptop_categories->fetch_assoc()): ?>
            <button class="btn btn-outline-primary laptop-cat" data-id="<?= $c['id'] ?>"><?= $c['name'] ?></button>
        <?php endwhile; ?>
    </div>
    <div class="d-flex gap-2 mb-4">
        <button class="btn btn-outline-dark laptop-brand active-filter" data-brand="">Tất cả</button>
        <?php while($b=$laptop_brands->fetch_assoc()): ?>
            <button class="btn btn-outline-dark laptop-brand" data-brand="<?= $b['brand'] ?>"><?= $b['brand'] ?></button>
        <?php endwhile; ?>
    </div>
    <div id="laptop-products" class="row g-3"></div>
</section>




<!-- ĐIỆN THOẠI -->
<section id="phone-section">
    <h2 class="fw-bold mt-5 mb-3">Điện thoại</h2>

    <!-- Category cha + con -->
    <div class="d-flex gap-2 mb-4">
        <button class="btn btn-outline-primary phone-cat active-filter" data-id="1">Điện thoại</button>

        <?php while($c=$phone_categories->fetch_assoc()): ?>
            <button class="btn btn-outline-primary phone-cat" data-id="<?= $c['id'] ?>">
                <?= $c['name'] ?>
            </button>
        <?php endwhile; ?>
    </div>

    <!-- XOÁ PHẦN BRAND (không dùng nữa) -->

    <div id="phone-products" class="row g-3"></div>
</section>






<!-- SMARTWATCH -->
<section id="watch-section">
    <h2 class="fw-bold mt-5 mb-3">Smartwatch</h2>

    <!-- Category cha + con -->
    <div class="d-flex gap-2 mb-4">
        <button class="btn btn-outline-primary watch-cat active-filter" data-id="3">Smartwatch</button>

        <?php while($c=$watch_categories->fetch_assoc()): ?>
            <button class="btn btn-outline-primary watch-cat" data-id="<?= $c['id'] ?>">
                <?= $c['name'] ?>
            </button>
        <?php endwhile; ?>
    </div>

    <!-- XOÁ PHẦN BRAND -->

    <div id="watch-products" class="row g-3"></div>
</section>



</div>

<!-- LOGIN MODAL -->
<div id="loginModal" class="login-modal">
  <div class="login-box">
    <span class="close-btn" onclick="closeLogin()">&times;</span>
    <h3 class="mb-3">Đăng nhập</h3>
    <form method="POST" action="login.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit" class="login-submit">Đăng nhập</button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openLogin(){ document.getElementById("loginModal").style.display="flex"; }
function closeLogin(){ document.getElementById("loginModal").style.display="none"; }
window.onclick=function(e){ let modal=document.getElementById("loginModal"); if(e.target===modal) modal.style.display="none"; }

// Load products
function loadSection(section, category=null, brand=null){
    $.post("load_products.php",{section:section, category:category, brand:brand}, function(data){
        $("#" + section + "-products").html(data);
    });
}

// Laptop
let laptopCat="", laptopBrand="";
$(".laptop-cat").click(function(){
    laptopCat=$(this).data("id");
    loadSection("laptop", laptopCat, laptopBrand);
    $(".laptop-cat").removeClass("active-filter"); $(this).addClass("active-filter");
});
$(".laptop-brand").click(function(){
    laptopBrand=$(this).data("brand");
    loadSection("laptop", laptopCat, laptopBrand);
    $(".laptop-brand").removeClass("active-filter"); $(this).addClass("active-filter");
});
loadSection("laptop", laptopCat, laptopBrand);

// Điện thoại
let phoneCat="", phoneBrand="";
$(".phone-cat").click(function(){
    phoneCat=$(this).data("id");
    loadSection("phone", phoneCat, phoneBrand);
    $(".phone-cat").removeClass("active-filter"); $(this).addClass("active-filter");
});

loadSection("phone", phoneCat, "");

// Smartwatch
let watchCat="", watchBrand="";
$(".watch-cat").click(function(){
    watchCat=$(this).data("id");
    loadSection("watch", watchCat, watchBrand);
    $(".watch-cat").removeClass("active-filter"); $(this).addClass("active-filter");
});

loadSection("watch", watchCat, "");

</script>
</body>
</html>

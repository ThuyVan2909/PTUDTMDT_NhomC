<?php
$conn = new mysqli("localhost","root","","lendly_db");
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;



// Laptop
$laptop_categories = $conn->query("SELECT * FROM categories WHERE parent_id = 2");


// Điện thoại
$phone_categories = $conn->query("SELECT * FROM categories WHERE parent_id = 1");


// Smartwatch
$watch_categories = $conn->query("SELECT * FROM categories WHERE parent_id = 3");


?>
<!DOCTYPE html>
<html>
<head>
    <title>TechZone</title>
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
        
/* Highlight khi scroll đến */
/* Highlight viền card với fade */
.product-highlight {
    position: relative;
    border: 2px solid #e30019;
    box-shadow: 0 0 15px rgba(227,0,25,0.5);
    border-radius: 8px; /* khớp với .card */
    opacity: 1;
    transition: opacity 1.5s ease, box-shadow 1.5s ease;
}

.product-highlight.fade-out {
    opacity: 0;
    box-shadow: 0 0 0 rgba(227,0,25,0);
}

</style>

    </style>
</head>
<body class="bg-light">




<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">TechZone</a>
    
    <div class="d-flex gap-2">

    <?php if(!$isLoggedIn): ?>
        <!-- Nếu chưa đăng nhập -->
        <button class="btn btn-outline-primary" onclick="openLogin()">Đăng nhập</button>

    <?php else: ?>
        <!-- Nếu đã đăng nhập -->
        <a href="account.php" class="btn btn-outline-primary">
            Tài khoản (<?= htmlspecialchars($userName) ?>)
        </a>
    <?php endif; ?>

    <a href="cart.php" class="btn btn-outline-success position-relative">
        Giỏ hàng
        <span id="cartCount" 
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
    </a>

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
    
    <div id="laptop-products" class="row g-3"></div>
</section>




<!-- ĐIỆN THOẠI -->
<section id="phone-section">
    <h2 class="fw-bold mt-5 mb-3">Điện thoại</h2>

    <!-- Category cha + con -->
    <div class="d-flex gap-2 mb-4">
        <button class="btn btn-outline-primary phone-cat phone-cat-all" data-id="">Điện thoại</button>


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
    <div class="text-center mt-2">
        <small>
            Bạn chưa có tài khoản? 
            <a href="register.php" class="fw-bold text-primary">Đăng ký ngay</a>
        </small>
    </div>
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
//$(".laptop-brand").click(function(){
    //laptopBrand=$(this).data("brand");
    //loadSection("laptop", laptopCat, laptopBrand);
    //$(".laptop-brand").removeClass("active-filter"); $(this).addClass("active-filter");
//});
loadSection("laptop", laptopCat, "");

// Điện thoại
let phoneCat="", phoneBrand="";
$(".phone-cat").click(function(){
    phoneCat=$(this).data("id");
    loadSection("phone", phoneCat, phoneBrand);
    $(".phone-cat").removeClass("active-filter"); $(this).addClass("active-filter");
});
// Reset filter khi click "Tất cả điện thoại"
$(".phone-cat-all").click(function(){
    phoneCat = ""; // reset biến
    loadSection("phone", phoneCat, ""); // load tất cả điện thoại
    $(".phone-cat").removeClass("active-filter"); 
    $(this).addClass("active-filter");
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



const url = new URL(window.location.href);
const section = url.searchParams.get("section");
const cat = url.searchParams.get("cat");
const pid = url.searchParams.get("product_id");


if (section && cat) {
    if (section === "laptop") {
    laptopCat = cat;
    loadSection("laptop", laptopCat, "");

    $(".laptop-cat").removeClass("active-filter");
    $(".laptop-cat[data-id='" + cat + "']").addClass("active-filter");

    // Scroll tới section trước
    window.scrollTo(0, document.getElementById("laptop-section").offsetTop - 80);

    // Scroll tới đúng sản phẩm
    if (pid) {
        setTimeout(() => {
            const el = document.getElementById("product-" + pid);
            if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
        }, 200);
    }
}
    if (section === "phone") {
    phoneCat = cat;
    loadSection("phone", phoneCat, "");

    $(".phone-cat").removeClass("active-filter");
    $(".phone-cat[data-id='" + cat + "']").addClass("active-filter");

    window.scrollTo(0, document.getElementById("phone-section").offsetTop - 80);

    if (pid) {
        setTimeout(() => {
            const el = document.getElementById("product-" + pid);
            if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
        }, 200);
    }
}
    if (section === "watch") {
    watchCat = cat;
    loadSection("watch", watchCat, "");

    $(".watch-cat").removeClass("active-filter");
    $(".watch-cat[data-id='" + cat + "']").addClass("active-filter");

    window.scrollTo(0, document.getElementById("watch-section").offsetTop - 80);

    if (pid) {
        setTimeout(() => {
            const el = document.getElementById("product-" + pid);
            if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
        }, 200);
    }
}

}

// SCROLL TO PRODUCT
const productId = url.searchParams.get("product_id");
if (productId) {
    setTimeout(() => {
        const el = document.getElementById("product-" + productId);
        if (el) {
            // Scroll tới sản phẩm
            window.scrollTo({
                top: el.offsetTop - 80,
                behavior: "smooth"
            });

            // Thêm highlight
            el.classList.add("product-highlight");

            // Tự động bỏ highlight sau 2.5s
            setTimeout(() => el.classList.remove("product-highlight"), 2500);
        }
    }, 500);
}


</script>

<script>
$(document).ready(function(){
    $.get("cart_count.php", function(count){
        $("#cartCount").text(count);
    });
});
</script>


</body>
</html>

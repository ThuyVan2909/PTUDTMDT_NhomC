<?php
$conn = new mysqli("localhost","root","","lendly_db");

// LEFT BANNERS (3 fixed)
$leftBanners = $conn->query("
    SELECT * FROM banners
    WHERE is_active = 1 AND position = 'left'
    ORDER BY sort_order ASC
    LIMIT 3
");

// TOP BANNERS (slider)
$topBanners = $conn->query("
    SELECT * FROM banners
    WHERE is_active = 1 AND position = 'top'
    ORDER BY sort_order ASC
");

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;

// LOAD LEFT BANNERS



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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/images/icon_logo.png">
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


/* ===== ANNOUNCEMENT BAR ===== */
.announcement-bar {
    background: #1A3D64; /* xanh TechZone */
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    overflow: hidden;
    white-space: nowrap;
}

.announcement-track {
    display: inline-flex;
    align-items: center;
    gap: 48px;
    padding: 8px 0;
    animation: marquee 18s linear infinite;
}

.announcement-track span {
    display: inline-block;
}

@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}


/* ===== HEADER STYLE (TechZone) ===== */
.navbar {
    background: linear-gradient(90deg, #EEF4FA, #F8FAFC);
    border-bottom: 1px solid #D6E0EA;
}


.navbar .nav-link {
    color: #0F172A !important;
    font-weight: 500;
}

.navbar .nav-link:hover {
    color: #1A3D64 !important;
}

.navbar-brand {
    letter-spacing: 0.5px;
    padding: 0;
    margin: 0;
    line-height: 1;
    height: 64px; 
}

input.form-control:focus {
    box-shadow: none;
    border-color: #1A3D64;
}

.navbar-brand img {
    object-fit: contain;
}

.brand-text {
    font-size: 1.25rem;
    color: #1A3D64;
    letter-spacing: 0.5px;
}
.logo-img {
    height: 50px;       
    width: auto;
    object-fit: contain;
    display: block;   
}
.navbar-nav .nav-item {
    position: relative;
}
.navbar-nav .nav-item:not(:last-child)::after {
    content: "";
    position: absolute;
    right: -12px;
    top: 50%;
    transform: translateY(-50%);
    width: 1px;
    height: 18px;
    background-color: #d0d7e2; 
}

.search-box {
    width: 100%;
    max-width: 360px;
}


/* Icon box bên trái */
.search-icon-box {
    width: 44px;
    height: 44px;
    border: 1px solid #dbe3ec;
    border-right: none;
    border-radius: 8px 0 0 8px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1A3D64;
    cursor: pointer;
    transition: all 0.2s ease;
}

.search-icon-box svg {
    width: 18px;
    height: 18px;
}

/* Input */
.search-input {
    height: 44px;
    border-radius: 0 8px 8px 0;
    border-left: none;
}

/* Focus */
.search-input:focus {
    box-shadow: none;
    border-color: #1A3D64;
}

/* Hover icon */
.search-icon-box:hover {
    background: #1A3D64;
    color: #fff;
}
.btn-icon {
    width: 18px;
    height: 18px;
}

.cart-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #dc3545;
    color: #fff;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 50%;
}

.btn-outline-primary {
    --bs-btn-color: #1A3D64;
    --bs-btn-border-color: #1A3D64;
    --bs-btn-hover-bg: #1A3D64;
    --bs-btn-hover-color: #fff;
}

.header-action-btn svg {
    width: 18px;
    height: 18px;
}

/* ===== FOOTER ===== */
.footer {
    background: linear-gradient(90deg, #EEF4FA, #F8FAFC);
    border-top: 1px solid #E2E8F0;
}

.footer h6 {
    color: #0F172A;
}

.footer-links li {
    margin-bottom: 8px;
}

.footer-links a {
    color: #475569;
    text-decoration: none;
    transition: 0.2s;
}

.footer-links a:hover {
    color: #1A3D64;
}

.social-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #E2E8F0;
    color: #1A3D64;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: 0.2s;
}

.social-icon:hover {
    background: #1A3D64;
    color: #fff;
}

.search-dropdown {
    position: absolute;
    top: 110%;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.2);
    z-index: 99999;
    display: none;
    padding: 12px 0;
}


.search-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    cursor: pointer;
    transition: background .15s ease;
}

.search-item:hover {
    background: #f7f7f7;
}

.search-thumb {
    width: 108px;      /* tăng từ 56 → 64 */
    height: 108px;
    border-radius: 10px;
    object-fit: cover;
    background: #eee;
}


.search-info {
    flex: 1;
}

.search-name {
    font-weight: 400;
    font-size: 18px;   /* tăng từ 14 */
    margin-bottom: 6px;
}

.search-price {
    color: #e30019;
    font-weight: 600;
    font-size: 18px;   /* tăng từ 13 */
}


.discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #e30019;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    z-index: 2;
}

.rating-box {
    position: absolute;
    bottom: 14px;
    right: 14px;
    background: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 13px;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}

body {
    font-family: Arial;
}

/* LOGIN POPUP */
.login-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
    z-index: 2000;
}

.login-box {
    background: white;
    padding: 25px;
    width: 350px;
    border-radius: 8px;
    position: relative;
}

.login-box input {
    width: 100%;
    margin: 8px 0;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.login-submit {
    width: 100%;
    padding: 10px;
    background: #135071;
    color: white;
    border: none;
    border-radius: 6px;
}

.close-btn {
    position: absolute;
    right: 15px;
    top: 10px;
    cursor: pointer;
    font-size: 20px;
}

/* ===== LEFT FIXED BANNERS ===== */
.left-fixed-banners {
    position: relative;
    top: 50px; /* dính dưới header */
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.left-fixed-banners a {
    display: block;
    width: 100%;
    height: 550px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,.15);
}


.left-fixed-banners img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
/* ===== TOP BANNER SLIDER ===== */
.top-banner-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 16/6; /* tự động co theo tỷ lệ */
    border-radius: 20px;
    overflow: hidden;
}
.top-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity .8s ease;
}

.top-slide.active {
    opacity: 1;
    z-index: 2;
}

.top-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-card {
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    transition: transform .25s ease, box-shadow .25s ease;
}

/* Lift card */
.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 14px 30px rgba(0,0,0,.15);
}

/* ===== IMAGE ZOOM SAFE ===== */
.product-card .product-img {
    overflow: hidden;
    position: relative;
}
.product-card .product-img img {
    transition: transform .35s ease;
    will-change: transform;
}


.product-img-wrapper {
    height: 290px;       /* 👈 ảnh cao hơn */
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}

.product-img {
    max-width: 100%;
    max-height: 90%;
    object-fit: cover;
}

.product-card img[src*="iphone14"],
.product-card img[src*="iphone15"] {
    height: 250px;
    object-fit: cover;
}

.product-card .card-body {
    flex: 1;
    padding: 14px;
}

/* ===== PRODUCT SLIDER ===== */
.product-slider-wrapper {
    position: relative;
}

.product-slider {
    overflow: hidden;
    width: 100%;
}

.product-track {
    display: grid;
    grid-template-rows: repeat(2, auto); /* 2 hàng */
    grid-auto-flow: column;
    grid-auto-columns: calc((100% - 48px) / 4); 
    /* 👆 4 cột / hàng, trừ gap */
    gap: 16px;
    transition: transform .4s ease;
}


/* Card giữ nguyên Bootstrap col */
.product-track .col {
    width: 100%;
}

/* Nút slide */
.slide-btn {
    position: absolute;
    top: 50%;                 /* ↓ hạ xuống so với 45% */
    transform: translateY(-50%);
    width: 40px;              /* ↓ nhỏ hơn */
    height: 40px;             /* ↓ nhỏ hơn */
    border-radius: 50%;
    border: none;
    background: #1A3D64;
    color: #fff;
    font-size: 20px;          /* ↓ icon nhỏ lại */
    cursor: pointer;
    z-index: 10;
    opacity: .85;
}

.slide-btn:hover {
    opacity: 1;
}

.slide-btn.prev { left: -24px; }
.slide-btn.next { right: -40px; }

.product-slider-wrapper {
    padding-left: 16px;   /* đẩy nội dung qua phải */
}

@media (max-width: 768px) {
    .top-banner-slider {
        aspect-ratio: 16/9; /* cao hơn cho mobile */
        margin-bottom: 16px;
    }
}
@media (min-width: 992px) {
    .left-fixed-banners a {
        height: 70vh; /* thay vì px cố định */
    }
}

@media (max-width: 768px) {
    .product-track {
        grid-auto-columns: calc((100% - 16px) / 2); /* 2 cột / hàng */
    }
    .slide-btn {
        display: block;        /* hiển thị nút */
        width: 36px;           /* kích thước nhỏ hơn desktop */
        height: 36px;
        font-size: 20px;
        opacity: 0.85;
    }

    /* Nút prev bên trái */
    .slide-btn.prev {
        left: 8px;             /* cách viền trái màn hình */
        top: 50%;
        transform: translateY(-50%);
    }

    /* Nút next bên phải */
    .slide-btn.next {
        right: 8px;            /* cách viền phải màn hình */
        top: 50%;
        transform: translateY(-50%);
    }
}
@media (max-width: 576px) {
    .search-dropdown {
        left: -12px;
        right: -12px;
        border-radius: 12px;
        padding: 8px 0;
    }
}


</style>

</head>
<body class="bg-light">


<!-- ANNOUNCEMENT BAR -->
<div class="announcement-bar">
  <div class="announcement-track">
    <span>Thu cũ giá ngon – Lên đời tiết kiệm</span>
    <span>Sản phẩm chính hãng – Xuất VAT đầy đủ</span>
    <span>Giao nhanh – Miễn phí cho đơn 300k</span>

    <!-- duplicate để chạy mượt -->
    <span>Thu cũ giá ngon – Lên đời tiết kiệm</span>
    <span>Sản phẩm chính hãng – Xuất VAT đầy đủ</span>
    <span>Giao nhanh – Miễn phí cho đơn 300k</span>
  </div>
</div>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg border-bottom">
  <div class="container d-flex flex-wrap align-items-center">

    <!-- LEFT: LOGO -->
    <a class="navbar-brand d-flex align-items-center fw-bold me-4" href="index.php">
      <img src="assets/images/logo.png" class="logo-img" alt="TechZone Logo">
    </a>

    <!-- MOBILE: TOGGLE MENU BUTTON -->
    <button class="navbar-toggler d-lg-none ms-auto" type="button" id="mobileMenuToggle">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MOBILE: ACCOUNT + CART -->
    <div class="d-flex align-items-center gap-2 d-lg-none ms-2">
      <?php if(!$isLoggedIn): ?>
        <button class="btn btn-outline-primary" onclick="openLogin()">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            <path d="M14 13c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4"/>
          </svg>
        </button>
      <?php else: ?>
        <a href="account.php" class="btn btn-outline-primary"><?= htmlspecialchars($userName) ?></a>
      <?php endif; ?>

      <a href="cart.php" class="btn btn-outline-success position-relative">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
             class="bi bi-bag-check" viewBox="0 0 16 16">
          <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1"/>
          <path d="M2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
        </svg>
        <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
      </a>
    </div>

    <!-- MOBILE MENU DROPDOWN (overlay) -->
    <div id="mobileMenuDropdown" class="position-absolute bg-white shadow-sm w-100 d-lg-none" 
         style="top:60px; left:0; z-index:1050; display:none;">
      <ul class="navbar-nav flex-column gap-2 p-2">
        <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Danh mục</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Liên hệ</a></li>
        <li class="nav-item">
          <?php if($isLoggedIn): ?>
            <a class="nav-link" href="account.php?tab=orders">Tra cứu đơn hàng</a>
          <?php else: ?>
            <a class="nav-link" href="javascript:void(0);" onclick="promptLogin()">Tra cứu đơn hàng</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>

    <!-- RIGHT: MENU + SEARCH + ACTIONS (Desktop) -->
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav flex-column flex-lg-row gap-2 gap-lg-4 me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="category.php">Danh mục</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Liên hệ</a></li>
        <li class="nav-item"><a class="nav-link" href="blog.php">Blogs</a></li>
        <li class="nav-item">
          <?php if($isLoggedIn): ?>
            <a class="nav-link" href="account.php?tab=orders">Tra cứu đơn hàng</a>
          <?php else: ?>
            <a class="nav-link" href="javascript:void(0);" onclick="promptLogin()">Tra cứu đơn hàng</a>
          <?php endif; ?>
        </li>
      </ul>

      <!-- SEARCH DESKTOP -->
      <div class="search-wrapper d-none d-lg-block position-relative me-3">
        <div class="search-box d-flex align-items-center">
          <div class="search-icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
          </div>
          <input type="text" id="searchInput" class="form-control search-input" placeholder="Bạn đang tìm gì?" autocomplete="off">
        </div>
        <div id="searchDropdown" class="search-dropdown"></div>
      </div>

      <!-- ACCOUNT / CART -->
      <div class="d-flex align-items-center gap-2">
        <?php if(!$isLoggedIn): ?>
          <button class="btn btn-outline-primary" onclick="openLogin()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-person" viewBox="0 0 16 16">
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
              <path d="M14 13c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4"/>
            </svg>
          </button>
        <?php else: ?>
          <a href="account.php" class="btn btn-outline-primary"><?= htmlspecialchars($userName) ?></a>
        <?php endif; ?>

        <a href="cart.php" class="btn btn-outline-success position-relative">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               class="bi bi-bag-check" viewBox="0 0 16 16">
            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1"/>
            <path d="M2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
          </svg>
          <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
        </a>
      </div>
    </div>

  
</nav>


<!-- ===== TOP BANNER SLIDER ===== -->
<div class="container my-3">
    <div class="top-banner-slider">

        <?php while($b = $topBanners->fetch_assoc()): ?>
            <a href="<?= $b['link'] ?>" class="top-slide">
                <img src="<?= $b['image_url'] ?>" alt="<?= $b['title'] ?>">
            </a>
        <?php endwhile; ?>

    </div>
</div>




<div class="container py-4">
  <div class="row">

    <!-- LEFT FIXED BANNERS -->
    <div class="col-lg-2 d-none d-lg-block left-col">
        <div class="left-fixed-banners">
            <?php while($b = $leftBanners->fetch_assoc()): ?>
                <a href="<?= $b['link'] ?>" class="left-banner">
                    <img src="<?= $b['image_url'] ?>" alt="<?= $b['title'] ?>">
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="col-12 col-md-9 col-lg-10">
            <!-- LAPTOP -->
            <section id="laptop-section">
                <h2 class="fw-bold mb-3">Laptop</h2>
                <div class="d-flex gap-2 mb-2">
                    <button class="btn btn-outline-primary laptop-cat active-filter" data-id="">Tất cả</button>
                    <?php while($c=$laptop_categories->fetch_assoc()): ?>
                        <button class="btn btn-outline-primary laptop-cat" data-id="<?= $c['id'] ?>">
                            <?= $c['name'] ?>
                        </button>
                    <?php endwhile; ?>
                </div>

                <div class="product-slider-wrapper">
    <button class="slide-btn prev" data-target="laptop">‹</button>

    <div class="product-slider">
        <div id="laptop-products" class="product-track"></div>
    </div>

    <button class="slide-btn next" data-target="laptop">›</button>
</div>

            </section>

            <!-- ĐIỆN THOẠI -->
            <section id="phone-section">
                <h2 class="fw-bold mt-5 mb-3">Điện thoại</h2>

                <div class="d-flex gap-2 mb-4">
                    <button class="btn btn-outline-primary phone-cat phone-cat-all" data-id="">
                        Điện thoại
                    </button>
                    <?php while($c=$phone_categories->fetch_assoc()): ?>
                        <button class="btn btn-outline-primary phone-cat" data-id="<?= $c['id'] ?>">
                            <?= $c['name'] ?>
                        </button>
                    <?php endwhile; ?>
                </div>

                <div class="product-slider-wrapper">
    <button class="slide-btn prev" data-target="phone">‹</button>

    <div class="product-slider">
        <div id="phone-products" class="product-track"></div>
    </div>

    <button class="slide-btn next" data-target="phone">›</button>
</div>

            </section>

        </div>
    </div>
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

    <!-- Link quên mật khẩu -->
    <div class="text-center mt-2">
        <small>
            <a href="reset_password.php" class="fw-bold text-danger">Bạn quên mật khẩu?</a>
        </small>
    </div>
  </div>
</div>


<!-- FOOTER -->
<footer class="footer mt-5">
  <div class="container py-5">
    <div class="row g-4">

      <!-- COL 1: BRAND -->
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-center gap-2 mb-3">
          <img src="assets/images/logo.png" alt="TechZone" height="36">

        </div>
        <p class="text-muted mb-2">
          Hệ thống bán lẻ thiết bị công nghệ chính hãng: Laptop, Điện thoại,
          Phụ kiện – Giá tốt, bảo hành minh bạch.
        </p>
        <small class="text-muted">
          © 2025 TechZone. All rights reserved.
        </small>
      </div>

      <!-- COL 2: POLICY -->
      <div class="col-lg-2 col-md-6">
        <h6 class="fw-semibold mb-3">Chính sách</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="/techzone/blog/policies.php#warranty">Chính sách bảo hành</a></li>
          <li><a href="/techzone/blog/policies.php#return">Chính sách đổi trả</a></li>
          <li><a href="/techzone/blog/policies.php#shipping">Chính sách vận chuyển</a></li>
          <li><a href="/techzone/blog/policies.php#payment">Chính sách thanh toán</a></li>
        </ul>
      </div>

      <!-- COL 3: SUPPORT -->
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-semibold mb-3">Hỗ trợ khách hàng</h6>
        <ul class="list-unstyled footer-links">
          <li>Hotline: <strong>1800 9999</strong></li>
          <li>Email: support@techzone.vn</li>
          <li><a href="contact.php">Câu hỏi thường gặp (FAQ)</a></li>
          <li><a href="contact.php">Liên hệ</a></li>
        </ul>
      </div>

      <!-- COL 4: SOCIAL -->
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-semibold mb-3">Kết nối với TechZone</h6>
        <div class="d-flex gap-3 mb-3">
        
          <a href="https://facebook.com/techzone" 
            class="social-icon" 
            target="_blank"
            aria-label="Facebook TechZone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
            </svg>
            </a>
            
            <a href="#" 
            class="social-icon" 
            target="_blank"
            aria-label="Instagram TechZone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
            </svg>
            </a>


            <a href="#" 
            class="social-icon" 
            target="_blank"
            aria-label="Youtube TechZone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/>
            </svg>
            </a>

        </div>

        <div>
          <small class="text-muted d-block mb-2">Phương thức thanh toán</small>
          <div class="d-flex gap-2">
            <img src="assets\images\Zalopay.png" height="26">
            <img src="assets\images\Apple_Pay_logo.svg.png" height="26">
            <img src="assets\images\Logo-VNPAY-QR-1.webp" height="26">
          </div>
        </div>
      </div>

    </div>
  </div>
</footer>

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

const sliderState = {
    laptop: 0,
    phone: 0
};

function slide(section, dir) {
    const track = document.querySelector(`#${section}-products`);
    if (!track) return;

    const colsPerView = 4;
    const totalCols = track.children.length / 2;

    sliderState[section] += dir;

    if (sliderState[section] < 0) sliderState[section] = 0;
    if (sliderState[section] > totalCols - colsPerView)
        sliderState[section] = totalCols - colsPerView;

    const percent = (sliderState[section] * 100) / colsPerView;
    track.style.transform = `translateX(-${percent}%)`;
}


document.querySelectorAll(".slide-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        const section = btn.dataset.target;
        slide(section, btn.classList.contains("next") ? 1 : -1);
    });
});


// Laptop
let laptopCat="", laptopBrand="";
$(".laptop-cat").click(function(){
    laptopCat=$(this).data("id");
    sliderState.laptop = 0;
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
    sliderState.phone = 0;
    loadSection("phone", phoneCat, ""); // load tất cả điện thoại
    $(".phone-cat").removeClass("active-filter"); 
    $(this).addClass("active-filter");
});

loadSection("phone", phoneCat, "");





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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let typingTimer;
const delay = 300;

$("#searchInput").on("keyup", function () {
    clearTimeout(typingTimer);
    let keyword = $(this).val().trim();

    if (keyword.length < 2) {
        $("#searchDropdown").hide();
        return;
    }

    typingTimer = setTimeout(() => {
        $.getJSON("search_suggest.php", { q: keyword }, function (data) {
            let html = "";

            if (data.length === 0) {
                html = `<div class="search-empty">Không tìm thấy sản phẩm</div>`;
            } else {
                data.forEach(item => {
                    html += `
    <div class="search-item" onclick="goProduct(${item.id})">
        <img 
            src="${item.image ?? 'assets/no-image.png'}"
            class="search-thumb"
        >
        <div class="search-info">
            <div class="search-name">
                ${highlight(item.name, keyword)}
            </div>
            <div class="search-price">
                ${Number(item.price).toLocaleString('vi-VN')}₫
            </div>
        </div>
    </div>
`;

                });
            }

            $("#searchDropdown").html(html).show();
        });
    }, delay);
});

function goProduct(id) {
    window.location.href = "product.php?id=" + id;
}

function highlight(text, keyword) {
    const regex = new RegExp(`(${keyword})`, "gi");
    return text.replace(regex, "<b>$1</b>");
}

$(document).click(function (e) {
    if (!$(e.target).closest(".search-wrapper").length) {
        $("#searchDropdown").hide();
    }
});
</script>
<script>
$(document).ready(function(){
    let slides = $(".top-slide");
    if (slides.length <= 1) return;

    let i = 0;
    slides.eq(0).addClass("active");

    setInterval(() => {
        slides.eq(i).removeClass("active");
        i = (i + 1) % slides.length;
        slides.eq(i).addClass("active");
    }, 4000);
});
</script>


<script>
function promptLogin() {
    alert("Hãy đăng nhập để tra cứu đơn hàng"); // thông báo
    openLogin(); // mở popup đăng nhập
}
</script>


<script>
  // Toggle menu mobile overlay
  const toggleBtn = document.getElementById('mobileMenuToggle');
  const menuDropdown = document.getElementById('mobileMenuDropdown');

  toggleBtn.addEventListener('click', () => {
    if(menuDropdown.style.display === 'none' || menuDropdown.style.display === ''){
      menuDropdown.style.display = 'block';
    } else {
      menuDropdown.style.display = 'none';
    }
  });

  // Optional: click outside để đóng menu
  document.addEventListener('click', function(e){
    if(!menuDropdown.contains(e.target) && !toggleBtn.contains(e.target)){
      menuDropdown.style.display = 'none';
    }
  });
</script>




</body>
</html>

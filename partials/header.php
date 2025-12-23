<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
?>



<!-- HEADER -->
<nav class="navbar navbar-expand-lg border-bottom">
  <div class="container d-flex align-items-center">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center fw-bold me-4" href="/techzone/index.php">
      <img src="/techzone/assets/images/logo.png" class="logo-img" alt="TechZone Logo">
    </a>

    <!-- RIGHT -->
    <div class="d-flex align-items-center gap-4 ms-auto">

      <!-- MENU -->
      <ul class="navbar-nav flex-row gap-4 d-none d-lg-flex">
        <li class="nav-item"><a class="nav-link" href="/techzone/index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Danh mục</a></li>
        <li class="nav-item"><a class="nav-link" href="/techzone/blog.php">Liên hệ</a></li>
        <li class="nav-item"><a class="nav-link" href="/techzone/blog.php">Blogs</a></li>
        <li class="nav-item"><a class="nav-link" href="/techzone/account.php?tab=orders">Tra cứu đơn hàng</a></li>
      </ul>

      <!-- SEARCH -->
      <div class="search-box d-flex align-items-center">
        <div class="search-icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
          </svg>
        </div>
        <input class="form-control search-input" placeholder="Bạn đang tìm gì?">
      </div>

      <!-- USER -->
      <?php if (!$isLoggedIn): ?>
        <button class="btn btn-outline-primary header-action-btn" onclick="openLogin()">
         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
  <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
</svg>
        </button>
      <?php else: ?>
        <a href="account.php" class="btn btn-outline-primary header-action-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
  <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
</svg>
        </a>
      <?php endif; ?>

      <!-- CART -->
      <a href="cart.php" class="btn btn-outline-success header-action-btn position-relative">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1"/>
          <path d="M2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
        </svg>
        <span id="cartCount"
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
      </a>

    </div>
  </div>
</nav>

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
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đánh giá chi tiết iPhone 15 Pro Max - TechZone</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            prefix: "tw-", // Quan trọng: Thêm prefix để tránh đụng độ với Bootstrap
            corePlugins: {
               preflight: false, // Tắt reset mặc định của Tailwind để không hỏng Bootstrap
            },
            theme: {
                extend: {
                    colors: {
                        "primary": "#1a3d65",
                        "accent-red": "#e30019",
                        "background-light": "#f6f7f8",
                        "background-dark": "#13191f",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e242b",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>

    <style>
        body { font-family: Arial, sans-serif; }
        
        /* CSS Header/Footer gốc */
        .announcement-bar { background: #1A3D64; color: #fff; font-size: 14px; font-weight: 500; overflow: hidden; white-space: nowrap; }
        .announcement-track { display: inline-flex; align-items: center; gap: 48px; padding: 8px 0; animation: marquee 18s linear infinite; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        
        .navbar { background: linear-gradient(90deg, #EEF4FA, #F8FAFC); border-bottom: 1px solid #D6E0EA; }
        .navbar .nav-link { color: #0F172A !important; font-weight: 500; }
        .navbar .nav-link:hover { color: #1A3D64 !important; }
        .navbar-brand { height: 64px; padding: 0; margin: 0; display: flex; align-items: center; }
        .logo-img { height: 50px; width: auto; object-fit: contain; }
        
        /* Search Box */
        .search-box { min-width: 350px; }
        .search-icon-box { width: 44px; height: 44px; border: 1px solid #dbe3ec; border-right: none; border-radius: 8px 0 0 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #1A3D64; cursor: pointer; }
        .search-icon-box svg { width: 18px; height: 18px; }
        .search-input { height: 44px; border-radius: 0 8px 8px 0; border-left: none; }
        .search-input:focus { box-shadow: none; border-color: #1A3D64; }
        .search-dropdown { position: absolute; top: 110%; left: 0; right: 0; background: #fff; border-radius: 16px; box-shadow: 0 16px 40px rgba(0,0,0,0.2); z-index: 99999; display: none; padding: 12px 0; }
        .search-item { display: flex; align-items: center; gap: 16px; padding: 16px 20px; cursor: pointer; transition: background .15s ease; }
        .search-item:hover { background: #f7f7f7; }
        .search-thumb { width: 64px; height: 64px; border-radius: 8px; object-fit: cover; }
        .search-name { font-size: 15px; font-weight: 500; color: #333; }
        .search-price { color: #e30019; font-weight: 600; font-size: 14px; }

        /* Login Modal */
        .login-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); justify-content:center; align-items:center; z-index:2000; }
        .login-box { background:#fff; padding:25px; width:350px; border-radius:8px; position:relative; }
        .login-box input { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:5px; }
        .login-submit { width:100%; padding:10px; background:#135071; color:#fff; border:none; border-radius:6px; cursor:pointer; }
        .close-btn { position:absolute; right:15px; top:10px; cursor:pointer; font-size:20px; }

        /* Footer */
        .footer { background: linear-gradient(90deg, #EEF4FA, #F8FAFC); border-top: 1px solid #E2E8F0; }
        .footer h6 { color: #0F172A; }
        .footer-links a { color: #475569; text-decoration: none; transition: 0.2s; }
        .footer-links a:hover { color: #1A3D64; }
        .social-icon { width: 36px; height: 36px; border-radius: 8px; background: #E2E8F0; color: #1A3D64; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.2s; }
        .social-icon:hover { background: #1A3D64; color: #fff; }

        /* Fix conflict Tailwind inside Bootstrap */
        .tw-prose img { margin: 0 auto; border-radius: 12px; }
        /* Style riêng cho nội dung bài viết để override bootstrap container */
        .article-container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
    </style>
</head>
<body class="bg-light">

    <div class="announcement-bar">
        <div class="announcement-track">
            <span>Thu cũ giá ngon – Lên đời tiết kiệm</span>
            <span>Sản phẩm chính hãng – Xuất VAT đầy đủ</span>
            <span>Giao nhanh – Miễn phí cho đơn 300k</span>
            <span>Thu cũ giá ngon – Lên đời tiết kiệm</span>
            <span>Sản phẩm chính hãng – Xuất VAT đầy đủ</span>
            <span>Giao nhanh – Miễn phí cho đơn 300k</span>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg border-bottom sticky-top">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center fw-bold me-4" href="index.php">
                <img src="assets/images/logo.png" class="logo-img" alt="TechZone Logo">
            </a>

            <div class="d-flex align-items-center gap-4 ms-auto">
                <ul class="navbar-nav flex-row gap-4 d-none d-lg-flex">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Danh mục</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Liên hệ</a></li>
                    <!-- <li class="nav-item"><a class="nav-link active" href="blog.php">Blog công nghệ</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="account.php?tab=orders">Tra cứu đơn hàng</a></li>
                </ul>

                <div class="search-wrapper" style="position:relative;">
                    <div class="search-box d-flex align-items-center">
                        <div class="search-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg>
                        </div>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Bạn đang tìm gì?" autocomplete="off">
                    </div>
                    <div id="searchDropdown" class="search-dropdown"></div>
                </div>

                <?php if(!$isLoggedIn): ?>
                    <button class="btn btn-outline-primary" onclick="openLogin()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/><path d="M14 13c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4"/></svg>
                    </button>
                <?php else: ?>
                    <a href="account.php" class="btn btn-outline-primary"><?= htmlspecialchars($userName) ?></a>
                <?php endif; ?>

                <a href="cart.php" class="btn btn-outline-success position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-check" viewBox="0 0 16 16"><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1"/><path d="M2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/></svg>
                    <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="tw-flex-grow tw-bg-white tw-border-b tw-border-[#f1f2f4]">
        <div class="tw-bg-white tw-border-b tw-border-[#f1f2f4]">
            <div class="tw-px-4 md:tw-px-10 lg:tw-px-40 tw-py-4 tw-max-w-[1440px] tw-mx-auto">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2 tw-text-sm">
                    <a class="tw-text-[#677483] hover:tw-text-primary tw-transition-colors" href="index.php">Trang chủ</a>
                    <span class="material-symbols-outlined tw-text-[14px] tw-text-[#9aa2ac]">chevron_right</span>
                    <a class="tw-text-[#677483] hover:tw-text-primary tw-transition-colors" href="blog.php">Blog công nghệ</a>
                    <span class="material-symbols-outlined tw-text-[14px] tw-text-[#9aa2ac]">chevron_right</span>
                    <span class="tw-text-[#121417] tw-font-medium tw-line-clamp-1">Đánh giá chi tiết iPhone 15 Pro Max</span>
                </div>
            </div>
        </div>

        <div class="tw-px-4 md:tw-px-10 lg:tw-px-40 tw-py-10 tw-max-w-[1440px] tw-mx-auto">
            <div class="tw-max-w-4xl tw-mx-auto">
                <header class="tw-mb-8">
                    <div class="tw-flex tw-items-center tw-gap-3 tw-mb-4">
                        <span class="tw-bg-accent-red tw-text-white tw-text-xs tw-font-bold tw-px-3 tw-py-1.5 tw-rounded-full tw-shadow-sm">Review</span>
                        <span class="tw-text-[#677483] tw-text-sm tw-font-medium">5 phút đọc</span>
                    </div>
                    <h1 class="tw-text-3xl md:tw-text-4xl lg:tw-text-5xl tw-font-black tw-text-primary tw-leading-tight tw-mb-6">
                        Đánh giá chi tiết iPhone 15 Pro Max: Đỉnh cao khung viền Titan và sức mạnh A17 Pro
                    </h1>
                    <div class="tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center tw-justify-between tw-gap-4 tw-border-t tw-border-b tw-border-[#f1f2f4] tw-py-4">
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <div class="tw-size-10 tw-rounded-full tw-bg-gray-200 tw-overflow-hidden">
                                <div class="tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center tw-bg-primary tw-text-white tw-font-bold" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">TZ</div>
                            </div>
                            <div>
                                <div class="tw-font-bold tw-text-[#121417] tw-text-sm">TechZone Editor</div>
                                <div class="tw-text-[#677483] tw-text-xs">Biên tập viên công nghệ</div>
                            </div>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-6 tw-text-[#677483] tw-text-sm">
                            <div class="tw-flex tw-items-center tw-gap-1.5">
                                <span class="material-symbols-outlined tw-text-[18px]">calendar_today</span>
                                <span>20 Tháng 10, 2023</span>
                            </div>
                            <div class="tw-flex tw-items-center tw-gap-1.5">
                                <span class="material-symbols-outlined tw-text-[18px]">visibility</span>
                                <span>15.2k xem</span>
                            </div>
                            <button class="tw-flex tw-items-center tw-gap-1.5 hover:tw-text-primary tw-transition-colors">
                                <span class="material-symbols-outlined tw-text-[18px]">share</span>
                                <span>Chia sẻ</span>
                            </button>
                        </div>
                    </div>
                </header>

                <div class="tw-mb-10 tw-rounded-xl tw-overflow-hidden tw-shadow-lg">
                    <img alt="iPhone 15 Pro Max Titanium" class="tw-w-full tw-h-auto tw-object-cover" src="https://pos.nvncdn.com/a135ac-81120/ps/20240126_1xIv7aEwEj.jpeg?v=1706258255/">
                    <p class="tw-text-center tw-text-sm tw-text-[#677483] tw-mt-2 tw-italic">iPhone 15 Pro Max với thiết kế khung viền Titanium mới.</p>
                </div>

                <article class="tw-prose tw-prose-lg tw-max-w-none tw-text-[#121417]">
                    <p class="tw-text-lg md:tw-text-xl tw-font-medium tw-leading-relaxed tw-mb-8 tw-text-[#121417]">
                        Sau bao ngày chờ đợi, cuối cùng siêu phẩm iPhone 15 Pro Max cũng đã chính thức cập bến TechZone. Với những thay đổi mang tính cách mạng về vật liệu thiết kế, cổng kết nối USB-C và đặc biệt là con chip A17 Pro 3nm, liệu đây có phải là chiếc smartphone hoàn hảo nhất năm 2023? Hãy cùng chúng tôi đi vào chi tiết.
                    </p>

                    <h2 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-primary tw-mt-10 tw-mb-5">1. Thiết kế Titanium: Nhẹ hơn, Đẳng cấp hơn</h2>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        Điểm ấn tượng đầu tiên khi cầm iPhone 15 Pro Max trên tay chính là trọng lượng. Nhờ chuyển sang sử dụng khung viền <strong>Titanium Grade 5</strong> (chuẩn hàng không vũ trụ), máy nhẹ hơn đáng kể so với người tiền nhiệm 14 Pro Max (221g so với 240g). Sự chênh lệch 19g nghe có vẻ nhỏ, nhưng trải nghiệm cầm nắm thực tế lại khác biệt hoàn toàn, đặc biệt khi sử dụng bằng một tay hoặc cầm máy trong thời gian dài để chơi game, lướt web.
                    </p>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        Các cạnh viền cũng được Apple bo cong nhẹ, không còn sắc cạnh như các đời trước, giúp cảm giác cầm máy trần cực kỳ êm ái và không bị cấn tay. Viền màn hình mỏng nhất từ trước đến nay cũng tạo nên hiệu ứng thị giác vô cùng ấn tượng.
                    </p>

                    <div class="tw-bg-[#EEF4FA] tw-p-6 tw-rounded-lg tw-border-l-4 tw-border-primary tw-my-8">
                        <h4 class="tw-font-bold tw-text-lg tw-text-primary tw-mb-2">Nút Action Button - Thay đổi thói quen sử dụng</h4>
                        <p class="tw-text-sm md:tw-text-base tw-text-[#677483]">
                            Cần gạt rung truyền thống đã chính thức bị loại bỏ để nhường chỗ cho nút Action Button đa năng. Người dùng có thể gán nhiều chức năng khác nhau như: Mở Camera, Bật đèn pin, Ghi âm, Dịch thuật, hoặc chạy các phím tắt (Shortcut) phức tạp. Đây là một nâng cấp nhỏ nhưng mang lại sự tiện lợi rất lớn trong quá trình sử dụng hàng ngày.
                        </p>
                    </div>

                    <h2 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-primary tw-mt-10 tw-mb-5">2. Hiệu năng A17 Pro: Quái vật trong làng chip di động</h2>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        Apple A17 Pro là con chip đầu tiên trên thế giới được sản xuất trên tiến trình <strong>3nm</strong>. Điều này không chỉ giúp tăng hiệu năng CPU lên 10% và GPU lên 20% mà còn tối ưu hóa điện năng tiêu thụ.
                    </p>
                    <div class="tw-my-8 tw-rounded-xl tw-overflow-hidden">
                        <img alt="Chip A17 Pro" class="tw-w-full tw-h-auto tw-object-cover tw-rounded-xl tw-shadow-md" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-2c0KYlHGtnEkLfjaitlFNqGl5v73u_hsKBZl6ZYqYpi82J6wWqwzT8uflnhAkwtZVyiYRGJlLwj1aGSletzR58L07PnWWbWYHoRPoFEV366KkBwnjptpqjp2V5WkA0MhITTa73yDYR1m84skgGHn1xG8YgCPsLIkFIqjA7R6pfWrACrnO6HvXrpjDXLdlWDd2L0f6eOFXeKRNSVWHgKCc3a41THc6iz2IO3nbQBXl7BhSPtyTaM6dZO6lrQ9GO1E62Z-t2yfWkY"/>
                        <p class="tw-text-center tw-text-sm tw-text-[#677483] tw-mt-2 tw-italic">A17 Pro mang lại khả năng chơi game Console ngay trên điện thoại.</p>
                    </div>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        Điểm nhấn lớn nhất của A17 Pro là khả năng xử lý Ray Tracing bằng phần cứng nhanh hơn gấp 4 lần so với A16 Bionic. Nhờ đó, iPhone 15 Pro Max có thể chơi mượt mà các tựa game AAA vốn chỉ dành cho PC/Console như <em>Resident Evil Village</em>, <em>Resident Evil 4 Remake</em> hay <em>Assassin's Creed Mirage</em>. Trải nghiệm thực tế cho thấy máy xử lý đồ họa cực kỳ mượt mà, khung hình ổn định và nhiệt độ được kiểm soát tốt hơn mong đợi.
                    </p>

                    <h2 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-primary tw-mt-10 tw-mb-5">3. Camera: Bước tiến lớn với Zoom quang 5x</h2>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        Hệ thống camera trên iPhone 15 Pro Max vẫn giữ nguyên độ phân giải 48MP cho cảm biến chính nhưng đã được nâng cấp thuật toán xử lý ảnh Photonic Engine và Smart HDR 5. Ảnh chụp ra có độ chi tiết cao hơn, màu sắc chân thực và dải nhạy sáng động (Dynamic Range) rộng hơn.
                    </p>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        Đặc biệt, camera Telephoto sử dụng thiết kế lăng kính tứ diện (tetraprism) cho phép zoom quang học lên đến <strong>5x</strong> (tương đương tiêu cự 120mm), vượt trội so với mức 3x trên dòng Pro thường. Khả năng chống rung quang học OIS 3D sensor-shift giúp các bức ảnh zoom xa vẫn sắc nét và ổn định, rất hữu ích khi chụp chân dung xóa phông từ xa hoặc chụp phong cảnh, sân khấu.
                    </p>

                    <h2 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-primary tw-mt-10 tw-mb-5">Tổng kết</h2>
                    <p class="tw-mb-6 tw-leading-7 tw-text-base md:tw-text-lg">
                        iPhone 15 Pro Max là một bản nâng cấp toàn diện và đáng giá. Thiết kế Titanium nhẹ bền, hiệu năng A17 Pro đỉnh cao, camera zoom 5x chất lượng và sự tiện lợi của cổng USB-C biến nó trở thành chiếc flagship đáng sở hữu nhất hiện nay. Nếu bạn đang tìm kiếm trải nghiệm công nghệ đỉnh cao nhất, iPhone 15 Pro Max chắc chắn sẽ không làm bạn thất vọng.
                    </p>
                </article>

                <div class="tw-mt-10 tw-flex tw-flex-wrap tw-gap-2">
                    <a class="tw-bg-[#f1f2f4] tw-text-[#677483] tw-px-3 tw-py-1.5 tw-rounded tw-text-sm hover:tw-bg-primary hover:tw-text-white tw-transition-colors" href="#">#iPhone15ProMax</a>
                    <a class="tw-bg-[#f1f2f4] tw-text-[#677483] tw-px-3 tw-py-1.5 tw-rounded tw-text-sm hover:tw-bg-primary hover:tw-text-white tw-transition-colors" href="#">#Apple</a>
                    <a class="tw-bg-[#f1f2f4] tw-text-[#677483] tw-px-3 tw-py-1.5 tw-rounded tw-text-sm hover:tw-bg-primary hover:tw-text-white tw-transition-colors" href="#">#Review</a>
                    <a class="tw-bg-[#f1f2f4] tw-text-[#677483] tw-px-3 tw-py-1.5 tw-rounded tw-text-sm hover:tw-bg-primary hover:tw-text-white tw-transition-colors" href="#">#CongNghe</a>
                </div>
            </div>

            <div class="tw-mt-20 tw-rounded-2xl tw-bg-[#EEF4FA] tw-p-8 md:tw-p-12 tw-relative tw-overflow-hidden">
                <div class="tw-absolute tw-top-0 tw-right-0 tw-p-10 tw-opacity-10 tw-pointer-events-none">
                    <span class="material-symbols-outlined tw-text-[300px] tw-text-primary">rss_feed</span>
                </div>
                <div class="tw-relative tw-z-10 tw-max-w-2xl tw-mx-auto tw-text-center md:tw-text-left">
                    <h2 class="tw-text-2xl md:tw-text-3xl tw-font-black tw-text-primary tw-mb-4">Đăng ký nhận tin tức công nghệ</h2>
                    <p class="tw-text-[#677483] tw-mb-8">
                        Đừng bỏ lỡ những bài đánh giá chi tiết và tin tức công nghệ nóng hổi nhất. Chúng tôi sẽ gửi email cập nhật hàng tuần cho bạn.
                    </p>
                    <form class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-3">
                        <input class="tw-flex-1 tw-px-4 tw-py-3 tw-rounded-lg tw-border tw-border-[#dde0e4] focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-primary focus:tw-border-primary" placeholder="Nhập địa chỉ email của bạn" type="email"/>
                        <button class="tw-bg-accent-red hover:tw-bg-[#c90016] tw-text-white tw-font-bold tw-px-6 tw-py-3 tw-rounded-lg tw-transition-colors tw-shadow-lg tw-shadow-red-900/20 tw-whitespace-nowrap">
                            Đăng ký ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div id="loginModal" class="login-modal">
        <div class="login-box">
            <span class="close-btn" onclick="closeLogin()">×</span>
            <h3 class="mb-3">Đăng nhập</h3>
            <form method="POST" action="login.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit" class="login-submit">Đăng nhập</button>
            </form>
            <div class="text-center mt-2">
                <small>Bạn chưa có tài khoản? <a href="register.php" class="fw-bold text-primary">Đăng ký ngay</a></small>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="assets/images/logo.png" alt="TechZone" height="36">
                    </div>
                    <p class="text-muted mb-2">Hệ thống bán lẻ thiết bị công nghệ chính hãng: Laptop, Điện thoại, Phụ kiện – Giá tốt, bảo hành minh bạch.</p>
                    <small class="text-muted">© 2025 TechZone. All rights reserved.</small>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-semibold mb-3">Chính sách</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="blog/policies.php#warranty">Chính sách bảo hành</a></li>
                        <li><a href="blog/policies.php#return">Chính sách đổi trả</a></li>
                        <li><a href="blog/policies.php#shipping">Chính sách vận chuyển</a></li>
                        <li><a href="blog/policies.php#payment">Chính sách thanh toán</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Hỗ trợ khách hàng</h6>
                    <ul class="list-unstyled footer-links">
                        <li>Hotline: <strong>1800 9999</strong></li>
                        <li>Email: support@techzone.vn</li>
                        <li><a href="#">Câu hỏi thường gặp (FAQ)</a></li>
                        <li><a href="contact.php">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Kết nối với TechZone</h6>
                    <div class="d-flex gap-3 mb-3">
                        <a href="https://facebook.com/techzone" class="social-icon" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/></svg></a>
                        <a href="#" class="social-icon" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg></a>
                        <a href="#" class="social-icon" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/></svg></a>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-2">Phương thức thanh toán</small>
                        <div class="d-flex gap-2">
                             <img src="assets/images/Zalopay.png" height="26">
                            <img src="assets/images/Apple_Pay_logo.svg.png" height="26">
                            <img src="assets/images/Logo-VNPAY-QR-1.webp" height="26">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS của Index.php
        function openLogin(){ document.getElementById("loginModal").style.display="flex"; }
        function closeLogin(){ document.getElementById("loginModal").style.display="none"; }
        window.onclick=function(e){ let modal=document.getElementById("loginModal"); if(e.target===modal) modal.style.display="none"; }

        // Cart Count
        $(document).ready(function(){
            $.get("cart_count.php", function(count){ $("#cartCount").text(count); });
        });

        // Search
        let typingTimer;
        const delay = 300;
        $("#searchInput").on("keyup", function () {
            clearTimeout(typingTimer);
            let keyword = $(this).val().trim();
            if (keyword.length < 2) { $("#searchDropdown").hide(); return; }
            typingTimer = setTimeout(() => {
                $.getJSON("search_suggest.php", { q: keyword }, function (data) {
                    let html = "";
                    if (data.length === 0) { html = `<div class="search-empty text-center p-3 text-muted">Không tìm thấy sản phẩm</div>`; } 
                    else {
                        data.forEach(item => {
                            html += `<div class="search-item" onclick="window.location.href='product.php?id=${item.id}'">
                                        <img src="${item.image ?? 'assets/no-image.png'}" class="search-thumb">
                                        <div class="search-info">
                                            <div class="search-name">${item.name}</div>
                                            <div class="search-price">${Number(item.price).toLocaleString('vi-VN')}₫</div>
                                        </div>
                                    </div>`;
                        });
                    }
                    $("#searchDropdown").html(html).show();
                });
            }, delay);
        });
        $(document).click(function(e){ if(!$(e.target).closest(".search-wrapper").length) $("#searchDropdown").hide(); });
    </script>
</body>
</html>
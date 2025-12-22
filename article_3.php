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
    <title>Xu hướng công nghệ 2024: AI, Metaverse và hơn thế nữa - TechZone</title>
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
            prefix: "tw-", 
            corePlugins: {
               preflight: false, 
            },
            theme: {
                extend: {
                    colors: {
                        "primary": "#1a3d65", // TechZone Blue
                        "accent-red": "#e30019",
                        "secondary": "#1A3D64",
                        "background-light": "#f6f7f8",
                        "background-dark": "#13191f",
                        "tech-blue": "#1A3D64",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>

    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc;}
        
        /* CSS Header/Footer */
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

        /* Styles for Article Content */
        .tw-prose img { margin: 0 auto; border-radius: 12px; }
        /* Style riêng cho nội dung bài viết để override bootstrap container */
        .article-content p { margin-bottom: 1.5rem; line-height: 1.75; color: #334155; }
        .article-content h2 { font-size: 1.875rem; font-weight: 700; color: #1A3D64; margin-top: 2.5rem; margin-bottom: 1rem; }
        .article-content h3 { font-size: 1.5rem; font-weight: 600; color: #1A3D64; margin-top: 2rem; margin-bottom: 0.75rem; }
        .article-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.5rem; color: #334155; }
        .article-content li { margin-bottom: 0.5rem; }
        .article-content blockquote { border-left: 4px solid #e30019; padding-left: 1rem; margin: 2rem 0; font-style: italic; color: #475569; background-color: #f1f5f9; padding: 1.5rem; border-radius: 0 0.5rem 0.5rem 0; }
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
                    <span class="tw-text-[#121417] tw-font-medium tw-line-clamp-1">Xu hướng công nghệ 2024</span>
                </div>
            </div>
        </div>

        <div class="tw-px-4 md:tw-px-10 lg:tw-px-40 tw-py-10 tw-max-w-[1440px] tw-mx-auto">
            <div class="tw-max-w-4xl tw-mx-auto">
                
                <div class="tw-mb-6">
                    <h1 class="tw-text-3xl md:tw-text-4xl lg:tw-text-[2.75rem] tw-font-black tw-leading-tight tw-tracking-tight tw-text-primary tw-mb-4">
                        Xu hướng công nghệ 2024: AI, Metaverse và hơn thế nữa
                    </h1>
                    <p class="tw-text-lg tw-text-[#64748b] tw-leading-relaxed">
                        Cập nhật những thay đổi đột phá sẽ định hình lại thế giới công nghệ trong năm nay. Từ trí tuệ nhân tạo tạo sinh đến thực tế ảo mở rộng.
                    </p>
                </div>

                <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-4 tw-mb-8 tw-text-sm tw-text-[#64748b] tw-border-b tw-border-[#e2e8f0] tw-pb-6">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <div class="tw-size-8 tw-rounded-full tw-bg-center tw-bg-cover tw-border tw-border-gray-200" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAGYUCUaILlt6uZiKNTXwhOZRFwQqBAOj3ae4Iai9qzEddBM6R6qayKTr0A-d0wAwluqQiCmFrDcU0jatYwlgG7D9PGaqfc3vPSM-HtFbYFdRhwmp82yEVJp2xmChkG1VuwzsEy1cXXrCh0lI_Rx63AGfAAIG9XmlgrYi-6PwXeaCETcZ4oYHW3BdM50MTamkSMTPkxvKoe2B6cXyOSTMYRgVN0IV1rB3NSCrSpvPHjWcBDEKEgKk3cngP0rPSvyq1tWh-EjtOOjEc");'></div>
                        <span class="tw-font-medium tw-text-slate-700">Nguyễn Văn A</span>
                    </div>
                    <span class="tw-text-slate-300">•</span>
                    <div class="tw-flex tw-items-center tw-gap-1">
                        <span class="material-symbols-outlined tw-text-[18px]">calendar_today</span>
                        <span>15/01/2024</span>
                    </div>
                    <span class="tw-text-slate-300">•</span>
                    <div class="tw-flex tw-items-center tw-gap-1">
                        <span class="material-symbols-outlined tw-text-[18px]">schedule</span>
                        <span>5 phút đọc</span>
                    </div>
                </div>

                <div class="tw-w-full tw-mb-10 tw-rounded-xl tw-overflow-hidden tw-shadow-lg tw-bg-gray-100">
                    <div class="tw-aspect-video tw-w-full tw-bg-center tw-bg-cover hover:tw-scale-105 tw-transition-transform tw-duration-700" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBuwtKnj7wpVg2Czk-t4lVUu5E5UkFITnkDrz0AhCcRyvkG_tUWeuJ_fxIMfYKMZTrZxMYLMTBueUN7Za3NfjKyThqE5Wtq3rvfblB0fShVJCtrx_TMsQUaUdFdxrVatDp-l8LyCDyHMogvJm6ftXZxaKny8GizrJ-f6l3T71_MSVw6jM8Dbl-u6swe0nqMXvR8ZDQmZhHjqtCwyXkW_YZcU-bcvTWJvApbg6C54O_DeWF3Wr0GJ0u9L6MVTlOkYpQhQP4hVYehxzE");'></div>
                </div>

                <div class="article-content tw-text-lg tw-text-[#334155]">
                    <p>
                        Năm 2023 đã chứng kiến sự bùng nổ của AI tạo sinh (Generative AI) với sự ra đời của ChatGPT và hàng loạt công cụ hỗ trợ sáng tạo nội dung. Tuy nhiên, 2024 hứa hẹn sẽ là một năm mà công nghệ không chỉ dừng lại ở mức "trải nghiệm" mà sẽ đi sâu vào thực tế cuộc sống, thay đổi cách chúng ta làm việc, giải trí và tương tác với thế giới xung quanh.
                    </p>
                    <h2>1. Sự trỗi dậy mạnh mẽ của AI tạo sinh</h2>
                    <p>
                        AI không còn là câu chuyện của tương lai xa xôi. Trong năm 2024, chúng ta sẽ thấy AI được tích hợp sâu vào các thiết bị cá nhân như <a class="tw-text-tech-blue tw-font-bold hover:tw-underline" href="#">Laptop</a> và <a class="tw-text-tech-blue tw-font-bold hover:tw-underline" href="#">Điện thoại</a>. Các dòng chip mới từ Intel, AMD và Apple đều đang tập trung vào khả năng xử lý AI ngay trên thiết bị (On-device AI), giúp tăng tốc độ phản hồi và bảo mật dữ liệu tốt hơn.
                    </p>
                    <div class="tw-my-8 tw-rounded-lg tw-overflow-hidden tw-bg-slate-50 tw-border tw-border-slate-100">
                        <div class="tw-aspect-[21/9] tw-w-full tw-bg-center tw-bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDyOXYxsG7a5C8k_r5wIWsQsOrg0-rnhDq_5EjpqLcmCbW0QuvPCpZH4Nez4kta0EAzvKyDrDPjvHOFwAAyQv60wQTfJBGivx0vpj7iJPybS3KBcRj-6Sh5hIPXMZav7e4E9Ve8M5tgqj9VoMMPNOLAlfeqPIfljMnmEialDs6EuQxGSBPjJ9pbxWyrpeR-Zp9QQ9DKJoqETSzybLG3pf-pNdrwUzQtz8mRPuIaa-0fGYd4NvwqHmqJSoT76uYeigFsUBgY_DRqzLg");'></div>
                        <p class="tw-p-3 tw-text-sm tw-text-center tw-text-slate-500 tw-italic">Chip xử lý AI thế hệ mới sẽ là tâm điểm của các dòng Laptop 2024.</p>
                    </div>
                    <p>
                        Không chỉ dừng lại ở chatbot, AI sẽ tham gia vào quy trình thiết kế đồ họa, lập trình và thậm chí là chẩn đoán y khoa sơ bộ. Các trợ lý ảo sẽ trở nên thông minh hơn, hiểu ngữ cảnh tốt hơn và thực hiện được các tác vụ phức tạp theo chuỗi mệnh lệnh.
                    </p>
                    <blockquote>
                        "Năm 2024 sẽ là năm bản lề, nơi AI chuyển từ một công cụ thú vị sang một trợ thủ đắc lực không thể thiếu trong mọi ngành nghề." - Theo TechCrunch.
                    </blockquote>
                    <h2>2. Metaverse và Thực tế ảo (VR/AR)</h2>
                    <p>
                        Mặc dù khái niệm Metaverse có phần hạ nhiệt trong năm qua, nhưng với sự ra mắt của các thiết bị phần cứng đột phá như Apple Vision Pro, không gian thực tế ảo đang nóng trở lại. Công nghệ này không chỉ phục vụ chơi game mà còn mở rộng sang giáo dục, đào tạo nghề và hội họp trực tuyến với độ chân thực cao.
                    </p>
                    <h3>Thiết bị đeo thông minh hơn</h3>
                    <p>
                        Các phụ kiện công nghệ như đồng hồ thông minh, nhẫn thông minh (Smart Ring) sẽ tích hợp nhiều cảm biến sức khỏe tiên tiến hơn. Khả năng đo huyết áp, đường huyết không xâm lấn đang được các ông lớn như Samsung và Apple ráo riết nghiên cứu và sớm thương mại hóa.
                    </p>
                    <ul>
                        <li><strong>AI PC:</strong> Máy tính cá nhân tối ưu hóa cho tác vụ AI.</li>
                        <li><strong>Wi-Fi 7:</strong> Tốc độ kết nối siêu nhanh, độ trễ cực thấp.</li>
                        <li><strong>Công nghệ xanh:</strong> Xu hướng sản xuất thiết bị điện tử bền vững, giảm rác thải.</li>
                    </ul>
                    <h2>3. Kết luận</h2>
                    <p>
                        Thế giới công nghệ luôn vận động không ngừng. Để không bị bỏ lại phía sau, việc cập nhật kiến thức và trang bị những thiết bị phù hợp là điều cần thiết. Tại <strong>TechZone</strong>, chúng tôi luôn sẵn sàng mang đến cho bạn những sản phẩm công nghệ mới nhất, đón đầu xu hướng 2024.
                    </p>
                </div>

                <div class="tw-mt-10 tw-pt-6 tw-border-t tw-border-[#e2e8f0]">
                    <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-6">
                        <span class="tw-px-3 tw-py-1 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-full tw-cursor-pointer hover:tw-bg-slate-200 tw-transition-colors">#AI2024</span>
                        <span class="tw-px-3 tw-py-1 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-full tw-cursor-pointer hover:tw-bg-slate-200 tw-transition-colors">#Metaverse</span>
                        <span class="tw-px-3 tw-py-1 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-full tw-cursor-pointer hover:tw-bg-slate-200 tw-transition-colors">#TechTrends</span>
                        <span class="tw-px-3 tw-py-1 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-full tw-cursor-pointer hover:tw-bg-slate-200 tw-transition-colors">#SmartHome</span>
                    </div>
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <span class="tw-text-sm tw-font-bold tw-text-primary tw-uppercase tw-tracking-wider">Chia sẻ bài viết</span>
                        <div class="tw-flex tw-gap-2">
                            <button class="tw-size-10 tw-rounded-full tw-bg-[#1877F2] tw-text-white tw-flex tw-items-center tw-justify-center hover:tw-opacity-90 tw-transition-opacity">
                                <svg class="tw-size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
                            </button>
                            <button class="tw-size-10 tw-rounded-full tw-bg-[#1da1f2] tw-text-white tw-flex tw-items-center tw-justify-center hover:tw-opacity-90 tw-transition-opacity">
                                <svg class="tw-size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path></svg>
                            </button>
                            <button class="tw-size-10 tw-rounded-full tw-bg-gray-200 tw-text-gray-700 tw-flex tw-items-center tw-justify-center hover:tw-bg-gray-300 tw-transition-colors">
                                <span class="material-symbols-outlined tw-text-[20px]">link</span>
                            </button>
                        </div>
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
<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?> 
<nav class="breadcrumb-container">
    <ol class="breadcrumb">
        <?php
        $count = count($breadcrumbs);
        $i = 0;

        foreach ($breadcrumbs as $item):
            $i++;
            $label = htmlspecialchars($item["label"]);
            $url   = $item["url"] ?? null;

            if ($url) {
                echo "<li class='breadcrumb-item'>
                        <a href='{$url}'>{$label}</a>
                      </li>";
            } else {
                echo "<li class='breadcrumb-item active'>{$label}</li>";
            }
        endforeach;
        ?>
    </ol>
</nav>
<?php endif; ?>


<style>/* ===== BREADCRUMB (NO ICON) ===== */
.breadcrumb-container {
    margin: 15px 0 20px 40px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    padding: 10px 16px;
    /* background: linear-gradient(90deg, #f8fafc, #eef2f7); */
    border-radius: 10px;
    font-size: 15px;
}

.breadcrumb-item {
    color: #64748b;
    font-weight: 500;
}

/* Link */
.breadcrumb-item a {
    color: #2563eb;
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Dấu phân cấp */
.breadcrumb-item::after {
    content: "›";
    margin: 0 6px;
    color: #94a3b8;
}

.breadcrumb-item:last-child::after {
    content: "";
}

/* Trang hiện tại */
.breadcrumb-item.active {
    color: #0f172a;
    font-weight: 600;
}
.breadcrumb-item::before {
    content: none !important;
}

/* Mũi tên chỉ xuất hiện giữa các breadcrumb */
.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    margin: 0 8px;
    color: #94a3b8;
}
</style>


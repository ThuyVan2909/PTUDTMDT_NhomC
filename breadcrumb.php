<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
<nav class="breadcrumb-container" style="font-size:16px; margin: 10px 0 10px 40px;">
    <?php
    $count_breadcrumbs = count($breadcrumbs);
    $breadcrumb_index = 0;

    foreach ($breadcrumbs as $item):
        $breadcrumb_index++;

        $label = htmlspecialchars($item["label"]);
        $url   = $item["url"] ?? null;

        if ($url) {
            echo "<a href='{$url}' class='text-primary' style='text-decoration:none;'>{$label}</a>";
        } else {
            echo "<span>{$label}</span>";
        }

        if ($breadcrumb_index < $count_breadcrumbs) {
            echo " / ";
        }
    endforeach;
    ?>
</nav>
<?php endif; ?>

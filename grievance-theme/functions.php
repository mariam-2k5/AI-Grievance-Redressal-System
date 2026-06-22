<?php
/* =====================================================
   THEME STYLE
===================================================== */
function grievance_theme_assets() {
    wp_enqueue_style('grievance-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'grievance_theme_assets');


/* =====================================================
   CUSTOM POST TYPE
===================================================== */
function create_grievance_post_type() {
    register_post_type('grievance', array(
        'labels' => array(
            'name' => __('Grievances'),
            'singular_name' => __('Grievance')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-warning',
        'supports' => array('title', 'editor'),
    ));
}
add_action('init', 'create_grievance_post_type');


/* =====================================================
   GENERATE GRIEVANCE ID
===================================================== */
function generate_grievance_id() {
    return 'GRV-' . strtoupper(wp_generate_password(6, false, false));
}


/* =====================================================
   AI ENGINE
===================================================== */
require_once get_template_directory() . '/ai-engine.php';


/* =====================================================
   ADMIN META BOX
===================================================== */
add_action('add_meta_boxes', function () {

    add_meta_box(
        'admin_response_box',
        'Admin Response',
        function ($post) {

            $status = get_post_meta($post->ID, 'status', true);
            $admin_response = get_post_meta($post->ID, 'admin_response', true);
            ?>

            <label><strong>Status</strong></label>
            <select name="grievance_status" style="width:100%;margin-bottom:10px;">
                <option value="Pending" <?php selected($status, 'Pending'); ?>>Pending</option>
                <option value="In Review" <?php selected($status, 'In Review'); ?>>In Review</option>
                <option value="Resolved" <?php selected($status, 'Resolved'); ?>>Resolved</option>
            </select>

            <label><strong>Admin Reply</strong></label>
            <textarea name="admin_response" style="width:100%;height:80px;"><?php
                echo esc_textarea($admin_response);
            ?></textarea>

            <?php
        },
        'grievance',
        'normal',
        'high'
    );
});


/* =====================================================
   SAVE META
===================================================== */
add_action('save_post_grievance', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['grievance_status'])) {
        update_post_meta($post_id, 'status', sanitize_text_field($_POST['grievance_status']));
    }

    if (isset($_POST['admin_response'])) {
        update_post_meta($post_id, 'admin_response', sanitize_textarea_field($_POST['admin_response']));
    }
});


/* =====================================================
   REAL DATA FUNCTIONS
===================================================== */
function get_total_grievances() {
    $count = wp_count_posts('grievance');
    return isset($count->publish) ? $count->publish : 0;
}

function get_status_count($status_value) {
    global $wpdb;

    return (int) $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(p.ID)
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm
            ON p.ID = pm.post_id AND pm.meta_key = 'status'
        WHERE p.post_type = 'grievance'
        AND p.post_status = 'publish'
        AND pm.meta_value = %s
    ", $status_value));
}


/* =====================================================
   CATEGORY DISTRIBUTION
===================================================== */
function get_category_distribution() {
    global $wpdb;

    return $wpdb->get_results("
        SELECT 
            COALESCE(pm.meta_value,'Uncategorized') AS category,
            COUNT(p.ID) AS count
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm 
            ON p.ID = pm.post_id AND pm.meta_key = 'ai_category'
        WHERE p.post_type = 'grievance'
        AND p.post_status = 'publish'
        GROUP BY category
    ");
}


/* =====================================================
   PRIORITY DISTRIBUTION
===================================================== */
function get_priority_distribution() {
    global $wpdb;

    return $wpdb->get_results("
        SELECT 
            COALESCE(NULLIF(pm.meta_value,''),'Not Set') AS priority,
            COUNT(p.ID) AS count
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm 
            ON p.ID = pm.post_id AND pm.meta_key = 'priority'
        WHERE p.post_type = 'grievance'
        AND p.post_status = 'publish'
        GROUP BY priority
    ");
}


/* =====================================================
   LOAD CHART.JS
===================================================== */
add_action('admin_enqueue_scripts', function ($hook) {

    if ($hook !== 'index.php') return;

    wp_enqueue_script(
        'chartjs',
        'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js',
        [],
        null,
        true
    );
});


/* =====================================================
   DASHBOARD WIDGETS
===================================================== */
add_action('wp_dashboard_setup', function () {

    wp_add_dashboard_widget('grievance_overview_widget','Grievance Overview','render_grievance_overview');
    wp_add_dashboard_widget('grievance_category_widget','Grievance by Category','render_grievance_category_chart');
    wp_add_dashboard_widget('grievance_priority_widget','Grievance by Priority','render_grievance_priority_chart');

});


/* =====================================================
   OVERVIEW
===================================================== */
function render_grievance_overview() {

    echo "<p><strong>Total:</strong> " . get_total_grievances() . "</p>";
    echo "<p><strong>Pending:</strong> " . get_status_count('Pending') . "</p>";
    echo "<p><strong>In Review:</strong> " . get_status_count('In Review') . "</p>";
    echo "<p><strong>Resolved:</strong> " . get_status_count('Resolved') . "</p>";
}


/* =====================================================
   CATEGORY PIE (WITH COLORS)
===================================================== */
function render_grievance_category_chart() {

    $data = get_category_distribution();

    $labels = [];
    $counts = [];

    foreach ($data as $d) {
        $labels[] = $d->category;
        $counts[] = (int)$d->count;
    }

    $id = 'cat_' . uniqid();
?>

<canvas id="<?php echo $id; ?>"></canvas>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("<?php echo $id; ?>");

    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($counts); ?>,
                    backgroundColor: ['#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6']
                }]
            }
        });
    }
});
</script>

<?php }


/* =====================================================
   PRIORITY BAR (WITH COLORS)
===================================================== */
function render_grievance_priority_chart() {

    $data = get_priority_distribution();

    $labels = [];
    $counts = [];

    foreach ($data as $d) {
        $labels[] = $d->priority;
        $counts[] = (int)$d->count;
    }

    $id = 'pri_' . uniqid();
?>

<canvas id="<?php echo $id; ?>"></canvas>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("<?php echo $id; ?>");

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Grievances',
                    data: <?php echo json_encode($counts); ?>,
                    backgroundColor: '#0f766e'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>

<?php }

/* =====================================================
   ADD COLUMNS IN ADMIN TABLE
===================================================== */
add_filter('manage_grievance_posts_columns', function ($columns) {

    $columns['priority'] = 'Priority';
    $columns['status'] = 'Status';

    return $columns;
});

add_action('manage_grievance_posts_custom_column', function ($column, $post_id) {

    if ($column === 'priority') {
        $priority = get_post_meta($post_id, 'priority', true);

        $color = 'gray';
        if ($priority === 'High') $color = 'red';
        elseif ($priority === 'Medium') $color = 'orange';
        elseif ($priority === 'Low') $color = 'green';

        echo "<strong style='color:$color'>" . esc_html($priority ?: 'Not Set') . "</strong>";
    }

    if ($column === 'status') {
        echo esc_html(get_post_meta($post_id, 'status', true));
    }

}, 10, 2);
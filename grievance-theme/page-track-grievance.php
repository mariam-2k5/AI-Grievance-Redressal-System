<?php
/* Template Name: Track Grievance */
get_header();
?>

<div class="track-box">
    <h2>Track Your Grievance</h2>

    <form method="post">
        <input type="text" name="grievance_id" placeholder="Enter Grievance ID (GRV-XXXXXX)" required>
        <button type="submit" name="track_grievance">Track</button>
    </form>

<?php
if (isset($_POST['track_grievance'])) {

    $gid = sanitize_text_field($_POST['grievance_id']);

    $query = new WP_Query(array(
        'post_type'      => 'grievance',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'name'           => sanitize_title($gid)
    ));

    if ($query->have_posts()) {
        $query->the_post();

        $post_id = get_the_ID();

        $status       = get_post_meta($post_id, 'status', true);
        $category     = get_post_meta($post_id, 'ai_category', true);
        $priority     = get_post_meta($post_id, 'priority', true);
        $ai_reply     = get_post_meta($post_id, 'admin_reply', true);
        $admin_reply  = get_post_meta($post_id, 'admin_response', true);

        $status_text = $status ?: 'Pending';

        $status_class = 'status-pending';
        if ($status_text === 'Resolved') {
            $status_class = 'status-resolved';
        } elseif ($status_text === 'In Review') {
            $status_class = 'status-review';
        }
        ?>

        <div class="result">

            <p>
                <strong>Status:</strong>
                <span class="status-badge <?php echo esc_attr($status_class); ?>">
                    <?php echo esc_html($status_text); ?>
                </span>
            </p>

            <p><strong>Category:</strong> <?php echo esc_html($category ?: 'Not assigned'); ?></p>
            <p><strong>Priority:</strong> <?php echo esc_html($priority ?: 'Low'); ?></p>

            <div class="ai-box">
                <strong>🤖 AI Suggested Response</strong>
                <p><?php echo esc_html($ai_reply ?: 'Under review'); ?></p>
            </div>

            <?php if ($admin_reply): ?>
                <div class="admin-box">
                    <strong>👨‍💼 Admin Reply</strong>
                    <p><?php echo esc_html($admin_reply); ?></p>
                </div>
            <?php endif; ?>

        </div>

        <?php
        wp_reset_postdata();
    } else {
        echo "<p class='error'>No grievance found with this ID.</p>";
    }
}
?>

</div>

<?php get_footer(); ?>

<?php
/* Template Name: Submit Grievance */
get_header();
?>

<style>
.form-box {
    max-width: 700px;
    margin: 60px auto;
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.form-box h2 {
    text-align: center;
    margin-bottom: 30px;
}
.form-box input,
.form-box select,
.form-box textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.form-box button {
    background: #0f766e;
    color: white;
    border: none;
    padding: 14px;
    width: 100%;
    font-size: 16px;
    border-radius: 25px;
    cursor: pointer;
}
</style>

<div class="form-box">
    <h2>Submit Grievance</h2>

    <form method="post">
        <input type="text" name="student_name" placeholder="Your Name (optional)">

        <select name="department" required>
    <option value="">Select Department</option>
    <option value="Science">Science</option>
    <option value="Computer Applications">Computer Applications</option>
    <option value="Language">Language</option>
    <option value="Humanities">Humanities</option>
    <option value="Commerce">Commerce</option>
    <option value="Management">Management</option>
</select>


        <input type="email" name="email" placeholder="Email ID" required>

        <textarea name="description" rows="5" placeholder="Describe your grievance" required></textarea>

        <button type="submit" name="submit_grievance">Submit Grievance</button>
    </form>
</div>

<?php
if (isset($_POST['submit_grievance'])) {

    // ✅ 1. Collect & sanitize input
    $student_name = sanitize_text_field($_POST['student_name']);
    $department   = sanitize_text_field($_POST['department']);
    $email        = sanitize_email($_POST['email']);
    $description  = sanitize_textarea_field($_POST['description']);

    // ✅ 2. Generate grievance ID
    $grievance_id = function_exists('generate_grievance_id') 
    ? generate_grievance_id() 
    : 'GRV-' . time();

    // ✅ 3. AI ANALYSIS
    $ai_result = analyze_grievance_ai($description);

    // ✅ 4. Insert grievance post
    $post_id = wp_insert_post(array(
        'post_title'   => $grievance_id,
        'post_type'    => 'grievance',
        'post_status'  => 'publish',
        'post_content' => $description
    ));

    if ($post_id) {

        // ✅ 5. Save student data
        update_post_meta($post_id, 'student_name', $student_name);
        update_post_meta($post_id, 'department', $department);
        update_post_meta($post_id, 'email', $email);
        update_post_meta($post_id, 'status', 'Pending');

        // ✅ 6. Save AI results (THIS IS WHERE THOSE LINES GO)
        update_post_meta($post_id, 'ai_category', $ai_result['category']);
        update_post_meta($post_id, 'priority', $ai_result['priority']);
        update_post_meta($post_id, 'admin_reply', $ai_result['response']);

        echo "<p style='text-align:center;color:green;font-weight:bold'>
                Grievance Submitted Successfully.<br>
                Your Grievance ID: <strong>$grievance_id</strong>
              </p>";
    }
}
?>

<?php get_footer(); ?>

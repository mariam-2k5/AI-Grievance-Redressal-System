<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>

</head>
<body>

<?php
// Show header ONLY on Submit & Track pages
if ( is_page('submit-grievance') || is_page('track-grievance') ) :
?>
<header class="inner-header">
    <h1>AI Grievance Redressal System</h1>
</header>
<?php endif; ?>

<div class="container">

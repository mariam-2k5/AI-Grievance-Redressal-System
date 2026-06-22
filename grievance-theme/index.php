<?php get_header(); ?>

<h2 style="text-align:center;">AI Grievance Dashboard</h2>

<div style="width:80%; margin:40px auto; display:flex; gap:40px; flex-wrap:wrap;">

    <!-- CATEGORY PIE CHART -->
    <div style="flex:1;">
        <h3 style="text-align:center;">Category Distribution</h3>
        <canvas id="categoryChart"></canvas>
    </div>

    <!-- PRIORITY BAR CHART -->
    <div style="flex:1;">
        <h3 style="text-align:center;">Priority Distribution</h3>
        <canvas id="priorityChart"></canvas>
    </div>

</div>

<?php
// FETCH DATA
$categories = get_category_distribution();
$priorities = get_priority_distribution();

$cat_labels = [];
$cat_counts = [];

foreach ($categories as $cat) {
    $cat_labels[] = $cat->category;
    $cat_counts[] = $cat->count;
}

$pri_labels = [];
$pri_counts = [];

foreach ($priorities as $pri) {
    $pri_labels[] = $pri->priority;
    $pri_counts[] = $pri->count;
}
?>

<script>
// CATEGORY PIE CHART
new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($cat_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($cat_counts); ?>,
            backgroundColor: ['#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6']
        }]
    }
});

// PRIORITY BAR CHART
new Chart(document.getElementById('priorityChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($pri_labels); ?>,
        datasets: [{
            label: 'Grievances',
            data: <?php echo json_encode($pri_counts); ?>,
            backgroundColor: '#0f766e'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php get_footer(); ?>

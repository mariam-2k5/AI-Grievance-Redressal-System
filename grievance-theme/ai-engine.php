<?php
function analyze_grievance_ai($text) {

    $text = strtolower($text);

    /* ===============================
       CATEGORY
    =============================== */
    if (strpos($text, 'exam') !== false) {
        $category = 'Examination';
    } elseif (
        strpos($text, 'network') !== false ||
        strpos($text, 'wifi') !== false ||
        strpos($text, 'internet') !== false
    ) {
        $category = 'IT & Network';
    } elseif (strpos($text, 'hostel') !== false) {
        $category = 'Hostel Facilities';
    } elseif (
        strpos($text, 'class') !== false ||
        strpos($text, 'teacher') !== false ||
        strpos($text, 'faculty') !== false
    ) {
        $category = 'Academic';
    } else {
        $category = 'General';
    }


    /* ===============================
       PRIORITY (SMART LOGIC)
    =============================== */

    // 🔴 HIGH PRIORITY
    if (
        strpos($text, 'urgent') !== false ||
        strpos($text, 'immediately') !== false ||
        strpos($text, 'asap') !== false ||
        strpos($text, 'serious') !== false ||
        strpos($text, 'harassment') !== false ||
        strpos($text, 'danger') !== false ||
        strpos($text, 'unsafe') !== false ||
        strpos($text, 'threat') !== false ||
        strpos($text, 'not working at all') !== false ||
        strpos($text, 'completely broken') !== false
    ) {
        $priority = 'High';
    }

    // 🟡 MEDIUM PRIORITY
    elseif (
        strpos($text, 'delay') !== false ||
        strpos($text, 'slow') !== false ||
        strpos($text, 'issue') !== false ||
        strpos($text, 'problem') !== false ||
        strpos($text, 'not working properly') !== false ||
        strpos($text, 'sometimes') !== false ||
        strpos($text, 'irregular') !== false
    ) {
        $priority = 'Medium';
    }

    // 🟢 DEFAULT (IMPORTANT CHANGE)
    else {
        $priority = 'Medium'; // 🔥 NOT LOW anymore
    }

    return [
        'category' => $category,
        'priority' => $priority,
        'response' => 'Your grievance has been received and is under review.'
    ];
}
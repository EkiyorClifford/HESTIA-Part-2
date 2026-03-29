<?php
header('Content-Type: application/json');
require_once '../classes/PropertyTracker.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_id = $_POST['property_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    
    if ($property_id) {
        $tracker = new PropertyTracker();
        $result = $tracker->track_view($property_id, $user_id);
        
        if ($result === 'counted') {
            // Get updated stats
            $stats = $tracker->count_stats($property_id);
            echo json_encode([
                'success' => true,
                'view_count' => $stats['views_count'] ?? 0,
                'message' => 'View counted'
            ]);
        } elseif ($result === 'already_counted') {
            echo json_encode([
                'success' => true,
                'message' => 'Already counted recently'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error tracking view'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Property ID required'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>

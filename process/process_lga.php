<?php
require_once __DIR__ . '/../classes/State.php';

// Check if state_id is set
if(isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    
    $state = new State();
    $lga = $state->get_active_lgas_by_state_id($state_id);
    
    $lglist = '<option value="" selected disabled>— select LGA —</option>';
    foreach ($lga as $lg) {
        $lglist .= '<option value="' . $lg['lga_id'] . '">' . htmlspecialchars($lg['lga_name']) . '</option>';
    }
    echo $lglist;
} else {
    echo '<option value="" selected disabled>— select LGA —</option>';
}
?>
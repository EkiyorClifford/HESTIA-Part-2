<?php
if (defined('HESTIA_EASTER_SCRIPTS_LOADED')) {
    return;
}
define('HESTIA_EASTER_SCRIPTS_LOADED', true);
$hestia_asset_base = '/Hestia-PHP/assets/';
?>
<script defer src="<?= htmlspecialchars($hestia_asset_base) ?>combined.min.js"></script>

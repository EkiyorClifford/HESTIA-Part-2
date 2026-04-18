<?php
if (!defined('HESTIA_EASTER_SCRIPTS_LOADED')) {
    define('HESTIA_EASTER_SCRIPTS_LOADED', true);
    $hestia_asset_base = '/Hestia-PHP/assets/';
?>
<script defer src="<?= htmlspecialchars($hestia_asset_base) ?>hestia-easter.js"></script>
<script defer src="<?= htmlspecialchars($hestia_asset_base) ?>hestia-easter-boot.js"></script>
<?php
}

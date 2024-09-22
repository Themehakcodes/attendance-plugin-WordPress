<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Cleanup plugin data on uninstall
delete_option('my_plugin_settings');

<?php
/**
 * @package RepopupPlugin
 */

class RePopupDeactivate
{
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
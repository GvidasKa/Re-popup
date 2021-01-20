<?php
/**
 * @package RepopupPlugin
 */

class RePopupActivate
{
    public static function activate() {
        flush_rewrite_rules();
    }
}
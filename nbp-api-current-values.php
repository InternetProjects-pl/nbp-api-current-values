<?php
/**
 * Plugin Name: NBP API Current Values
 * Description: Wyświetla aktualne wartości: ceny złota oraz kursów walut w panelu admina pod tabelą Tagów.
 * Version: 1.0
 * Author: Gerard Urbański
 */

 if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

require_once __DIR__ . '/vendor/autoload.php';

use NbpApiCurrentValues\Admin\CurrentValuesAdmin;

add_action('admin_init', function() {
  $admin = new CurrentValuesAdmin();
  $admin->init();
  $admin->registerCss();
});
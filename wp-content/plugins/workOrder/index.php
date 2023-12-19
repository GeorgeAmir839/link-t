<?php
/*
 * Plugin Name:       Work Order
 * Description:       Custom plugin to manage your custom Work Order form.
 * Version:           1.0.0
 * Author:            George Amir
 * Author URI:        https://github.com/GeorgeAmir839
 * Text Domain:       consistentclientsupport
 */

add_action('admin_menu', 'activate_work_order');

function activate_work_order()
{
    add_menu_page('Work Order', 'Activate your workOrder', 'manage_options', 'activate-work-order', 'activate_work_order_callback');
}

function activate_work_order_callback()
{
    ?>
    <div class="wrap" style="padding: 55px;">
        <h2>consistentclientsupport You can custmuze your form now</h2>
        <a href="https://consistentclientsupport.com/" target="_blank">Actiate now</a>
    </div>
<?php
}



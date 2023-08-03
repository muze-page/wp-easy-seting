<?php

/**
 * Plugin Name:       测试设置选项
 * Description:       测试选项
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       accordion
 *
 * @package           create-block
 */

//添加菜单

function config_menu()
{
    add_submenu_page(
        'options-general.php',
        '退款配置',
        '退款配置',
        'administrator',
        'refun_config',
        'menu_displays',
        '90.1', //顺序
    );
}
add_action('admin_menu', 'config_menu');

function menu_displays()
{

?>
    <button id="get_click">获取值</button>
    <button id="post_click">更新值</button>
    <script src="https://unpkg.com/vue@3.3.4"></script>
    <div id="app"></div>
<?
}

//加载JS
function load_admin_script()
{
    // 获取 my_object_option 的值
    $default_value = get_option('my_object_option');

    $index_js = plugin_dir_url(__FILE__) . 'main.js';
    wp_enqueue_script('666', $index_js, array(), '1.1', true);

    wp_localize_script('666', 'myObjectOption', $default_value);
}
add_action('admin_enqueue_scripts', 'load_admin_script');



// 添加Ajax请求处理函数
add_action('wp_ajax_save_object_option', 'save_object_option_callback');

function save_object_option_callback()
{
    // 获取通过 Ajax POST 请求传递的对象数据
    $object_data = $_POST['object_data'];

    // 将 JSON 字符串解析为 PHP 对象
    $object = json_decode(stripslashes($object_data));

    // 保存设置选项
    update_option('my_object_option', $object);

    // 发送成功响应
    $response = array(
        'message' => '设置选项已保存！',
        'object' => $object,
    );
    wp_send_json_success($response);
}


// 设置选项的值为一个对象
$my_option = array(
    'name' => 'John',
    'age' => 30,
    'email' => 'john@example.com'
);
//update_option('my_object_option', $my_option);


add_action('wp_head', 'shouTop');
function shouTop()
{
    $value = get_option("my_object_option");
    $config = $value->name;
    $content = "666<h1>88{$config}</h1>";
    echo $content;
}

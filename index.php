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

$global_npcink_option = 'my_object_option_d';

//添加菜单

function option_menu()
{
    add_submenu_page(
        'options-general.php',
        '测试选项',
        '测试选项',
        'administrator',
        'option_config',
        'option_displays',
        '90.1', //顺序
    );
}
add_action('admin_menu', 'option_menu');

function option_displays()
{

?>
    <div class="wrap">
        <h3>请填写选项</h3>
        <h5>Jquery</h5>
        <form action="" method="">
            姓名： <input type="text" name="name"><br>
            年龄： <input type="text" name="age"><br>
            邮箱：<input type="text" name="email"><br>
            <br />
            <input type="submit" id="submit-btn" class="button button-primary" value="保存">
        </form>
        <br />


        <hr />
        <h5>Vue</h5>
        <script src="https://unpkg.com/vue@3.3.4"></script>
        <div id="app"></div>

        <hr />
        <button id="get_click" class="button button-primary">获取值</button>
    </div><!--end-->


<?
}

//加载JS
function load_admin_script($hook)
{
    global $global_npcink_option;
    //是否是指定页面
    if ('settings_page_option_config' != $hook) {
        return;
    }
    // 获取 my_object_option 的值
    $default_value = get_option($global_npcink_option);

    $index_js = plugin_dir_url(__FILE__) . 'main.js';
    wp_enqueue_script('666', $index_js, array(), '1.1', true);

    wp_localize_script('666', 'myObjectOption', $default_value);
}
add_action('admin_enqueue_scripts', 'load_admin_script');



// 添加Ajax请求处理函数
add_action('wp_ajax_save_object_option', 'save_object_option_callback');

function save_object_option_callback()
{
    global $global_npcink_option;
    // 获取通过 Ajax POST 请求传递的对象数据
    $object_data = $_POST['object_data'];

    // 将 JSON 字符串解析为 PHP 对象
    $object = json_decode(stripslashes($object_data));

    // 保存设置选项
    update_option($global_npcink_option, $object);

    // 发送成功响应
    $response = array(
        'message' => '设置选项已保存！',
        'object' => $object,
    );
    wp_send_json_success($response);
}


/**
 * 判断指定选项是否存在，不存在则给默认值
 */
//add_action('init', 'add_option_npcink');
function add_option_npcink()
{
    global $global_npcink_option;
    $value = get_option($global_npcink_option);

    $my_option = array(
        'name' => '测试中',
        'age' => 30,
        'email' => 'john@example.com'
    );

    if (isset($value->key)) {
        update_option($global_npcink_option, $my_option);
    }
}




add_action('wp_head', 'shouTop');
function shouTop()
{
    global $global_npcink_option;
    $value = get_option($global_npcink_option);
    $config = $value->name;
    $content = "666<h1>88{$config}</h1>";
    echo $content;
}

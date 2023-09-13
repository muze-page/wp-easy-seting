<?php

/**
 * Plugin Name:       简单设置选项
 * Plugin URI:        https://www.npc.ink
 * Description:       使用更加简单的方法实现设置选项，jS传值作选项默认值，触发回调保存选项值
 * Author:            Npcink
 * Author URI:        https://www.npc.ink
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Npcink
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       accordion
 *
 * @package           create-block
 */

$global_npcink_option = 'my_object_option_n';

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

//菜单回调
function option_displays()
{

?>
    <div class="wrap">
        <h3>请填写选项</h3>
        <h4>Jquery</h4>
        <form action="" method="">
            姓名： <input type="text" name="name"><br>
            年龄： <input type="text" name="age"><br>
            邮箱：<input type="text" name="email"><br>
            <br />
            <input type="submit" id="submit-btn" class="button button-primary" value="保存">
        </form>
        <br/>
        <button id="get_click" class="button button-primary">打印变量</button>
        <br />


        <hr />
        <h4>Vue</h4>
        <script src="https://unpkg.com/vue@3.3.4"></script>
        <div id="app"></div>

       
       
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
    $default_value  = get_option($global_npcink_option);

    $index_js = plugin_dir_url(__FILE__) . 'main.js';
    wp_enqueue_script('option', $index_js, array(), '1.0', true);

    //传递选项值
    wp_localize_script('option', 'myObjectOption', $default_value);
}
add_action('admin_enqueue_scripts', 'load_admin_script');



// 添加Ajax请求处理函数 - jS发出的请求中这里处理，保存到选项中
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
    //wp_send_json_success($response);
    // 使用 wp_send_json 函数发送 JSON 响应，避免汉字转义
    wp_send_json($response, 200, JSON_UNESCAPED_UNICODE);
}




//添加设置按钮
//设置按钮
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $links[] = '<a href="' . get_admin_url(null, 'options-general.php?page=option_config') . '">' . __('设置', 'n') . '</a>';
    return $links;
});


//调试用
add_action('wp_head', 'shouTop');
function shouTop()
{
    global $global_npcink_option;
    $value = get_option($global_npcink_option);
    $config = $value->name;
    $content = "666<h1>88{$config}</h1>";
    echo $content;
}

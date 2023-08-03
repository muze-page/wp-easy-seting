jQuery(document).ready(function ($) {
  //获取设置选项数据
  $("#get_click").click(function () {
    // 在这里处理返回的设置选项值
    console.log(myObjectOption); // 输出设置选项的默认值
  });

  //设置值
  $("#post_click").click(function () {
    // 创建包含对象的变量
    var myObject = {
      name: "bar",
      age: 123666,
      email: "6666",
    };

    // 发送POST请求
    jQuery.ajax({
      url: ajaxurl, // WordPress提供的全局变量，指向admin-ajax.php文件
      type: "POST",
      data: {
        action: "save_object_option", // 自定义的Ajax处理函数名称
        object_data: JSON.stringify(myObject), // 将对象转换为 JSON 字符串
      },
      success: function (response) {
        // 请求成功的回调函数
        console.log("设置选项已保存！");
        console.log(myObject);
      },
      error: function (error) {
        // 请求失败的回调函数
        console.error("保存设置选项时出错：" + error.responseText);
      },
    });
  });
});

//vue内容部分

const App = {
  setup() {
    const datas = Vue.reactive({
      myObject: {
        name: "",
        age: 0,
        email: "",
      },
    });
    //设为默认值
    datas.myObject = myObjectOption;
    //发出请求
    const postData = () => {
      // 发送POST请求
      jQuery.ajax({
        url: ajaxurl, // WordPress提供的全局变量，指向admin-ajax.php文件
        type: "POST",
        data: {
          action: "save_object_option", // 自定义的Ajax处理函数名称
          object_data: JSON.stringify(datas.myObject), // 将对象转换为 JSON 字符串
        },
        success: function (response) {
          // 请求成功的回调函数
          console.log("设置选项已保存！");
          console.log(datas.myObject);
        },
        error: function (error) {
          // 请求失败的回调函数
          console.error("保存设置选项时出错：" + error.responseText);
        },
      });
    };
    return { datas, postData };
  },
  template: `
文本框1：<input type="text" v-model="datas.myObject.name">
<br/>文本框2：<input type="text" v-model="datas.myObject.age">
<hr/><button class="button button-primary" @click="postData">保存</button>
`,
};

Vue.createApp(App).mount("#app");

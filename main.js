//纯JS实现设置选项
jQuery(document).ready(function ($) {
  // 给输入字段赋值
  $('input[name="name"]').val(myObjectOption.name);
  $('input[name="age"]').val(myObjectOption.age);
  $('input[name="email"]').val(myObjectOption.email);
  //提交按钮
  $("#submit-btn").click(function (e) {
    e.preventDefault(); // 阻止表单默认提交行为

    const optionData = {
      name: "",
      age: "",
      email: "",
    };
    //准备数据
    optionData.name = $('input[name="name"]').val();
    optionData.age = $('input[name="age"]').val();
    optionData.email = $('input[name="email"]').val();
    jQuery.ajax({
      url: ajaxurl, // WordPress提供的全局变量，指向admin-ajax.php文件
      type: "POST",
      data: {
        action: "save_object_option", // 自定义的Ajax处理函数名称
        object_data: JSON.stringify(optionData), // 将对象转换为 JSON 字符串
      },
      success: function (response) {
        // 请求成功的回调函数
        console.log("设置选项已保存！");
        console.log(optionData);
      },
      error: function (error) {
        // 请求失败的回调函数
        console.error("保存设置选项时出错：" + error.responseText);
      },
    });
  });

  //获取设置选项数据
  $("#get_click").click(function () {
    // 在这里处理返回的设置选项值
    console.log(myObjectOption); // 输出设置选项的默认值
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
姓名：<input type="text" v-model="datas.myObject.name"><br/>
年龄：<input type="text" v-model="datas.myObject.age"><br/>
邮箱：<input type="text" v-model="datas.myObject.email"><br/>
<hr/><button class="button button-primary" @click="postData">保存</button>
`,
};

Vue.createApp(App).mount("#app");

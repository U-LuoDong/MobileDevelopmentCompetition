//适应网页大小开始
//浏览器窗口大小被改变时触发事件
window.onresize = function () {
    layer(); //动态改变遮罩大小
    //动态改变弹窗位置
    setPosition();
}
/**
 * 动态改变遮罩大小
 */
function layer() {
    var doc = document.documentElement;
    relHeight = (doc.clientHeight > doc.scrollHeight) ? doc.clientHeight : doc.scrollHeight; //获取屏幕高度和当前页面高度中的较大值
    document.getElementById('Layer').style.height = relHeight + 'px';
    relWidth = (doc.clientWidth > doc.scrollWidth) ? doc.scrollWidth : doc.clientWidth; //获取屏幕宽度和当前页面宽度中的较小值
    document.getElementById('Layer').style.width = relWidth + 'px';
}

layer();

function setPosition() {
    var doc = document.documentElement;
    //获得窗口的垂直位置;
    var iTop = (doc.clientHeight - 6 - document.getElementById('login').offsetHeight) / 2;
    //获得窗口的水平位置;
    var iLeft = (doc.clientWidth - 5 - document.getElementById('login').offsetWidth) / 2;
    $(login).css({
        'top': iTop,
        'left': iLeft
    });
}

setPosition();

//适应网页大小结束

/**
 * 异步获取验证码并放入隐藏框中
 */
function getCode() {
    $.ajax({
        url: 'http://10.118.35.182/tp5/public/static/admin/getCode2.php',
        type: 'POST',
        dataType: 'json',
        success: function (msg) {
            document.getElementById("chimg_code").value = msg;
        }
    });
}

getCode();

/**
 * 刷新验证码
 */
function shuaxin() {
    document.getElementById('code').src = "http://10.118.35.182/tp5/public/static/admin/VerificationCode2.php?" + Math.random();
    var code = getCode();
    document.getElementById("chimg_code").value = code;
}

//图片验证码和账号提示设为全局
var img_code_info = document.getElementById('img_code_info');
var emailinfo = document.getElementById('txt_1info');
/**
 * 对账号的长度检验 图片验证码 手机验证码的检验
 * @returns {boolean}
 * 1、提示信息tel_code_info img_code_info
 * 2、用户输入的：tel_code img_code
 * 3、正确检查的：chtel_code chimg_code
 */
function check() {
    /*获取表单元素和提示信息*/
    var email = document.getElementById('txt_1').value.trim(); //去掉用户输入的空格
    var img_code = document.getElementById('img_code').value.trim();//图片验证码 用户输入
    var chimg_code = document.getElementById('chimg_code').value.trim();//正确的图片验证码
    var tel_code = window.btoa(parseInt(document.getElementById("tel_code").value));//手机验证码 用户输入【window.btoa加密 强转为int，因为服务器上返回的是int】
    var chtel_code = document.getElementById("chtel_code").value;//正确的手机验证码
    // var chtel_code ={$Request.session.chtel_code};//正确的手机验证码
    var tel_code_info = document.getElementById("tel_code_info");//手机验证码 提示

    /*定义变量返回错误 开始*/
    var a = 1;
    var b = 1;
    var c = 1;
    /*定义变量返回错误 结束*/
    /*验证账号*/
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(email)) {
        emailinfo.innerHTML = "格式错误";
        a = 0;
    }
    //验证手机验证码
    if (tel_code != chtel_code || chtel_code == "") {
        tel_code_info.innerText = "验证码错误";
        tel_code_info.style.top="243px";
        tel_code_info.style.color = "red";
        b = 0;
    } else {
        tel_code_info.style.display = "none";
    }
    //验证图片验证码
    if (img_code.toLowerCase() != chimg_code.toLocaleLowerCase()) { //忽略大小写  全部转化为小写：toLowerCase
        img_code_info.innerHTML = "验证码错误";
        c = 0;
    }
    /*定义变量返回错误*/
    if (a == "0" || b == "0" || c == "0") {
        return false;
    }
    /*定义变量返回错误*/
}

/**
 * 异步检查手机号是否正确
 * @returns {boolean}
 */
function checkemail() {
    var email = document.getElementById('txt_1');
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(email.value.trim())) {
        emailinfo.innerHTML = "格式错误"; //手机格式有误
        emailinfo.style.color = "red";
        return false;
    } else {
        emailinfo.innerHTML = "格式正确";
        emailinfo.style.color = "green";
        return true;
    }
}

var btn = document.getElementById("btn");
var time = 60;
var setId;

/**
 * 点击手机验证码触发
 * @returns {boolean}
 */
function time1() {
    /**
     *发送并获取验证码
     */
    function getTelCode() {
        $.ajax({
            url: 'http://10.118.35.182/tp5/public/admin/Login/sms.html',
            type: 'POST',
            dataType: 'json',
            data: {
                //这里是请求参数，前面是键名，后面是值
                uPhone_Number: document.getElementById("txt_1").value.trim(),
            },
            success: function (res) {
                var tel_code_success = document.getElementById("tel_code_success");//手机验证码 提示
                if (res > 0) {

                    tel_code_success.innerText = "发送成功！请在2分钟之内进行登录";
                    tel_code_success.style.color = "green";
                    document.getElementById("chtel_code").value = window.btoa(res);//对手机验证码进行window.btoa加密

                    btn.setAttribute("disabled", "true"); //设置按钮为禁用状态
                    setId = setInterval(function () {
                        time--;
                        if (time >= 0) {
                            btn.value = "倒计时" + time;
                        } else {
                            btn.value = "重新获取验证码";
                            clearInterval(setId);
                            time = 60;
                        }
                    }, 1000);
                } else if (res == 0) {
                    tel_code_info.innerText = "发送失败！";
                    tel_code_info.style.color = "red";
                }
            }
        });
    }

    /**
     * 异步提交判断是否存在账号
     */
    function checkAccount() {
        $.ajax({
            url: 'http://10.118.35.182/tp5/public/admin/Login/checkAccount.html',
            type: 'POST',
            dataType: 'json',
            data: {
                //这里是请求参数，前面是键名，后面是值
                tel: document.getElementById("txt_1").value.trim(),
            },
            success: function (res) {
                //执行逻辑
                if (res == 1) {
                    //3、异步发送用户名到sms页面
                    getTelCode();
                } else if (res == 0) {
                    emailinfo.innerHTML = "账号不存在";
                    emailinfo.style.color = "red";
                    emailinfo.style.left = "240px";
                    return false;
                }
            }
        });
    }

    //1、验证图片验证码是否正确
    var img_code = document.getElementById('img_code').value.trim();
    if (img_code.toLowerCase() != document.getElementById("chimg_code").value.toLocaleLowerCase()) { //忽略大小写  全部转化为小写：toLowerCase
        img_code_info.innerHTML = "验证码错误";
        return false;
    } else {
        img_code_info.innerHTML = "";
        //2、异步提交判断是否存在账号
        checkAccount();
    }
}


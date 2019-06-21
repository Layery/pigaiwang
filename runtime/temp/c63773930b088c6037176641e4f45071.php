<?php /*a:1:{s:64:"D:\phpStudy\WWW\pigaiwang\application\haoyi\view\auth\login.html";i:1560928958;}*/ ?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>登录页</title>
    <link rel="stylesheet" type="text/css" href="/pigaiwang/public/static/css/saas-login.css" />
    <script type="text/javascript" src="/pigaiwang/public/static/js/jquery-2.1.1.js"></script>
</head>

<body>
<div class="login-wrap">
    <p class="tit"><i class="icon"></i>&nbsp;&nbsp;&nbsp;作业批改助手</p>
    <p class="tit-mini">用户登录</p>
    <form action="" class="login_form">
        <ul class="login-form">
            <li>
                <input type="text" name="user_name" placeholder="登录账号" class="inp-txt user" value="jiaoshi" />
                <text class="err-tip" id="user_name_tip"></text>
            </li>
            <li>
                <input type="password" name="password" placeholder="密码" class="inp-txt pswd" value="12345" />
                <text class="err-tip" id="password_tip"></text>
            </li>
        
            <li>
                <a href="javascript:;" class="green-btn login-btn">登录</a>
                <a href="" class="tip-txt">忘记密码请联系管理员</a>
            </li>
        </ul>
    </form>
</div>
</body>

</html>

<script>
    $(function() {

        $(document).keyup(function(event){
            if(event.keyCode ==13){
                $(".login-btn").trigger("click");
            }
        });

        $(".login-btn").on('click', function(){
            var formObj = new FormData($(".login_form")[0]);
            $.ajax({
                type: "POST",
                url: "/pigaiwang/public/index.php/haoyi/auth/login",
                data: formObj,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(s){
                    if (s.status == 200) {
                        window.location.href = s.data.jump_url;
                    } else {
                        $.each(s.data, function (i, n) {
                            $("#"+i+"_tip").html(n);
                        });
                        changeCode();
                    }
                }
            });

        });

        setInterval(hideError, 3900);

    });

    function changeCode() {
        $("#captcha_code")[0].src='/captcha/index/?tm='+Math.random();
    }

    function hideError() {
        $(".err-tip").text("");
    }
</script>

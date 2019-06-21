<?php
error_reporting(E_ALL);
$data = file_get_contents('./newhaoyi.sql');
if (isset($_GET['install']) && $_GET['install']) {
    try {
//        $dbms='mysql';     //数据库类型
//        $host='127.0.0.1'; //数据库主机名
//        $dbName='';    //使用的数据库
//        $user='root';      //数据库连接用户名
//        $pass='root';          //对应的密码
//        $dsn="$dbms:host=$host;dbname=$dbName";
//        $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
//        p($dbh);
        $mysqli = new mysqli('127.0.0.1', 'root', 'root');
        $data = explode(';', $data);
        foreach ($data as $key => $sql) {
            mysqli_query($mysqli, $sql);
        }
        $sql = 'insert into `hy_zuoye` (`id`, `title`, `content`, `tips`, `add_time`, `insert_user`, `insert_user_name`, `is_answer`) values(\'6\',\'借条的格式练习\',\'<p style=\"text-align: center;\">借条</p><p style=\"text-align: left;\">&nbsp; &nbsp; 今借到学校总务处音响壹套, 供学校迎新晚会使用, 会后立即归还 . 此据</p><p style=\"text-align: right;\">经手人: 李红</p><p style=\"text-align: right;\">2019年5月12日</p>\',\'借条，学校总务处，壹套，迎新晚会，经手人\',\'1561008402\',\'1\',\'陈老师\',\'1\')';
        mysqli_query($mysqli, $sql);
        exit(json_encode(['status' => 'ok']));
    } catch (\Exception $e) {
        exit(json_encode(['status' => 'false']));
    }

}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>作业批改助手安装界面</title>
    <link rel="stylesheet" type="text/css" href="./static/css/saas-login.css"/>
    <script type="text/javascript" src="./static/js/jquery-2.1.1.js"></script>
    <style type="text/css">
        .err_tip {color: red;}
    </style>
</head>

<body>
<div class="login-wrap">
    <p class="tit"><i class="icon"></i>作业批改助手安装界面</p>
    <p class="tit-mini"></p>
    <form action="" class="login_form">
        <ul class="login-form">
            <li>
                <a href="javascript:;" class="green-btn login-btn">开始安装</a>
                <a href="" class="err_tip"></a>
            </li>
        </ul>
    </form>
</div>
</body>

</html>

<script>
    $(function() {
        $(".login-btn").on('click', function(){
            $(this).text('安装中，请稍后。。。');
            $.ajax({
                type: "get",
                url: "./install.php?install=true",
                dataType: 'json',
                success: function(s){
                    console.log(s);
                    if (s.status == 'ok') {
                        $(this).text('安装成功');
                        alert('安装成功');
                        window.location.href = "pigaiwang/public/index.php/haoyi/auth/login";
                    } else {
                        $('.err_tip').text('安装失败，请按F5刷新重试！');
                        return false;
                    }
                }
            });
        });
    });
</script>

<?php /*a:3:{s:75:"D:\phpStudy\WWW\pigaiwang\application\haoyi\view\pigai\change_password.html";i:1560783078;s:38:"../application/common/view/layout.html";i:1560941184;s:39:"../application/common/view/sidebar.html";i:1561005163;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo config('app_desc'); ?></title>
    <link rel="stylesheet" type="text/css" href="/pigaiwang/public/static/bootstrap-3.3.7-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/pigaiwang/public/static/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" type="text/css" href="/pigaiwang/public/static/css/saas.css" />
    <link rel="stylesheet" type="text/css" href="/pigaiwang/public/static/layui/css/layui.css" />
    <script type="text/javascript" src="/pigaiwang/public/static/js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="/pigaiwang/public/static/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/pigaiwang/public/static/js/sass.js"></script>
    <script type="text/javascript" src="/pigaiwang/public/static/layui/layui.js"></script>
</head>

<body class="container">
<div class="header">
    <div class="left header-tit">
        <i class="icon"></i> <?php echo config('app_desc'); ?>
        <a href="/pigaiwang/public/index.php/haoyi/pigai/worklist" class="home"></a>
    </div>
    <div class="inner">
        <div class="right operation-box">
            <div class="menu-list">
                <span class="username"><?php echo htmlentities(app('session')->get('userinfo.nickname')); ?></span>
                <div class="cover-list">
                    <a href="/pigaiwang/public/index.php/haoyi/pigai/changepassword">修改密码</a>
                    <a href="/pigaiwang/public/index.php/haoyi/auth/logout">退出</a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-menu">
        <div class="inner">
    <?php foreach ($sidebar_list as $row) { ?>
        <div class="menu-box now">
            <span class="lev1"><?= $row['name'] ?></span>
            <div>
                <?php if (isset($row['child'])) { foreach ($row['child'] as $sunRow) { ?>
                        <a href="/pigaiwang/public/index.php/haoyi/<?= $sunRow['controller'] ?>/<?= $sunRow['action']?>" id="<?php echo $sunRow['controller']. '-'. $sunRow['action']?>"><?= $sunRow['name'] ?></a>
                    <?php } } ?>
            </div>
        </div>
    <?php } ?>
</div>
    </div>
</div>
<div class="base-wrap">
    <div class="main-wrap">
        
<div class="main-cont">
    <!--页面内容-->
    <p class="h5 page-tit-lev1">系统管理 &gt;&gt; 修改密码</p>
    <div class="form-wraper" style="width:60%;">
        <p class="pub-tit"></p>
        <form class="form-horizontal" onsubmit="return false;">
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">旧密码</label>
                <div class="col-sm-10">
                    <input type="password" name="oldpsw" class="form-control" id="oldpsw" placeholder="">
                    <text class="err-tip" id="old_tip"></text>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-10">
                    <input type="password" name="newpsw" class="form-control" id="newpsw" placeholder="">
                    <text class="err-tip" id="new_tip"></text>
                    <p class="grey">6-24位，必须包含字母和数字</p>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">重复新密码</label>
                <div class="col-sm-10">
                    <input type="password" name="repsw" class="form-control" id="repsw" placeholder="">
                    <p class="grey">6-24位，必须包含字母和数字</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" id="changePsw" class="btn btn-success">保存</button>
                </div>
            </div>
        </form>
    </div>
    <!--//页面内容-->
</div>
<div class="hy-pub-popup tip-popup">
    <div class="inner">
        <div class="tit"><p><span class="close doClose">X</span></p></div>
        <div class="cont">
            <p class="popup-tip">修改密码成功</p>
            <div class="btn-box">
                <div class="center"><span class="btn btn-success doCancle">关闭</span></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        //关闭弹框
        $(".doCancle").click(function(){
            $(".tip-popup").hide();
        });
        //提交保存
        $("#changePsw").on('click', function(){
            var oldpsw = $("#oldpsw").val();//旧密码
            var newpsw = $("#newpsw").val();//新密码
            var repsw = $("#repsw").val();//再次密码
            $.ajax({
                type: "post",
                url: "",
                data:{oldpsw: oldpsw,newpsw:newpsw,repsw:repsw},
                dataType:'json',
                success: function(data) {
                    if(data.status==1){
                        $("input").val('');
                        $(".grey").show();
                        $(".err-tip").hide();
                        $(".tip-popup").show();
                        return false;
                    }else{
                        if(data.pos==1){
                            $(".err-tip").hide();
                            $(".grey").show();
                            $("#old_tip").html(data.message).show();
                        }else {
                            $(".err-tip").hide();
                            $("#new_tip").parent().find(".grey").hide();
                            $("#new_tip").html(data.message).show();
                        }
                        return false;
                    }
                }
            });

        });
    });
</script>

    </div>
</div>
</body>
<script>
    $(function () {
        var curr_menu = "<?php echo htmlentities($curr_menu); ?>";
        $("#" + curr_menu).addClass('now')
            .parents('.menu-box')
            .siblings('.menu-box')
            .removeClass('now');
        $("#" + curr_menu).parents('.menu-box').addClass('now');
    })
</script>
</html>


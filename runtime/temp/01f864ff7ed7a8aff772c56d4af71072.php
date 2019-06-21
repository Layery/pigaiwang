<?php /*a:3:{s:64:"D:\phpStudy\WWW\pigaiwang\application\haoyi\view\pigai\edit.html";i:1561022028;s:38:"../application/common/view/layout.html";i:1560941184;s:39:"../application/common/view/sidebar.html";i:1561005163;}*/ ?>
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
        <style type="text/css">
    pre {
        line-height: 30px;
        padding-left: 30px;
        font-size: 20px;
    }
    table td img {
        height: 50px;
    }
</style>
<div class="main-cont">
    <!--页面内容-->
    <p class="h5 page-tit-lev1">作业管理 &gt;&gt; 批改</p>
    <div class="review-template">
    </div>
    <form class="order-draw clearfix" method="post">
        <div class="form-inline draw-title">
            <div class="form-group ">
            </div>
        </div>
    </form>
    <input class="btn btn-success flush" type="button" value="重置">
    <span style="color: red"><?php echo htmlentities($msg); ?></span>
    <br><br><br>
    <a href="" class="edit-btn blue btn-edit-reception"></a>
    <div class="clearfix">
        <pre style="float: left;width: 56%; height: auto; padding-right: 30px; border: 1px solid grey">
            <?php echo $zuoye['content']; ?>
        </pre>
        <div class="right-border details-content" style="margin-top: -100px">
            <table class="table table-hover center-tit deduct-right" style="display: table; margin-left: 0px; width: 100%; margin-right: 0px;">
                <tbody class="user-order-list">

                </tbody>
            </table>
        </div>
    </div>
    <!--//页面内容-->
</div>
<script>
    $(function () {
        //阻止浏览器默认右键点击事件
        $("pre").bind("contextmenu", function(){
            return false;
        });

        $('.reg_succ').on('mouseover', function () {
            $(this).css({
                "color":"white",
                "background-color":"#98bf21",
                "font-size":"20px",
                "padding":"5px",
                'cursor': 'pointer'
            });
        });

        $(".reg_succ").mousedown(function(e) {
            var img = '/pigaiwang/public/static/images/';
            console.log(e.which);
            //右键为3
            if (3 == e.which) {
                img += 'false.png';
            } else if (1 == e.which) {
                //左键为1
                img += 'ok.png';
            }
            var desc = $(this).text();
            var trHtml = '<tr class="reg_tips"><td style="width: 10%"><img src="'+ img +'" alt=""></td><td style="font-size: 18px; text-align: left">'+ desc +'</td></tr>';
            $('tbody').append(trHtml);
        });

        $('.flush').on('click', function () {
            $('.reg_succ').removeAttr('style');
            $('tbody').html('');
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


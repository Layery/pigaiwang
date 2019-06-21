<?php /*a:3:{s:66:"D:\phpStudy\WWW\pigaiwang\application\haoyi\view\pigai\create.html";i:1561027008;s:38:"../application/common/view/layout.html";i:1560941184;s:39:"../application/common/view/sidebar.html";i:1561005163;}*/ ?>
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
        <script type="text/javascript" charset="utf-8" src="/pigaiwang/public/static/ue/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/pigaiwang/public/static/ue/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/pigaiwang/public/static/ue/lang/zh-cn/zh-cn.js"></script>
<div class="main-cont">
    <!--页面内容-->
<!--    <p class="h5 page-tit-lev1">作业管理&gt;&gt; 作业列表 &gt;&gt; 写作业</p>-->
    <div class="base-wrap customer-info add-hospital">
        <form class="form-horizontal form-reception-wraper">
            <p class="tit" id="tit" style="padding-bottom: 11px;font-size: 16px;border-bottom: 1px dashed #E5E5E5">写作业</p>
            <div class="cont-form">
                <div class="form-group">

                    <label for="" class="col-sm-2 control-label"><i class="red">*</i>作业标题：</label>
                    <div class="col-sm-3">
                        <div class="sc-baseBox">
                            <input type="text" class="form-control" id="zuoye_title" placeholder="" name="zuoye_title" value="">
                            <div class="res-box callback-result" style="display: none;">
                            </div>
                            <span class="red-msg red-msg-real_name" style="color: red; display: none;"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="cont-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label"><i class="red">*</i>作业内容：</label>
<!--                    <div class="col-sm-8">-->
<!--                        <textarea id="zuoye_content" onselect="getContent()" class="form-control" rows="5" cols="20" placeholder="" name="zuoye_content"></textarea>-->
<!--                    </div>-->
                    <div class="col-sm-8">
                        <script id="zuoye_content" type="text/plain" style="width:auto; z-index: 0; height:350px;"></script>
                    </div>
                </div>
                <?php if(app('session')->get('userinfo.role_id') == 1): ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-8">
                        <span style="color: red"><b>教师可不必上传作业内容, 直接设置预设提示文字即可</b></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><i class="red">*</i>预设提示文字：</label>
                    <div class="col-sm-8">
                        <textarea id="zuoye_tip" onselect="getContent()" class="form-control" rows="5" cols="20" placeholder="" name="zuoye_tip"></textarea>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-8">
                        <span class="red-msg red-msg-real_name" style="color: red; display: block;"><b>复制文本到该预设文本框, 多个文本请用中文逗号分隔"，"</b></span>
                    </div>
                </div>
                <?php endif; ?>
                <div class="btn-box w468">
                    <div class="left">
                        <span class="btn btn-success btn-save-reception">提交</span>&nbsp;&nbsp;&nbsp;
                        <span class="btn btn-default" onclick="javascript:(window.location.reload());">清空</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--//页面内容-->
</div>
<script>

    var ue = UE.getEditor('zuoye_content');
    UE.getEditor('zuoye_content', {zIndex: 200});
    setTimeout("UE.getEditor('zuoye_content').container.style.zIndex = 0;", 300);
    setTimeout("$('#zuoye_content').find('.edui-default').css('z-index', '0 !important');", 1000);

    $('.btn-save-reception').on('click', function () {
            var cnt = UE.getEditor('zuoye_content').getContent();
            var title = $('#zuoye_title').val();
            var tips = $('#zuoye_tip').val();
            $.ajax({
                url: '/pigaiwang/public/index.php/haoyi/pigai/create',
                data: {'title': title, 'cnt' : cnt, 'tips': tips},
                type: 'POST',
                dataType: 'json',
                success: function (s) {
                    alert(s.msg);
                    if (s.status == 'N') {
                        return false;
                    }
                    window.location.href = '/pigaiwang/public/index.php/haoyi/pigai/worklist';
                }
            });
    });


    // layui.use('layedit', function(){
    //     layedit = layui.layedit;
    //     index = layedit.build('zuoye_content', {
    //         // 'tool' : ["strong","italic","underline","del","|","left","center","right","|","link","unlink","face","image"]
    //         'tool' : ["strong","italic","underline","del","|","left","center","right","|","link","unlink","face"],
    //         'height' : 350
    //     });
    //     $('.btn-save-reception').on('click', function () {
    //         var cnt = layedit.getContent(index);
    //         var title = $('#zuoye_title').val();
    //         var tips = $('#zuoye_tip').val();
    //         $.ajax({
    //             url: '/pigaiwang/public/index.php/haoyi/pigai/create',
    //             data: {'title': title, 'cnt' : cnt, 'tips': tips},
    //             type: 'POST',
    //             dataType: 'json',
    //             success: function (s) {
    //                 alert(s.msg);
    //                 if (s.status == 'N') {
    //                     return false;
    //                 }
    //                 window.location.href = '/pigaiwang/public/index.php/haoyi/pigai/worklist';
    //             }
    //         });
    //     });
    // });
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


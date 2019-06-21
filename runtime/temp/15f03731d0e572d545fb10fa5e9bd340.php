<?php /*a:3:{s:69:"D:\phpStudy\WWW\pigaiwang\application\haoyi\view\pigai\work_list.html";i:1561008732;s:38:"../application/common/view/layout.html";i:1560941184;s:39:"../application/common/view/sidebar.html";i:1561005163;}*/ ?>
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
    <p class="h5 page-tit-lev1">作业管理 >> 作业列表</p>
    <div class="review-template">
        <form class="clearfix normal-iptWraper" method="post">
            <a href="?jumpDate=<?php echo htmlentities($params['beforeJumpDate']); ?>" class="icon-yesterday">&lt;</a>
            <span class="line-tit"><?php echo htmlentities($params['jumpDate']); ?> &nbsp;<?php echo htmlentities($params['week']); ?>&nbsp;(<?php echo htmlentities($params['dateTip']); ?>) &nbsp;</span>
            <a href="?jumpDate=<?php echo htmlentities($params['afterJumpDate']); ?>" class="icon-tomorrow">&gt;</a>
            <span class="btn btn-default mg-l-10" onclick="javascript:(window.location.href='/pigaiwang/public/index.php/haoyi/pigai/worklist')">返回今天</span>
            <a href="/pigaiwang/public/index.php/haoyi/pigai/create" class="btn btn-default mg-l-10" id="add-rota-two"><?php if(app('session')->get('userinfo.role_id') == 1): ?>布置作业<?php else: ?>写作业<?php endif; ?></a>
        </form>
        <table class="table table-bordered table-hover center-tit">
            <thead>
            <tr>
                <th>序号</th>
                <th>作业标题</th>
                <th>作业内容</th>
                <th>姓名</th>
                <th>提交时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr id="<?php echo htmlentities($v['id']); ?>">
                <td><?php echo htmlentities($v['id']); ?></td>
                <td><?php echo htmlentities($v['title']); ?></td>
                <td title="<?php echo htmlentities($v['content']); ?>"><?php echo htmlentities($v['intro']); ?></td>
                <td><?php echo htmlentities($v['insert_user_name']); ?></td>
                <td><?php echo htmlentities(date('Y-m-d H:i:s',!is_numeric($v['add_time'])? strtotime($v['add_time']) : $v['add_time'])); ?></td>
                <?php if(app('session')->get('userinfo.role_id') == 1): ?>
                <td>
                    <a class="edit-btn blue btn-edit-reception" href="/pigaiwang/public/index.php/haoyi/pigai/edit?id=<?php echo htmlentities($v['id']); ?>">批改</a>
                    &nbsp;&nbsp;&nbsp;
                    <a class="edit-btn blue btn-edit-reception work_del" data-id="<?php echo htmlentities($v['id']); ?>" href="javascript:;">删除</a>
                    &nbsp;&nbsp;&nbsp;
                    <a class="edit-btn blue btn-edit-reception" href="/pigaiwang/public/index.php/haoyi/pigai/view?id=<?php echo htmlentities($v['id']); ?>">查看</a>
                </td>
                <?php else: ?>
                <td>
                    <a class="edit-btn blue btn-edit-reception" href="/pigaiwang/public/index.php/haoyi/pigai/view?id=<?php echo htmlentities($v['id']); ?>">查看</a>
                </td>
                <?php endif; ?>

            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </thead>
        </table>
    </div>
</div>
<!-- select zxs pop begin-->
<div class="hy-pub-popup form-popup" id="pop-select-comein" style="display: none;">
    <div class="inner">

        <div class="cont">
            <form class="form-horizontal form-reception-wraper">
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
                        <pre class="col-sm-8" style="height: 350px;">
                            借条<br/>&nbsp;&nbsp;大家好, 我是借条, 阿斯顿发生的发送大手动阀是的发生大手动阀按说发手动阀阿斯顿发生的法师打发斯蒂芬按说大是大非阿斯顿发生大手动阀阿斯蒂发送大手动阀阿斯蒂芬阿斯顿发生大发斯蒂



                            2019年6月18日

                            未定义
                        </pre>
                    </div>
                </div>
            </form>
            <input type="hidden" name="id-list" value="">
            <div class="btn-box">
                <div class="left"><span class="btn btn-success" id="btn-sure-comein">确定</span></div>
                <div class="right"><span class="btn btn-default doCancle">取消</span></div>
            </div>
        </div>
    </div>
</div>
<!-- select zxs pop-end -->

<script>
    $(function () {
        $('.work_del').on('click', function () {
            if (confirm('确认删除该条记录吗?')) {

            } else {
                return false;
            }
            var id = $(this).attr('data-id');
            var url = '/pigaiwang/public/index.php/haoyi/pigai/delete?id=' + id;
            window.location.href = url;

        });
    })
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


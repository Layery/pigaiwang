<?php /*a:3:{s:69:"D:\phpStudy\WWW\pigaiwang\application\haoyi\view\pigai\user_list.html";i:1560826306;s:38:"../application/common/view/layout.html";i:1560941184;s:39:"../application/common/view/sidebar.html";i:1561005163;}*/ ?>
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
    <p class="h5 page-tit-lev1">系统管理 &gt;&gt; 用户管理</p>
    <div class="table-wraper center-whole">
        <form class="form-inline user-list" action="/index.php/haoyi/pigai/userlist" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="" style="width: 200px;" name="keywords" placeholder="登录账号、用户姓名、角色" value="<?php echo htmlentities($search_params['keywords']); ?>">
                <span class="btn btn-default query">查询</span>
                <span class="btn btn-default add-user">+新增用户</span>
            </div>
        </form>
        <p class="pub-tit"></p>
        <table class="table table-bordered table-hover center-tit">
            <thead>
            <tr>
                <th style="width: 5%">登录账号</th>
                <th style="width: 7%">用户姓名</th>
                <th style="width: 5%">性别</th>
                <th style="width: 12%">部门</th>
                <th style="width: 10%">联系方式</th>
                <th style="width: 10%">备注</th>
                <th style="width: 8%">创建时间</th>
                <th style="width: 10%">操作</th>
            </tr>
            </thead>
            <tbody>

            <?php if(is_array($user_list) || $user_list instanceof \think\Collection || $user_list instanceof \think\Paginator): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?>
            <tr id="<?php echo htmlentities($row['id']); ?>">
                <td><?php echo htmlentities($row['username']); ?></td>
                <td><?php echo htmlentities($row['nickname']); ?></td>
                <td><?php echo htmlentities($row['sex']); ?></td>
                <td><?php echo htmlentities($row['duties']); ?></td>
                <td><?php echo htmlentities($row['contact']); ?></td>
                <td><?php echo htmlentities($row['remark']); ?></td>
                <td><?php echo htmlentities(date('Y-m-d',!is_numeric($row['insert_time'])? strtotime($row['insert_time']) : $row['insert_time'])); ?><br><?php echo htmlentities(date('H:i:s',!is_numeric($row['insert_time'])? strtotime($row['insert_time']) : $row['insert_time'])); ?></td>
                <td>
                    <a href="javascript:;" class="edit-btn blue edit">修改</a>
                    <a href="javascript:;" class="edit-btn blue edit">重置密码</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
    <!--//页面内容-->
</div>

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


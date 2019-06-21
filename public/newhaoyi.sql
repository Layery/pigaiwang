
DROP DATABASE `haoyi`;

CREATE DATABASE `haoyi`;

USE `haoyi`;

DROP TABLE IF EXISTS `hy_admin_users`;

CREATE TABLE `hy_admin_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(128) NOT NULL COMMENT '用户名',
  `username` varchar(128) NOT NULL COMMENT '登录账号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '默认密码123456',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户组——角色',
  `sex` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1男2女',
  `contact` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '联系方式',
  `duties` varchar(20) DEFAULT '' COMMENT '职务',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '状态值：0-删除  、1-正常',
  `insert_user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加人ID',
  `insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台用户表';

DROP TABLE IF EXISTS `hy_zuoye`;

CREATE TABLE `hy_zuoye` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `tips` varchar (5000) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `insert_user` int(11) unsigned NOT NULL DEFAULT '0',
  `insert_user_name` varchar (50) NOT NULL DEFAULT '',
  `is_answer` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否是老师布置的作业, 0=不是(学生提交)1=是(老师提交)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

insert into `hy_admin_users` (`id`, `nickname`, `username`, `password`, `role_id`, `sex`, `contact`, `duties`, `status`, `insert_user`, `insert_time`, `remark`) values('1','陈老师','jiaoshi','123456','1','2','','教育部','1','2','1560929862','');
insert into `hy_admin_users` (`id`, `nickname`, `username`, `password`, `role_id`, `sex`, `contact`, `duties`, `status`, `insert_user`, `insert_time`, `remark`) values('2','admin','admin','123456','1','1','','','1','0','1560929862','');
insert into `hy_admin_users` (`id`, `nickname`, `username`, `password`, `role_id`, `sex`, `contact`, `duties`, `status`, `insert_user`, `insert_time`, `remark`) values('3','李丽','lili','123456','2','1','','电子电工班','1','1','1560929862','');
insert into `hy_admin_users` (`id`, `nickname`, `username`, `password`, `role_id`, `sex`, `contact`, `duties`, `status`, `insert_user`, `insert_time`, `remark`) values('4','马云','mayun','123456','2','1','','数控班','1','1','1560929862','');
insert into `hy_admin_users` (`id`, `nickname`, `username`, `password`, `role_id`, `sex`, `contact`, `duties`, `status`, `insert_user`, `insert_time`, `remark`) values('5','王美丽','wangmeili','123456','2','1','','财会班','1','1','1560929862','');

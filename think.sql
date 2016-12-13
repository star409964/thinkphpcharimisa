-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-12-13 12:02:01
-- 服务器版本： 5.6.34-log
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `think`
--

-- --------------------------------------------------------

--
-- 表的结构 `lyx_access`
--

CREATE TABLE IF NOT EXISTS `lyx_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `lyx_access`
--

INSERT INTO `lyx_access` (`role_id`, `node_id`, `level`, `pid`, `module`) VALUES
(8, 121, 1, 0, NULL),
(8, 125, 3, 122, NULL),
(8, 135, 3, 123, NULL),
(8, 136, 3, 123, NULL),
(8, 137, 3, 123, NULL),
(8, 138, 3, 123, NULL),
(8, 139, 3, 123, NULL),
(8, 140, 3, 123, NULL),
(8, 141, 3, 123, NULL),
(8, 142, 3, 123, NULL),
(8, 143, 3, 123, NULL),
(8, 126, 3, 124, NULL),
(8, 127, 3, 124, NULL),
(8, 128, 3, 124, NULL),
(8, 129, 3, 124, NULL),
(8, 130, 3, 124, NULL),
(8, 131, 3, 124, NULL),
(8, 132, 3, 124, NULL),
(8, 133, 3, 124, NULL),
(8, 134, 3, 124, NULL),
(8, 122, 2, 121, NULL),
(8, 123, 2, 121, NULL),
(8, 124, 2, 121, NULL),
(8, 144, 2, 121, NULL),
(8, 145, 2, 121, NULL),
(8, 164, 3, 144, NULL),
(8, 165, 3, 144, NULL),
(8, 166, 3, 144, NULL),
(8, 167, 3, 144, NULL),
(8, 168, 3, 144, NULL),
(8, 169, 3, 144, NULL),
(8, 170, 3, 144, NULL),
(8, 171, 3, 144, NULL),
(8, 172, 3, 144, NULL),
(8, 146, 3, 145, NULL),
(8, 147, 3, 145, NULL),
(8, 148, 3, 145, NULL),
(8, 149, 3, 145, NULL),
(8, 150, 3, 145, NULL),
(8, 151, 3, 145, NULL),
(8, 152, 3, 145, NULL),
(8, 153, 3, 145, NULL),
(8, 154, 3, 145, NULL),
(36, 121, 1, 0, NULL),
(36, 125, 3, 122, NULL),
(36, 122, 2, 121, NULL),
(36, 123, 2, 121, NULL),
(36, 124, 2, 121, NULL),
(36, 144, 2, 121, NULL),
(36, 145, 2, 121, NULL),
(36, 135, 3, 123, NULL),
(36, 136, 3, 123, NULL),
(36, 137, 3, 123, NULL),
(36, 138, 3, 123, NULL),
(36, 139, 3, 123, NULL),
(36, 140, 3, 123, NULL),
(36, 141, 3, 123, NULL),
(36, 142, 3, 123, NULL),
(36, 143, 3, 123, NULL),
(36, 146, 3, 145, NULL),
(36, 147, 3, 145, NULL),
(36, 148, 3, 145, NULL),
(36, 149, 3, 145, NULL),
(36, 150, 3, 145, NULL),
(36, 151, 3, 145, NULL),
(36, 152, 3, 145, NULL),
(36, 153, 3, 145, NULL),
(36, 154, 3, 145, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_group`
--

CREATE TABLE IF NOT EXISTS `lyx_group` (
  `id` smallint(3) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `app` smallint(6) DEFAULT NULL COMMENT '应用端的id'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `lyx_group`
--

INSERT INTO `lyx_group` (`id`, `name`, `title`, `create_time`, `update_time`, `status`, `sort`, `show`, `app`) VALUES
(1, '系统管理', '系统管理', 0, 0, 1, 0, 0, NULL),
(2, '文章管理', '文章管理', 0, 0, 1, 0, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_node`
--

CREATE TABLE IF NOT EXISTS `lyx_node` (
  `id` smallint(6) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `group_id` tinyint(3) unsigned DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=196 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `lyx_node`
--

INSERT INTO `lyx_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`, `type`, `group_id`) VALUES
(121, 'Cms', '内容管理', 1, '', NULL, 0, 1, 0, NULL),
(122, 'Index', '后台首页', 1, '', NULL, 121, 2, 0, NULL),
(123, 'Category', '内容分类', 1, '', NULL, 121, 2, 0, NULL),
(124, 'Article', '文章管理', 1, '', NULL, 121, 2, 0, NULL),
(125, 'index', '后台首页页面权限', 1, '                                                         		', NULL, 122, 3, 0, NULL),
(126, 'index', '列表页权限', 1, '                                                         		', NULL, 124, 3, 0, NULL),
(127, 'add', '添加页面权限', 1, '', NULL, 124, 3, 0, NULL),
(128, 'edit', '修改页面', 1, '', NULL, 124, 3, 0, NULL),
(129, 'update', '更新权限', 1, '', NULL, 124, 3, 0, NULL),
(130, 'insert', '添加权限', 1, '', NULL, 124, 3, 0, NULL),
(131, 'delete', '删除权限', 1, '', NULL, 124, 3, 0, NULL),
(132, 'del', '批量删除权限', 1, '', NULL, 124, 3, 0, NULL),
(133, 'forbid', '禁用权限', 1, '', NULL, 124, 3, 0, NULL),
(134, 'resume', '启用权限', 1, '', NULL, 124, 3, 0, NULL),
(135, 'index', '列表页面', 1, '                                                         		', NULL, 123, 3, 0, NULL),
(136, 'add', '添加页面', 1, '', NULL, 123, 3, 0, NULL),
(137, 'edit', '编辑页面', 1, '', NULL, 123, 3, 0, NULL),
(138, 'insert', '添加权限', 1, '', NULL, 123, 3, 0, NULL),
(139, 'update', '更新权限', 1, '', NULL, 123, 3, 0, NULL),
(140, 'delete', '删除权限', 1, '', NULL, 123, 3, 0, NULL),
(141, 'del', '批量删除权限', 1, '', NULL, 123, 3, 0, NULL),
(142, 'resume', '启用权限', 1, '', NULL, 123, 3, 0, NULL),
(143, 'forbid', '禁用权限', 1, '', NULL, 123, 3, 0, NULL),
(144, 'CommCategory', '商品分类', 1, '', NULL, 121, 2, 0, NULL),
(145, 'CommProducts', '商品详情', 1, '', NULL, 121, 2, 0, NULL),
(146, 'add', '添加页面', 1, '', NULL, 145, 3, 0, NULL),
(147, 'edit', '编辑页面', 1, '', NULL, 145, 3, 0, NULL),
(148, 'index', '列表页面', 1, '', NULL, 145, 3, 0, NULL),
(149, 'update', '更新权限', 1, '', NULL, 145, 3, 0, NULL),
(150, 'insert', '新增权限', 1, '', NULL, 145, 3, 0, NULL),
(151, 'resume', '启用权限', 1, '', NULL, 145, 3, 0, NULL),
(152, 'forbid', '禁用权限', 1, '', NULL, 145, 3, 0, NULL),
(153, 'delete', '删除权限', 1, '', NULL, 145, 3, 0, NULL),
(154, 'del', '批量删除权限', 1, '', NULL, 145, 3, 0, NULL),
(164, 'index', '列表页面', 1, NULL, NULL, 144, 3, NULL, NULL),
(165, 'add', '添加页面', 1, NULL, NULL, 144, 3, NULL, NULL),
(166, 'edit', '编辑页面', 1, NULL, NULL, 144, 3, NULL, NULL),
(167, 'insert', '添加权限', 1, NULL, NULL, 144, 3, NULL, NULL),
(168, 'update', '更新权限', 1, NULL, NULL, 144, 3, NULL, NULL),
(169, 'delete', '删除权限', 1, NULL, NULL, 144, 3, NULL, NULL),
(170, 'del', '批量删除权限', 1, NULL, NULL, 144, 3, NULL, NULL),
(171, 'resume', '启用权限', 1, NULL, NULL, 144, 3, NULL, NULL),
(172, 'forbid', '禁用权限', 1, NULL, NULL, 144, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_notice_category`
--

CREATE TABLE IF NOT EXISTS `lyx_notice_category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '接口名称',
  `str` varchar(255) NOT NULL COMMENT '接口标识，必须为字符串'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `lyx_notice_users`
--

CREATE TABLE IF NOT EXISTS `lyx_notice_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '通知人的姓名',
  `openid` varchar(255) DEFAULT NULL COMMENT '微信的唯一标识',
  `pid` int(11) DEFAULT NULL COMMENT '分类的id'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `lyx_role`
--

CREATE TABLE IF NOT EXISTS `lyx_role` (
  `id` smallint(6) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL COMMENT '排序'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `lyx_role`
--

INSERT INTO `lyx_role` (`id`, `name`, `pid`, `status`, `remark`, `sort`) VALUES
(11, 'k网站用户群租', 0, 1, '2222', 1),
(12, 'k网站用户群租', 0, 1, '2222', 1),
(13, 'k网站用户群租', 0, 1, '2222', 1),
(14, 'k网站用户群租', 0, 1, '2222', 1),
(15, 'k网站用户群租', 0, 1, '2222', 1),
(16, 'k网站用户群租', 0, 1, '2222', 1),
(17, 'k网站用户群租', 0, 1, '2222', 1),
(18, 'k网站用户群租', 0, 1, '2222', 1),
(19, 'k网站用户群租', 0, 1, '2222', 1),
(20, 'k网站用户群租', 0, 1, '2222', 1),
(21, 'k网站用户群租', 0, 1, '2222', 1),
(22, '网站用户群租', 0, 1, '2222', 1),
(23, 'k网站用户群租', 0, 1, '2222', 1),
(27, '网站用户群租', 0, 1, '2222', 1),
(28, '网站用户群租', 0, 1, '2222', 1),
(29, '网站用户群租', 0, 1, '2222', 1),
(30, '网站用户群租', 0, 1, '2222', 1),
(31, '网站用户群租', 0, 1, '2222', 1),
(32, '网站用户群租', 0, 1, '2222', 1),
(33, '网站用户群租', 0, 1, '2222', 1),
(34, '网站用户群租', 0, 1, '2222', 1),
(35, '网站用户群租', 0, 1, '2222', 1),
(36, '想休息休息1', NULL, 1, '3333', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_role_user`
--

CREATE TABLE IF NOT EXISTS `lyx_role_user` (
  `role_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `lyx_role_user`
--

INSERT INTO `lyx_role_user` (`role_id`, `user_id`) VALUES
(8, 4),
(11, 4);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_site`
--

CREATE TABLE IF NOT EXISTS `lyx_site` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '站点名称',
  `url` varchar(255) DEFAULT NULL COMMENT '网站域名',
  `str` varchar(255) DEFAULT NULL COMMENT '网站安全吗16位',
  `state` int(11) DEFAULT NULL COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `lyx_site`
--

INSERT INTO `lyx_site` (`id`, `title`, `url`, `str`, `state`) VALUES
(1, '第一个网站', 'www.sylyx.cn', 'sdfjsldjfojo34l23j4ljlsjdfljsdlkfjowuerosf', 1),
(2, 'facpros', 'www.facpros.cn', 'DGSs54234oerDFL5234JLJ34OUO234', 1),
(4, '第二个网站', 'www.baidu.com', 'sldfjlsjdlfjlwoew', 1);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_user`
--

CREATE TABLE IF NOT EXISTS `lyx_user` (
  `id` smallint(5) unsigned NOT NULL,
  `account` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `site` int(11) NOT NULL DEFAULT '0' COMMENT '网站的id号',
  `phone` varchar(11) DEFAULT NULL COMMENT '电话',
  `openid` varchar(255) DEFAULT NULL COMMENT '公众号对应的用户openid',
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `lyx_user`
--

INSERT INTO `lyx_user` (`id`, `account`, `nickname`, `password`, `last_login_time`, `last_login_ip`, `login_count`, `email`, `remark`, `create_time`, `update_time`, `status`, `site`, `phone`, `openid`, `sort`) VALUES
(1, 'admin', '管理员', '15b09780a0286ca79d2d69e3b16d2d40', 1460954166, '10.211.55.2', 7, '', '', 0, 0, 1, 0, NULL, NULL, NULL),
(4, 'mikes', 'mike', '15b09780a0286ca79d2d69e3b16d2d40', 1460353127, '10.211.55.2', 7, 'admin@126.com', '', 1458629742, 1458634113, 1, 4, NULL, NULL, NULL),
(6, 'abc123', 'sdsad', '96e79218965eb72c92a549dd5a330112', 0, NULL, 0, '', '', 1481186824, 0, 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `lyx_user_group`
--

CREATE TABLE IF NOT EXISTS `lyx_user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL COMMENT '群组名称',
  `status` varchar(1) NOT NULL DEFAULT '1' COMMENT '状态1启用，0停用，默认是1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `lyx_wxchat_account`
--

CREATE TABLE IF NOT EXISTS `lyx_wxchat_account` (
  `id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL COMMENT '随机生成的密码32位（服务器生成）',
  `encodingaeskey` varchar(255) DEFAULT NULL COMMENT '加密秘药',
  `appid` varchar(255) DEFAULT NULL,
  `appsecret` varchar(255) DEFAULT NULL,
  `mch_id` varchar(255) NOT NULL COMMENT '商户id',
  `md5_key` varchar(255) NOT NULL COMMENT 'md5 秘钥',
  `cert_path` varchar(255) NOT NULL,
  `key_path` varchar(255) NOT NULL COMMENT '密钥路径',
  `notify_url` varchar(255) NOT NULL COMMENT '密钥路径',
  `level` int(11) DEFAULT NULL COMMENT '公众号等级',
  `access_token` varchar(255) DEFAULT NULL COMMENT '签名token'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `lyx_wxchat_account`
--

INSERT INTO `lyx_wxchat_account` (`id`, `token`, `encodingaeskey`, `appid`, `appsecret`, `mch_id`, `md5_key`, `cert_path`, `key_path`, `notify_url`, `level`, `access_token`) VALUES
(1, 'star409964901', 'oCCw99JBgEEe6RuMtfrA3JA3qK99PDuHxXkqXGHR0Qv', 'wxaadc01561c625579', '9a971876c4a2658c9dfcd63d317a99ba', '1417015102', 'Lfeo9343DLFjlseo013jll34l2j3s4as', '', '', '', 1, '1'),
(3, '3', '3', '3', '3', '', '', '', '', '', 3, '3'),
(6, '6', '6', NULL, NULL, '', '', '', '', '', NULL, NULL),
(9, '9', '9', NULL, NULL, '', '', '', '', '', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lyx_access`
--
ALTER TABLE `lyx_access`
  ADD KEY `groupId` (`role_id`),
  ADD KEY `nodeId` (`node_id`);

--
-- Indexes for table `lyx_group`
--
ALTER TABLE `lyx_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lyx_node`
--
ALTER TABLE `lyx_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level` (`level`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `lyx_notice_category`
--
ALTER TABLE `lyx_notice_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lyx_notice_users`
--
ALTER TABLE `lyx_notice_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lyx_role`
--
ALTER TABLE `lyx_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `lyx_role_user`
--
ALTER TABLE `lyx_role_user`
  ADD KEY `group_id` (`role_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lyx_site`
--
ALTER TABLE `lyx_site`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lyx_user`
--
ALTER TABLE `lyx_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`);

--
-- Indexes for table `lyx_user_group`
--
ALTER TABLE `lyx_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lyx_wxchat_account`
--
ALTER TABLE `lyx_wxchat_account`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lyx_group`
--
ALTER TABLE `lyx_group`
  MODIFY `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lyx_node`
--
ALTER TABLE `lyx_node`
  MODIFY `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=196;
--
-- AUTO_INCREMENT for table `lyx_notice_category`
--
ALTER TABLE `lyx_notice_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `lyx_notice_users`
--
ALTER TABLE `lyx_notice_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `lyx_role`
--
ALTER TABLE `lyx_role`
  MODIFY `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `lyx_site`
--
ALTER TABLE `lyx_site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `lyx_user`
--
ALTER TABLE `lyx_user`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `lyx_wxchat_account`
--
ALTER TABLE `lyx_wxchat_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

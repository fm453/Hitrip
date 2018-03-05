<?php
/**
 * @author Fm453(方少)
 * @DACMS https://api.hiluker.com
 * @site https://www.hiluker.com
 * @url http://s.we7.cc/index.php?c=home&a=author&do=index&uid=662
 * @email fm453@lukegzs.com
 * @QQ 393213759
 * @wechat 393213759
*/

/*
 * @remark 清空已点菜单
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

//入口判断
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$op            = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//开始操作管理
$shopname      =$settings['brands']['shopname'];
$shopname      = !empty($shopname) ? $shopname :FM_NAME_CN;

//页面具体操作
pdo_delete('fm453_vfoods_cart', array('uniacid' => $_W['uniacid'], 'from_user' => $_W['fans']['from_user']));
message('清空菜单成功。', fm_murl($do,'list','index',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');

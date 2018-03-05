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
 * @remark 前端演示
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('view'); 	//浏览量处理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//来路计算
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID（从mc_members表中读取）
$fromplatid = intval($_GPC['fromplatid']);	//来源平台（用于跨平台支付等情形）
$from_user = $_W['openid'];
$url_condition="";
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = $shopname.'开发演示中心';
$pagename .='|'.$_W['account']['name'];
//初始权限判断
$_FM['settings']['force_follow']=$settings['force_follow']=FALSE;
$_FM['settings']['force_login']=$settings['force_login']=FALSE;

//进行演示，重新各种自定义
$appstyle='demo/';
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$pagetpl = $_GPC['pagetpl'];
$pagetpls=array(
	"accordion"=>'折叠面板',
	'typography'=>'排版',
	"actionsheet"=>'H5操作表',
	"picker"=>'下拉/级联选择器',
	"dtpicker"=>'日期选择器',
	'tabbar'=>'底部选项卡-div模式',
	'tabbar-with-submenus'=>'底部选项卡-二级菜单(div)',
	'tabbar-labels-only'=>'文字选项卡',
	'switches'=>'开关',
	'tab-vertical-scroll'=>'侧面选项卡',
	'tab-with-segmented-control'=>'顶部选项卡-div模式',
	'tab-with-segmented-control-vertical'=>'侧面选项卡-div模式',
	'tab-with-viewpagerindicator'=>'顶部选项卡-可左右拖动',
	'tableviews'=>'普通列表',
	'tableviews-with-swipe'=>'滑动触发列表项菜单',
	'tableviews-with-collapses'=>'二级列表',
	'tableviews-with-badges'=>'列表右侧带数字角标',
	'grid-default'=>'9宫格默认样式',
	'grid-pagination'=>'左右拖动分页9宫',
	'indexed-list'=>'索引列表',
	'indexed-list-select'=>'可选择的索引列表',
	'list-with-input'=>'带input类的列表',
	'input'=>'输入框',
	'icons'=>'图标',
	'icons-extra'=>'附加图标',
	'range'=>'滑块',
	'radio'=>'单选框',
	'badges'=>'数字角标',
	'ajax'=>'ajax网络请求',
	'buttons'=>'按钮',
	'checkbox'=>'复选框',
	'dialog'=>'消息框',
	'echarts'=>'EChart图表',
	'feedback'=>'问题反馈',
	'locker'=>'手势图案锁屏',
	'nav'=>'导航栏',
	'numbox'=>'数字输入框',
	'popovers'=>'弹出菜单',
	'modals'=>'弹出窗口',
	'tab-webview-subpage-contact'=>'选项卡视图-通讯录方式'
	//以下plus才能用
	//'date'=>'日期选择-native模式',
	//'tab-webview-main'=>'侧面选项卡',
);

$address['province']="海南省";
$address['city']="三亚市";
$address['district']="";
$optionsdata=array(
	0=>array('text'=>'测试一','value'=>'1'),
	1=>array('text'=>'测试二','value'=>'2'),
	2=>array('text'=>'测试三','value'=>'3')
);
 $optionsdata = json_encode($optionsdata);
 //print_r($optionsdata);
//自定义微信分享内容(必须在渲染HTML文件之前)
$_share = array();
$_share['title'] = $pagename;
$_share['link'] = fm_murl($do,$ac,$operation,array('pagetpl'=>$pagetpl));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = '我发现一个不错的旅游行业的微商城系统(Hitrip),现在分享链接给你！';

if($ac=='index' ) {
	include $this->template($appstyle.$ac);
}elseif($ac=='entry' ) {
	include $this->template($appstyle.$ac);
}elseif($ac=='guide' ) {
	include $this->template($appstyle.$ac);
}elseif($ac=='examples' ) {
	include $this->template($appstyle.$ac.'/'.$pagetpl);
}else{
	include $this->template($appstyle.$ac.'/'.$operation.'/'.$pagetpl);
}

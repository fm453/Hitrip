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
 * @remark 接口-畅言评论接口 changyan.kuaizhan.com
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->model('account');//加载公众号函数
fm_load()->fm_model('article');
fm_load()->fm_class('changyan');
fm_load()->fm_func('view'); 	//浏览量处理

//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];

$operation_i = stripos($_GPC['op'],'?');		//判断后面是否跟了?from= 的内容
$operation =substr($_GPC['op'],0,$operation_i);    //由于有回调from网址参数的原因，op无法直接获取
$redirect_url = substr($_GPC['op'],$operation_i+6);    //?from= 之后的部分为返回网址

$operation = empty($operation) ? $_GPC['op'] : $operation;

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;

//初始化接口类
$changyan=new fmClass_changyan();

if($operation=='index')
{
	//获取用户信息
	$changyan->getuserinfo();
}elseif($operation=='login')
{
	//用户登录
	if($_GET['user_id']==''){//判断畅言是否绑定了该用户
		$changyan->login_nouid(); //没绑定,说明畅言的用户cy_user_id与您的网站用户user_id不存在绑定关系，你可以引导用户进行下一步行为

		/*———————首次绑定的时候做记录设置与微信知会————————*/
		$data=array(
			'title'=>'uid',
			'status'=>'127',
			'value'=>$_GET['user_id']
		);
		fmMod_member_settings_save($_FM['member']['uid'],$openid=$_W['openid'],$data,$setfor='changyan', $uniacid=$_W['uniacid']);
		$NOTICE = "您的账号刚刚绑定了畅言评论";
		$result=fmMod_notice($_W['openid'],$NOTICE);

	}else{
		$changyan->login_hasuid(); //已绑定
	}
}elseif($operation=='logout')
{
	//用户登出
	if($_FM['member']['uid']==0)
	{
		$return=array(
			'code'=>1,
			'reload_page'=>0
		);
	}else{
		$mwuser->logout();
		$return=array(
			'code'=>1,
			'reload_page'=>1
		);
	}
}elseif($operation=='callback'){
	//接收回调信息
	$originalData=$_POST['data'];
		//$originalData='{"comments":[{"apptype":43,"attachment":[],"channelid":1038740,"channeltype":1,"cmtid":"1298634613","content":"\u6d4b\u8bd5","ctime":1486721165000,"from":0,"ip":"124.225.89.46","opcount":0,"referid":"1298634613","replyid":"0","spcount":0,"status":0,"user":{"nickname":"\u8def\u5ba2\u7f51\u7edcHilukerOOP","sohuPlusId":568975751,"usericon":"http:\/\/sucimg.itc.cn\/avatarimg\/568975751_1486692880573_c55"},"useragent":"Mozilla\/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit\/601.1.46 (KHTML, like Gecko) Version\/9.0 Mobile\/13B143 Safari\/601.1"}],"metadata":"{\\"ChangY_ImgTag\\":\\"YES\\",\\"local_ip\\":\\"10.11.157.47\\",\\"ChangY_Img\\":\\"http:\/\/0d077ef9e74d8.cdn.sohucs.com\/topic_picture_2537575679_1486701692817\\"}","sourceid":"7-","title":"\u4eba\u53ef\u4ee5\u767d\u624b\u8d77\u5bb6\uff0c\u4f46\u4e0d\u53ef\u4ee5\u624b\u65e0\u5bf8\u94c1","ttime":1486700347000,"url":"http:\/\/vcms.localhost\/app\/index.php?c=entry&i=1&m=fm453_shopping&do=article&ac=detail&op=index&fromplatid=1&lastid=&shareid=&id=7"}';
	$data=$originalData;
	$ischecked = $changyan->data_check($data);	//校验数据
	if(!$ischecked) {
		exit();
	}
	//写入操作日志START
	$dologs=array(
		'url'=>$_W['siteurl'],
		'description'=>'畅言评论回调日志',
		'addons'=>$originalData,
	);
	fmMod_log_record($platid,$uniacid,$uid,$fanid,$openid,'',$id,'comment',$dologs);
	unset($dologs);
	//写入操作日志END

	fm_load()->fm_func('comment');	//评论处理函数
	fmFunc_comment_save($data,'changyan');
}

?>

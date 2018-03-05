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
 * @remark 消息处理器
 */
defined('IN_IA') or exit('Access Denied');
$_FM_NAME = array_reverse(explode(DIRECTORY_SEPARATOR,dirname(__FILE__)));
$_FM_NAME = $_FM_NAME[0];
define('FM_NAME',$_FM_NAME);
require IA_ROOT."/addons/". FM_NAME ."/version.php";
require IA_ROOT."/addons/". FM_NAME ."/core/defines.php";
require IA_ROOT."/addons/". FM_NAME ."/core/fmloader.php";
fm_load()->fm_func('fm'); //内置专用函数库
fm_load()->fm_func('url'); //链接路由处理
fm_load()->fm_func('server'); //授权服务器
fm_load()->fm_func('fans'); //内置专用函数库
fm_load()->fm_func('route'); //获取路径函数
fm_load()->fm_func('tables'); //数据表函数
fm_load()->fm_func('qrcode'); //二维码处理
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_func('mobile'); 	//手机号处理
fm_load()->fm_func('bankcard'); 	//银行卡处理
fm_load()->fm_func('identitycard'); //身份证号校验函数库
fm_load()->fm_func('pinyin'); //汉字转拼音函数库
fm_load()->fm_model('setting'); //内置模块配置模块
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_func('msg');//消息通知前置函数
fm_load()->fm_model('notice');//消息通知模块
fm_load()->fm_func('api'); //api数据接口函数库

class Fm453_immsModuleProcessor extends WeModuleProcessor {
/**
$this->message
@	array(
@	'from' => 'fromUser',  //来源openid
@	'to' => 'toUser', //公众号标识
@	'time' => '1448694116',
@	'type' => 'text', //消息类型(text,文本；image,图片；voice，语音；video，视频；shortvideo，小视频；location,地理位置；link，链接；subscribe，关注；qr，二维码；trace，上报地理位置；click，点击菜单；enter，；）
@	'event' => '',
@	'tousername' => 'toUser', //同from
@	'fromusername' => 'fromUser', //同to
@	'createtime' => '1448694116',
@	'msgtype' => 'text',
@	'content' => '官方示例', //关键字
@	'msgid' => '1234567890123456',
@	'redirection' => '',  //是否有消息重定向，例如扫描二维码后触发某一个关键字，这样的消息就属于重定向消息
@	'source' => '', //重定向消息原消息类型
@	);

$this→respNews(array $news); 回复一个图文消息
$this→respMusic(array $music); 回复一个音乐消息
$this->respText();	回复文本信息

**/

	public $name = 'HiChatRobotModuleProcessor';
	public $EARTH_RADIUS = 6371; //地球半径，平均半径为6371km

	public function respond() {
		global $_W;

		if ($this->inContext) {
			//处于上下文环境中时使用的规则
			return $this->post();
		} else {
			//初次进入的规则()
			return $this->welcome();
		}
	}

	private function welcome() {
		global $_GPC;
		global $_W;
		global $_FM;
		$message = $this -> message;
		$openid = $this -> message['from'];	//来源用户的openid
		$content = $this -> message['content'];	//关键字
		$msgtype = strtolower($message['msgtype']);	//消息类型
		$event = strtolower($message['event']);	//事件
		$rid = $this->rule;	//当前的回复规则序号

		if (empty($_W['openid'])) {
			return '请先关注公众号才能正常使用本系统功能！';
		}

		$settings = fmMod_settings_all();//全局加载配置
		$uniacid=$_W['uniacid'];
		$plattype=$settings['plattype'];
		$platids = fmFunc_getPlatids($settings);//获取平台模式关联的公众号商户ID参数
		$oauthid=$platids['oauthid'];
		$fendianids=$platids['fendianids'];
		$supplydianids=$platids['supplydianids'];
		$blackids=$platids['blackids'];

		//通过API接口取配置数据
		$getData = array();
		$getData['platid'] = $oauthid;
		$getData['uniacid'] = $_W['uniacid'];
		$getData['shopid'] = 0;
		//先取微搜索模型信息
		$getData['ac'] = 'model';
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result) {
			$ModelData = $result;
		}

		$tips = $ModelData['params']['default']['reply']['welcome']['tips'];

		$this->beginContext();
		$tips = empty($tips) ? '请输入您要查询的内容？' : $tips;

		return $this->respText($tips);
	}

	private function post() {
		global $_GPC;
		global $_W;
		global $_FM;
		$message = $this -> message;
		$openid = $this -> message['from'];	//来源用户的openid
		$content = $this -> message['content'];	//关键字
		$msgtype = strtolower($message['msgtype']);	//消息类型
		$event = strtolower($message['event']);	//事件
		$rid = $this->rule;	//当前的回复规则序号

		if (!in_array($msgtype, array('text', 'image'))) {
			$tips = '只能接受文字和图片消息';
			return $this->respText($tips);
		}

		//默认搜索类型触发词组
		$allKeywords = array();
		$allKeywords['_goods'] = '产品';
		$allKeywords['_member'] = '会员';
		$allKeywords['_article'] = '文章';

		$settings = fmMod_settings_all();//全局加载配置
		$uniacid=$_W['uniacid'];
		$plattype=$settings['plattype'];
		$platids = fmFunc_getPlatids($settings);//获取平台模式关联的公众号商户ID参数
		$oauthid=$platids['oauthid'];
		$fendianids=$platids['fendianids'];
		$supplydianids=$platids['supplydianids'];
		$blackids=$platids['blackids'];

		//通过API接口取配置数据
		$getData = array();
		$getData['platid'] = $oauthid;
		$getData['uniacid'] = $_W['uniacid'];
		$getData['shopid'] = 0;
		//先取微搜索模型信息
		$getData['ac'] = 'model';
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result) {
			$ModelData = $result;
		}
		$s_sn = $ModelData['sn'];
		$f_sn = cache_load(md5(FM_NAME.'fm_processor_reply_instance_'.$openid));
		//取微搜索实例列表
		$search_instance_list = array();
		$getData['ac'] = 'instance';
		$getData['op'] = 'all';
		$postUrl = '/index.php?r=search/get';
		$postData = array();
		$postData['s_sn'] = $s_sn;
		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$Instances = $result;

		foreach($Instances as $k => $instanceData){
			$isavailable = true;
			if($instanceData['params']['status']==0){
				$isavailable = false;
			}

			if($instanceData['params']['open']==0){
				$isavailable = false;
			}

			if(isset($instanceData['params']['opendate']) && (intval($instanceData['params']['opendate'])<$_W['timestamp'] && intval($instanceData['params']['opendate'])>0)) {
				$isavailable = false;	//还未开始
			}

			if(isset($instanceData['params']['closedate']) && ($instanceData['params']['closedate']>$_W['timestamp'] && intval($instanceData['params']['closedate'])>0)) {
				$isavailable = false;	//已经结束
			}

			if($isavailable) {
				$key = $instanceData['params']['keywords'];
				$allKeywords[$key] = $instanceData['params']['reply']['welcome']['key'];
				$search_instance_list[$key] = $instanceData;
			}
		}

		$searchType = cache_load(md5(FM_NAME.'fm_processor_reply_type_'.$openid));

		if(!$searchType) {
			//无搜索类型的缓存约定时，为一次新的搜索请求,回应搜索提示
			if($content == $allKeywords['_goods']){
				$tips = '请输入您想要查询的产品关键词。
					提示：您可使用 产品名称中的词语作为条件(如，××酒店，芒果等)，进行模糊查询匹配；我们默认最多取符合条件的前9个作为结果，回复给您。';
				if(isset($search_instance_list['_goods'])){
					$InstanceData = $search_instance_list['_goods'];
					$tips = $InstanceData['params']['reply']['welcome']['tips'];
				}
				cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),'_goods');
				return $this->respText($tips);
			}elseif($content == $allKeywords['_member']){
				$tips = '请输入您想要查询的乡友关键词。
					提示：您可使用 姓名、籍贯、行业等条件，进行模糊查询匹配；我们默认最多取符合条件的前9个作为结果，回复给您。';
					cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),'_member');
					return $this->respText($tips);
			}elseif($content == $allKeywords['_article']){
				$tips = '请输入您想要查询的文章内容关键词。
					提示：我们默认最多取符合条件的前9个作为结果，回复给您。';
					cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),'_article');
					return $this->respText($tips);
			}elseif(in_array($content,$allKeywords)){
				$key = array_search($content,$allKeywords);
				$InstanceData = $search_instance_list[$key];	//找到关键字的搜索实例
				$f_sn = $InstanceData['sn'];
				$tips = $InstanceData['params']['reply']['welcome']['tips'];
				cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),$key);
				cache_write(md5(FM_NAME.'fm_processor_reply_instance_'.$openid),$f_sn);
				return $this->respText($tips);
			}

		switch($content) {

			default:
				$tips = $ModelData['params']['default']['reply']['notype']['tips'];
				$tips = !empty($tips) ? $tips : '系统尚不明确您要查询的内容，请根据指引输入有效的查询方式。
				提示：如您在使用过程中有任何疑问的，请参考我们的使用指南，或联系在线客服寻求帮助。';
				cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),'_error');
				return $this->respText($tips);
			break;
		}

	}else{

		$keywords = explode('+',$content);
		$quit_key = isset($ModelData['params']['default']['reply']['quit']['key']) ? $ModelData['params']['default']['reply']['quit']['key'] : 0;
		$back_key = isset($ModelData['params']['default']['reply']['back']['key']) ? $ModelData['params']['default']['reply']['back']['key'] : 1;
		//先对约定规则进行响应
		if($content == $quit_key){
			//退出查询
				$this->endContext();
				$tips = $ModelData['params']['default']['reply']['quit']['tips'];
				$tips = !empty($tips) ? $tips : '好的，您已退出查询。欢迎再次使用！';
				cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),'');
				return $this->respText($tips);
		}elseif($content == $back_key){
			//返回上级查询(选择查询模式)
				$tips = $ModelData['params']['default']['reply']['back']['tips'];
				$tips = !empty($tips) ? $tips : '好的，您需要查找哪方面的内容呢？';
				cache_write(md5(FM_NAME.'fm_processor_reply_type_'.$openid),'');
				$this->refreshContext();
				return $this->respText($tips);
		}

		//根据查询类型不同，作不同响应
		switch($searchType) {
			case '_goods':

				$sql = 'SELECT id,sn,title,description,thumb,xsthumb FROM ' . tablename('fm453_shopping_goods') . ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted AND `status` > :status  AND (`title` LIKE :title OR `description` LIKE :keyword) ORDER BY displayorder DESC, isrecommand DESC, id DESC LIMIT 0,8 ';

				$list = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':deleted' => 0,':status' => 0,':title' => "%". trim($content)."%", ':keyword' => "%". trim($content)."%"));
				$news = array();
				if(!empty($list)){
					foreach($list as $goods){
						$row = array();
						$row['title'] = $goods['title'];
						$row['description'] = strip_tags($goods['description']);
						$row['picurl'] = tomedia($goods['thumb']);
						$row['url'] = fm_murl('goods', 'detail', 'index',array('id' => $goods['id']));
						$news[] = $row;
					}
				}else{
					$tips = '您正在查询产品；
					关键词:'.$content.';
					查询结果:抱歉，未查询到相关产品，您可尝试一下其他关键词。';
					return $this->respText($tips);
				}

				return $this->respNews($news);

			break;

			case '_member':

				$sql = 'SELECT id,sn FROM ' . tablename('fm453_site_article') . ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted AND `a_tpl` = :atpl AND `status` > :status AND (`title` LIKE :title OR `keywords` LIKE :keyword) ORDER BY displayorder DESC, isrecommand DESC, id DESC LIMIT 0,8 ';

				$list = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':deleted' => 0,':atpl' => 'member',':status' => 0,':title' => "%". trim($content)."%", ':keyword' => "%". trim($content)."%"));
				$news = array();
				if(!empty($list)){
					foreach($list as $article){
						$row = array();
						fm_load()->fm_model('article');//文章模块
						$id = $article['sn'];
						$goods = fmMod_article_basic($id);
						$row['title'] = $goods['title'];
						$row['description'] = strip_tags($goods['description']);
						$row['picurl'] = tomedia($goods['thumb']);
						$row['url'] = fm_murl('article', 'detail', 'index',array('id' => $goods['id']));
						$news[] = $row;
					}
				}else{
					$tips = '您正在乡友资料；
					关键词:'.$content.';
					查询结果:抱歉，未查询到相关的资料，您可尝试一下其他关键词。';
					return $this->respText($tips);
				}

				return $this->respNews($news);
			break;

			case '_article':

				$sql = 'SELECT id,sn FROM ' . tablename('fm453_site_article') . ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted AND `status` > :status AND (`title` LIKE :title OR `keywords` LIKE :keyword) ORDER BY displayorder DESC, isrecommand DESC, id DESC LIMIT 0,8 ';

				$list = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':deleted' => 0,':status' => 0,':title' => "%". trim($content)."%", ':keyword' => "%". trim($content)."%"));
				$news = array();
				if(!empty($list)){
					foreach($list as $article){
						$row = array();
						fm_load()->fm_model('article');//文章模块
						$id = $article['sn'];
						$goods = fmMod_article_basic($id);
						$row['title'] = $goods['title'];
						$row['description'] = strip_tags($goods['description']);
						$row['picurl'] = tomedia($goods['thumb']);
						$row['url'] = fm_murl('article', 'detail', 'index',array('id' => $goods['id']));
						$news[] = $row;
					}
				}else{
					$tips = '您正在查询文章内容;
					关键词:'.$content.';
					查询结果:抱歉，未查询到相关的资料，您可尝试一下其他关键词';
					return $this->respText($tips);
				}

				return $this->respNews($news);
			break;

			case 'dangan':

				if(count($keywords)<2){
					$tips = '您查询的格式不正确，请参考：
					查询格式：“姓名” + “身份证号”
					示范1：方少+460001198806020898';
					return $this->respText($tips);
				}
				$name = $keywords[0];
				$idnum = $keywords[1];
				if(!fmFunc_idcard_validation_filter($idnum)){
					$tips = '您输入的身份证号不正确，请检查并重新输入;';
					return $this->respText($tips);
				}

				$result = '';	//查询结果-TBD
				$getData['ac'] = 'content';
				$getData['op'] = '';
				$getData['nocache'] = '1';
				$postUrl = '/index.php?r=search/get';
				$postData = array();
				$postData['s_sn'] = $s_sn;
				$postData['f_sn'] = $f_sn;
				$postData['searching']['keyword'] = trim($content);
				$postData['sql_limits'] = array(
					'start' => 0,
					'end' => 1,
				);

				//取内容
				$result = fmFunc_api_push($postUrl,$postData,$getData);
				if($result){
					$ContentData = $result[0];
					$tips = $ContentData['value'];

				}else{
					//无结果
					$tips = '您好，'.$name.',您的档案在我局存放，请带好身份证，来我局二楼窗口办理;
				地址：白龙南路53号';
				}


				return $this->respText($tips);
			break;

			case 'shiyejin':

				if(count($keywords)<2){
					$tips = '您查询的格式不正确，请参考：
					查询格式：“姓名” + “身份证号”
					示范1：方孟+460001198806020898';
					return $this->respText($tips);
				}
				$name = $keywords[0];
				$idnum = $keywords[1];
				if(!fmFunc_idcard_validation_filter($idnum)){
					$tips = '您输入的身份证号不正确，请检查并重新输入;';
					return $this->respText($tips);
				}

				$result = '';	//查询结果-TBD

				$tips = '您好，'.$name.',您查询的信息已找到——
				您3月份的失业金发放情况如下：
				总计：1062.36元;
				查询信息以网站公布数据为准； 如果您有异议，请致电 0898-65312345';
				return $this->respText($tips);
			break;

			case '_error':
				$tips = '系统尚不明确您要查询的内容，请根据指引输入有效的查询方式：
				提示(1)：如您在使用过程中有任何疑问的，请参考我们的使用指南，或联系在线客服寻求帮助。
				(2)您还可以回复数字0,退出当前查询。';
				return $this->respText($tips);
			break;

			default:
				$tips = '系统尚不明确您要查询的内容，请根据指引输入有效的查询方式：
				提示(1)：如您在使用过程中有任何疑问的，请参考我们的使用指南，或联系在线客服寻求帮助。
				(2)您也可以回复数字1,返回上级查询。
				(3)您还可以回复数字0,退出当前查询。';
				return $this->respText($tips);
			break;
		}

	}

	//$this->refreshContext();

		// 文字类型信息
		if ($this->message['type'] == 'text') {
			$data['content'] = $this->message['content'];
		}

		// 图片类型信息
		if ($this->message['type'] == 'image') {
			load()->func('file');
			load()->func('communication');
			$image = ihttp_request($this->message['picurl']);
			$filename = 'images/' . $_W['uniacid'] . '/' . date('Y/m/') . md5(TIMESTAMP + CLIENT_IP + random(12)) . '.jpg';
			file_write($filename, $image['content']);
			$data['content'] = $filename;
		}

	}

	public function hookBefore() {
		global $_W, $engine;
	}
}

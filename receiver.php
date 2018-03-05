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
 * @remark 订阅接收器
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
fm_load()->fm_model('setting'); //内置模块配置模块
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_func('msg');//消息通知前置函数
fm_load()->fm_model('notice');//消息通知模块
fm_load()->fm_func('api'); //api数据接口函数库

class Fm453_immsModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W,$_GPC;
		load()->model('mc');
		load()->func('communication');
		$message = $this->message;
		$event = $this->message['event'];	//事件类型
		$openid = $this->message['from'];
		$status = isset($this->module['config']['status']) ? $this->module['config']['status'] : '0';	//模块是否设置状态为0

		$f_log = pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans') . " WHERE `uniacid` = '{$_W['uniacid']}' AND `openid` = '{$openid}'");	//是否有该粉丝的关注记录，根据情况取或生成uid
		if ($f_log['uid'] != 0) {
			//pdo_update('fm453_shopping_data', array('uid'=>$f_log['uid']), array('openid' => $openid));
			//已是系统会员
			$uid = $f_log['uid'];
		}else{
			//还未成为系统会员
			$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));	//取系统的默认会员组id
			$data = array(
				'uniacid' => $_W['uniacid'],
				'email' => md5($openid).'@we7.cc',
				'salt' => random(8),
				'groupid' => $default_groupid,
				'createtime' => TIMESTAMP,
			);
			$data['password'] = md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']);
			pdo_insert('mc_members', $data);	//插入会员
			$uid = pdo_insertid();
			pdo_update('mc_mapping_fans', array('uid'=>$uid),array('openid'=>$openid));
			//pdo_update('fm453_shopping_data', array('uid'=>$uid), array('openid' => $openid));
		}

		$credit_type = isset($this->module['config']['credit_type']) ? $this->module['config']['credit_type'] : 'credit1';
		$credit_subscribe = isset($this->module['config']['credit_subscribe']) ? $this->module['config']['credit_subscribe'] : 5;
		$credit_lever_1 = isset($this->module['config']['credit_lever_1']) ? $this->module['config']['credit_lever_1'] : 2;
		$credit_lever_2 = isset($this->module['config']['credit_lever_2']) ? $this->module['config']['credit_lever_2'] : 1;

		switch($event) {
			case 'subscribe':
				$s_log = [];
				//$s_log = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_data') . " WHERE `uniacid`='{$_W['uniacid']}' AND `openid`='{$openid}'");
				if (empty($s_log)) {//如果没记录
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'openid' => $openid,
					'uid' => $uid,
					'from_uid' => '0',
					'sn' => time(),
					'follow' => '1',
					'article_id' => '0',
					'shouyi' => $credit_subscribe,
					'createtime' => TIMESTAMP,
					);
				//pdo_insert('fm453_shopping_data',$insert);
				//mc_credit_update($uid,$credit_type,$credit_subscribe,array('1','关注增加积分'));
			}else{//如果有记录
				if ($s_log['follow'] != 1) {//如果记录未关注
					$insert = array(
						'follow' => '1',
						//'shouyi' => $s_log['shouyi'] + $credit_subscribe,
						);
					//pdo_update('fm453_shopping_data',$insert,array('id'=>$s_log['id']));
					//mc_credit_update($uid,$credit_type,$credit_subscribe,array('1','关注增加积分'));
				}
				if (!empty($s_log['from_uid'])) {//如果来源ID不为空
					$from_user = [];
					//$from_user = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_data') . " WHERE `uniacid`='{$_W['uniacid']}' AND `uid`='{$s_log['from_uid']}'");
					if (!empty($from_user)) {
						$data = array(
							'shouyi' => $from_user['shouyi'] + $credit_lever_1,
							'zjrs' => $from_user['zjrs'] + 1,
							);
						//pdo_update('fm453_shopping_data',$data,array('id'=>$from_user['id']));
						//mc_credit_update($s_log['from_uid'],$credit_type,$credit_lever_1,array('1','推荐一级关注增加积分'));
						if (!empty($from_user['from_uid'])) {
							$from_user_2 = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_data') . " WHERE `uniacid`='{$_W['uniacid']}' AND `uid`='{$from_user['from_uid']}'");
							$from_user_2 = [];
							if (!empty($from_user_2)) {
								$data2 = array(
									'shouyi' => $from_user_2['shouyi'] + $credit_lever_2,
									'jjrs' => $from_user_2['jjrs'] + 1,
									);
								//pdo_update('fm453_shopping_data',$data2,array('id'=>$from_user_2['id']));
								//mc_credit_update($from_user['from_uid'],$credit_type,$credit_lever_2,array('1','推荐二级关注增加积分'));
							}
						}
					}
				}
			}
			//pdo_update('fm453_shopping_data',array('follow'=>1),array('openid'=>$openid));
			break;

			default:
			break;
		}

		//TBD 不正确
		// 直接处理图片类型信息(上传到临时素材库后，再返回给用户)
		if ($this->message['type'] == 'image') {
			load()->func('file');
			load()->func('communication');
			$image = ihttp_request($this->message['picurl']);
			$filename = 'images/' . $_W['uniacid'] . '/' . date('Y/m/') . md5(TIMESTAMP + CLIENT_IP + random(12)) . '.jpg';
			file_write($filename, $image['content']);
			$data['content'] = $filename;
			$account_api = WeAccount::create();
			//文件上传
			$result = $account_api->uploadMedia($filename, 'image');	//上传到微信临时素材库
			//上传成功的结果
			/*
			array (
			'type' => 'image'
			'media_id' => 'PlwECnLT9a6btGwBjGzZ5zJC5Lf_BN1o0MIp9yWp6dxak3mrj0LXHKv0oISdmd-1'
			'created_at' => '1481007002'
			)
			*/
			$mediaid = $result['media_id'];

			$processor = new WeModuleProcessor;
			return $processor->respImage($mediaid);
		}

	}
}

?>

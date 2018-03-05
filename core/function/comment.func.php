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
 * @remark：评论与点评的处理方法
 */
defined('IN_IA') or exit('Access Denied');

/*———————规定评论组件————————*/
/*
	@multi 是否返回复杂结构；默认否
*/
function fmFunc_comment_modules($multi=null){
	$modules = array(
		'changyan'=>'畅言',
		'default'=>'默认',
	);
	if($multi){
		return $modules;
	}else{
		$modules_key = array();
		foreach($modules as $key=>$module)
		{
			$modules_key[] = $key;
		}
		return $modules_key;
	}
}

	/*———————数据解析函数————————*/
function fmFunc_comment_save($data,$module){
	global $_W;
	global $_FM;
	$modules = fmFunc_comment_modules();
	$module = (in_array($module,$modules)) ? $module : 'dianping';	//默认使用系统自带的点评模块
	$STORE = array();
	if($module == 'changyan'){
		$data=json_decode($data, true);	//畅言评论回调数据需要json解码
		$title = $data['title'];	//文章标题
		$url = $data['url'];	//文章链接
		$ttime = $data['ttime'];	//文章时间(毫秒)
		$sourceid = $data['sourceid'];	//文章id(传入格式$id-$sn)
		$parentid = $data['parentid'];	//文章分类/所属专辑id,逗号分隔
		$ownerid = $data['ownerid'];	//文章发布者id
		$metadata = $data['metadata'];	//文章发布者id
		$commentsAll = $data['comments'];	// 文章评论信息（是一个数组）

		$comments=$commentsAll[0];	//取第一条评论
		$cmtid = $comments['cmtid'];	 //评论唯一ID
		$ctime = $comments['ctime'];	 //评论时间
		$content = $comments['content'];	 //评论内容
		$replyid = $comments['replyid'];	 //回复的评论ID，没有为0
		$replyer = $comments['user'];	 //回复发布人信息
			$userid = $replyer['userid'];	 // 发布者ID
			$nickname = $replyer['nickname'];	 // 发布者昵称
			$usericon = $replyer['usericon'];	 // 发布者头像(留空使用默认头像)
			$userurl = $replyer['userurl'];	 // 发布者主页地址（可留空）
			$usermeta = $replyer['usermeta'];		//用户其它相关信息，例如性别，头衔等数据
			$user_area = $usermeta['area'];		//区域
			$user_gender = $usermeta['gender'];		//性别
			$user_kk = $usermeta['kk'];		//头衔
			$user_level = $usermeta['level'];		//头衔
		$ip = $comments['ip'];	 //回复发布ip
		$useragent = $comments['useragent'];	 //回复浏览器信息
		$channeltype = $comments['channeltype'];	 //1为评论框直接发表的评论，2为第三方回流的评论
		$from = $comments['from'];	 //评论来源
		$spcount = $comments['spcount'];	 //评论被顶次数
		$opcount = $comments['opcount'];	 //评论被踩次数

		$attachmentAll = $comments['attachment'];	 //评论附件列表(是一个数组)
		if(is_array($attachmentAll) && !empty($attachmentAll)){
			foreach($attachmentAll as $attachment){
				$attachment_type = $attachment['type'];		//1为图片、2为语音、3为视频
				$attachment_desc = $attachment['desc'];		//描述
				$attachment_url = $attachment['url'];		//附件地址
			}
		}

		//根据$url判断被评论页，从而进行进一步的处理
		$isArticle = (stripos($url,"do=article")) ? TRUE : FALSE;
		$isGoods = (stripos($url,"do=goods")) ? TRUE : FALSE;

		if($isGoods){
			//取产品id
			$Ids=explode('-',$sourceid);	//do-ac-id
			$do=$Ids[0];
			$ac=$Ids[1];
			$id=$Ids[2];
			fm_load()->fm_model('goods');
			$goodsInfo=fmMod_goods_detail_all_m($id)['data'];

			$goodsadm=$goodsInfo['goodadm'];
			$goodstpl=$goodsInfo['goodtpl'];

			/*———————数据入库————————*/
			$STORE['addon']=json_encode($comments);	//将原始评论数据也保存入库，备用
			$STORE['uniacid']=$_W['uniacid'];
			$STORE['commentid']=$cmtid;
			$STORE['replyid']=$replyid;
			$STORE['goodsid']=$id;
			$STORE['uid']=$userid;
			$STORE['nickname']=$nickname;
			$STORE['headimgurl']=$usericon;
			$STORE['content']=$content;
			$STORE['attachments']=iserializer($attachmentAll);
			$STORE['createtime']=$_W['timestamp'];
			pdo_insert('fm453_shopping_comment',$STORE);

			/*———————微信知会————————*/
			//通知给管理员
			$notice_data['comment']['goods']['admin']=array(
				'header'=>array('title'=>'产品评论通知','value'=>$title),
				'replyer'=>array('title'=>'评论人','value'=>$nickname),
				'content'=>array('title'=>'评论内容','value'=>$content),
				'url'=>array('title'=>'产品链接','value'=> $url)
			);
			$NOTICE = $notice_data['comment']['goods']['admin'];
			$result=fmMod_notice($_FM['settings']['manageropenids'],$NOTICE);

			if($goodsInfo['goodadm'])
			{
				$NOTICE = "有人对您的产品进行了点评;"."\r\n";
				$NOTICE .= "评论人:".$nickname."(".$userid.")"."\r\n";
				$NOTICE .= "评论内容:".$content."\r\n";
				$NOTICE .= "<a href='" . $url . "'>链接:".$title."</a>";
				$result=fmMod_notice($goodsInfo['goodadm'],$NOTICE);
				if($replyid>0){
					//如果是评论已经存在的评论消息
					//待
				}
			}

		}elseif($isArticle){
			//取文章id
			$ArticleIds=explode('-',$sourceid);
			$id=$ArticleIds[0];
			$sn=$ArticleIds[1];
			$articleInfo=fmMod_article_detail_m($id,$sn)['data'];
			$author=$articleInfo['goodadm'];
			$articleModel=$articleInfo['a_tpl'];

			/*———————数据入库————————*/
			$STORE['addon']=json_encode($comments);	//将原始评论数据也保存入库，备用
			$STORE['uniacid']=$_W['uniacid'];
			$STORE['commentid']=$cmtid;
			$STORE['replyid']=$replyid;
			$STORE['articleid']=$id;
			$STORE['uid']=$userid;
			$STORE['nickname']=$nickname;
			$STORE['headimgurl']=$usericon;
			$STORE['content']=$content;
			$STORE['attachments']=iserializer($attachmentAll);
			$STORE['createtime']=$_W['timestamp'];
			pdo_insert('fm453_shopping_comment',$STORE);

			/*———————微信知会————————*/
			//通知给管理员
			$notice_data['comment']['article']['admin']=array(
				'header'=>array('title'=>'文章评论通知','value'=>$title),
				'replyer'=>array('title'=>'评论人','value'=>$nickname),
				'content'=>array('title'=>'评论内容','value'=>$content),
				'url'=>array('title'=>'原文链接','value'=> $url)
			);
			$NOTICE = $notice_data['comment']['article']['admin'];
			$result=fmMod_notice($_FM['settings']['manageropenids'],$NOTICE);

			if($articleInfo['goodadm']){
				if($articleModel=='default'){
					$NOTICE = "您的文章收到一条评论信息;"."\r\n";
					$NOTICE .= "评论人:".$nickname."(".$userid.")"."\r\n";
					$NOTICE .= "评论内容:".$content."\r\n";
					$NOTICE .= "<a href='" . $url . "'>原文:".$title."</a>";
					$result=fmMod_notice($articleInfo['goodadm'],$NOTICE);
					if($replyid>0){
						//如果是评论已经存在的评论消息
						//待
					}
				}elseif($articleModel=='member'){
					$NOTICE = "有会员对您的资料进行了评论;"."\r\n";
					$NOTICE .= "评论人:".$nickname."(".$userid.")"."\r\n";
					$NOTICE .= "评论内容:".$content."\r\n";
					$NOTICE .= "<a href='" . $url . "'>点击这里查看详情</a>";
					$result=fmMod_notice($articleInfo['goodadm'],$NOTICE);
					if($replyid>0){
						//如果是评论已经存在的评论消息
						//待
					}
				}elseif($articleModel=='business'){
					$NOTICE = "有会员对您的企业资料进行了评论;"."\r\n";
					$NOTICE .= "评论人:".$nickname."(".$userid.")"."\r\n";
					$NOTICE .= "评论内容:".$content."\r\n";
					$NOTICE .= "<a href='" . $url . "'>点击这里查看详情</a>";
					$result=fmMod_notice($articleInfo['goodadm'],$NOTICE);
					if($replyid>0){
						//如果是评论已经存在的评论消息
						//待
					}
				}elseif($articleModel=='zhaopin'){
					$NOTICE = "有会员对您的招求聘信息进行了评论;"."\r\n";
					$NOTICE .= "评论人:".$nickname."(".$userid.")"."\r\n";
					$NOTICE .= "评论内容:".$content."\r\n";
					$NOTICE .= "<a href='" . $url . "'>信息原文:".$title."</a>";
					$result=fmMod_notice($articleInfo['goodadm'],$NOTICE);
					if($replyid>0){
						//如果是评论已经存在的评论消息
						//待
					}
				}else{
					$NOTICE = "有人对您的资料/文章/信息进行了评论;"."\r\n";
					$NOTICE .= "评论人:".$nickname."(".$userid.")"."\r\n";
					$NOTICE .= "评论内容:".$content."\r\n";
					$NOTICE .= "<a href='" . $url . "'>原文:".$title."</a>";
					$result=fmMod_notice($articleInfo['goodadm'],$NOTICE);
					if($replyid>0){
						//如果是评论已经存在的评论消息
						//待
					}
				}
			}
		}
	}

}

?>

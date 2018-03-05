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
 * @remark 接口-文章接口
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->model('account');//加载公众号函数
fm_load()->fm_model('article');
fm_load()->fm_func('comment');
fm_load()->fm_func('view'); 	//浏览量处理

//入口判断
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;

$modules = fmFunc_comment_modules();

/*———————数据解析函数————————*/
function fmFormatComment($data,$module=null){
	$module = (in_array($module,$modules)) ? $module : 'changyan';
	if($module=='changyan') {
		$cmtid = $comments['cmtid'];	 //评论唯一ID
		$ctime = $comments['ctime'];	 //评论时间
		$content = $comments['content'];	 //评论内容
		$replyid = $comments['replyid'];	 //回复的评论ID，没有为0
		$replyer = $comments['user'];	 //回复发布人信息
			$user = $replyer['userid'];	 // 发布者ID
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
		if(is_array($attachmentAll) && !empty($attachmentAll)) {
			foreach($attachmentAll as $attachment){
				$attachment_type = $attachment['type'];		//1为图片、2为语音、3为视频
				$attachment_desc = $attachment['desc'];		//描述
				$attachment_url = $attachment['url'];		//附件地址
			}
		}
	}
}

if($op=='changyan'){
	//$originalData='{"comments":[{"apptype":43,"attachment":[],"channelid":1038740,"channeltype":1,"cmtid":"1298634613","content":"\u6d4b\u8bd5","ctime":1486721165000,"from":0,"ip":"124.225.89.46","opcount":0,"referid":"1298634613","replyid":"0","spcount":0,"status":0,"user":{"nickname":"\u8def\u5ba2\u7f51\u7edcHilukerOOP","sohuPlusId":568975751,"usericon":"http:\/\/sucimg.itc.cn\/avatarimg\/568975751_1486692880573_c55"},"useragent":"Mozilla\/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit\/601.1.46 (KHTML, like Gecko) Version\/9.0 Mobile\/13B143 Safari\/601.1"}],"metadata":"{\\"ChangY_ImgTag\\":\\"YES\\",\\"local_ip\\":\\"10.11.157.47\\",\\"ChangY_Img\\":\\"http:\/\/0d077ef9e74d8.cdn.sohucs.com\/topic_picture_2537575679_1486701692817\\"}","sourceid":"7-","title":"\u4eba\u53ef\u4ee5\u767d\u624b\u8d77\u5bb6\uff0c\u4f46\u4e0d\u53ef\u4ee5\u624b\u65e0\u5bf8\u94c1","ttime":1486700347000,"url":"http:\/\/vcms.hiluker.com\/app\/index.php?c=entry&i=1&m=fm453_shopping&do=article&ac=detail&op=index&fromplatid=1&lastid=&shareid=&id=7"}';
	$originalData=$_POST['data'];

/*———————解析数据————————*/
	$data=$originalData;
	$data=json_decode($data, true);
	//var_dump($data);
	//print_r($data);

	$title = $data['title'];	//文章标题
	$url = $data['url'];	//文章链接
	$ttime = $data['ttime'];	//文章时间(毫秒)
	$sourceid = $data['sourceid'];	//文章id(传入格式$id-$sn)
	$parentid = $data['parentid'];	//文章分类/所属专辑id,逗号分隔
	$ownerid = $data['ownerid'];	//文章发布者id
	$metadata = $data['metadata'];	//文章发布者id
	$commentsAll = $data['comments'];	// 文章评论信息（是一个数组）
	//var_dump($commentsAll[0]);

	//取文章id
	$ArticleIds=explode('-',$sourceid);
	$id=$ArticleIds[0];
	$sn=$ArticleIds[1];
	$articleInfo=fmMod_article_detail_m($id,$sn)['data'];

	//分解评论
	/*
	if(is_array($commentsAll) && !empty($commentsAll)) {
		foreach($commentsAll as $comments){
			fmFormatComment($comments,'changyan');
		}
	}
	*/
		$comments=$commentsAll[0];
		$cmtid = $comments['cmtid'];	 //评论唯一ID
		$ctime = $comments['ctime'];	 //评论时间
		$content = $comments['content'];	 //评论内容
		$replyid = $comments['replyid'];	 //回复的评论ID，没有为0
		$replyer = $comments['user'];	 //回复发布人信息
			$user = $replyer['userid'];	 // 发布者ID
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
		if(is_array($attachmentAll) && !empty($attachmentAll)) {
			foreach($attachmentAll as $attachment){
				$attachment_type = $attachment['type'];		//1为图片、2为语音、3为视频
				$attachment_desc = $attachment['desc'];		//描述
				$attachment_url = $attachment['url'];		//附件地址
			}
		}

/*———————数据入库————————*/
	//写入操作日志START
	$dologs=array(
		'url'=>$url,
		'description'=>'畅言评论回调日志',
		'addons'=>$originalData,
	);
	fmMod_log_record($platid,$uniacid,$uid,$fanid,$openid,'',$id,'changyan',$dologs);
	unset($dologs);
	//写入操作日志END

	$STORE = array('');

/*———————微信知会————————*/

	if($articleInfo['goodadm']){
		$NOTICE = "您收到一条评论信息；<a href='" . $url . "'>点击查看</a>";
		$result=fmMod_notice($articleInfo['goodadm'],$NOTICE);
		if($replyid>0){
			//如果是评论已经存在的评论消息
			//待
		}
	}
	$notice_data['comment']['article']['author']=array(
		'header'=>array('title'=>'文章评论通知','value'=>$title),
		'replyer'=>array('title'=>'评论人','value'=>$nickname),
		'content'=>array('title'=>'评论内容','value'=>$content),
		'url'=>array('title'=>'原文链接','value'=> $url)
	);
	$NOTICE = $notice_data['comment']['article']['author'];
	$result=fmMod_notice($settings['manageropenids'],$NOTICE);

}

	/*———————畅言数据格式————————*/
	/*
{
    "title":"123",                      //文章标题
    "url":"http://localhost/?p=9",      //文章url
    "ttime":1401327899094,      //文章创建时间
    "sourceid":"9",                     //文章Id
    "parentid":"0",                     //文章所属专辑的ID,多个的话以,号分隔
    "categoryid":"",                    //文章所属频道ID（可留空）
    "ownerid":"",                       //文章发布者ID（可留空）
    "metadata":"",                      //文章其他信息（可留空）
    "comments":[
        {
            "cmtid":"358",                                  //评论唯一ID
            "ctime":1401327899094,                          //评论时间
            "content":"2013年8月1日18:36:29 O(∩_∩)O~",      //评论内容
            "replyid":"0",                                  //回复的评论ID，没有为0
            "user":{
                "userid":"1",                               //发布者ID
                "nickname":"admin",                         //发布者昵称
                "usericon":"",                              //发布者头像（留空使用默认头像）
                "userurl":"",                                //发布者主页地址（可留空）
                "usermetadata":{                            //其它用户相关信息，例如性别，头衔等数据
                    "area": "北京市",
                    "gender": "1",
                    "kk": "",
                    "level": 1
                }
            },
            "ip":"127.0.0.1",                                                                       //发布ip
            "useragent":"Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20100101 Firefox/22.0",        //浏览器信息
            "channeltype":"1",                                      //1为评论框直接发表的评论，2为第三方回流的评论
            "from":"",                                                                              //评论来源
            "spcount":"",                                                                           //评论被顶次数
            "opcount":"",                                                                           //评论被踩次数
            "attachment":[                                          //附件列表
                {
                    "type":1，                                        //1为图片、2为语音、3为视频
                    "desc":""，                                      //描述，
                    "url":"http://img.sohu.itc/xxxx"               //附件地址
                }
            ]
        }
    ]
}
*/

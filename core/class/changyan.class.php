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
 * @remark	搜狐畅言评论接口类
 */
/*——————SSO单点登陆————————— */

class fmClass_changyan{
	//获取用户信息
	public function getuserinfo(){
		global $_W;
		global $_FM;
		if($_FM['member']['uid']!=0)
		{
			$ret=array(
				"is_login"=>1, //已登录，返回登录的用户信息
				"user"=>array(
					"user_id"=>$_FM['member']['uid'],
					"nickname"=> tomedia($_FM['member']['username']),
					"img_url"=>$_FM['member']['avatar'],
					"profile_url"=>"",
					"sign"=>md5($_FM['member']['openid']) //注意这里的sign签名验证已弃用，任意赋值即可
				)
			);
		}else{
			$ret=array("is_login"=>0);//未登录
		}
		echo $_GET['callback'].'('.json_encode($ret).')';
	}

	//已注册用户登陆
	public static function login_hasuid()
	{
		global $_W;
		global $_FM;
		$ret=array(
			'user_id'=>$_FM['member']['uid'],
			'reload_page'=>1 //reload_page为1表示会重新刷新当前页
		);
		echo $_GET['callback'].'('.json_encode($ret).')';
	}

	//未注册用户登陆
	public static function login_nouid()
	{
		global $_W;
		global $_FM;
		if($_FM['member']['uid']==0)
		{
			$ret=array(
				'user_id'=>'1',
				'reload_page'=>0
			);
		}else{
			$ret=array(
				'user_id'=>$_FM['member']['uid'],
				'reload_page'=>1
			);
		}
		echo $_GET['callback'].'('.json_encode($ret).')';
	}

	//校验数据
	public function data_check($data)
	{
		global $_W;
		global $_FM;
		$data=json_decode($data, true);	//畅言评论回调数据需要json解码
		return true;    //直接返回true

		$url = $data['url'];	//文章链接与当前网络域名一致时，返回TRUE
		if(stripos($url,$_W['siteroot'])===0)
		{
			return TRUE;	//回调的网址不包含
		}else{
			return FALSE;
		}
	}

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
 ?>

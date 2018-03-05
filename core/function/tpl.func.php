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
 * @remark：自定义代码块模板处理函数
 */
defined('IN_IA') or exit('Access Denied');

/*
php删除字符串中的所有换行标记,压缩成一行；便于输出为JS可用的html字符串
*/

function fmFunc_tpl_oneLine($str)
{
	$qian=array("\t","\n","\r");
	$hou=array("","","","");
	return str_replace($qian,$hou,$str);
}

/*
*获取会员清单
*id	元素名称，表单取值时为$_POST[$id]或$_GPC[$id]
*value	代入值内容,字符串格式
*members	预设会员信息,数组格式，每条为独立的会员数据
*multi	单选或复选模式
*/
function fmFunc_tpl_getMember($id,$value=array(),$members,$multi=false){
	global $_GPC;
	global $_W;
	global $_FM;

	$html ='<div class="input-group">
			<input type="text" name="'.$id.'" maxlength="30" value="'.$value.'" id="input-'.$id.'" class="form-control"/>
			<div class="input-group-btn">
				<button class="btn btn-default" type="button" onclick="$(\'#seletor-search-member-target\').val(\''.$id.'\');popwin = $(\'#modal-selector-member\').modal();">选择</button>
				<button class="btn btn-danger" type="button" onclick="$(\'#input-'.$id.'\').val(\'\');$(\'#avatar-'.$id.'\').hide()">清除选择</button>
			</div>
		</div>
		<span id="avatar-'.$id.'" class="help-block">';
	foreach($members as $member){
		$html .='<div class="multi-item" id="'.$member['openid'].'" data-openid="'.$member['openid'].'" data-name="'.$id.'" style="max-height: 100px;max-width: 100px;position: relative;float: left;margin-right: 18px;margin-top: 10px;">
			<img class="img-responsive img-thumbnail" src="'.$member['avatar'].'" onerror="this.src=\'\/\/public.hiluker.com\/vcms\/nopic.jpg\'" style="width:100px;height:100px;">
			<div class="img-nickname" style="position: absolute;bottom: 0px;line-height: 25px;height: 25px;color: rgb(255, 255, 255);text-align: center;width: 90px;left: 5px;bottom: 5px;background: rgba(0,0,0,0.8);">'.$member['nickname'].'</div>
			<input type="hidden" value="'.$member['openid'].'" name="_'.$id.'[]">
			<em onclick="selector_remove(this,\'input-'.$id.'\')" class="close" style="position: absolute;top: 0;right: -14px;">×</em>
		</div>';
	}
	$html .='</span>';
	switch($multi){
		case false:
			$html .='<script language="javascript">
			function select_member(o,target,obj) {
				$("#avatar-"+target).show();
				$("#"+o.openid).show();
				//单选会员时使用替换方式 S
				var _length = $("#avatar-"+target).find("img").length;
				if(_length==0){
					var _html = \'<div class="multi-item" id="\'+o.openid+\'" data-openid="\'+o.openid+\'" data-name="\'+target+\'" style="max-height: 100px;max-width: 100px;position: relative;float: left;margin-right: 18px;margin-top: 10px;"><img class="img-responsive img-thumbnail" src="\'+o.avatar+\'" onerror="this.src=\\\'//public.hiluker.com/vcms/nopic.jpg\\\'" style="width:100px;height:100px;"><div class="img-nickname" style="position: absolute;bottom: 0px;line-height: 25px;height: 25px;color: rgb(255, 255, 255);text-align: center;width: 90px;left: 5px;bottom: 5px;background: rgba(0,0,0,0.8);">\'+o.realname+\'</div><input type="hidden" value="\'+o.openid+\'" name="_\'+target+\'[]"><em onclick="selector_remove(this,\\\'input-\'+target+\'\\\')" class="close" style="position: absolute;top: 0;right: -14px;">×</em></div>\';
					$("#avatar-"+target).append(_html);
				}else{
					$("#avatar-"+target).find("img").attr("src",o.avatar);
				}

				$("#input-"+target).val(o.openid);
				//单选会员时使用替换方式 E

				$("#modal-selector-member .close").click();
				// $(obj).parent().hide();
			}
		</script>';
		break;

		case true:
			$html .='<script language="javascript">
			function select_member(o,target,obj) {
				$("#avatar-"+target).show();
				$("#"+o.openid).show();
				//多选会员时使用追加方式 S
				var _html = \'<div class="multi-item" id="\'+o.openid+\'" data-openid="\'+o.openid+\'" data-name="\'+target+\'" style="max-height: 100px;max-width: 100px;position: relative;float: left;margin-right: 18px;margin-top: 10px;"><img class="img-responsive img-thumbnail" src="\'+o.avatar+\'" onerror="this.src=\\\'//public.hiluker.com/vcms/nopic.jpg\\\'" style="width:100px;height:100px;"><div class="img-nickname" style="position: absolute;bottom: 0px;line-height: 25px;height: 25px;color: rgb(255, 255, 255);text-align: center;width: 90px;left: 5px;bottom: 5px;background: rgba(0,0,0,0.8);">\'+o.realname+\'</div><input type="hidden" value="\'+o.openid+\'" name="_\'+target+\'[]"><em onclick="selector_remove(this,\\\'input-\'+target+\'\\\')" class="close" style="position: absolute;top: 0;right: -14px;">×</em></div>\';
				$("#avatar-"+target).append(_html);
				$("#input-"+target).val($("#input-"+target).val()+","+o.openid);
				//多选会员时使用追加方式 E

				$(obj).parent().hide();
			}
		</script>';
		break;
	}

	return $html;
}

/*
*富文本编辑器组件
*id	元素名称，表单取值时为$_POST[$id]或$_GPC[$id]
*value	值内容
*isapp	是否输出到手机前端
*datatype	要返回的数据类型=》html,json.默认为html
*options 参数数组，对编辑器进行个性设置
*/
function fmFunc_tpl_ueditor($id,$value,$isapp=null,$datatype=null,$options=array()){
	global $_GPC;
	global $_W;
	global $_FM;
	$html ='';
	$ueditor = '';
	$options['height'] = empty($options['height']) ? 667 : $options['height'];
	$options['width'] = empty($options['width']) ? 375 : $options['width'];
	$options['toolbars'] = trim($options['toolbars']);

	//预定义工具栏toolbars
	$toolbars = "
		[
			[
        'source', //源代码
        '|',
        'cleardoc', //清空文档
        '|',
        'undo', //撤销
        'redo', //重做
        '|',
        'print', //打印
        'preview', //预览
        '|',
        'autotypeset', //自动排版
        'removeformat', //清除格式
        '|',
        'formatmatch', //格式刷
        '|',
        'selectall', //全选
        '|',
        'anchor', //锚点
        '|',
        'directionalityltr', //从左向右输入
        'directionalityrtl', //从右向左输入
        '|',
        'touppercase', //字母大写
        'tolowercase', //字母小写
        '|',
        'pagebreak', //分页
        '|',
        'insertframe', //插入Iframe
        '|',
        'template', //模板
        '|',
        'background', //背景
         '|',
        'searchreplace', //查询替换
        'paste', //纯文本粘贴模式
        'drafts', // 从草稿箱加载
        '|',
        'help', //帮助
        'fullscreen', //全屏
        ],[
        'indent', //首行缩进
        '|',
        'justifyleft', //居左对齐
        'justifyright', //居右对齐
        'justifycenter', //居中对齐
        'justifyjustify', //两端对齐
        '|',
        'forecolor', //字体颜色
        'backcolor', //背景色
        '|',
        'bold', //加粗
        'italic', //斜体
        'underline', //下划线
        'strikethrough', //删除线
        'fontborder', //字符边框
        '|',
        'subscript', //下标
        'superscript', //上标
        '|',
        'blockquote', //引用
        '|',
         'rowspacingtop', //段前距
        'rowspacingbottom', //段后距
        'lineheight', //行间距
        '|',
        //'fontfamily', //字体 //手机适配性太差,暂去除
        'fontsize', //字号
        'paragraph', //段落格式
        '|',
        'customstyle', //自定义标题
        'insertorderedlist', //有序列表
        'insertunorderedlist', //无序列表
        ],[
        'inserttable', //插入表格
        'edittable', //表格属性
        'insertrow', //前插入行
        'insertcol', //前插入列
        'edittd', //单元格属性
        'mergeright', //右合并单元格
        'mergedown', //下合并单元格
        'deleterow', //删除行
        'deletecol', //删除列
        'splittorows', //拆分成行
        'splittocols', //拆分成列
        'splittocells', //完全拆分单元格
        'deletecaption', //删除表格标题
        'inserttitle', //插入标题
        'mergecells', //合并多个单元格
        'deletetable', //删除表格
        'insertparagraphbeforetable', //表格前插入行
        ],[
        'insertcode', //代码语言	//对其中的特殊字符都进行转义
        'attachment', //附件
        'wordimage', //图片转存
        'edittip ', //编辑提示
        'horizontal', //分隔线
        'time', //时间
        'date', //日期
        '|',
        'link', //超链接
        'unlink', //取消链接
        '|',
        'emotion', //表情
        'spechars', //特殊字符
		'|',
        'map', //Baidu地图
        '|',
        'charts', // 图表
        '|',
        'snapscreen', //截图
        'scrawl', //涂鸦
        '|',
         'imagenone', //默认
        'imageleft', //左浮动
        'imageright', //右浮动
        'imagecenter', //居中
        '|',
		'insertimage', //多图上传及图片管理
		//'insertvideo', //引用网络视频；编辑器自带，粘贴网址，已不能用
        'myinsertimage', //调用系统自定义图片管理组件
        'myinsertvideo', //调用系统自定义视频管理组件
        'myinsertaudio', //调用系统自定义音频管理组件
			]
		]
	";
	if($options['toolbars']=='simple')
	{
		$toolbars = "
		[
			['redo','undo']
		]";
	}

	//开始生成html代码格式
	$ueditor .=!empty($id) ? "<textarea id='{$id}' name='{$id}' type=\"text/plain\" >{$value}</textarea>" : '';

	//实例化
	$ueditor .= "
	<script type=\"text/javascript\">
	var ue_{$id}=UE.getEditor('{$id}',{
		toolbars: {$toolbars},
		autoHeightEnabled: true,//是否自动长高，默认true
		initialFrameHeight: {$options['height']},
		initialFrameWidth: {$options['width']},
    maximumWords:9999999999999,
    focus:false,//初始化时，是否让编辑器获得焦点true或false
    allHtmlEnabled:false,//不注释掉会在保存时产生额外的html代码
    autoClearinitialContent : false,//是否自动清除编辑器初始内容
    elementPathEnabled : false,
    allowDivTransToP: false,//阻止自动将div标签转换为p标签
    autotypeset: {
    	clearFontFamily: true,	//去掉所有的内嵌字体，使用编辑器默认的字体
    	removeEmptyNode: true,         // 去掉空节点
    	pasteFilter: false,             //根据规则过滤没事粘贴进来的内容
    },
    autoTransWordToList:true,  //禁止word中粘贴进来的列表自动变成列表标签
	});
	</script>";

	$ueditor.=!empty($id) ? "
		<script type=\"text/javascript\">
			$(function(){
				var ue_{$id} = UE.getEditor('{$id}');
				$('#{$id}').data('editor', ue_{$id});
				$('#{$id}').parents('form').submit(function() {
					if (ue_{$id}.queryCommandState('source')) {
						ue_{$id}.execCommand('source');
					}
				});
			});
		</script>" : '';

	//添加ue自定义配置
	$ueditor .="
	<script type=\"text/javascript\">
	var opts = {
				type :'image',
				direct : false,
				multiple : true,
				tabs : {
					'upload' : 'active',
					'browser' : '',
					'crawler' : ''
				},
				path : '',
				dest_dir : '',
				global : false,
				thumb : false,
				width : 0,
				fileSizeLimit : 10240000
			};

			UE.registerUI('myinsertimage',function(editor,uiName){
				editor.registerCommand(uiName, {
					execCommand:function(){
						require(['fileUploader'], function(uploader){
							uploader.show(function(imgs){
								if (imgs.length == 0) {
									return;
								} else if (imgs.length == 1) {
									editor.execCommand('insertimage', {
										'src' : imgs[0]['url'],
										'_src' : imgs[0]['attachment'],
										'width' : 'auto',
										'alt' : imgs[0].filename
									});
								} else {
									var imglist = array();
									for (i in imgs) {
										imglist.push({
											'src' : imgs[i]['url'],
											'_src' : imgs[i]['attachment'],
											'width' : 'auto',
											'alt' : imgs[i].filename
										});
									}
									editor.execCommand('insertimage', imglist);
								}
							}, opts);
						});
					}
				});
				var btn = new UE.ui.Button({
					name: 'myinsertimage',
					title: '插入或引用系统私有图片',
					//cssRules :'background-position: -726px -77px',
					cssRules :'background-position: -560px -40px',
					onclick:function () {
						editor.execCommand(uiName);
					}
				});
				editor.addListener('selectionchange', function () {
					var state = editor.queryCommandState(uiName);
					if (state == -1) {
						btn.setDisabled(true);
						btn.setChecked(false);
					} else {
						btn.setDisabled(false);
						btn.setChecked(state);
					}
				});
				return btn;
			}, 100);

			UE.registerUI('myinsertvideo',function(editor,uiName){
				editor.registerCommand(uiName, {
					execCommand:function(){
						require(['fileUploader'], function(uploader){
							uploader.show(function(video){
								if (!video) {
									return;
								} else {
									var videoType = video.isRemote ? 'iframe' : 'video';
									editor.execCommand('fmvideo', {
										'url' : video.url,
										'filename' : video.filename,
										'id' : video.id,
										'src' : video.attachment,
										'autoplay' : '',
										'width' : 300,
										'height' : 200
									});
								}
							}, {fileSizeLimit : 20480000, type : 'video', allowUploadVideo : true});
						});
					}
				});
				var btn = new UE.ui.Button({
					name: 'myinsertvideo',
					title: '插入或引用视频',
					cssRules :'background-position: -320px -20px',
					onclick:function () {
						editor.execCommand(uiName);
					}
				});
				editor.addListener('selectionchange', function () {
					var state = editor.queryCommandState(uiName);
					if (state == -1) {
						btn.setDisabled(true);
						btn.setChecked(false);
					} else {
						btn.setDisabled(false);
						btn.setChecked(state);
					}
				});
				return btn;
			}, 100);

			UE.registerUI('myinsertaudio',function(editor,uiName){
				editor.registerCommand(uiName, {
					execCommand:function(){
						require(['fileUploader'], function(uploader){
							uploader.show(function(audio){
								if (!audio) {
									return;
								} else {
									editor.execCommand('fmmusic', {
										'url' : audio.url,
										'filename' : audio.filename,
										'fileid' : audio.id,
										'src' : audio.attachment,
									});
								}
							}, {fileSizeLimit : 61440000, type : 'audio', allowUploadAudio : true});
						});
					}
				});
				var btn = new UE.ui.Button({
					name: 'myinsertaudio',
					title: '插入或引用音乐文件',
					cssRules :'background-position: -18px -40px',
					onclick:function () {
						editor.execCommand(uiName);
					}
				});
				editor.addListener('selectionchange', function () {
					var state = editor.queryCommandState(uiName);
					if (state == -1) {
						btn.setDisabled(true);
						btn.setChecked(false);
					} else {
						btn.setDisabled(false);
						btn.setChecked(state);
					}
				});
				return btn;
			}, 100);

	</script>
	";

	$html=$ueditor;
	return $html;
}

/*
*弹出界面，选择音乐后返回链接与参数
*/
function fmFunc_tpl_audio($name, $value = '',$options = array()) {
	if (!is_array($options)) {
		$options = array();
	}
	$options['autoplay'] = true;
	$options['controls'] = true;
	$options['direct'] = true;
	$options['multiple'] = false;
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['audio']['limit']) * 1024;
	$s = '';
	if (!defined('TPL_INIT_AUDIO')) {
		$s = '
<script type="text/javascript">
	function showAudioDialog(elm, base64options, options) {
		require(["util"], function(util){
			var btn = $(elm);
			var ipt = btn.parent().prev();
			var val = ipt.val();
			util.audio(val, function(url){
				if(url && url.attachment && url.url){
					btn.prev().show();
					ipt.val(url.attachment);
					ipt.attr("filename",url.filename);
					ipt.attr("url",url.url);
					setAudioPlayer();
				}
				if(url && url.media_id){
					ipt.val(url.media_id);
				}
			}, "" , ' . json_encode($options) . ');
		});
	}

	function setAudioPlayer(){
		require(["jquery", "util", "jquery.jplayer"], function($, u){
			$(function(){
				$(".audio-player").each(function(){
					$(this).prev().find("button").eq(0).click(function(){
						var src = $(this).parent().prev().val();
						if($(this).find("i").hasClass("fa-stop")) {
							$(this).parent().parent().next().jPlayer("stop");
						} else {
							if(src) {
								$(this).parent().parent().next().jPlayer("setMedia", {mp3: u.tomedia(src)}).jPlayer("play");
							}
						}
					});
				});

				$(".audio-player").jPlayer({
					playing: function() {
						$(this).prev().find("i").removeClass("fa-play").addClass("fa-stop");
					},
					pause: function (event) {
						$(this).prev().find("i").removeClass("fa-stop").addClass("fa-play");
					},
					swfPath: "resource/components/jplayer",
					supplied: "mp3"
				});
				$(".audio-player-media").each(function(){
					$(this).next().find(".audio-player-play").css("display", $(this).val() == "" ? "none" : "");
				});
			});
		});
	}
	setAudioPlayer();
</script>';
		echo $s;
		define('TPL_INIT_AUDIO', true);
	}
	$s .= '
	<div class="input-group">
		<input type="text" value="' . $value . '" name="' . $name . '" class="form-control audio-player-media" autocomplete="off" ' . ($options['extras']['text'] ? $options['extras']['text'] : '') . '>
		<span class="input-group-btn">
			<button class="btn btn-default audio-player-play" type="button" style="display:none;"><i class="fa fa-play"></i></button>
			<button class="btn btn-default" type="button" onclick="showAudioDialog(this, \'' . base64_encode(iserializer($options)) . '\',' . str_replace('"', '\'', json_encode($options)) . ');">选择音乐</button>
		</span>
	</div>
	<div class="input-group audio-player"></div>';
	return $s;
}

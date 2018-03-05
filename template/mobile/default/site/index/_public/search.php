<?php
if(!isset($settings['ui']['site']['index_search']) || $settings['ui']['site']['index_search']==1){
?>
<div class="" >
	<div class="mui-content-padded" style="margin: 5px;">
		<form action="<?php echo fm_murl('search','article','index',array());?>" method="post" class="mui-input-group" id="form1" enctype="multipart/form-data" onsubmit='return formcheck();' style="background-color:rgba(255,255,255,.5);border-color:1px; ">
		<input type="hidden" name="token" value="<?php echo $_W['token'];?>" />
			<div class="mui-input-row">
				<input id="search" type="search" name="keyword" class="mui-input-clear /*mui-input-speech voice-search*/" value="<?php echo $_GPC['keyword'];?>" placeholder="输入关键词可查询" style="height:40px;">
			</div>
			<button type="submit" class="hidden" style="display: none;"></button>
		</form>
	</div>
</div>
<!-- 语音识别完成事件 -->
<script>
	document.getElementById("search").addEventListener('recognized', function(e) {
		console.log(e.detail.value);
	});
</script>
<script language='javascript'>
	function formcheck() {
		var form = document.getElementById('form1');
		form.submit();//直接利用表单自身进行提交（不通过AJAX方式进行动态查询，不建议）
		return false;
		var container = 'pullrefresh';
		var url = form.getAttribute('action');
		var data = {
			'rpage': 1,
			'psize': "<?php echo $rpsize;?>",
			'pcate': "<?php echo $pcate;?>",
			'ccate': "<?php echo $ccate;?>",
			'keyword': form.keyword.value,
			'token' : "<?php echo $_W['token'];?>"
		};
		$('#search').html('正在搜索…');//替换内容
		$.get(url,data,function(res){
			$('#'+container).html(res);//替换内容
		});

		$.get(url,data,function(res){
			$('#'+container).html(res);//替换内容
			$('#showstatus').html('结果已刷新!');//替换内容
			$('#pagename').html('结果已刷新!');//替换内容
			setTimeout(function(){
					$('#pagename').html('<?php echo $pagename;?>');
					$('#showstatus').html('');
				},3000);
		},'html');
		$('input').blur();	//让输入框失焦，以关闭键盘
		return false;
	}
</script>
<?php }?>

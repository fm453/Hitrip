<script type="text/javascript" charset="utf-8">
	mui.init({
		swipeBack: false, //启用右滑关闭功能
		gestureConfig: {
			tap: true, //默认为true,单击屏幕
			doubletap: true, //默认为false，双击屏幕
			longtap: false, //默认为false，长按屏幕
			hold:false,//默认为false，按住屏幕
			release:false,//默认为false，离开屏幕
			swipe: true, //默认为true，滑动（swipeleft  , swiperight,   swipeup,   swipedown )
			drag: true, //默认为true，拖动  ( dragstart,  drag,   dragend )
		},
		pullRefresh: {
			container: '#pullrefresh',
			down: {
				auto: false, //自动下拉刷新一次
				contentover : "<?php echo $shopname;?>提示：立即释放 刷新一下",//可选，在释放可刷新状态时，下拉刷新控件上显示的标题内容
				contentdown : "下拉可以刷新",//可选，在下拉可刷新状态时，下拉刷新控件上显示的标题内容
				contentrefresh : "<?php echo $shopname;?>正在刷新...",//可选，正在刷新状态时，下拉刷新控件上显示的标题内容
				callback: pulldownRefresh
			},
			up: {
				contentrefresh: '<?php echo $shopname;?>正在加载...',
				contentnomore:'已到页底,没有更多订单了',//可选，请求完毕若没有更多数据时显示的提醒内容；
				callback: pullupRefresh
			}
		}
	});
	//定义支持区域滚动的层类
	mui('.mui-scroll-wrapper').scroll();
	//侧滑容器父节点
	var offCanvasWrapper = mui('#offCanvasWrapper');
	//主界面容器
	var offCanvasInner = offCanvasWrapper[0].querySelector('.mui-inner-wrap');
	//菜单容器
	var offCanvasSide = document.getElementById("offCanvasSide");
	//不是安卓时，隐藏安卓元素
	if(!mui.os.android) {
		var spans = document.querySelectorAll('.android-only');
		for(var i = 0, len = spans.length; i < len; i++) {
			spans[i].style.display = "none";
		}
	}
	//移动效果是否为整体移动
	var moveTogether = false;
	//侧滑容器的class列表，增加.mui-slide-in即可实现菜单移动、主界面不动的效果；
	var classList = offCanvasWrapper[0].classList;

	//主界面和侧滑菜单界面均支持区域滚动；
	mui('#offCanvasSideScroll').scroll();
	mui('#offCanvasContentScroll').scroll();

	//实现ios平台原生侧滑关闭页面；
	if(mui.os.plus && mui.os.ios) {
		mui.plusReady(function() { //5+ iOS暂时无法屏蔽popGesture时传递touch事件，故直接屏蔽popGesture功能
			plus.webview.currentWebview().setStyle({
				'popGesture': 'none'
			});
		});
	}

	//启动后即执行的动作
	if (mui.os.plus) {
		mui.plusReady(function() {
			setTimeout(function() {
				//mui('#pullrefresh').pullRefresh().pullupLoading();	//执行一次上拉刷新
			}, 1000);
		});
	} else {
		mui.ready(function() {
			//mui('#pullrefresh').pullRefresh().pullupLoading(); //执行一次上拉刷新
		});
	}

	//弹出菜单
	mui('body').on('shown', '.mui-popover', function(e) {
		//console.log('shown', e.detail.id);//detail为当前popover元素
	});
	mui('body').on('hidden', '.mui-popover', function(e) {
		//console.log('hidden', e.detail.id);//detail为当前popover元素
	});

	//监听标题
	//监听header的双击事件
	document.querySelector('header').addEventListener('doubletap', function() {
		$('#pagename').html('正在刷新…');
		refreshLoad("pullrefresh-table");
	});

	/**
	 * 下拉刷新具体业务实现
	 */
	function pulldownRefresh() {
		setTimeout(function() {
			 refreshLoad("pullrefresh-table");
			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
		}, 500);
	}
	//页面刷新加载处理
	function refreshLoad(container) {
		var url = "<?php echo fm_murl($do,$ac,'load',array());?>";
		var data = {
			'psize': '<?php echo $psize;?>',
			'page': '<?php echo $page;?>',
			'refresh': '1'
		};
		$.get(url,data,function(res){
			if (res) {
				$('#'.container).html(res);//替换内容
				$('#pagename').html('内容更新完毕');
				setTimeout(function(){
					$('#pagename').html('<?php echo $shopname;?>');
				},1000);
			}
		});
	}

	var rpindex = "<?php echo $rpindex;?>"; //分页计数
	var rtotal = "<?php echo $alltotal['article']['rec'];?>";	//推荐内容的总条数（首页产品推荐条数）
	var count = 0;	//起始计数
	var maxpages = "<?php echo $maxpages;?>";	//当前分类情况下的最大页数
	/**
	 * 上拉加载具体业务实现
	 */
	function pullupRefresh() {
		setTimeout(function() {
			count ++;	//累计一次上拉次数
			mui('#pullrefresh').pullRefresh().endPullupToRefresh(
				function () {
					if (count <maxpages) {
						return false;
						loadPage("pullrefresh-table",count);
					}else {
						return true;
					}
				}
			); //参数为true(累计刷新页数达到最大分页数)代表没有更多数据了
			}, 500);
		}

	//页面迭加新数据
	function loadPage(container,count) {
		var rpindex = parseInt(count)+1;//当前所在页
		var page = "<?php echo $pindex;?>";
		var psize = 2;
		var url = "<?php echo fm_murl($do,$ac,'loadmore',array());?>";
		var keyword = "<?php echo $_GPC['keyword'];?>";
		var data = {
			'rpage': rpindex,
			'page': psize,
			'psize': 2,	//每次迭加2条新数据（考虑手机屏幕大小）
			'keyword': keyword,
		};
		$.get(url,data,function(res){
			if (rpindex <=1) {
				$('#pull-refresh-table').html(res);//替换内容
			} else {
				$('#'+container).append(res);//加在后面
			}
		});
		//return true;
	}

	//更新浏览量及点击量（待）
	function UpdateView(setfor,id) {
		var url = "<?php echo fm_murl('ajax','index','',array());?>";
		var data = {
			'setfor': setfor,
			'id': id,
			'd_o': 'view'
		};
		$.get(url,data,function(res){
			var result = res
		});
		return;
	}

	function UpdateClick(setfor,id) {
		return;
	}

</script>

<!-- TBD !-->
<!-- 启动时自动上拉刷新一次加载 -->
<script type="text/javascript" >
if (mui.os.plus) {
	mui.plusReady(function() {
		setTimeout(function() {
			//mui('#pullrefresh').pullRefresh().pullupLoading();
		}, 1000);
	});
} else {
	mui.ready(function() {
		//mui('#pullrefresh').pullRefresh().pullupLoading();
	});
}
</script>
<!-- 左右滑动触屏菜单 -->
<script>
(function($) {
//方案1
	//拖拽后显示操作图标，右滑出操作菜单，打开链接，所以暂不监控；
			$('.huadongcaozong').on('tap', '.mui-btn', function(event) {
					var elem = this;
					var id = this.getAttribute('data-id');
					var LinkUrl = this.getAttribute('data-url');
					var dotype = this.getAttribute('date-swipe2do');
					var li = elem.parentNode.parentNode;
					if (dotype=='delete') {
						var btnArray = ['不,点错了', '嗯,删除吧'];
						mui.confirm('确认删除该条记录？', '<?php echo $shopname;?>提示', btnArray, function(e) {
							if (e.index == 1) {
								var res = changeOrderStatus(id,'delete');
									str = res.type;
								setTimeout(function () {
									if (str==undefined) {
										mui.toast('订单操作遇到未知错误！');
										li.parentNode.removeChild(li);
									}else if (str=='success') {
										mui.toast('订单删除成功！');
										li.parentNode.removeChild(li);
									}else{
										li.parentNode.removeChild(li);
										mui.toast('网络有点不给力，订单删除失败');
									}
									$.swipeoutClose(li);
								},500);
							} else {
								setTimeout(function() {
									$.swipeoutClose(li);
								}, 0);
							}
						});
					} else if (dotype=='recovery') {
						var btnArray = ['还是不了', '我要恢复'];
						mui.confirm('确认要恢复该订单？', '<?php echo $shopname;?>请您确认', btnArray, function(e) {
							if (e.index == 0) {
								setTimeout(function() {
									$.swipeoutClose(li);
								}, 0);
							} else {
								setTimeout(function () {
									var res = changeOrderStatus(id,'recovery');
									if (res>0) {
										mui.toast('订单已恢复成功');
										elem.parentNode.removeChild(elem);
									}else if (res==0) {
										mui.alert('抱歉，暂不支持已取消订单直接恢复');
									}else {
										mui.toast('网络有点不给力，订单删除失败');
									}
									$.swipeoutClose(li);
								},3000);
							}
						});
					} else if (dotype=='link') {
						mui.openWindow({
							id : '',
							url : LinkUrl
						});
					}
				});
//方案2
				var btnArray = ['点错了', '嗯,删除吧'];
				//向左拖拽后显示操作图标，右滑确认后自动执行删除操作(不太友好，暂不使用)
				$('.huadong-caozong').on('slideleft', '.mui-table-view-cell', function(event) {
					var elem = this;
					mui.confirm('确认要删除该订单？', '<?php echo $shopname;?>请您确认', btnArray, function(e) {
						if (e.index == 0) {
							setTimeout(function() {
								$.swipeoutClose(elem);
							}, 0);
						} else {
								var  id = elem.parentNode.getAttribute('data-id');
								//取得规格所属类型的id(存放在父节点，方便取用)
								var res = changeOrderStatus(id,'delete');
								str = res.type;
								setTimeout(function () {
									if (str==undefined) {
										mui.toast('订单操作遇到未知错误！');
										elem.parentNode.removeChild(elem);
									}else if (str=='success') {
										mui.toast('订单删除成功！');
										elem.parentNode.removeChild(elem);
									}else{
										elem.parentNode.removeChild(elem);
										mui.toast('网络有点不给力，订单删除失败');
									}
								},500);
							$.swipeoutClose(elem);
						}
					});
				});

			})(mui);
</script>
<!-- 修改订单状态 -->
<script>
	function changeOrderStatus(id,dowhat){
		//添加async:false.修改为同步请求
		//等ajax给返回对象赋值完毕后，才执行下面的js部分。而异步的话，还没有来得及赋值，就已经return了。
		var toBack = false;
		var op = '';
		if (id>0) {
			var posturl = "<?php echo fm_murl($do,$ac,'',array());?>";
			if(dowhat = 'delete'){
				op = 'delete';
			}
			var postdata = {id:id,op:op,todo:dowhat};
			$.ajax(
				{
					type: "POST",
					url: posturl,
					data: postdata,
					async: false,
					success: function (res) {
						console.log(res);
						toBack = res;
					},
					dataType: "json"
				}
			);
		}
		return toBack;
	}
</script>
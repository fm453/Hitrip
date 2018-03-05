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

	//幻灯片
	//自动轮播
	var slider = mui("#slider");
	slider.slider({
		interval: 5000
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
			'keyword': '<?php echo $_GPC["keyword"];?>',
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

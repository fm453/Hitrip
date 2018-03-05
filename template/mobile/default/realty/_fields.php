<?php
/**
 公用字段集
 */

//区域关联
$allareas = array();
$allareas = array(
	'haikou' => '海口',
	'chengmai' => '澄迈',
	'wenchang' => '文昌',
	'qionghai' => '琼海',
	'wanning' => '万宁',
	'lingshui' => '陵水',
	'sanya' => '三亚',
);

$areas = array();
$areas['haikou'] = array(
	'xiuying' => '秀英',
	'longhua' => '龙华',
	'meilan' => '美兰',
	'qiongshan' => '琼山',
	);
$areas['chengmai'] = array(
	'chengmai' => '澄迈',
	);
$areas['wenchang'] = array(
	'wenchang' => '文昌',
	);
$areas['qionghai'] = array(
	'qionghai' => '琼海',
	'boao' => '博鳌'
);
$areas['wanning'] = array(
	'wanning' => '万宁',
);
$areas['lingshui'] = array(
	'lingshui' => '陵水',
);
$areas['sanya'] = array(
	'shiqu' => '市区',
	'sanyawan' => '三亚湾',
	'dadonghai' => '大东海',
	'yalongwan' => '亚龙湾',
	'haitangwan' => '海棠湾',
);

//价格关联
$allprices = array();
$allprices = array(
	'all' => '不限',
	'6000' => '5000~6000',
	'8000' => '6000~8000',
	'10000' => '8000~10000',
	'15000' => '10000~15000',
	'more' => '15000以上',
);

//特色关联
$allfeatures = array();
$allfeatures = array(
	'all' => '不限',
	'small_house' => '小户型',
	'low_price' => '低总价',
	'famouse_brand' => '品牌地产',
	'tourist_able' => '旅游地产',
	'sea_scenery' => '海景房',
);

//户型关联
$allroomtypes = array();
$allroomtypes = array(
	'all' => '不限',
	'1' => '一室',
	'2' => '二室',
	'3' => '三室',
	'4' => '四室',
	'5' => '五室',
);

//楼盘类型关联
$allhousetypes = array();
$allhousetypes = array(
	'all' => '不限',
	'villadom' => '别墅',
	'seaview' => '海景房',
	'uptown' => '住宅',
	'mall' => '商铺',
);

//短新闻类型
$shortnewsTypes = array();
$shortnewsTypes = array(
	'sales' => '销售动态',
	'owners' => '业主动态',
);
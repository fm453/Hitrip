<?php

//初始化的产品模型参数清单;

$_GOODS_TPL_PARAMS = array();

//租车产品模型
$_GOODS_TPL_PARAMS['car'] = array();
$_GOODS_TPL_PARAMS['car']['type'] =array(
  // 'sn' => 参数编码
  // 'goodstpl' => 归属模型
  'param' => '车型',
  'datatype' => 'text',
  'value' => '车辆型号',	//车辆类型值的填写说明
  'isfront' => 1,	//适用范围,是否前端（系统后台、商城前台、公用）
  'isneeded' => 0,	//是否必填
  'isform' => '-1',		//是否应用于表单收集（数据收集，信息展示）
  // 'displayorder' => 0,	//显示顺序
  //'statuscode' => '128' //状态码
  // 'deleted' => 0,	//是否删除
);

$_GOODS_TPL_PARAMS['car']['brand'] =array(
  'param' => '车品牌',
  'datatype' => 'text',
  'value' => '车辆品牌'	,
  'isfront' => 1,
  'isneeded' => 0,
  'isform' => '-1',
);

$_GOODS_TPL_PARAMS['car']['series'] =array(
  'param' => '车系',
  'datatype' => 'text',
  'value' => '所属车系',
  'isfront' => 1,
  'isneeded' => 0,
  'isform' => '-1',
);
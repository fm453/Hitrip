<?php

$openids = array();
/*——————————————租车订单接收人员————————————*/
$openids['bike']=array();
//乐达嗨骑工作人员
$openids['bike']['leda']=array();
$openids['bike']['leda']['huangxiaolei']=array('name'=>'黄晓蕾','status'=>1,'openid'=>'oD7Cms2yOHT1ABh6gbMomuTFSKk4');
$openids['bike']['leda']['fm453']=array('name'=>'方孟','status'=>1,'openid'=>'oD7Cmsy_2Rq2jV_fysgvaxMsJndo');

//各网点知会人员
$openids['bike']['hotel']=array();
$openids['bike']['hotel'][]=array('name'=>'二流男','status'=>1,'openid'=>'oD7Cms25W3IQhZEQ2ftuNoES2yqg');

/*——————————————商城订单接收人员————————————*/
$openids['shop']=array();
//乐达嗨骑工作人员
$openids['shop']['leda']=array();
$openids['shop']['leda']['huangxiaolei']=array('name'=>'黄晓蕾','status'=>1,'openid'=>'oD7Cms2yOHT1ABh6gbMomuTFSKk4');
$openids['shop']['leda']['fm453']=array('name'=>'方孟','status'=>1,'openid'=>'oD7Cmsy_2Rq2jV_fysgvaxMsJndo');

/*——————————————骑游订单接收人员————————————*/
$openids['tour']=array();
//乐达嗨骑工作人员
$openids['tour']['leda']=array();
$openids['tour']['leda']['huangxiaolei']=array('name'=>'黄晓蕾','status'=>1,'openid'=>'oD7Cms2yOHT1ABh6gbMomuTFSKk4');
$openids['tour']['leda']['fm453']=array('name'=>'方孟','status'=>1,'openid'=>'oD7Cmsy_2Rq2jV_fysgvaxMsJndo');

//消息模板
$wxmsg_template_id = 'IjWKkjx1WjmTmo_x3KcEf-M0w5xOv61A20efb-scAdU'; //任务通知模板

//联系电话
$tels = array();
$tels['fm453'] = '18608981880';
$tels['it'] = '13647574270';
$tels['hiride'] = '0898-88279223';

//链接
$links = array();
$links['fm453'] = 'tel:'.$tels['fm453'];
$links['it'] = 'tel:'.$tels['it'];
$links['hiride'] = 'tel:'.$tels['hiride'];


/*————————租车订单表字段——————
order_no char(24) 订单号
bid int(11) 单车编号
cid int(11) 公司编号
bmid int(11) 车辆品牌编号
lid int(11) 车锁编号
ltype tinyint(4) 车锁类型
macaddr char(17) 蓝牙锁mac地址
pid int(11) 价格编号
hid int(11) 酒店编号
btid int(11) 车辆
ptype tinyint(4) 单车型号编号，如单人车
unit int(11) 计价单位
price1~6 int(11) 价格阶梯1~6
insurance tiny(1) 是否购买保险
realname char(30) 购买保险人姓名
id_no char(18) 购买保险人身份证
realname1 char(20)
id_no1 char(18)
realname2 char(20)
id_no2 char(18)
realname3 char(30)
id_no3 char(18)
uid int(11) 用户编号
phone char(11) 手机号码
time_from int(11) 取车时间
time_to int(11) 还车时间
time3 int(11) 用车时长
timefee double 时长费用
get_from char(30)
get_usrlng	double
get_usrlat	double
rtn_lng	double
rnt_lat double
get_mile double
rnt_mile double
get_power	int(11)
rnt_power	int(11)
mile_by_power	tiny(1)
miles 	double
milefee 	double
total double 总车费
available double 钱包余额支付金额
save 	double 内部测试费用减免
pay double 订单实付金额
tran_id	char(32) 押金抵扣车费时的押金支付微信订单号
step 	int(11)	 订单流程步骤（500待还车，700付支付，900已完成）
status tiny(2) 	订单状态 1正常 其他异常
*/

/*————————商城订单表字段——————
order_no char(24) 订单号
uid int(11) 用户编号
phone char(11) 手机
mgid int(11) 商品编号
mgname char(20) 商品名称
color char(20) 商品特征说明
price int(11) 价格
num int(11) 购买总量
total int(11) 总价
save double
refund int(11) 退款金额
pay double 实付金额
name char(30)  收货人
contact char(11) 收货人手机
addr char(120) 收货地址
postcode char(12) 邮政编码
time int(11) 下单时间
step int(11) 订单步骤（700待支付，900待发货，其他状态需要……）
status tiny(4) 	订单状态
*/
?>
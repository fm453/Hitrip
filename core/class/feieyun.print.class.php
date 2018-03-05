<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}
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
 * @remark	打印机接口类
 */
/*——————总类————————— */


fm_load()->fm_class('Print'); //获取飞鹅云打印机类
fm_load()->fm_class('HttpClient'); //获取飞鹅云网络请求类

class FeiEYunPrint extends FmPrint
{
	private $host;
	private $port;

	public function __construct($params = array()){
		$this-> feie_host = "api.feieyun.cn";
		$this-> feie_port = 80;
		$this->init($params);
	}

	public function SetHost($host=null){
		if(!$host){
			$this-> host = $this-> feie_host;
		}else{
			$this-> host = $host;
		}
		return $this-> host;
	}

	public function SetPort($port=null){
		if(!$port){
			$this-> port = $this-> feie_port;
		}else{
			$this-> port = $port;
		}
		return $this-> port;
	}

	//打印初始化
	public function init($params=array()){
		$this->setHost($params['host']);
		$this->setPort($params['port']);
		$return = array('errorcode'=>0,'msg'=>true);
		return $return;
	}

	//打印测试数据
	/*
	@sn  打印机编号;
    @$key 打印机key;
	*/
	public function test($sn,$key) {
        $orderInfo = '<CB>测试打印</CB><BR>';
        $orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
        $orderInfo .= '炒饭　　　　　 10.0   10  10.0<BR>';
        $orderInfo .= '蛋炒饭　　　　 10.0   100 100.0<BR>';
        $orderInfo .= '鸡蛋炒饭　　　 100.0  100 100.0<BR>';
        $orderInfo .= '西红柿炒饭　　 1000.0 1   100.0<BR>';
        $orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0<BR>';
        $orderInfo .= '西红柿鸡蛋炒饭 15.0   10   150.0<BR>';
        $orderInfo .= '备注：这是一个测试<BR>';
        $orderInfo .= '--------------------------------<BR>';
        $orderInfo .= '合计：xx.0元<BR>';
        $orderInfo .= '送货地点：海南省三亚市嗨路客电商研发中心<BR>';
        $orderInfo .= '联系电话：18608981880<BR>';
        $orderInfo .= '订餐时间：2008-08-08 08:08:08<BR>';
        $orderInfo .= '<QR>http://www.hiluker.com</QR>';//把二维码字符串用标签套上即可自动生成二维码

        //调用接口通信函数
        //********************************************
        $result = $this->sendFreeMessage($deviceNo=$sn,$deviceKey=$key,$times=1,$orderInfo);
        //********************************************
        return $result;
    }

    //调用飞鹅云接口代码
    //********************************************
    public function sendFreeMessage($deviceNo,$deviceKey,$times,$orderInfo)
    {
        $content = array(
            'sn'=>$deviceNo,
            'printContent'=>$orderInfo,
            'key'=>$deviceKey,
            'times'=>$times//打印次数
        );
        $result = $this -> sendMessage($content);
        if($result != 0){
                //重发一次
                $result = $this -> sendMessage($content);
            }
        return $result;
    }

    //执行一次打印连接动作
    public function sendMessage($message)
    {
        $client = new FmHttpClient($this-> host, $this-> port);
        if(!$client -> post('/FeieServer/printOrderAction', $message)){
            $return = array('errorcode'=>-1,'msg'=>false);
			return $return;
        }else{
            $result = $client -> getContent();
            //这里得到调用接口的返回值json,这里可以自由做处理
            return $result;
        }
    }

    //查询打印机状态
    public function getStatus($device_no)
    {
    	$client = new FmHttpClient($this-> host, $this-> port);
    	if(!$client->get('/FeieServer/queryprinterstatus?clientCode='.$device_no)){ //请求失败
			$return = array('errorcode'=>-1,'msg'=>"状态查询失败");
			return $return;
		}else{
			$result = $client->getContent();
			$result = json_decode($result,JSON_UNESCAPED_UNICODE);
			$return = array('errorcode'=>0,'msg'=>$result['status']);
			return $return;
		}
    }

    //查询打印机单日打印统计
    public function getOrderNumbersByTime($device_no,$date)
    {
    	$msgInfo = array(
	        'clientCode'=>$device_no,
		    'date'=>$date
		);
    	$client = new FmHttpClient($this-> host, $this-> port);
    	if(!$client->get('/FeieServer/queryorderinfo',$msgInfo)){
			$return = array('errorcode'=>-1,'msg'=>"状态查询失败");
			return $return;
		}else{
			$result = $client->getContent();
			$result = json_decode($result,JSON_UNESCAPED_UNICODE);
			// $printed = $result['print'];
			// $waiting = $result['waiting'];
			$return = array('errorcode'=>0,'msg'=>$result);
			return $return;
		}
    }

    //格式化消息数据
    public function sendSelfFormatMessage($msgInfo)
    {
    	$client = new FmHttpClient($this-> host, $this-> port);
    	if(!$client->post('/FeieServer/printSelfFormatOrder',$msgInfo)){
			$return = array('errorcode'=>-1,'msg'=>"状态查询失败");
			return $return;
		}else{
			$result = $client->getContent();
			$result = json_decode($result,JSON_UNESCAPED_UNICODE);
			$return = array('errorcode'=>0,'msg'=>$result['msg']);
			return $return;
		}
    }
}

?>
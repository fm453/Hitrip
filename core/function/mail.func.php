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
 * @remark：邮件处理函数
 */
defined('IN_IA') or exit('Access Denied');

function fmFunc_mail_send($_title='',$_content='',$_tomail="",$_Host="",$_Username="",$_Password=""){
	global $_W, $_GPC;
	if(trim($_Host)=="smtp.qq.com"){
		$_Host="ssl://smtp.qq.com";
		$_Port = 465;
		$_Authmode= 1;
	}else{
		$_Port = 25;
	}
	if ($_Authmode==1) {
		if (!extension_loaded('openssl')) {
			return '请开启 php_openssl 扩展！';
		}
	}
	include_once FM_CORE.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer.class.php';
	try {
		$mail = new PHPMailer(true); //New instance, with exceptions enabled
		$body			  =$_content;
		$body             = preg_replace('/\\\\/','', $body); //Strip backslashes

		$mail->IsSMTP();
		$mail->Charset='UTF-8';			// tell the class to use SMTP
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Port       = $_Port;                    // set the SMTP server port
		$mail->Host       = $_Host; // SMTP server
		$mail->Username   = $_Username;     // SMTP server username
		$mail->Password   = $_Password;            // SMTP server password
		if($_Authmode==1){
			$mail->SMTPSecure = 'ssl';
		}
		//$mail->IsSendmail();  // tell the class to use Sendmail
		$mail->AddReplyTo($_Username,"First Last");
		$mail->From       = $_Username;
		$mail->FromName   = $_W['account']['name']."-微信订餐".date('m-d H:i');
		$to = $_tomail;
		$mail->AddAddress($to);
		$mail->Subject  = $_title;
		$mail->AltBody    = "请使用HTML兼容模式阅读本邮件，以获得最佳的浏览效果!"; // optional, comment out and test
		$mail->WordWrap   = 80; // set word wrap
		$mail->MsgHTML($body);
		$mail->IsHTML(true); // send as HTML
		$mail->Send();
		return true;
	} catch (phpmailerException $e) {
		return $e->errorMessage();
	}
}

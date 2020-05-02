<? // Set up our socket 
header('Content-Type:text/html;charset=utf-8');
require("../inc/conn.php");
require_once('../inc/class.phpmailer.php');
$jsr=$_POST["lxemail"];
mysql_query("update crm_khb set lxemail='$jsr' where id=".$_POST["id"],$conn);
if (preg_match('/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/',$jsr)) {

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
$mail->IsSMTP(); // telling the class to use SMTP
try {
  $mail->Host       = "smtp.exmail.qq.com"; // SMTP server
  $mail->CharSet = "UTF-8";                     
  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->Host       = "smtp.exmail.qq.com"; // sets the SMTP server
  $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
  $mail->SMTPSecure = "ssl"; 
  $mail->Username   = "support@namecardio.com"; // SMTP account username
  $mail->Password   = "qwert123";        // SMTP account password
  $mail->AddAddress("$jsr", '');
  $mail->SetFrom('support@namecardio.com', '易卡名片');
//  $mail->AddReplyTo('name@yourdomain.com', 'First Last');
  $mail->Subject = "=?utf-8?B?" . base64_encode("[易卡工坊]发送给您的资料，请查收") . "?=";
  $mail->AltBody = '本邮件是易卡工坊发送给您的资料，查看本邮件请使用HTML兼容的邮件阅读软件!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML(file_get_contents('emailcontent.html'));
  //$mail->AddAttachment('images/phpmailer.gif');      // attachment
  //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
  $mail->Send();
  mysql_query("insert into crm_khb_contact values (0,".$_POST["id"].",'".$_SESSION["YKOAUSER"]."',now(),'发送Email到：{$jsr}','00')",$conn);
  echo "Message Sent OK";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}
?>

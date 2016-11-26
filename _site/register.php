<?php
/**
 * Created by PhpStorm.
 * User: yonki
 * Date: 2016/11/22
 * Time: AM10:42
 */

if(isset($_POST["register"]) && $_POST["register"] == "register")
{
    $email = $_POST["email"];
    $email_confirm = $_POST["emailConfirm"];
    $id_security_id1 = $_POST["isSecurity1"];
    $id_security_id2 = $_POST["isSecurity2"];
    $id_security_id3 = $_POST["isSecurity3"];
    $answer_security1 = $_POST["answerSecurity1"];
    $answer_security2 = $_POST["answerSecurity2"];
    $answer_security3 = $_POST["answerSecurity3"];
    if($email=="" || $id_security_id1=="" || $id_security_id2=="" || $id_security_id3== ""
        || $answer_security1=="" || $answer_security2=="" || $answer_security3=="")
    {
        echo "<script>alert('Please ensure you entered all of the information which is required!'); history.go(-1);</script>";
    } else
    {
        if($email == $email_confirm)
        {
            $dbc = mysqli_connect("localhost", "root", "123456", "CS741-AHSR");
            $name_user = create_random_string(5);
            $sql = "SELECT nameUser FROM User where nameUser='$name_user'";
            $result = mysqli_query($sql);
            $count = mysqli_num_rows($result);
            while($count)
            {
                $name_user = create_random_string(5);
                $sql = "SELECT nameUser FROM User where nameUser='$name_user'";
                $result = mysqli_query($sql);
                $count = mysqli_num_rows($result);
            }
            $password = create_random_string(8);
            send_email($email, $name_user, $password);
            //$subject = 'AHSR system account confirmation';
            //$message = "Your username is $name_user\n" . "Your password is $password\n" . "Enjoy our website";
            //$from = 'yonkimkii@gmail.com';
            //mail($email, $subject, $message, 'From:' . $from);
            $sql_insert = "INSERT INTO User (passwordUser, idSecurity1, idSecurity2, idSecurity3, answerSecurity1, " .
"answerSecurity2, answerSecurity3, email, isAdm, nameUser) VALUE ($password, $idcurity_question_id1, $id_security_id2,  $id_security_id3," .
"$answer_security1, $answer_security2, $answer_security3, $email, 0, $name_user)";
            $res_insert = mysqli_query($sql_insert);
            if($res_insert)
            {
                echo "<script>alert('Register succeed'; history.go(-1);)</script>";
            } else
            {
                echo "<script>alert('System is busy'); history.go(-1);</script>";
            }


        } else
        {
            echo "<script>alert('Confirm email is different form Email'); history.go(-1)</script>";
        }
    }

} else
{
    echo "<script>alert('Register failed'); history.go(-1);</script>";
}

function create_random_string($length)
{
    $randpwd = "";
for ($i = 0; $i < $length; $i++)
{
    $randpwd .= chr(mt_rand(33, 126));
}
return $randpwd;
}

function send_email($to, $name_user, $password)
{
    // Pear Mail 扩展
    require_once('Mail.php');
    require_once('Mail/mime.php');
    require_once('Net/SMTP.php');

    $smtpinfo = array();
    $smtpinfo["host"] = "smtp.gmail.com";//SMTP服务器
    $smtpinfo["port"] = "587"; //SMTP服务器端口
    $smtpinfo["username"] = "yonkimkii@gmail.com"; //发件人邮箱
    $smtpinfo["password"] = "password";//发件人邮箱密码
    $smtpinfo["timeout"] = 10;//网络超时时间，秒
    $smtpinfo["auth"] = true;//登录验证
//$smtpinfo["debug"] = true;//调试模式

// 收件人列表
    //$to = array('receiver@163.com');

// 发件人显示信息
    $from = "Name <yonkimkii@gmail.com>";

// 收件人显示信息
    $to = implode(',',$to);

// 邮件标题
    $subject = 'AHSR system account confirmation';

// 邮件正文
    $content = "Your username is $name_user\n" . "Your password is $password\n" . "Enjoy our website";

// 邮件正文类型，格式和编码
    $contentType = "text/html; charset=utf-8";

//换行符号 Linux: \n Windows: \r\n
    $crlf = "\n";
    $mime = new Mail_mime($crlf);
    $mime->setHTMLBody($content);

    $param['text_charset'] = 'utf-8';
    $param['html_charset'] = 'utf-8';
    $param['head_charset'] = 'utf-8';
    $body = $mime->get($param);

    $headers = array();
    $headers["From"] = $from;
    $headers["To"] = $to;
    $headers["Subject"] = $subject;
    $headers["Content-Type"] = $contentType;
    $headers = $mime->headers($headers);

    $smtp =& Mail::factory("smtp", $smtpinfo);


    $mail = $smtp->send($to, $headers, $body);
    $smtp->disconnect();

    if (PEAR::isError($mail)) {
        //发送失败
        echo 'Email sending failed: ' . $mail->getMessage()."\n";
    }
    else{
        //发送成功
        echo "success!\n";
    }
}
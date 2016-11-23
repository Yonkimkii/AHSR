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
            $sql_insert = "INSERT INTO User (passwordUser, idSecurity1, idSecurity2, idSecurity3, answerSecurity1,
answerSecurity2, answerSecurity3, email, isAdm, nameUser) VALUE ($password, $idcurity_question_id1, $id_security_id2, $id_security_id3
$answer_security1, $answer_security2, $answer_security3, $email, 0, $name_user)";
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
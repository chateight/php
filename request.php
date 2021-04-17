<?php
    session_start();
    if ($_SESSION[‘nname’] == null){
        echo "すでに送信済みです";
        exit;
        }
    else{
    $ret = db_read();
    $mailad ="";
    $cnt = count($ret);
        foreach ($ret as $cnt => $value) {
         $mailad .= $value['mail']."@isehara-3lv.sakura.ne.jp,";
        }
    $mailad .= "postmaster@isehara-3lv.sakura.ne.jp";
    $toad = explode(',', $mailad);
        $title = $_SESSION[‘title’];
        $msg = $_SESSION[‘msg’]."</br></br>---------------------------</br>このメールは配信専用につき返信はできません";
        send($title, $msg, $toad);
        echo "送信しました『戻る』を押して下さい";
//session clear
        $_SESSION = array();
        session_destroy();
        }
//
// sendgrid smtpで送信
//
function send($title, $msg, $toad){
        require 'vendor/autoload.php';
        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();

$api_key           = $_ENV['API_KEY'];
$from              = $_ENV['FROM'];
//$tos               = explode(',', $_ENV['TOS']);

$sendgrid = new SendGrid($api_key, array("turn_off_ssl_verification" => true));
$email    = new SendGrid\Email();
$email->setSmtpapiTos($toad)->
       setFrom($from)->
       setFromName($from)->
       setSubject($title)->
       setText($msg)->
       setHtml($msg)->
       addCategory('category1')->
       addHeader('X-Sent-Using', 'SendGrid-API');

$response = $sendgrid->send($email);
}
//
// Mysqlに接続して、email tableから読み出して配列で返す。
//
    function db_read()
{
define('DB_HOST', 'xxxxx');
define('DB_NAME', 'xxxxx');
define('DB_USER', 'xxxxx');
define('DB_PASSWORD', 'yyyyy');

// 文字化け対策
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

// データベースの接続
try {
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER, DB_PASSWORD, $options);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo 'success';
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
// DBからメールアドレス読み出して返却
    $stmt = $dbh->prepare("SELECT * FROM `email`");
    $stmt->execute();
    $results = $stmt->fetchAll();
// connection close
    $dbh = null;
    return $results;
}

?>

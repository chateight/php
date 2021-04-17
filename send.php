<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="local.css"> 
<title>メール配信</title>
</head>
<?php
    	function sanitize($target){
        $checked = htmlspecialchars($target, ENT_QUOTES);
        return($checked);
    }
?>
    <body>
<?php
//
// Are post data available?
//
    if (isset($_POST['title']) && isset($_POST['message']) && $_SERVER["HTTP_HOST"]=== "isehara-3lv.sakura.ne.jp"){
        echo "<div class=\"body\">";
        echo "<div class=\"center\"><h2>メール送付前の確認</h2></div>";
        echo "<hr>";
        echo "<p><div class=\"tpos\">タイトル</div><p>";
        $title = "<div class=\"col\">".sanitize($_POST['title'])."</div>";
        echo $title;
        echo "</br>";
        echo "<p><div class=\"tpos\">メッセージ</div><p>";
        $message = "<div class=\"col\">".nl2br(sanitize($_POST['message']))."</div>";
        echo $message;
        echo "<hr>";
//
// prepare for MySql & SMTP
//
    session_start();
    $_SESSION[‘nname’] = "yes";
    $_SESSION[‘title’] = sanitize($_POST['title']);
    $_SESSION[‘msg’] = nl2br(sanitize($_POST['message']));
    
    echo "<div class=\"center\"><p>よろしければ『送信』";
    echo "<p><button id=\"button\" class=\"btn-squ\">送信</button></div>";
    echo "<div class=\"center\" id=\"result\">修正するなら『戻る』</div>";
    echo "<div class=\"center\"><p><button type=\"button\" onclick=history.back() class=\"btn-squ\">戻る</button></div>";
    echo "</div>";
    }else{
    echo "不正なリクエストです";
    }
?>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script>
  $(function(){
    $("#button").click(function(event){
      $.ajax({
        type: "POST",
        url: "request.php",
        data: { val: 1 },
        dataType : "text"})
    .done(function(data){
        $("#result").text(data);})
    .fail(function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);});
    });
  });
  </script>
</body>
</html>


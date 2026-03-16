<?php

$title .= $languageArray["account.title"];

if( $_SESSION["neira_userlogin"] != 1  || $user["client_type"] == 1  ){
  Header("Location:".site_url('logout'));
}

if($_SESSION["neira_userlogin"] == 1 ):
if($settings["sms_verify"] == 2 && $user["sms_verify"] != 2){
    header("Location:".site_url('verify/sms'));
}
if($settings["mail_verify"] == 2 && $user["mail_verify"] != 2 ){
    header("Location:".site_url('verify/mail')); 
}
endif;

$user["apikey"] = private_str($user["apikey"], 10, 12);

if(isset($_SESSION["apikey_success"])):
    $success    = 1;
    $successText= "API key has been generated: <br>".$_SESSION["apikey_success"];
    unset($_SESSION["apikey_success"]);
endif;    

$financialSummary = [
  "total_deposit" => 0,
  "total_spent" => 0,
  "current_balance" => (float) $user["u_balance"],
  "total_orders" => 0,
  "completed_orders" => 0,
  "pending_payments" => 0,
  "avg_order_value" => 0,
];

$paymentSummary = $conn->prepare("SELECT COALESCE(SUM(payment_amount),0) AS total_deposit,
                                        SUM(CASE WHEN payment_status='1' THEN 1 ELSE 0 END) AS pending_payments
                                 FROM payments WHERE client_id=:id AND payment_status IN ('1','3')");
$paymentSummary->execute(["id" => $user["client_id"]]);
$paymentSummary = $paymentSummary->fetch(PDO::FETCH_ASSOC);

$orderSummary = $conn->prepare("SELECT COALESCE(SUM(order_charge),0) AS total_spent,
                                      COUNT(order_id) AS total_orders,
                                      SUM(CASE WHEN order_status='completed' THEN 1 ELSE 0 END) AS completed_orders
                               FROM orders WHERE client_id=:id");
$orderSummary->execute(["id" => $user["client_id"]]);
$orderSummary = $orderSummary->fetch(PDO::FETCH_ASSOC);

$financialSummary["total_deposit"] = (float) $paymentSummary["total_deposit"];
$financialSummary["pending_payments"] = (int) $paymentSummary["pending_payments"];
$financialSummary["total_spent"] = (float) $orderSummary["total_spent"];
$financialSummary["total_orders"] = (int) $orderSummary["total_orders"];
$financialSummary["completed_orders"] = (int) $orderSummary["completed_orders"];

if ($financialSummary["total_orders"] > 0) {
  $financialSummary["avg_order_value"] = $financialSummary["total_spent"] / $financialSummary["total_orders"];
}

if( route(1) == "currency_preferred" ){
    
  
$conn->beginTransaction();
    $id = route(2);
    $update = $conn->prepare("UPDATE clients SET currency=:type WHERE client_id=:id ");
    $update = $update->execute(array("id"=>$user["client_id"],"type"=>$id ));
    $conn->commit();
header("Location:".site_url(@$_GET['url'] =='neworder' ? '' : $_GET['url']));
 } 
 
if( route(1) == "newapikey" ){
    $conn->beginTransaction();
    $insert= $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
    $insert= $insert->execute(array("c_id"=>$user["client_id"],"action"=>"API Key değiştirildi","ip"=>GetIP(),"date"=>date("Y-m-d H:i:s") ));
    $apikey = CreateApiKey(["email"=>$user["email"],"username"=>$user["username"]]);
    $update = $conn->prepare("UPDATE clients SET apikey=:key WHERE client_id=:id ");
    $update = $update->execute(array("id"=>$user["client_id"],"key"=>$apikey ));
    if( $update && $insert ):
      $conn->commit();
      $_SESSION["apikey_success"] = $apikey;
    else:
      $conn->rollBack();
    endif;
    header('Location:'.site_url('account'));
}elseif( route(1) == "change_lang" && $_POST ){
    $lang     = $_POST["lang"];
    
     $_SESSION['lang'] = $lang;
     
    if($user['auth']){
    $update = $conn->prepare("UPDATE clients SET lang=:lang WHERE client_id=:id ");
    $update = $update->execute(array("id"=>$user["client_id"],"lang"=>$lang ));
     header("Location:".site_url('account'));
    }
    else{
          header("Location:".site_url(''));
    }
    
   
   
}elseif( route(1) == "timezone" && $_POST ){
    $timezone = $_POST["timezone"];
    $update   = $conn->prepare("UPDATE clients SET timezone=:timezone WHERE client_id=:id ");
    $update   = $update->execute(array("id"=>$user["client_id"],"timezone"=>$timezone ));
    header("Location:".site_url('account'));
}elseif( route(1) == "profile" && $_POST ){

  $displayName = trim($_POST["display_name"]);
  $firstName = trim($_POST["first_name"]);
  $lastName = trim($_POST["last_name"]);
  $telephone = trim($_POST["telephone"]);
  $bio = trim($_POST["bio"]);

  if ($displayName == "" || strlen($displayName) < 3 || strlen($displayName) > 80) {
    $error    = 1;
    $errorText= $languageArray["account.profile.validation.display_name"];
  } elseif (strlen($firstName) > 100 || strlen($lastName) > 100 || strlen($telephone) > 40 || strlen($bio) > 500) {
    $error    = 1;
    $errorText= $languageArray["account.profile.validation.basic"];
  } else {
    $update = $conn->prepare("UPDATE clients SET display_name=:display_name, first_name=:first_name, last_name=:last_name, telephone=:telephone, bio=:bio WHERE client_id=:id");
    $update = $update->execute([
      "display_name" => $displayName,
      "first_name" => $firstName,
      "last_name" => $lastName,
      "telephone" => $telephone,
      "bio" => $bio,
      "id" => $user["client_id"]
    ]);

    if ($update) {
      $success = 1;
      $successText = $languageArray["account.profile.update.success"];
      $user["display_name"] = $displayName;
      $user["first_name"] = $firstName;
      $user["last_name"] = $lastName;
      $user["telephone"] = $telephone;
      $user["bio"] = $bio;
    } else {
      $error = 1;
      $errorText = $languageArray["account.profile.update.fail"];
    }
  }
}elseif( route(1) == "avatar" && $_POST ){
  if (!isset($_FILES["avatar"]) || !is_array($_FILES["avatar"]) || $_FILES["avatar"]["error"] !== UPLOAD_ERR_OK) {
    $error = 1;
    $errorText = $languageArray["account.avatar.validation.required"];
  } else {
    $avatarFile = $_FILES["avatar"];
    $maxAvatarSize = 2 * 1024 * 1024;
    $allowedMimeTypes = [
      "image/jpeg" => "jpg",
      "image/png" => "png",
      "image/webp" => "webp"
    ];

    if ($avatarFile["size"] > $maxAvatarSize) {
      $error = 1;
      $errorText = $languageArray["account.avatar.validation.size"];
    } else {
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $detectedMimeType = $finfo->file($avatarFile["tmp_name"]);

      if (!isset($allowedMimeTypes[$detectedMimeType]) || !getimagesize($avatarFile["tmp_name"])) {
        $error = 1;
        $errorText = $languageArray["account.avatar.validation.type"];
      } else {
        $avatarDirectory = dirname(__DIR__, 2)."/img/avatars";
        if (!is_dir($avatarDirectory)) {
          mkdir($avatarDirectory, 0755, true);
        }

        $newAvatarFilename = bin2hex(random_bytes(16)).".".$allowedMimeTypes[$detectedMimeType];
        $relativeAvatarPath = "img/avatars/".$newAvatarFilename;
        $absoluteAvatarPath = dirname(__DIR__, 2)."/".$relativeAvatarPath;

        if (move_uploaded_file($avatarFile["tmp_name"], $absoluteAvatarPath)) {
          $update = $conn->prepare("UPDATE clients SET avatar=:avatar WHERE client_id=:id");
          $update = $update->execute([
            "avatar" => $relativeAvatarPath,
            "id" => $user["client_id"]
          ]);

          if ($update) {
            if (!empty($user["avatar"]) && strpos($user["avatar"], "img/avatars/") === 0) {
              $oldAvatarPath = dirname(__DIR__, 2)."/".ltrim($user["avatar"], "/");
              if (file_exists($oldAvatarPath) && $oldAvatarPath !== $absoluteAvatarPath) {
                @unlink($oldAvatarPath);
              }
            }

            $user["avatar"] = $relativeAvatarPath;
            $success = 1;
            $successText = $languageArray["account.avatar.update.success"];
          } else {
            @unlink($absoluteAvatarPath);
            $error = 1;
            $errorText = $languageArray["account.avatar.update.fail"];
          }
        } else {
          $error = 1;
          $errorText = $languageArray["account.avatar.update.fail"];
        }
      }
    }
  }
}elseif( route(1) == "security" && $_POST ){

  $pass     = $_POST["current_password"];
  $new_pass = $_POST["password"];
  $new_again= $_POST["confirm_password"];

  if( !userdata_check('password',md5(sha1(md5($pass)))) ){
    $error    = 1;
    $errorText= $languageArray["error.account.password.notmach"];
  }elseif( strlen($new_pass) < 8 ){
    $error    = 1;
    $errorText= $languageArray["error.account.password.length"];
  }elseif( $new_pass != $new_again ){
    $error    = 1;
    $errorText= $languageArray["error.account.passwords.notmatch"];
  }else{
    $conn->beginTransaction();
      $insert= $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
      $insert= $insert->execute(array("c_id"=>$user["client_id"],"action"=>"User password changed","ip"=>GetIP(),"date"=>date("Y-m-d H:i:s") ));
      $update = $conn->prepare("UPDATE clients SET password=:pass WHERE client_id=:id ");
      $update = $update->execute(array("id"=>$user["client_id"],"pass"=>md5(sha1(md5($new_pass))) ));
        if( $update && $insert ):
          $_SESSION["neira_userpass"]       = md5(sha1(md5($new_pass)));
          setcookie("u_password", md5(sha1(md5($new_pass))), time()+(60*60*24*7), '/', null, null, true );

          $conn->commit();
          $success    = 1;
          $successText= $languageArray["error.account.password.success"];

        else:
          $conn->rollBack();
          $error    = 1;
          $errorText= $languageArray["error.account.password.fail"];
        endif;
  }
}elseif( route(0) == "account" && $_POST ){

  $pass     = $_POST["current_password"];
  $new_pass = $_POST["password"];
  $new_again= $_POST["confirm_password"];

  if( !userdata_check('password',md5(sha1(md5($pass)))) ){
    $error    = 1;
    $errorText= $languageArray["error.account.password.notmach"];
  }elseif( strlen($new_pass) < 8 ){
    $error    = 1;
    $errorText= $languageArray["error.account.password.length"];
  }elseif( $new_pass != $new_again ){
    $error    = 1;
    $errorText= $languageArray["error.account.passwords.notmatch"];
  }else{
    $conn->beginTransaction();
      $insert= $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
      $insert= $insert->execute(array("c_id"=>$user["client_id"],"action"=>"User password changed","ip"=>GetIP(),"date"=>date("Y-m-d H:i:s") ));
      $update = $conn->prepare("UPDATE clients SET password=:pass WHERE client_id=:id ");
      $update = $update->execute(array("id"=>$user["client_id"],"pass"=>md5(sha1(md5($new_pass))) ));
        if( $update && $insert ):
          $_SESSION["neira_userpass"]       = md5(sha1(md5($new_pass)));
          setcookie("u_password", md5(sha1(md5($new_pass))), time()+(60*60*24*7), '/', null, null, true );

          $conn->commit();
          $success    = 1;
          $successText= $languageArray["error.account.password.success"];

        else:
          $conn->rollBack();
          $error    = 1;
          $errorText= $languageArray["error.account.password.fail"];
        endif;
  }

}

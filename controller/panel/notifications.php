<?php

$title .= $languageArray["notifications.title"] ?? "Notifications";

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

if (!function_exists('ensureNotificationsSchema')) {
  function ensureNotificationsSchema($conn)
  {
    $columns = [];
    $query = $conn->query("SHOW COLUMNS FROM notifications");
    if ($query) {
      foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $column) {
        $columns[$column['Field']] = true;
      }
    }

    $alterParts = [];
    if (!isset($columns['is_read'])) {
      $alterParts[] = "ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0";
    }
    if (!isset($columns['read_at'])) {
      $alterParts[] = "ADD COLUMN read_at DATETIME NULL DEFAULT NULL";
    }
    if (!isset($columns['type'])) {
      $alterParts[] = "ADD COLUMN type VARCHAR(50) NOT NULL DEFAULT 'general'";
    }

    if (!empty($alterParts)) {
      $conn->exec("ALTER TABLE notifications ".implode(', ', $alterParts));
    }
  }
}

ensureNotificationsSchema($conn);

$clientId = (int) $user['client_id'];

if (route(1) == "api") {
  header('Content-Type: application/json; charset=utf-8');

  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
  if ($page < 1) { $page = 1; }
  if ($limit < 1) { $limit = 10; }
  if ($limit > 50) { $limit = 50; }
  $offset = ($page - 1) * $limit;

  $totalStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE client_id=:client_id");
  $totalStmt->execute(["client_id" => $clientId]);
  $total = (int) $totalStmt->fetchColumn();

  $unreadStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE client_id=:client_id AND is_read=0");
  $unreadStmt->execute(["client_id" => $clientId]);
  $unread = (int) $unreadStmt->fetchColumn();

  $listStmt = $conn->prepare("SELECT id, title, content, type, is_read, created_at, read_at
                             FROM notifications
                             WHERE client_id=:client_id
                             ORDER BY id DESC
                             LIMIT {$offset}, {$limit}");
  $listStmt->execute(["client_id" => $clientId]);
  $notifications = $listStmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    "success" => true,
    "page" => $page,
    "limit" => $limit,
    "total" => $total,
    "unread" => $unread,
    "items" => $notifications
  ]);
  exit;
}

if (route(1) == "mark-read" && $_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json; charset=utf-8');

  $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

  if ($id > 0) {
    $markStmt = $conn->prepare("UPDATE notifications
                               SET is_read=1, read_at=NOW()
                               WHERE id=:id AND client_id=:client_id");
    $markStmt->execute([
      "id" => $id,
      "client_id" => $clientId
    ]);
  } else {
    $markStmt = $conn->prepare("UPDATE notifications
                               SET is_read=1, read_at=NOW()
                               WHERE client_id=:client_id AND is_read=0");
    $markStmt->execute(["client_id" => $clientId]);
  }

  $unreadStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE client_id=:client_id AND is_read=0");
  $unreadStmt->execute(["client_id" => $clientId]);
  $unread = (int) $unreadStmt->fetchColumn();

  echo json_encode(["success" => true, "unread" => $unread]);
  exit;
}

if( route(1) && !in_array(route(1), ["api", "mark-read"]) ){
  header("Location:".site_url('notifications'));
  exit;
}

$page = route(1) && is_numeric(route(1)) ? (int) route(1) : 1;
if ($page < 1) { $page = 1; }
$to = 20;

$totalStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE client_id=:client_id");
$totalStmt->execute(["client_id" => $clientId]);
$count = (int) $totalStmt->fetchColumn();

$pageCount = $count > 0 ? (int) ceil($count / $to) : 1;
if ($page > $pageCount) { $page = 1; }
$where = ($page * $to) - $to;
$paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];

$listStmt = $conn->prepare("SELECT id, title, content, type, is_read, created_at, read_at
                           FROM notifications
                           WHERE client_id=:client_id
                           ORDER BY id DESC
                           LIMIT {$where}, {$to}");
$listStmt->execute(["client_id" => $clientId]);
$notifications = $listStmt->fetchAll(PDO::FETCH_ASSOC);

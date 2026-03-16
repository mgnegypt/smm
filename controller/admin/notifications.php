<?php

if( $user["access"]["users"] != 1  ):
  header("Location:".site_url("admin"));
  exit();
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

if ($_POST) {
  $recipientType = $_POST['recipient_type'];
  $username = trim($_POST['username']);
  $groupUsernamesRaw = trim($_POST['group_usernames']);
  $titleText = trim($_POST['title']);
  $message = trim($_POST['message']);
  $notificationType = trim($_POST['notification_type']);

  if ($titleText == '') { $titleText = 'Notification'; }
  if ($notificationType == '') { $notificationType = 'general'; }

  if ($message == '') {
    $error = 1;
    $errorText = 'Notification message cannot be empty';
  } else {
    $targetClientIds = [];

    if ($recipientType == 'all') {
      $usersStmt  = $conn->prepare("SELECT client_id FROM clients");
      $usersStmt->execute();
      foreach ($usersStmt->fetchAll(PDO::FETCH_ASSOC) as $targetUser) {
        $targetClientIds[] = (int) $targetUser['client_id'];
      }
    } elseif ($recipientType == 'secret') {
      $targetUser = getRow(["table"=>"clients","where"=>["username"=>$username]]);
      if ($targetUser) {
        $targetClientIds[] = (int) $targetUser['client_id'];
      }
    } elseif ($recipientType == 'group') {
      $groupUsernames = preg_split('/[\s,]+/', (string) $groupUsernamesRaw);
      $groupUsernames = array_filter(array_map('trim', $groupUsernames));
      if (!empty($groupUsernames)) {
        $placeholders = implode(',', array_fill(0, count($groupUsernames), '?'));
        $usersStmt = $conn->prepare("SELECT client_id FROM clients WHERE username IN ({$placeholders})");
        $usersStmt->execute(array_values($groupUsernames));
        foreach ($usersStmt->fetchAll(PDO::FETCH_ASSOC) as $targetUser) {
          $targetClientIds[] = (int) $targetUser['client_id'];
        }
      }
    }

    $targetClientIds = array_values(array_unique(array_filter($targetClientIds)));

    if (empty($targetClientIds)) {
      $error = 1;
      $errorText = 'No target users found';
    } else {
      $insertStmt = $conn->prepare("INSERT INTO notifications SET client_id=:client_id, title=:title, content=:content, type=:type, is_read=0, read_at=NULL");
      foreach ($targetClientIds as $targetClientId) {
        $insertStmt->execute([
          'client_id' => $targetClientId,
          'title' => $titleText,
          'content' => $message,
          'type' => $notificationType
        ]);
      }

      $success = 1;
      $successText = 'Notification has been sent';
    }
  }
}

$recentNotifications = $conn->prepare("SELECT n.*, c.username
                                      FROM notifications n
                                      LEFT JOIN clients c ON c.client_id=n.client_id
                                      ORDER BY n.id DESC
                                      LIMIT 50");
$recentNotifications->execute();
$recentNotifications = $recentNotifications->fetchAll(PDO::FETCH_ASSOC);

require admin_view('notifications');

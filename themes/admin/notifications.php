<?php include 'header.php'; ?>
<div class="container-fluid">
  <?php if( $success ): ?>
    <div class="alert alert-success"><?php echo $successText; ?></div>
  <?php endif; ?>
  <?php if( $error ): ?>
    <div class="alert alert-danger"><?php echo $errorText; ?></div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Send internal notification</strong></div>
        <div class="panel-body">
          <form method="post" action="<?=site_url('admin/notifications')?>">
            <div class="form-group">
              <label>Recipient</label>
              <select class="form-control" name="recipient_type" id="recipient_type">
                <option value="all">All users</option>
                <option value="secret">Specific user</option>
                <option value="group">Group (usernames)</option>
              </select>
            </div>

            <div class="form-group" id="recipient_username" style="display:none;">
              <label>Username</label>
              <input type="text" class="form-control" name="username" placeholder="single username">
            </div>

            <div class="form-group" id="recipient_group" style="display:none;">
              <label>Group usernames</label>
              <textarea class="form-control" name="group_usernames" rows="3" placeholder="user1,user2,user3"></textarea>
            </div>

            <div class="form-group">
              <label>Notification category</label>
              <input type="text" class="form-control" name="notification_type" value="general" placeholder="general">
            </div>

            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" name="title" placeholder="Notification title">
            </div>

            <div class="form-group">
              <label>Message</label>
              <textarea class="form-control" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Send notification</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Recent notifications</strong></div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Type</th>
                <th>Title</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($recentNotifications as $item): ?>
              <tr>
                <td><?=$item['id']?></td>
                <td><?=htmlspecialchars($item['username'])?></td>
                <td><?=htmlspecialchars($item['type'])?></td>
                <td><?=htmlspecialchars($item['title'])?></td>
                <td><?php if($item['is_read']): ?>Read<?php else: ?>Unread<?php endif; ?></td>
                <td><?=$item['created_at']?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    var recipientType = document.getElementById('recipient_type');
    var usernameBox = document.getElementById('recipient_username');
    var groupBox = document.getElementById('recipient_group');

    function updateRecipientFields(){
      var mode = recipientType.value;
      usernameBox.style.display = mode === 'secret' ? 'block' : 'none';
      groupBox.style.display = mode === 'group' ? 'block' : 'none';
    }

    recipientType.addEventListener('change', updateRecipientFields);
    updateRecipientFields();
  })();
</script>
<?php include 'footer.php'; ?>

<div style="padding: 10px;">
  <table class="layui-table">
    <tr><th style="width:100px;font-weight:bold;">ID</th><td>{$detail.id}</td></tr>
    <tr><th style="width:100px;font-weight:bold;">用户名</th><td>{$detail.username}<?php echo $this->detail['username'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">昵称</th><td>{$detail.nickname}<?php echo $this->detail['nickname'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">账号创建时间</th><td>{$detail.create_time}<?php echo $this->detail['create_time'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">资料更新时间</th><td>{$detail.update_time}<?php echo $this->detail['update_time'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">最后登录时间</th><td>{$detail.login_time}<?php echo $this->detail['login_time'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">状态</th><td>{$detail.status}</td></tr>
  </table>
</div>

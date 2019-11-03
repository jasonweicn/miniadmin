<div style="padding: 10px;">
  <table class="layui-table">
    <tr><th style="width:100px;font-weight:bold;">ID</th><td><?php echo $this->detail['id'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">用户名</th><td><?php echo $this->detail['username'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">昵称</th><td><?php echo $this->detail['nickname'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">账号创建时间</th><td><?php echo $this->detail['create_time'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">资料更新时间</th><td><?php echo $this->detail['update_time'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">最后登录时间</th><td><?php echo $this->detail['login_time'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">状态</th><td><?php echo $this->detail['status'];?></td></tr>
    <?php if ($this->detail['delete_mark'] == 1) {?>
    <tr><th style="width:100px;font-weight:bold;">删除时间</th><td><?php echo $this->detail['delete_time'];?></td></tr>
    <?php }?>
  </table>
</div>

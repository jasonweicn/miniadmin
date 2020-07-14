<div style="padding: 10px;">
  <table class="layui-table">
    <tr><th style="width:100px;font-weight:bold;">ID</th><td><?php echo $this->detail['id'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">菜单名称</th><td><?php echo $this->detail['menu_name'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">父级</th><td><?php echo $this->detail['parent_menu_name'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">路由</th><td><?php echo $this->detail['route'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">排序</th><td><?php echo $this->detail['sort'];?></td></tr>
    <tr><th style="width:100px;font-weight:bold;">状态</th><td><?php echo $this->detail['status'];?></td></tr>
  </table>
</div>

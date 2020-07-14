<div style="padding: 10px;">
  <form class="layui-form layui-form-pane" method="post" action="">
    <input type="hidden" name="id" value="<?php echo $this->detail['id'];?>" />
  <div class="layui-form-item">
    <label class="layui-form-label">菜单名称</label>
    <div class="layui-input-block">
      <input type="text" name="menu_name" lay-verify="required" autocomplete="off" placeholder="请输入菜单名称" class="layui-input" value="<?php echo $this->detail['menu_name'];?>">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">父级</label>
    <div class="layui-input-inline">
      <select name="pid">
        <option value="0" selected="selected">无（一级菜单）</option>
        <?php foreach ($this->topMenuList as $value) {?>
          <option value="<?php echo $value['id'];?>"<?php if ($value['selected'] == 1) { echo ' selected="selected"';}?>><?php echo $value['menu_name'];?></option>
        <?php }?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">路由</label>
    <div class="layui-input-block">
      <input type="text" name="route" autocomplete="off" class="layui-input" value="<?php echo $this->detail['route'];?>"<?php if ($this->detail['protected'] == 1) {echo ' disabled="disabled"';}?>>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block">
      <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?php echo $this->detail['sort'];?>">
    </div>
  </div>
  <div class="layui-form-item" style="padding-top:10px;text-align:center">
    <button name="add_submit" class="layui-btn" lay-submit="" lay-filter="add_submit">保存</button>
  </div>
</form>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['form'], function () {
  var form = layui.form;

  // 异步提交表单
  form.on('submit(add_submit)', function (data) {
    // 按钮禁用
    $('button[name="add_submit"]').addClass('layui-btn-disabled').attr('disabled', 'true');
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/menu/update',
      data: data.field,
      dataType: 'json',
      success: function (data) {
        if (data.suc == 1) {
          layer.msg('数据保存成功', {icon:1, anim:5, time:3000, offset:'100px'}, function () {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layui.table.reload("data-list");
            parent.layer.close(index);
          });
        } else {
          layer.alert(data.info, {icon:2,anim:5,closeBtn:0,offset:'100px'}, function (index) {
            layer.close(index);
            if (data.err_code == 'E05') {
              $('input[name="route"]').addClass('layui-form-danger').focus();
            }
            // 按钮解禁
            $('button[name="add_submit"]').removeClass('layui-btn-disabled').removeAttr('disabled', 'true');
          });
        }
      },
      error: function (data) {
        layer.alert('网络请求异常',{icon:2,anim:5});
        // 按钮解禁
        $('button[name="add_submit"]').removeClass('layui-btn-disabled').removeAttr('disabled', 'true');
      }
    });
    return false;
  });

});
</script>
<?php $this->endBlock();?>

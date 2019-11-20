<div style="padding: 10px;">
  <form class="layui-form layui-form-pane" method="post" action="">
    <input type="hidden" name="id" value="<?php echo $this->detail['id'];?>">
    <div class="layui-form-item">
      <label class="layui-form-label">角色名称</label>
      <div class="layui-input-block">
        <input type="text" name="role_name" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $this->detail['role_name'];?>">
      </div>
    </div>
    <div class="layui-form-item" style="padding-top:10px;text-align:center">
      <button name="edit_submit" class="layui-btn" lay-submit="" lay-filter="edit_submit">保存</button>
    </div>
  </form>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['form'], function () {
  var form = layui.form;

  // 异步提交表单
  form.on('submit(edit_submit)', function (data) {
    // 按钮禁用
    $('button[name="edit_submit"]').addClass('layui-btn-disabled').attr('disabled', 'true');
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/role/update',
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
          layer.alert(data.info,{icon:2,anim:5,offset:'100px'}, function () {
            // 按钮解禁
            $('button[name="add_submit"]').removeClass('layui-btn-disabled').removeAttr('disabled', 'true');
          });
        }
      },
      error: function (data) {
        layer.alert('网络请求异常',{icon:2,anim:5});
      }
    });
    return false;
  });

});
</script>
<?php $this->endBlock();?>

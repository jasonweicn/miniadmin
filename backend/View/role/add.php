<div style="padding: 10px;">
  <form class="layui-form layui-form-pane" method="post" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">角色名称</label>
    <div class="layui-input-block">
      <input type="text" name="role_name" lay-verify="required" autocomplete="off" placeholder="请输入角色名称" class="layui-input">
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
      url: '<?php echo $this->baseUrl();?>/role/addsave',
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
            if (data.err_code == 'E03') { // 角色名称重复
              $('input[name="username"]').addClass('layui-form-danger').focus();
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

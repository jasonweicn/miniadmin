<div style="padding: 10px;">
  <form class="layui-form layui-form-pane" method="post" action="">
    <fieldset class="layui-elem-field">
      <legend>基本资料</legend>
      <div class="layui-field-box">
        <div class="layui-form-item">
          <label class="layui-form-label">用户名</label>
          <div class="layui-input-block">
            <input type="text" name="username" lay-verify="required" autocomplete="off" placeholder="请输入用户名" class="layui-input">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">昵称</label>
          <div class="layui-input-block">
            <input type="text" name="nickname" lay-verify="required" autocomplete="off" placeholder="请输入昵称" class="layui-input">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">密码</label>
          <div class="layui-input-inline">
            <input type="password" id="password1" name="password1" lay-verify="required|pass" placeholder="请输入密码" autocomplete="off" class="layui-input">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">密码</label>
          <div class="layui-input-inline">
            <input type="password" name="password2" lay-verify="required|pass|password2" placeholder="请再次输入密码" autocomplete="off" class="layui-input">
          </div>
        </div>
      </div>
    </fieldset>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top:0px;">
      <legend>角色设定</legend>
      <div class="layui-field-box">
        <div class="layui-form-item">
          <?php foreach ($this->roleList as $val) {?>
            <input type="checkbox" name="role[<?php echo $val['id'];?>]" title="<?php echo $val['role_name'];?>" lay-verify="required|choiceOne">
          <?php }?>
        </div>
      </div>
    </fieldset>
    <div class="layui-form-item" style="padding-top:10px;text-align:center">
      <button name="add_submit" class="layui-btn" lay-submit="" lay-filter="add_submit">保存</button>
    </div>
  </form>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['form'], function () {
  var form = layui.form;

  // 验证
  form.verify ({
    pass: [
      /^[\S]{6,32}$/,
      '新密码必须6到32位，且不能包含空格'
    ],
    password2: function (value) {
      if (value != password1.value) {
        return '您两次输入的密码不一致，请检查';
      }
    },
    choiceOne: function (value) {
      var len = $("input:checked").length;
      if (len < 1) {
        return '请至少选择一个角色';
      }
    }
  });

  // 异步提交表单
  form.on('submit(add_submit)', function (data) {
    // 按钮禁用
    $('button[name="add_submit"]').addClass('layui-btn-disabled').attr('disabled', 'true');
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/adminuser/save',
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
            if (data.err_code == 'E08') { // 用户名重复
              $('input[name="username"]').addClass('layui-form-danger').focus();
            } else if (data.err_code == 'E09') { // 昵称重复
              $('input[name="nickname"]').addClass('layui-form-danger').focus();
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

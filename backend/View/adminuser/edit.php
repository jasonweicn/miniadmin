<div style="padding: 10px;">
  <form class="layui-form layui-form-pane" method="post" action="">
    <input type="hidden" name="id" value="<?php echo $this->detail['id'];?>">
    <fieldset class="layui-elem-field">
      <legend>基本资料</legend>
      <div class="layui-field-box">
        <div class="layui-form-item">
          <label class="layui-form-label">用户名</label>
          <div class="layui-input-block">
            <input type="text" name="username" autocomplete="off" class="layui-input" value="<?php echo $this->detail['username'];?>" readonly>
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">昵称</label>
          <div class="layui-input-block">
            <input type="text" name="nickname" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $this->detail['nickname'];?>">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">密码</label>
          <div class="layui-input-inline">
            <input type="password" id="password" name="password" lay-verify="password" placeholder="不修改请留空" autocomplete="off" class="layui-input">
          </div>
        </div>
      </div>
    </fieldset>
    <fieldset class="layui-elem-field">
      <legend>角色设定</legend>
      <div class="layui-field-box">
        <div class="layui-form-item">
          <?php foreach ($this->roleList as $val) {?>
            <input type="checkbox" name="role[<?php echo $val['id'];?>]" title="<?php echo $val['role_name'];?>" lay-verify="required|choiceOne" <?php if ($val['on'] === true) {echo 'checked="checked"';}?>>
          <?php }?>
        </div>
      </div>
    </fieldset>
    <div class="layui-form-item" style="padding-top:10px;text-align:center">
      <button name="edit_submit" class="layui-btn" lay-submit="" lay-filter="edit_submit">保存</button>
    </div>
  </form>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['form'], function () {
  var form = layui.form;

  // 验证
  form.verify ({
    password: function (value) {
      if (value != "") {
        if (! value.match(/^[\S]{6,32}$/)) {
          return '密码必须6到32位，且不能包含空格';
        }
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
  form.on('submit(edit_submit)', function (data) {
    // 按钮禁用
    $('button[name="edit_submit"]').addClass('layui-btn-disabled').attr('disabled', 'true');
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/adminuser/update',
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
          layer.alert(data.info,{icon:2,anim:5,offset:'100px'});
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

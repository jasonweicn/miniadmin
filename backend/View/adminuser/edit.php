<div style="padding: 10px;">
  <form class="layui-form layui-form-pane" method="post" action="">
    <input type="hidden" name="id" value="<?php echo $this->detail['id'];?>">
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
    }
  });

  // 异步提交表单
  form.on('submit(edit_submit)', function (data) {
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/adminuser/update',
      data: data.field,
      dataType: 'json',
      success: function (data) {
        if (data.suc == 1) {
          //$("input[type='password']").val('');
          layer.alert('数据保存成功',{icon:1,anim:5,time:3000,offset:'100px'});
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

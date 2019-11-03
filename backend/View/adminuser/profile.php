<div class="layui-body">
  <div style="padding: 15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
      <legend>我的账号</legend>
    </fieldset>
    <form class="layui-form" method="post" action="">
      <div class="layui-row">
        <div class="layui-col-lg8 layui-col-md18 layui-col-sm10 layui-col-xs12">
          <div class="layui-form-item">
            <label class="layui-form-label">用户名：</label>
            <div class="layui-input-inline">
              <div class="layui-form-mid">admin</div>
            </div>
          </div>
        </div>
      </div>
      <div class="layui-row">
        <div class="layui-col-lg8 layui-col-md18 layui-col-sm10 layui-col-xs12">
          <div class="layui-form-item">
            <label class="layui-form-label">旧密码：</label>
            <div class="layui-input-inline">
              <input type="password" name="password" lay-verify="password" placeholder="请输入旧密码" autocomplete="off" class="layui-input">
            </div>

          </div>
        </div>
      </div>
      <div class="layui-row">
        <div class="layui-col-lg8 layui-col-md18 layui-col-sm10 layui-col-xs12">
          <div class="layui-form-item">
            <label class="layui-form-label">新密码：</label>
            <div class="layui-input-inline">
              <input type="password" id="newpassword1" name="newpassword1" lay-verify="pass" placeholder="请输入新密码" autocomplete="off" class="layui-input">
            </div>
          </div>
        </div>
      </div>
      <div class="layui-row">
        <div class="layui-col-lg8 layui-col-md18 layui-col-sm10 layui-col-xs12">
          <div class="layui-form-item">
            <label class="layui-form-label">新密码：</label>
            <div class="layui-input-inline">
              <input type="password" name="newpassword2" lay-verify="pass|password2" placeholder="请再次输入新密码" autocomplete="off" class="layui-input">
            </div>
          </div>
        </div>
      </div>
      <div class="layui-row">
        <div class="layui-form-item">
          <label class="layui-form-label"></label>
          <div class="layui-input-inline">
            <button name="profile_submit" class="layui-btn" lay-submit="" lay-filter="profile_submit">提交</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['form'], function () {
  var form = layui.form;

  // 验证
  form.verify ({
    password: function (value) {
      if (value.length <= 0){
        return '修改密码时需要输入旧密码进行验证';
      }
    },
    pass: [
      /^[\S]{6,32}$/,
      '新密码必须6到32位，且不能包含空格'
    ],
    password2: function (value) {
      if (value != newpassword1.value) {
        return '您两次输入的新密码不一致，请检查';
      }
    }
  });

  // 异步提交表单
  form.on('submit(profile_submit)', function (data) {
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/adminuser/profilesave',
      data: data.field,
      dataType: 'json',
      success: function (data) {
        if (data.suc == 1) {
          $("input[type='password']").val('');
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

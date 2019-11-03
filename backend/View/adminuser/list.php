<div class="layui-body">
  <div style="padding: 15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
      <legend>用户管理</legend>
    </fieldset>
    <table id="adminuser-list" lay-filter="adminuser-list-event"></table>
  </div>
</div>
<?php $this->beginBlock('jscode');?>
<script type="text/html" id="listBar">
  <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
layui.use(['table'], function () {
  var table = layui.table;

  // 渲染表格
  table.render({
    elem: "#adminuser-list",
    url: '<?php echo $this->baseUrl();?>/adminuser/listdata',
    page: true,
    cols: [[
      {field: 'id', title: 'ID', minWidth: 60, sort: true, fixed: 'left'},
      {field: 'username', title: '用户名', minWidth: 100},
      {field: 'nickname', title: '昵称', minWidth: 100},
      {field: 'login_time', title: '最后登录时间', minWidth: 120, sort: true},
      {field: 'status', title: '状态', minWidth: 60},
      {title: '操作', toolbar: '#listBar', minWidth: 120},
    ]]
  });

  // 表格事件
  table.on('tool(adminuser-list-event)', function (obj) {
    var data = obj.data;
    var curEvent = obj.event;
    if (curEvent === 'detail') {
      layer.open({
        type: 2,
        area: ['350px', '400px'],
        title: '用户管理 - 详情',
        content: '<?php echo $this->baseUrl();?>/adminuser/detail?id=' + data.id,
        success : function (layero, index) {
          setTimeout(function () {
            layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
              tips: 3
            });
          }, 300);
        }
      });
    } else if (curEvent === 'edit') { //编辑
      layer.open({
        type: 2,
        area: ['350px', '400px'],
        title: '用户管理 - 编辑',
        content: '<?php echo $this->baseUrl();?>/adminuser/edit?id=' + data.id,
        success : function (layero, index) {
          setTimeout(function () {
            layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
              tips: 3
            });
          }, 300);
        }
      });
    } else if (curEvent === 'del') { //删除
      if (data.id == 1) {
        layer.alert('超级管理员为系统默认账号，不可删除！',{icon:0,anim:5,offset:'100px'});
        return false;
      }
      layer.confirm('确认要删除用户 ' + data.username + ' 吗？', function (index) {
        layer.close(index);
        $.ajax({
          type: 'get',
          url: '<?php echo $this->baseUrl();?>/adminuser/del?id=' + data.id,
          dataType: 'json',
          success: function (data) {
            if (data.suc == 1) {
              obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
              layer.alert('删除成功',{icon:1,anim:5,time:3000,offset:'100px'});
            } else {
              layer.alert(data.info,{icon:2,anim:5,offset:'100px'});
            }
          },
          error: function (data) {
            layer.alert('网络请求异常',{icon:2,anim:5});
          }
        });
      });
    }
  });
});
</script>
<?php $this->endBlock();?>

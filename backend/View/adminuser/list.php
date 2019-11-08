<div class="layui-body">
  <div style="padding:15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top:0px;">
      <legend>用户管理</legend>
    </fieldset>
    <blockquote class="layui-elem-quote layui-form">
      <div class="layui-input-inline" style="width:100px;">
        <select name="searchfield">
          <option value="id">ID</option>
          <option value="username" selected="selected">用户名</option>
          <option value="nickname">昵称</option>
        </select>
      </div>
      <div class="layui-input-inline">
        <input class="layui-input" name="keyword" autocomplete="off">
      </div>
      <button id="searchBtn" type="button" class="layui-btn layui-btn-sm">
        <i class="layui-icon layui-icon-search"></i> 搜索
      </button>
      <button id="addBtn" type="button" class="layui-btn layui-btn-sm">
        <i class="layui-icon layui-icon-add-circle"></i> 新增
      </button>
      <button type="button" class="layui-btn layui-btn-sm">
        <i class="layui-icon layui-icon-refresh"></i> 刷新
      </button>
    </blockquote>
    <table id="adminuser-list" lay-filter="adminuser-list-event"></table>
  </div>
</div>
<?php $this->beginBlock('jscode');?>
<script type="text/html" id="statusTpl">
  {{# if(d.delete_mark == 1) { }}
    <span style="color: #C00;">{{ d.status }}</span>
  {{# } else { }}
    {{ d.status }}
  {{# } }}
</script>
<script type="text/html" id="listBar">
  <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
  <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
  {{# if (d.delete_mark == 1) { }}
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="recover">恢复</a>
  {{# } else { }}
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">冻结</a>
  {{# } }}
</script>
<script>
layui.use(['table'], function () {
  var table = layui.table;

  // 渲染表格
  var listTable = table.render({
    elem: "#adminuser-list",
    url: '<?php echo $this->baseUrl();?>/adminuser/listdata',
    page: true,
    cols: [[
      {field: 'id', title: 'ID', minWidth: 60, sort: true, fixed: 'left'},
      {field: 'username', title: '用户名', minWidth: 100},
      {field: 'nickname', title: '昵称', minWidth: 100},
      {field: 'login_time', title: '最后登录时间', minWidth: 120, sort: true},
      {field: 'status', title: '状态', minWidth: 60, templet: '#statusTpl'},
      {title: '操作', toolbar: '#listBar', minWidth: 120},
    ]]
  });

  // 搜索按钮点击事件
  $('#searchBtn').click(function () {
    listTable.reload({
      where: {
        searchfield: $("select[name='searchfield']").val(),
        keyword: $("input[name='keyword']").val()
      },
      page: {
        curr: 1
      }
    });
  });

  // 新增按钮点击事件
  $("#addBtn").click(function () {
    layer.open({
      type: 2,
      area: ['350px', '400px'],
      title: '用户管理 - 新增',
      content: '<?php echo $this->baseUrl();?>/adminuser/add',
      success : function (layero, index) {
        setTimeout(function () {
          layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
            tips: 3
          });
        }, 300);
      }
    });
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
        layer.alert('超级管理员为系统默认账号，不可冻结！', {icon:0,anim:5,offset:'100px'});
        return false;
      }
      layer.confirm('确认要冻结用户 ' + data.username + ' 吗？', function (index) {
        layer.close(index);
        $.ajax({
          type: 'get',
          url: '<?php echo $this->baseUrl();?>/adminuser/del?id=' + data.id,
          dataType: 'json',
          success: function (data) {
            if (data.suc == 1) {
              layer.alert('冻结成功', {icon:1,anim:5,time:3000,offset:'100px'});
              listTable.reload({
                where: {
                  searchfield: $("select[name='searchfield']").val(),
                  keyword: $("input[name='keyword']").val()
                }
              });
            } else {
              layer.alert(data.info, {icon:2,anim:5,offset:'100px'});
            }
          },
          error: function (data) {
            layer.alert('网络请求异常',{icon:2,anim:5});
          }
        });
      });
    } else if (curEvent === 'recover') { //恢复
      if (data.id == 1) {
        layer.alert('超级管理员为系统默认账号，不可进行此操作！', {icon:0,anim:5,offset:'100px'});
        return false;
      }
      layer.confirm('确认要恢复用户 ' + data.username + ' 吗？', function (index) {
        layer.close(index);
        $.ajax({
          type: 'get',
          url: '<?php echo $this->baseUrl();?>/adminuser/recover?id=' + data.id,
          dataType: 'json',
          success: function (data) {
            if (data.suc == 1) {
              layer.alert('恢复成功', {icon:1,anim:5,time:3000,offset:'100px'});
              listTable.reload({
                where: {
                  searchfield: $("select[name='searchfield']").val(),
                  keyword: $("input[name='keyword']").val()
                }
              });
            } else {
              layer.alert(data.info, {icon:2,anim:5,offset:'100px'});
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

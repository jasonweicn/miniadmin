<div class="layui-body">
  <div style="padding:15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top:0px;">
      <legend>菜单管理</legend>
    </fieldset>
    <blockquote class="layui-elem-quote layui-form">
      <button id="addBtn" type="button" class="layui-btn layui-btn-sm">
        <i class="layui-icon layui-icon-add-circle"></i> 新增
      </button>
      <button id="refreshBtn" type="button" class="layui-btn layui-btn-sm">
        <i class="layui-icon layui-icon-refresh"></i> 刷新
      </button>
    </blockquote>
    <table id="data-list" lay-filter="data-list-event"></table>
  </div>
</div>
<?php $this->beginBlock('jscode');?>
<script type="text/html" id="statusTpl">
  {{# if(d.disable == 1) { }}
    <span style="color:#FF5722;">{{ d.status }}</span>
  {{# } else { }}
    <span style="color:#009688">{{ d.status }}</span>
  {{# } }}
</script>
<script type="text/html" id="routeTpl">
  {{# if(! d.route) { }}
    <span style="color:#FF5722;">(未设置)</span>
  {{# } else { }}
    {{ d.route }}
  {{# } }}
</script>
<script type="text/html" id="pnameTpl">
  {{# if(d.pid == 0) { }}
    <span style="color:#FF5722;">{{ d.pname }}</span>
  {{# } else { }}
    {{ d.pname }}
  {{# } }}
</script>
<script type="text/html" id="listBar">
  <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
  {{# if (d.protected == 0) { }}
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>
  {{# } else { }}
    <a class="layui-btn layui-btn-disabled layui-btn-xs" style="padding:0 4px;" lay-event="disabledMsg">编辑</a>
  {{# }}}
  {{# if (d.disable == 1) { }}
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="enable">启用</a>
  {{# } else { }}
    {{# if (d.protected == 0) { }}
      <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="disable">禁用</a>
    {{# } else { }}
      <a class="layui-btn layui-btn-disabled layui-btn-xs" style="padding:0 4px;" lay-event="disabledMsg">禁用</a>
    {{# }}}
  {{# } }}
  {{# if (d.protected == 0) { }}
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  {{# } else { }}
    <a class="layui-btn layui-btn-disabled layui-btn-xs" style="padding:0 4px;" lay-event="disabledMsg">删除</a>
  {{# }}}
</script>
<script>
layui.use(['table'], function () {
  var table = layui.table;

  // 渲染表格
  var listTable = table.render({
    elem: "#data-list",
    url: '<?php echo $this->baseUrl();?>/menu/list',
    page: true,
    cols: [[
      {field: 'id', title: 'ID', minWidth: 60, sort: true, fixed: 'left'},
      {field: 'menu_name', title: '菜单名称', minWidth: 100},
      {field: 'route', title: '路由', minWidth: 200, sort: true, templet: '#routeTpl'},
      {field: 'pname', title: '父级', minWidth: 100, sort: true, templet: '#pnameTpl'},
      {field: 'sort', title: '排序', minWidth: 60, sort: true},
      {field: 'status', title: '状态', minWidth: 60, templet: '#statusTpl'},
      {title: '操作', toolbar: '#listBar', width: 210},
    ]]
  });

  // 新增按钮点击事件
  $("#addBtn").click(function () {
    layer.open({
      type: 2,
      area: ['350px', '400px'],
      title: '菜单管理 - 新增',
      content: '<?php echo $this->baseUrl();?>/menu/add',
      success : function (layero, index) {
        setTimeout(function () {
          layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
            tips: 3
          });
        }, 300);
      }
    });
  });

  // 刷新按钮点击事件
  $("#refreshBtn").click(function () {
    listTable.reload({
      where: {
        searchfield: $("select[name='searchfield']").val(),
        keyword: $("input[name='keyword']").val()
      }
    });
  });

  // 表格事件
  table.on('tool(data-list-event)', function (obj) {
    var data = obj.data;
    var curEvent = obj.event;
    if (curEvent === 'detail') {
      layer.open({
        type: 2,
        area: ['350px', '400px'],
        title: '菜单管理 - 详情',
        content: '<?php echo $this->baseUrl();?>/menu/detail?id=' + data.id,
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
        title: '菜单管理 - 编辑',
        content: '<?php echo $this->baseUrl();?>/menu/edit?id=' + data.id,
        success : function (layero, index) {
          setTimeout(function () {
            layui.layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
              tips: 3
            });
          }, 300);
        }
      });
    } else if (curEvent === 'disable') { //禁用（逻辑删除）
      layer.confirm('确认要禁用 ' + data.menu_name + ' 吗？', function (index) {
        layer.close(index);
        $.ajax({
          type: 'get',
          url: '<?php echo $this->baseUrl();?>/menu/disable?id=' + data.id,
          dataType: 'json',
          success: function (data) {
            if (data.suc == 1) {
              layer.alert('禁用成功', {icon:1,anim:5,time:3000,offset:'100px'});
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
    } else if (curEvent === 'enable') { //启用
      layer.confirm('确认要启用 ' + data.menu_name + ' 吗？', function (index) {
        layer.close(index);
        $.ajax({
          type: 'get',
          url: '<?php echo $this->baseUrl();?>/menu/enable?id=' + data.id,
          dataType: 'json',
          success: function (data) {
            if (data.suc == 1) {
              layer.alert('启用成功', {icon:1,anim:5,time:3000,offset:'100px'});
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
    } else if (curEvent === 'del') {
      layer.confirm('确认要删除 ' + data.menu_name + ' 吗？（删除后无法恢复）', function (index) {
        layer.close(index);
        $.ajax({
          type: 'get',
          url: '<?php echo $this->baseUrl();?>/menu/del?id=' + data.id,
          dataType: 'json',
          success: function (data) {
            if (data.suc == 1) {
              layer.alert('删除成功', {icon:1,anim:5,time:3000,offset:'100px'});
              obj.del();
            } else {
              layer.alert(data.info, {icon:2,anim:5,offset:'100px'});
            }
          },
          error: function (data) {
            layer.alert('网络请求异常',{icon:2,anim:5});
          }
        });
      });
    } else if (curEvent === 'disabledMsg') {
      layer.alert('此为受保护的系统默认菜单，不可修改、禁用或删除！', {icon:0,anim:5,offset:'100px'});
    }
  });
});

</script>
<?php $this->endBlock();?>

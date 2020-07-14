<div style="padding:10px;">
  <div id="purviewData" class="demo-tree demo-tree-box"></div>
  <div style="text-align:center;"><button type="button" id="saveBtn" class="layui-btn" style="margin-top:30px;">保存</button></div>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['tree'], function () {
  var tree = layui.tree;

  var purview_data;
  purview_data = JSON.parse('<?php echo $this->purview_data;?>');
  console.log(purview_data);
  tree.render({
    elem: '#purviewData',
    data: purview_data,
    showCheckbox: true,
    id: 'purviewTree'
  });


  $('#saveBtn').click(function () {
    // 按钮禁用
    $(this).addClass('layui-btn-disabled').attr('disabled', 'true');
    var purviewData = tree.getChecked('purviewTree');
    //console.log(purviewData);

    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/role/purviewsave',
      data: {role_id:<?php echo $this->role_id;?>,purview_data:purviewData},
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
  });


});
</script>
<?php $this->endBlock();?>

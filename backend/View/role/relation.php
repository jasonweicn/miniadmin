<div style="padding:10px;text-align:center;">
  <div id="relationData" class="demo-transfer"></div>
  <button type="button" id="saveBtn" class="layui-btn" style="margin-top:30px;">保存</button>
</div>
<?php $this->beginBlock('jscode');?>
<script>
layui.use(['transfer'], function () {
  var transfer = layui.transfer;

  var relation_data;
  relation_data = JSON.parse('<?php echo $this->relation_data;?>');
  console.log(relation_data);
  transfer.render({
    elem: '#relationData',
    id: 'defaultTransfer',
    width: 150,
    height: 200,
    title: ['未关联', '已关联'],
    data: relation_data['l'],
    value: relation_data['r']
  });

  $('#saveBtn').click(function () {
    // 按钮禁用
    $(this).addClass('layui-btn-disabled').attr('disabled', 'true');
    var relationData = transfer.getData('defaultTransfer');
    $.ajax({
      type: 'post',
      url: '<?php echo $this->baseUrl();?>/role/relationsave',
      data: {role_id:<?php echo $this->role_id;?>,relation_data:relationData},
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

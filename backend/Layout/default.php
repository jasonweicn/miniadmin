<?php echo $this->render(LAYOUT_PATH . '/header.php');?>

<!-- side start -->
<?php echo $this->render(LAYOUT_PATH . '/side.php');?>
<!-- side start -->

<!-- main start -->
<?php echo $this->_layout->content;?>
<!-- main end -->

<!-- footer start -->
<?php echo $this->render(LAYOUT_PATH . '/footer.php');?>
<!-- footer end -->

</div>
<script src="<?php echo $this->baseUrl();?>/assets/jquery-3.4.1.min.js"></script>
<script src="<?php echo $this->baseUrl();?>/assets/layui/layui.js"></script>
<script src="<?php echo $this->baseUrl();?>/js/backend.js"></script>
<script>
//JavaScript代码区域
layui.use('element', function () {
  var element = layui.element;
});
layui.use('layer', function () {
    var layer = layui.layer;
});

// 注销
$('#logout').click(function () {
    layer.confirm('确定要注销登录状态吗？', {
        btn: ['确定注销', '返回']
    }, function() {
        window.location.href = $("#logout").data('link');
        layer.closeAll();
    }, function() {
        layer.closeAll();
        return false;
    });
});
</script>
<?php $this->insertBlock('jscode');?>
<?php $this->insertBlock('side_jscode');?>
</body>
</html>

<?php echo $this->_layout->header;?>
<body class="layui-layout-body">
    <?php echo $this->_layout->content;?>
<script src="<?php echo $this->baseUrl();?>/assets/jquery.js"></script>
<script src="<?php echo $this->baseUrl();?>/assets/layui/layui.js"></script>
<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
  
});
</script>
</body>
</html>
{layout:header}

<!-- side start -->
{layout:side}
<!-- side start -->

<!-- main start -->
{layout:content}
<!-- main end -->

<!-- footer start -->
{layout:footer}
<!-- footer end -->

</div>
<script src="{$baseUrl}/assets/jquery-3.4.1.min.js"></script>
<script src="{$baseUrl}/assets/layui/layui.js"></script>
<script src="{$baseUrl}/js/backend.js"></script>
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
{insertBlock:jscode}
{insertBlock:side_jscode}
</body>
</html>

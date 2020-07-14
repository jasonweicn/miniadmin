  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <dl class="layui-nav layui-nav-tree" id="side_menu">
      </dl>
    </div>
  </div>
  <?php $this->beginBlock('side_jscode');?>
  <script>
  layui.use('element', function(){
    var element = layui.element;

    $.ajax({
      type: 'get',
      url: '<?php echo $this->baseUrl();?>/common/loadmenu',
      dataType: 'json',
      success: function (data) {
        if (data.suc == 1) {
          var menu_tree = createTree(data.data);
          //console.log(menu_tree);
          $('#side_menu').html(menu_tree);

          //元素初始化，以便让异步追加的代码重新渲染
          element.init();

        } else {
          //...
        }
      },
      error: function (data) {
        layer.alert('网络请求异常',{icon:2,anim:5});
      }
    });

    // 创建树形菜单
    function createTree (data, i = 0) {
      var tree = '';
      var k = i;
      var j = 0;

      for (i in data) {
        //console.log(data[i]);

        //匹配URL和路由是否一致
        thisclass = '';
        //console.log(window.location.pathname);
        if (window.location.pathname == data[i]['route']) {
          thisclass = ' layui-this';
        }

        if (data[i]['pid'] == 0) {

          class_itemed = '';
          if (i == 0) {
            class_itemed = ' layui-nav-itemed';
          }

          //判断是否有子菜单
          if (data[i]['child'].length > 0) {
            tree = tree + '<dd class="layui-nav-item' + class_itemed + '"><a href="javascript:;">' + data[i]['menu_name'] + '</a>';
          } else {
            tree = tree + '<dd class="layui-nav-item '+thisclass+'"><a href="<?php echo $this->baseUrl();?>' + data[i]['route'] + '">' + data[i]['menu_name'] + '</a>';
          }

        } else {
          if (j == 0) {
            tree = tree + '<dl class="layui-nav-child">';
          }

          //判断是否有子菜单
          if (data[i]['child'].length > 0) {
            tree = tree + '<dd><a href="javascript:;">' + data[i]['menu_name'] + '</a>';
          } else {
            tree = tree + '<dd class="'+thisclass+'"><a href="<?php echo $this->baseUrl();?>' + data[i]['route'] + '">' + data[i]['menu_name'] + '</a>';
          }

        }

        // 递归
        if (data[i]['child'].length > 0) {
          //console.log('in');
          tree = tree + createTree(data[i]['child'], i);
          //console.log('out');
        }

        tree = tree + '</dd>';

        j++;
      }

      if (data[i]['pid'] > 0) {
        tree = tree + '</dl>';
      }
      i = k;

      return tree;
    }

  });
  </script>
  <?php $this->endBlock();?>

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
          var menu_tree_data = setTreeStatus(data.data);
          var menu_tree = createTree(menu_tree_data);
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

    //var menu_tree_data = JSON.parse('<?php \Mini\Base\Session::start();echo \Mini\Base\Session::get('admin_menu');?>');
    //menu_tree_data = setTreeStatus(menu_tree_data);
    //console.log(menu_tree_data);
    //var menu_tree = createTree(menu_tree_data);
    //$('#side_menu').html(menu_tree);

    //元素初始化，以便让异步追加的代码重新渲染
    element.init();

    function setTreeStatus(treeData, i = 0) {
      k = i;
      var curPathName = window.location.pathname;
      for (i in treeData) {
        if (treeData[i]['route'] == curPathName) {
          treeData[i]['on'] = true;
        } else {
          treeData[i]['on'] = false;
        }
        // 递归
        if (treeData[i]['child'].length > 0) {
          //console.log('in');
          treeData[i]['child'] = setTreeStatus(treeData[i]['child'], i);
          //console.log('out');
          j = 0;
          for (j in treeData[i]['child']) {
            if (treeData[i]['child'][j]['on'] == true) {
              treeData[i]['on'] = true;
              break;
            }
          }
        }
      }
      i = k;
      return treeData;
    }

    // 创建树形菜单
    function createTree (data, i = 0) {
      var tree = '';
      var k = i;
      var j = 0;

      for (i in data) {
        if (data[i]['on'] == true) {
          thisclass = ' layui-this';
          class_itemed = ' layui-nav-itemed';
        } else {
          thisclass = '';
          class_itemed = '';
        }

        if (data[i]['pid'] == 0) { //一级菜单

          //判断是否有子菜单
          if (data[i]['child'].length > 0) {
            tree = tree + '<dd class="layui-nav-item' + class_itemed + '"><a href="javascript:;">' + data[i]['menu_name'] + '</a>';
          } else {
            tree = tree + '<dd class="layui-nav-item' + class_itemed + thisclass + '"><a href="<?php echo $this->baseUrl();?>' + data[i]['route'] + '">' + data[i]['menu_name'] + '</a>';
          }

        } else { //子菜单

          if (j == 0) {
            tree = tree + '<dl class="layui-nav-child">';
          }

          //判断是否有子菜单
          if (data[i]['child'].length > 0) {
            tree = tree + '<dd class="' + class_itemed + '"><a href="javascript:;">' + data[i]['menu_name'] + '</a>';
          } else {
            tree = tree + '<dd class="' + thisclass + '"><a href="<?php echo $this->baseUrl();?>' + data[i]['route'] + '">' + data[i]['menu_name'] + '</a>';
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

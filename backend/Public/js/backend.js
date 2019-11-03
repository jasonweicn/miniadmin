// 手机设备的简单适配
var treeMobile = $('.site-tree-mobile');
var shadeMobile = $('.site-mobile-shade');
treeMobile.on('click', function() {
  $('body').addClass('site-mobile');
});
shadeMobile.on('click', function() {
  $('body').removeClass('site-mobile');
});

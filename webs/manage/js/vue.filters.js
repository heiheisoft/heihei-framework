/*!
 * Vue.js v2.3.3
 * (c) 2014-2017 Evan You
 * Released under the MIT License.
 */
(function (global, factory) {
	if(!global.Vue)return false;
  factory(global.Vue);
}(this, (function (Vue) { 'use strict';
  /*  */
  var round = function(num,v){
    if(num == 0)return 0; 
    var vv = Math.pow(10,v);
    return Math.round(num*vv)/vv;
  }
  Vue.filter('formatCurrency', function(value) {
      return round(value,2);
  });

  Vue.filter('round', function(value,v) {
      return round(value,v);
  });

  Vue.filter('timestampFormat', function(timestamp, fmt) {
      if(timestamp * 1000 == 0){
        return '-';
      }
      if(fmt == undefined){
        fmt = 'yyyy-MM-dd hh:mm:ss';
      }
      var date = new Date(timestamp * 1000);
      var o = {   
          "M+" : date.getMonth()+1,                 //月份   
          "d+" : date.getDate(),                    //日   
          "h+" : date.getHours(),                   //小时   
          "m+" : date.getMinutes(),                 //分   
          "s+" : date.getSeconds(),                 //秒   
          "q+" : Math.floor((date.getMonth()+3)/3), //季度   
          "S"  : date.getMilliseconds()             //毫秒   
      };   
      if(/(y+)/.test(fmt))   
          fmt=fmt.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));   
      for(var k in o)   
          if(new RegExp("("+ k +")").test(fmt))   
              fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
      return fmt;
  });
})));

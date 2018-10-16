/**
 * Vue Jquery.dataTable 整合
 *
 */
(function(global, $, Vue, factory){
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global.VueDataTable = factory());
}(this, jQuery , Vue , (function() {'use strict';
    var paginationTemplate = '<ul class="pagination">' +
        '<li class="paginate_button first" v-bind:class="pages.page == 1 ? \'disabled\' \: \'\'">' +
        '    <a href="javascript:-1" v-on:click="pages.page < 2 ? \'\' \: toPage(1)">首页</a>' +
        '</li>' +
        '<li class="paginate_button hidden-480 previous" v-bind:class="pages.page == 1 ? \'disabled\' \: \'\'">' +
        '    <a href="javascript:-1" v-on:click="pages.page < 2 ? \'\' \: toPage(pages.page > 1 ? pages.page - 1 : 1)">上页</a>' +
        '</li>' +
        '<li class="paginate_button" v-for="curPage in pageNumbers" v-bind:class="{active:curPage == pages.page}">' +
        '    <a href="javascript:-1" v-if="curPage != pages.page" v-on:click="toPage(curPage)">{{curPage}}</a>' +
        '    <span v-if="curPage == pages.page">{{curPage}}</span>' +
        '</li>' +
        '<li class="paginate_button hidden-480 next" v-bind:class="pages.page == pages.pageCount ? \'disabled\' \: \'\'"><a href="javascript:-1" v-on:click="pages.page >= pages.pageCount ? \'\' \: toPage(pages.page < pages.pageCount ? pages.page + 1 : pages.pageCount)">下页</a></li>' +
        '<li class="paginate_button last" v-bind:class="pages.page >= pages.pageCount ? \'disabled\' \: \'\'"><a href="javascript:-1" v-on:click="pages.page >= pages.pageCount ? \'\' \: toPage(pages.pageCount)">末页</a></li>' +
        '</ul>';

    var pageinfoTemplate = '<div class="dataTables_info">' +
        '<label v-if="pageLengthMenu != null">每页 ' +
        '    <select class="input-sm" @change="loadData()" v-model="reqParams.pagesize">' +
        '        <option v-for="(value, key) in pageLengthMenu" v-bind:value="value">{{key}}</option>' +
        '    </select> 项 &nbsp;' +
        '</label>' +
        '共 {{pages.totalCount}} 项,共 {{pages.pageCount}} 页</div>';

    /**
     * 触发回调函数和触发事件。注意事项
     * 回调数组存储的循环是向后执行的! 
     * 进一步注意，您不希望在时间敏感的应用程序(例如单元创建)中触发触发器
     * 因为它的速度很慢。
     */
    function _fnCallbackFire( settings, callbackArr, eventName, args )
    {
        var ret = [];
    
        if ( callbackArr ) {
            ret = $.map( settings[callbackArr].slice().reverse(), function (val, i) {
                return val.fn.apply( settings.vue, args );
            } );
        }
    
        if ( eventName !== null ) {
            var e = $.Event( eventName+'.dt' );
    
            $(settings.nTable).trigger( e, args );
    
            ret.push( e.result );
        }
    
        return ret;
    }

    

    function getObjectFirstPropertyName(obj){
        for (var key in obj)
            return key;
    }

    function getObjectFirstPropertyValue(obj){
        for (var key in obj)
            return obj[key];
    }

    // vue 方法开始
    function timestampFormat(fmt, timestamp){
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
    }

    function drawData(data){
        var _that = this;
        this.pageNumbers = [];
        this.list = [];
        if(data.list){
          this.list = data.list;  
        }
        else if(Array.isArray(data)){
            this.list = data;
            return;
        }
        if(data.pages)_that.pages = data.pages;
        var showPageCount = 2;
        var curPage = _that.pages.page;
        var startPage = curPage - showPageCount > 0 ? curPage - showPageCount : 1;
        var endPage = startPage + showPageCount * 2 > _that.pages.pageCount ? _that.pages.pageCount : startPage + showPageCount * 2;
        startPage = endPage - showPageCount * 2 > 0 ? endPage - showPageCount * 2 : 1;
        
        for (var i = startPage; i <= endPage; i++) {
            _that.pageNumbers.push(i);
        }
    }

    function loadData(){
        var _that = this,
            options = this.$options.ajaxOptions,
            params = this.reqParams; 
        $.ajax({
            'url':options.url,
            'data':params,
            'success':function(result){
                if(result.code == 'SUCCESS'){
                    drawData.call(_that, result.data);                   
                }
            },
            'type':options.type,
            'dataType':options.dataType
        });
    }

    

    function toPage(page){
        this.reqParams.page = page;
        this.loadData();
    }

    function sort(index){
        var options = this.$options.dtOptions,
            orderby = this.sortCols[index].orderby,
            sortingBy = this.reqParams.sortingby || '',
            colName = this.sortCols[index].colName;
        
        if(options.bSortMulti != true){
            for (var i = 0; i < this.sortCols.length; i++) {
                this.sortCols[i].sortClass = 'sorting';
                this.sortCols[i].orderby = '';
            }
        }
        if(orderby == 'desc'){
            this.sortCols[index].sortClass = 'sorting_asc';
            orderby = this.sortCols[index].orderby = 'asc';
        }
        else if(orderby == 'asc'){
            this.sortCols[index].sortClass = 'sorting';
            orderby = this.sortCols[index].orderby = '';
        }
        else{
            this.sortCols[index].sortClass = 'sorting_desc';
            orderby = this.sortCols[index].orderby = 'desc';
        }

        
        if(options.bSortMulti == true && sortingBy != ''){
            var sortingByArr = sortingBy.split(',');
            var oldIndex = -1;       
            for (var i = 0; i < sortingByArr.length; i++) {
                if(sortingByArr[i].indexOf(colName + ' ') == 0){
                    oldIndex = i;
                    break;
                }
            }
            if(oldIndex < 0 && orderby != ''){
                sortingByArr.push(colName + ' ' + orderby);
            }
            else if(oldIndex > -1){
                if(orderby == ''){
                    //删除元素
                    sortingByArr.splice(oldIndex, 1);
                }
                else{
                    //替换元素
                    sortingByArr.splice(oldIndex, 1, colName + ' ' + orderby);
                }
            }
            this.reqParams.sortingby = sortingByArr.join(',');
        }
        else{
            this.reqParams.sortingby = orderby == '' ? undefined : colName + ' ' + orderby;
        }
        this.loadData();
    }

    function urlQuery(url,query){
        if(!query){
            return url;
        }
        return url + (url.indexOf('?') == -1 ? '?' : '&') + query;
    }
    // vue 方法结束


    //return Vue();
    function VueDataTable (options) {    
        this._init(options);
        this._fnInitDataTable($(options.el));
        this.vueObj = new Vue(this.vueOptions);
        return this;
    }

    VueDataTable.prototype._init = function(options){
        var _that = this;
        var vueOptions = $.extend({}, options);

        vueOptions.mounted = function(){
            this.loadData();
            options.mounted && options.mounted.apply(this,arguments);            
            $(this.$el).find('tbody').removeClass('hide');
        }
        vueOptions.beforeCreate = function(){
            this.$table = _that.$table;   
            options.beforeCreate && options.beforeCreate.apply(this, arguments);
        }
        var defaultData = {
            pageNumbers:[],
            pageLengthMenu:{
                '20':20,
                '50':50,
                '100':100
            },
            list:[],
            pages:{},
            reqParams:{}
        };       

        var defaultPageInfo = {
            page:1,
            totalCount:0,
            pageCount:0
        };
        
        var defaultMethods = {
            toPage: toPage,
            sort: sort,
            urlQuery: urlQuery,
            timestampFormat: timestampFormat,
            loadData: loadData
        };
        vueOptions.methods = $.extend({}, defaultMethods, options.methods);
        
        vueOptions.ajaxOptions = $.extend({'type':'GET','dataType':'json'}, options.ajaxOptions);
        vueOptions.dtOptions = $.extend({bSortMulti:false,bNoPage:false}, options.dtOptions);


        vueOptions.data = $.extend({}, defaultData, options.data);
        if(vueOptions.dtOptions.bNoPage != true){
           vueOptions.data.reqParams.pagesize = getObjectFirstPropertyValue(vueOptions.data.pageLengthMenu); 
        }
        vueOptions.data.pages = $.extend({}, defaultPageInfo, options.pages);

        this.vueOptions = vueOptions;
    }

    VueDataTable.prototype._fnInitDataTable = function($dts){
        if($dts.length == 0){
            return;
        }
        var $table = $dts,
            options = this.vueOptions.dtOptions;
        if($dts[0].nodeName != 'TABLE' ){
            $table = $dts.find('table');
            if($table.length == 0)return;
        }
        $table = $table.eq(0);
        var insert = $('<div/>', {
            id:'datatable_wrapper',
            'class': "dataTables_wrapper form-inline"
        });
        this.$table = $table;           
        $table.wrap( insert );
        if(options.bNoPage != true){
            var paginationHtml = $('<div/>',{"class":"col-xs-12 col-sm-6"}).append($('<div/>',{"class":"dataTables_paginate paging_simple_numbers"}).append(paginationTemplate));
            var infoHtml = $('<div/>',{"class":"col-xs-12 col-sm-6"}).append(pageinfoTemplate);
            var footerHtml = $('<div/>', {"class":"row"}).append(infoHtml,paginationHtml);
            $table.after(footerHtml);
        }        
        var oi = 0, _that = this;
        var sortCols = [];
        $table.find('thead th').each(function(i, th){
            var $th = $(th),
                colName = $th.data('col-name') || '';
            if($th.data('orderable') == true && colName != ''){
                $th.addClass('sorting');
                
                $th.attr('v-on:click', 'sort(' + oi + ')');
                $th.attr('v-bind:class', '[sortCols[' + oi + '].sortClass]');
                sortCols.push({
                    index:oi,
                    colName:colName,
                    sortClass:'sorting',
                    orderby:''
                });
                oi++;
            }
        });
        this.vueOptions.data.sortCols = sortCols;
    }
    return VueDataTable;

})));
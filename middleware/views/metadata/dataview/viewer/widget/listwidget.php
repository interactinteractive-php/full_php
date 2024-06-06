
<?php
if (isset($this->row['dataViewLayoutTypes']) && isset($this->row['dataViewLayoutTypes']['ecommerce'])) {
    $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];
    if (isset($this->useBasket) && $this->useBasket) {
?>
$.extend($.fn.datagrid.defaults, {
    rowHeight: 20,
    maxDivHeight: 10000000,
    height: 500,
    maxVisibleHeight: 15000000,
    deltaTopHeight: 0,
    onBeforeFetch: function(page){},
    onFetch: function(page, rows){},
    loader: function(param, success, error){
        var opts = $(this).datagrid('options');
        if (!opts.url) return false;
        
        if (opts.view.type == 'ecommerceview_<?php echo $this->metaDataId; ?>'){
            param.page = param.page || 1;
            param.rows = param.rows || opts.pageSize;
        }
        $.ajax({
            type: opts.method,
            url: opts.url,
            data: param,
            dataType: 'json',
            success: function(data){
                success(data);
            },
            error: function(){
                error.apply(this, arguments);
            }
        });
    }
});
$.extend($.fn.datagrid.defaults.finder, {
    getRow: function(target, p){	// p can be row index or tr object
        var index = (typeof p == 'object') ? p.attr('datagrid-row-index') : p;
        
        var opts = $(target).datagrid('options');
        if (opts.view.type == 'ecommerceview_<?php echo $this->metaDataId; ?>'){
                index -= opts.view.index;
        }
        return $.data(target, 'datagrid').data.rows[index];
    }
});
var ecommerceview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    type: 'ecommerceview_<?php echo $this->metaDataId; ?>',
    index: 0,
    r1: [],
    r2: [],
    rows: [],
    render: function (target, container, frozen) {
        
        var state = $.data(target, "datagrid");
        <!--var rows = data.data.rows;-->
        var opts = state.options;
        var rowData = state.data;
        var rows = this.rows || [];
        
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], grouped = [], dgindex = 0;
        var table = ['<div class="datagrid-btable-top"></div>',
                        '<table class="datagrid-btable" cellspacing="0" cellpadding="0" border="0"><tbody>'];
        table.push('<ul class="media-list media-list-<?php echo $this->metaDataId ?>">');
        for (var i = 0; i < rows.length; i++) {
            table.push('<li datagrid-row-index=\'' + i + '\'  data-index=\'' + i + '\' class="datagrid-row media p-1 border-bottom-1 border-gray" style="height: 40px;">');
                table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
            table.push('</li>');
        }
        table.push('</ul>');
        table.push('</tbody></table>');
		table.push('<div class="datagrid-btable-bottom"></div>');
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
                
            cc.push('<a href="javascript:;" class="mr-2 position-relative" >');
            <?php
            if (isset($typeRow['fields']['name1'])) {
                $name1 = strtolower($typeRow['fields']['name1']); ?>
                var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                    cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name1; ?>);
                }
            <?php
            } else { ?>
                cc.push('<img src="assets/custom/addon/admin/layout4/img/user.png" width="34" height="34" class="rounded-circle" alt="" onerror="onUserImgError(this);">');
            <?php } ?>
            cc.push('</a>');
            cc.push('<div class="media-body">');
            <?php if (isset($typeRow['fields']['name2'])) {
                    $name2 = strtolower($typeRow['fields']['name2']);
                ?>
                cc.push('<div class="membername text-blue text-uppercase line-height-normal d-flex align-items-center font-size-11" title="'+detectHtmlStr(rowData.<?php echo $name2; ?>)+'"><span>');
                    var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                    if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                        cc.push(name2Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                    } else {
                        cc.push(rowData.<?php echo $name2; ?>);
                    }
                cc.push('</span></div>'); 

                <?php
            }
            if (isset($typeRow['fields']['name3'])) {
                $name3 = strtolower($typeRow['fields']['name3']);
            ?>
            cc.push('<span class="memberposition" style="font-size: 10px;color: #999;text-transform: uppercase;" title="'+detectHtmlStr(rowData.<?php echo $name3; ?>)+'">');
                var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                    cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name3; ?>);
                }
            cc.push('</span>'); 

            <?php
            }
            ?>
            cc.push('</div>'); 
            
            cc.push('<div class="ml10 mr10 align-self-center" style="width:15px;">');
                cc.push('<a href="javascript:;" class="position-relative basket-btn d-none" onclick="pushCommerceBasket<?php echo $this->metaDataId; ?>(this)" data-row-data="' + encodeURIComponent(JSON.stringify(rowData)) + '" ><i style="color:#4caf50" class="icon-plus2"></i></a>');
            cc.push('</div>');
                
        return cc.join('');
    },
    onBeforeRender: function(target){
        var state = $.data(target, 'datagrid');
        var opts = state.options;
        var dc = state.dc;
        var view = this;

        state.data.firstRows = state.data.rows;
        state.data.rows = [];

        dc.body1.add(dc.body2).empty();
        this.rows = [];	// the rows to be rendered
        this.r1 = this.r2 = [];	// the first part and last part of rows

        init<?php echo $this->metaDataId; ?>();
        createHeaderExpander<?php echo $this->metaDataId; ?>();

        function init<?php echo $this->metaDataId; ?>(){
                var pager = $(target).datagrid('getPager');
                pager.pagination({
                        onSelectPage: function(pageNum, pageSize){
                                opts.pageNumber = pageNum || 1;
                                opts.pageSize = pageSize;
                                pager.pagination('refresh',{
                                        pageNumber:pageNum,
                                        pageSize:pageSize
                                });
                                $(target).datagrid('gotoPage', opts.pageNumber);
                        }
                });
                // erase the onLoadSuccess event, make sure it can't be triggered
                state.onLoadSuccess = opts.onLoadSuccess;
                opts.onLoadSuccess = function(){};
                if (!opts.remoteSort){
                        var onBeforeSortColumn = opts.onBeforeSortColumn;
                        opts.onBeforeSortColumn = function(name, order){
                                var result = onBeforeSortColumn.call(this, name, order);
                                if (result == false){
                                        return false;
                                }
                                state.data.rows = state.data.firstRows;
                        }
                }
                dc.body2.unbind('.datagrid');
                setTimeout(function(){
                        dc.body2.unbind('.datagrid').bind('scroll.datagrid', function(e){
                                if (state.onLoadSuccess){
                                        opts.onLoadSuccess = state.onLoadSuccess;	// restore the onLoadSuccess event
                                        state.onLoadSuccess = undefined;
                                }
                                if (view.scrollTimer){
                                        clearTimeout(view.scrollTimer);
                                }
                                view.scrollTimer = setTimeout(function(){
                                        view.scrolling.call(view, target);
                                }, 50);
                        });
                        dc.body2.triggerHandler('scroll.datagrid');
                }, 0);
        }
        function createHeaderExpander<?php echo $this->metaDataId; ?>(){
            if (!opts.detailFormatter){return}

            var t = $(target);
            var hasExpander = false;
            var fields = t.datagrid('getColumnFields',true).concat(t.datagrid('getColumnFields'));
            for(var i=0; i<fields.length; i++){
                var col = t.datagrid('getColumnOption', fields[i]);
                if (col.expander){
                    hasExpander = true;
                    break;
                }
            }
            if (!hasExpander){
                if (opts.frozenColumns && opts.frozenColumns.length){
                    opts.frozenColumns[0].splice(0,0,{field:'_expander',expander:true,width:24,resizable:false,fixed:true});
                } else {
                    opts.frozenColumns = [[{field:'_expander',expander:true,width:24,resizable:false,fixed:true}]];
                }

                var t = dc.view1.children('div.datagrid-header').find('table');
                var td = $('<td rowspan="'+opts.frozenColumns.length+'"><div class="datagrid-header-expander" style="width:24px;"></div></td>');
                if ($('tr',t).length == 0){
                    td.wrap('<tr></tr>').parent().appendTo($('tbody',t));
                } else if (opts.rownumbers){
                    td.insertAfter(t.find('td:has(div.datagrid-header-rownumber)'));
                } else {
                    td.prependTo(t.find('tr:first'));
                }
            }

            setTimeout(function(){
                    view.bindEvents(target);
            },0);
        }
    },
    onAfterRender: function(target){
        $.fn.datagrid.defaults.view.onAfterRender.call(this, target);
        var dc = $.data(target, 'datagrid').dc;
        var footer = dc.footer1.add(dc.footer2);
        footer.find('span.datagrid-row-expander').css('visibility', 'hidden');
    },
    scrolling: function(target){
        var state = $.data(target, 'datagrid');
        var opts = state.options;
        var dc = state.dc;
        
        if (!opts.finder.getRows(target).length){
                this.reload.call(this, target);
        } else {
                if (!dc.body2.is(':visible')){return}
                var headerHeight = dc.view2.children('div.datagrid-header').outerHeight();

                var topDiv = dc.body2.children('div.datagrid-btable-top');
                var bottomDiv = dc.body2.children('div.datagrid-btable-bottom');
                if (!topDiv.length || !bottomDiv.length){return;}
                var top = topDiv.position().top + topDiv._outerHeight() - headerHeight;
                var bottom = bottomDiv.position().top - headerHeight;
                top = Math.floor(top);
                bottom = Math.floor(bottom);

                if (top > dc.body2.height() || bottom < 0){
                        this.reload.call(this, target);
                } else if (top > 0){
                        var page = Math.floor(this.index/opts.pageSize);
                        this.getRows.call(this, target, page, function(rows){
                                this.page = page;
                                this.r2 = this.r1;
                                this.r1 = rows;
                                this.index = (page-1)*opts.pageSize;
                                this.rows = this.r1.concat(this.r2);
                                this.populate.call(this, target);
                        });
                } else if (bottom < dc.body2.height()){
                        if (state.data.rows.length+this.index >= state.data.total){
                                return;
                        }
                        var page = Math.floor(this.index/opts.pageSize)+2;
                        if (this.r2.length){
                                page++;
                        }
                        this.getRows.call(this, target, page, function(rows){
                                this.page = page;
                                if (!this.r2.length){
                                        this.r2 = rows;
                                } else {
                                        this.r1 = this.r2;
                                        this.r2 = rows;
                                        this.index += opts.pageSize;
                                }
                                this.rows = this.r1.concat(this.r2);
                                this.populate.call(this, target);
                        });
                }
        }
    },
    reload: function(target){
            var state = $.data(target, 'datagrid');
            
            var opts = state.options;
            var dc = state.dc;
            var top = $(dc.body2).scrollTop() + opts.deltaTopHeight;
            var index = Math.floor(top/opts.rowHeight);
            var page = Math.floor(index/opts.pageSize) + 1;

            this.getRows.call(this, target, page, function(rows){
                    this.page = page;
                    this.index = (page-1)*opts.pageSize;
                    this.rows = rows;
                    this.r1 = rows;
                    this.r2 = [];
                    this.populate.call(this, target);
                    dc.body2.triggerHandler('scroll.datagrid');
            });
    },
    getRows: function(target, page, callback){
		var state = $.data(target, 'datagrid');
                
		var opts = state.options;
		var index = (page-1)*opts.pageSize;

		if (index < 0){return}
		if (opts.onBeforeFetch.call(target, page) == false){return;}

		var rows = state.data.firstRows.slice(index, index+opts.pageSize);
                                
		if (rows.length && (rows.length==opts.pageSize || index+rows.length==state.data.total)){
			opts.onFetch.call(target, page, rows);
			callback.call(this, rows);
		} else {
			var param = $.extend({}, opts.queryParams, {
				page: page,
				rows: opts.pageSize
			});
			if (opts.sortName){
				$.extend(param, {
					sort: opts.sortName,
					order: opts.sortOrder
				});
			}
			if (opts.onBeforeLoad.call(target, param) == false) return;
			
			$(target).datagrid('loading');
			var result = opts.loader.call(target, param, function(data){
				$(target).datagrid('loaded');
				var data = opts.loadFilter.call(target, data);
				opts.onFetch.call(target, page, data.rows);
				if (data.rows && data.rows.length){
					callback.call(opts.view, data.rows);
				} else {
					opts.onLoadSuccess.call(target, data);
				}
			}, function(){
				$(target).datagrid('loaded');
				opts.onLoadError.apply(target, arguments);
			});
			if (result == false){
				$(target).datagrid('loaded');
				if (!state.data.firstRows.length){
					opts.onFetch.call(target, page, state.data.firstRows);
					opts.onLoadSuccess.call(target, state.data);
				}
			}
		}
	},
	
	populate: function(target){
		var state = $.data(target, 'datagrid');
		var opts = state.options;
		var dc = state.dc;
		var rowHeight = opts.rowHeight;
		var maxHeight = opts.maxDivHeight;
                
		if (this.rows.length){
			opts.view.render.call(opts.view, target, dc.body2, false);
			opts.view.render.call(opts.view, target, dc.body1, true);
			
			var body = dc.body1.add(dc.body2);
			var topDiv = body.children('div.datagrid-btable-top');
			var bottomDiv = body.children('div.datagrid-btable-bottom');
			var topHeight = this.index * rowHeight;
                        
			var bottomHeight = state.data.total*rowHeight - this.rows.length*rowHeight - topHeight;
			fillHeight<?php echo $this->metaDataId; ?>(topDiv, topHeight);
			fillHeight<?php echo $this->metaDataId; ?>(bottomDiv, bottomHeight);

			state.data.rows = this.rows;
			
			var spos = dc.body2.scrollTop() + opts.deltaTopHeight;
			if (topHeight > opts.maxVisibleHeight){
                            opts.deltaTopHeight = topHeight - opts.maxVisibleHeight;
                            fillHeight<?php echo $this->metaDataId; ?>(topDiv, topHeight - opts.deltaTopHeight);
			} else {
                            opts.deltaTopHeight = 0;
			}
			if (bottomHeight > opts.maxVisibleHeight){
                            fillHeight<?php echo $this->metaDataId; ?>(bottomDiv, opts.maxVisibleHeight);
			} else if (bottomHeight == 0){
                            var lastCount = state.data.total % opts.pageSize;
                            if (lastCount){
                                fillHeight<?php echo $this->metaDataId; ?>(bottomDiv, dc.body2.height() - lastCount * rowHeight);
                            }
			}

			$(target).datagrid('setSelectionState');
			dc.body2.scrollTop(spos - opts.deltaTopHeight);

			var pager = $(target).datagrid('getPager');
			pager.pagination('refresh', {
				pageNumber: this.page
			});

			opts.onLoadSuccess.call(target, {
				total: state.data.total,
				rows: this.rows
			});
		}
		function fillHeight<?php echo $this->metaDataId; ?>(div, height){
                    var count = Math.floor(height/maxHeight);
                    var leftHeight = height - maxHeight*count;
                    if (height < 0){
                            leftHeight = 0;
                    }
                    var cc = [];
                    for(var i=0; i<count; i++){
                        cc.push('<div style="height:'+maxHeight+'px"></div>');
                    }
                    cc.push('<div style="height:'+leftHeight+'px"></div>');
                    
                    $(div).html(cc.join(''));
		}
	}
});

<?php
}
 else {
    ?>
var ecommerceview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"datagrid-row\"";
        var table = [], grouped = [], dgindex = 0;
        
                
        table.push('<ul class="media-list">');
        for (var i = 0; i < rows.length; i++) {
            table.push('<li datagrid-row-index=\'' + i + '\'  data-index=\'' + i + '\' class="datagrid-row media p-1 border-bottom-1 border-gray" style="height: 40px;">');
                table.push(this.renderRow.call(this, target, fields, frozen, i, rows[i]));
            table.push('</li>');
        }
        table.push('</div>');

        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
                
            cc.push('<a href="javascript:;" class="mr-2 position-relative" >');
            <?php
            if (isset($typeRow['fields']['name1'])) {
                $name1 = strtolower($typeRow['fields']['name1']); ?>
                var name1Col = $(target).datagrid('getColumnOption', '<?php echo $name1; ?>');
                if (typeof name1Col !== 'undefined' && name1Col != null && name1Col.formatter) {
                    cc.push(name1Col.formatter(rowData.<?php echo $name1; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name1; ?>);
                }
            <?php
            } else { ?>
                cc.push('<img src="assets/custom/addon/admin/layout4/img/user.png" width="34" height="34" class="rounded-circle" alt="" onerror="onUserImgError(this);">');
            <?php } ?>
            cc.push('</a>');
            cc.push('<div class="media-body">');
            <?php if (isset($typeRow['fields']['name2'])) {
                    $name2 = strtolower($typeRow['fields']['name2']);
                ?>
                cc.push('<div class="membername text-blue text-uppercase line-height-normal d-flex align-items-center font-size-11" title="'+detectHtmlStr(rowData.<?php echo $name2; ?>)+'"><span>');
                    var name2Col = $(target).datagrid('getColumnOption', '<?php echo $name2; ?>');
                    if (typeof name2Col !== 'undefined' && name2Col != null && name2Col.formatter) {
                        cc.push(name2Col.formatter(rowData.<?php echo $name2; ?>, rowData, rowIndex));
                    } else {
                        cc.push(rowData.<?php echo $name2; ?>);
                    }
                cc.push('</span></div>'); 

                <?php
            }
            if (isset($typeRow['fields']['name3'])) {
                $name3 = strtolower($typeRow['fields']['name3']);
            ?>
            cc.push('<span class="memberposition" style="font-size: 10px;color: #999;text-transform: uppercase;" title="'+detectHtmlStr(rowData.<?php echo $name3; ?>)+'">');
                var name3Col = $(target).datagrid('getColumnOption', '<?php echo $name3; ?>');
                if (typeof name3Col !== 'undefined' && name3Col != null && name3Col.formatter) {
                    cc.push(name3Col.formatter(rowData.<?php echo $name3; ?>, rowData, rowIndex));
                } else {
                    cc.push(rowData.<?php echo $name3; ?>);
                }
            cc.push('</span>'); 

            <?php
            }
            ?>
            cc.push('</div>'); 
            
            <?php if (isset($this->useBasket) && $this->useBasket &&  Config::getFromCache('tmsCustomerCode') == 'gov') { ?>
                cc.push('<div class="ml10 mr10 align-self-center" style="width:15px;">');
                    cc.push('<a href="javascript:;" class="position-relative basket-btn d-none" onclick="pushCommerceBasket<?php echo $this->metaDataId; ?>(this)" data-row-data="' + encodeURIComponent(JSON.stringify(rowData)) + '" ><i style="color:#4caf50" class="icon-plus2"></i></a>');
                cc.push('</div>');   
            <?php } ?>
        return cc.join('');
    }
});
<?php
}
}
?>
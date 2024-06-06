/* global Core, PNotify, NaN */

var contentHtmlList=function(){

    //<editor-fold defaultstate="collapsed" desc="variables">
    var $pagingContentHtmlList,
            page=1,
            pageSize=20,
            totalCount,
            $dialogName,
            contentName='';
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(){
        $pagingContentHtmlList=$("#pagingContentHtmlList");
        initPaginWidgetDv();
        initContentHtmlChooseEvent();
        initSearchEvent();
    };

    var initSearchEvent=function(){
        $dialogName.find('#contentNameSearch').keydown(function(e){
            if(e.which === 13){
                contentName=$(this).val();
                refreshContentHrmlList();
            }
        });
    };

    var initContentHtmlChooseEvent=function(){
        $dialogName.find('.contentHtml').click(function(){
            var $target=$(this);
            $dialogName.find('.contentHtml').removeClass('choosen');
            $target.addClass('choosen');
            $dialogName.find('.content-name-choosen').html($target.data('content-name')).data('content-id', $target.data('content-id'));
        });
    };

    //<editor-fold defaultstate="collapsed" desc="pagination">
    var initPaginWidgetDv=function(){
        var html="";
        if(page !== 1){
            html+='<a href="javascript:;" class="btn btn-prev prev">Өмнөх</a>';
        } else {
            html+='<a href="javascript:;" class="btn btn-prev prev disactive">Өмнөх</a>';
        }

        var total=decimalAdjust('ceil', parseFloat(totalCount) / parseFloat(pageSize));

        for(var i=1; i <= total; i++){
            if(page === i){
                html+='<a href="javascript:;" class="btn pagination active">' + i + '</a>';
            } else {
                html+='<a href="javascript:;" class="btn pagination">' + i + '</a>';
            }
        }

        if(page !== total){
            html+='<a href="javascript:;" class="btn btn-next next">Дараах</a>';
        } else {
            html+='<a href="javascript:;" class="btn btn-next next disactive">Дараах</a>';
        }


        $pagingContentHtmlList.html(html);
        initPaginWidgetDvEvent();
    };

    var initPaginWidgetDvEvent=function(){
        $pagingContentHtmlList.find('.pagination').click(function(){
            page=parseFloat($(this).html());
            refreshContentHrmlList();
        });
        $pagingContentHtmlList.find('.prev').click(function(){
            page=parseInt($pagingContentHtmlList.find('.pagination.active').html()) - 1;
            refreshContentHrmlList();
        });
        $pagingContentHtmlList.find('.next').click(function(){
            page=parseInt($pagingContentHtmlList.find('.pagination.active').html()) + 1;
            refreshContentHrmlList();
        });
    };
    //</editor-fold>

    var refreshContentHrmlList=function(){
        getContentHtmlList();
    };

    var getContentHtmlList=function(callback){
        var dialogName='dialog-manage-help-content';
        if(!$("#" + dialogName).length){
            $('<div id="' + dialogName + '" class="display-none"></div>').appendTo('body');
        }

        $dialogName=$("#" + dialogName);

        $.ajax({
            type: 'post',
            url: 'mdcontentui/getContentHtmlList',
            data: {
                rows: pageSize,
                page: page,
                contentName: contentName
            },
            dataType: "json",
            beforeSend: function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data){
                if(typeof data.errorMessage === 'undefined'){
                    var $appendToForm;
                    if($("form#addMetaSystemForm").length > 0){
                        $appendToForm="form#addMetaSystemForm";
                    } else {
                        $appendToForm="form#editMetaSystemForm";
                    }
                    $dialogName.empty().html(data.Html);

                    if(typeof callback === "function"){
                        callback($dialogName, $appendToForm, data);
                    }
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.errorMessage,
                        type: 'error',
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function(){
                alert("Error");
            }
        }).complete(function(){
            Core.initAjax($dialogName);
            Core.unblockUI();
        });
    };

    var showContentHtmlListDialog=function($dialogName, $appendToForm, data, elem){
        $dialogName.dialog({
            appendTo: $appendToForm,
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: data.Title,
            width: 1200,
            minWidth: 1200,
            height: 600,
            modal: false,
            open: function(){

            },
            close: function(){
                $dialogName.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: data.save_btn, class: 'btn btn-sm green', click: function(){
                        saveContentId(elem);
                    }},
                {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function(){
                        $dialogName.dialog('close');
                    }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }
        });
        $dialogName.dialog('open');
        $dialogName.dialogExtend("maximize");
    };

    var saveContentId=function(elem){
        var $parent=$(elem).closest("tr");
        if(typeof $dialogName.find('.content-name-choosen').data('content-id') !== "undefined"){
            var selectedContentId=$dialogName.find('.content-name-choosen').data('content-id');
            $parent.find('#helpContentId').val(selectedContentId);
            $dialogName.dialog('close');
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Контент сонгоно уу',
                type: 'warning',
                sticker: false
            });
        }
    };
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="helper">

    /**
     * Decimal adjustment of a number.
     *
     * @param {String}  type  The type of adjustment.
     * @param {Number}  value The number.
     * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
     * @returns {Number} The adjusted value.
     */
    var decimalAdjust=function(type, value, exp){
        // If the exp is undefined or zero...
        if(typeof exp === 'undefined' || +exp === 0){
            return Math[type](value);
        }
        value=+value;
        exp=+exp;
        // If the value is not a number or the exp is not an integer...
        if(isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)){
            return NaN;
        }
        // Shift
        value=value.toString().split('e');
        value=Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
        // Shift back
        value=value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
    };

    var initDecimalAdjust=function(){
        // Decimal round
        if(!Math.round10){
            Math.round10=function(value, exp){
                return decimalAdjust('round', value, exp);
            };
        }
        // Decimal floor
        if(!Math.floor10){
            Math.floor10=function(value, exp){
                return decimalAdjust('floor', value, exp);
            };
        }
        // Decimal ceil
        if(!Math.ceil10){
            Math.ceil10=function(value, exp){
                return decimalAdjust('ceil', value, exp);
            };
        }
    };
    //</editor-fold>

    return {
        init: function(total){
            contentName='';
            totalCount=total;
            initEvent();
        },
        initContentHtmlList: function(callback){
            getContentHtmlList(callback);
        },
        initDecimalAdjust: function(){
            initDecimalAdjust();
        },
        showContentHtmlListDialog: function($dialogName, $appendToForm, data, elem){
            showContentHtmlListDialog($dialogName, $appendToForm, data, elem);
        }
    };
}();
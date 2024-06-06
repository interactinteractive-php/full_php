/* global Core */

var layoutShowTypeRender=function(){

    var $showTypeLayout,
            $pagingDataViewWidget=$("#pagingDataViewWidget"),
            tmpMetaDataId,
            page=1,
            pageSize=10;

    var initEvent=function(){
        $showTypeLayout=$("#showTypeLayout");
    };
    var getShowTypeTemplateAndConfig=function(metaDataId){
        tmpMetaDataId=metaDataId;
        Core.blockUI();
        $.ajax({
            url: "mdlayoutrender/getTemplateAndConfig",
            type: "POST",
            data: {metaDataId: tmpMetaDataId},
            dataType: "JSON",
            success: function(response){
                if(typeof response.themeHtml !== "undefined"){
                    renderTemplate(response.themeHtml, response.metaWidgetLinkParamList, metaDataId);
                }
            },
            error: function(jqXHR, exception){
                Core.unblockUI();
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };
    var renderTemplate=function(themeHtml, metaWidgetLinkParamList, metaDataId){
        var id='carouselSlideGenerated' + metaDataId;
        themeHtml=themeHtml.replace(/carouselSlideGenerated/g, id);
        $("div#layout-" + metaDataId).html(themeHtml);
        var $themeHtml=$("div#layout-" + metaDataId),
                dataSectionLength=$themeHtml.find('[data-section]').length,
                clonedHtml,
                $useClonedSection;
        $pagingDataViewWidget=$("#pagingDataViewWidget");
        if(dataSectionLength > 0){
            if($themeHtml.find('.carousel').length === 0){
                id=$themeHtml.find('.show-type-slider').attr('id') + metaDataId;
                $themeHtml.find('.show-type-slider').attr('id', id);
            }

            if(dataSectionLength === 1){
                clonedHtml=$themeHtml.find('[data-section]').parent().html();
                $themeHtml.find('[data-section].show-type-data-section-item').remove();
                $useClonedSection=$(clonedHtml);
                loadDataView(metaWidgetLinkParamList, function(dataViewResponse){
                    setDataParametersForTypeOne(dataViewResponse, $themeHtml, metaWidgetLinkParamList, $useClonedSection, metaDataId, clonedHtml, id);
                    $themeHtml.find('.carousel-inner .show-type-data-section-item:first-child').addClass('active');
                    if($themeHtml.find('.wt-slider').length > 0){
                        initWtSlider(id);
                    }
                });
            } else {
               
                loadDataView(metaWidgetLinkParamList, function(dataViewResponse){
                    setDataParametersForTypeTwo(dataViewResponse, $themeHtml, metaWidgetLinkParamList);
                });
            }
        }
    };
    var loadDataView=function(metaWidgetLinkParamList, callBack){
        pageSize=metaWidgetLinkParamList[0]['ROW_COUNT'];
        Core.blockUI();
        $.ajax({
            url: "mdobject/dataViewDataGrid",
            type: "POST",
            data: {
                metaDataId: metaWidgetLinkParamList[0]['LIST_META_DATA_ID'],
                rows: pageSize,
                page: page
            },
            dataType: "JSON",
            success: function(dataViewResponse){
                if(dataViewResponse && dataViewResponse !== null &&
                        typeof dataViewResponse.rows !==
                        "undefined" && typeof callBack === "function"){
                    callBack(dataViewResponse.rows);
                    if($pagingDataViewWidget.length > 0){
                        initPaginWidgetDv(dataViewResponse.total);
                    }
                }
            },
            error: function(jqXHR, exception){
                Core.unblockUI();
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };
    var setDataParametersForTypeOne=function(rows, $themeHtml, metaWidgetLinkParamList, $useClonedSection, metaDataId, clonedHtml, id){
        var i=1;
        $.each(rows, function(num, row){
            replaceWidgetParamWithListParam(row, metaWidgetLinkParamList, $useClonedSection);
            $useClonedSection.attr('data-section', i).removeClass('active');
            $themeHtml.find('#' + id).find('.show-type-slider-inner').append($useClonedSection);
            $useClonedSection=$(clonedHtml);
            i++;
        });
    };
    var setDataParametersForTypeTwo=function(rows, $themeHtml, metaWidgetLinkParamList){
        var themeSections=$themeHtml.find("[data-section]");
        $.each(rows, function(num, row){
            replaceWidgetParamWithListParam(row, metaWidgetLinkParamList, $(themeSections[num]));
        });
    };
    var replaceWidgetParamWithListParam=function(row, metaWidgetLinkParamList, $section){
        var lParam;
        $.each(metaWidgetLinkParamList, function(wKey, wParam){
            lParam=wParam.LIST_PARAM;
            if(typeof row[lParam.toLowerCase()] !== "undefined"){
                if(row[lParam.toLowerCase()] !== null){
                    if(lParam === 'path' || lParam === 'picture'){
                        $section.html($section.html().replace(new RegExp('{' + wParam.WIDGET_PARAM + '}', 'g'), 'middleware/assets/img/layout-themes/news/news-img.jpg'));
                    } else {
                        $section.html($section.html().replace(new RegExp('{' + wParam.WIDGET_PARAM + '}', 'g'), row[lParam.toLowerCase()]));
                    }
                } else {
                    $section.html($section.html().replace(new RegExp('{' + wParam.WIDGET_PARAM + '}', 'g'), ''));
                }
            }
        });
    };
    var initWtSlider=function(idName){
        $('#' + idName).flexslider({
            animation: "slide",
            controlNav: false,
            slideshow: true,
            directionNav: true,
            smoothHeight: false,
            controlsContainer: "#" + idName + " .slider-nav"
        });
    };
    var initPaginWidgetDv=function(totalCount){
        var html="";
        if(page !== 1){
            html+='<li class="prev"><i class="fa fa-arrow-left"></i></li>';
        } else {
            html+='<li class="prev" style="pointer-events: none;"><i class="fa fa-arrow-left"></i></li>';
        }

        var total = decimalAdjust('ceil', parseFloat(totalCount) / parseFloat(pageSize));

        for(var i=1; i <= total; i++){
            if(page === i){
                html+='<li class="pagination active">' + i + '</li>';
            } else {
                html+='<li class="pagination">' + i + '</li>';
            }
        }

        if(page !== total){
            html+='<li class="next"><i class="fa fa-arrow-right"></i></li>';
        } else {
            html+=
                    '<li class="next" style="pointer-events: none;"><i class="fa fa-arrow-right"></i></li>';
        }


        $pagingDataViewWidget.html(html);
        initPaginWidgetDvEvent();
    };
    var initPaginWidgetDvEvent=function(){
        $pagingDataViewWidget.find('.pagination').click(function(){
            page=parseFloat($(this).html());
            getShowTypeTemplateAndConfig(tmpMetaDataId);
        });
        $pagingDataViewWidget.find('.prev').click(function(){
            page=parseInt($pagingDataViewWidget.find('.pagination.active').html()) - 1;
            getShowTypeTemplateAndConfig(tmpMetaDataId);
        });
        $pagingDataViewWidget.find('.next').click(function(){
            page=parseInt($pagingDataViewWidget.find('.pagination.active').html()) + 1;
            getShowTypeTemplateAndConfig(tmpMetaDataId);
        });
    };
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
    return {
        init: function(){
            initEvent();
        },
        getShowTypeTemplateAndConfig: function(metaDataId){
            initDecimalAdjust();
            getShowTypeTemplateAndConfig(metaDataId, 1);
        }
    };
}();
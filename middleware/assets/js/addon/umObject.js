
/* global Core, close_btn, choose, PNotify, UM_OBJECT_DV_ID */

var umObject=function(){

    var $umObjectSelectedList=$('#umObjectSelectedList'),
        $wsForm,
        uniqId,
        relationParamList=[];

    var initEvent = function(metaValueRelationRows, metaValueRelationRows1) {
        relationParamList = [];
        $umObjectSelectedList = $('#umObjectSelectedList_' + uniqId);

        $('#addUmObjectBtn').click(function(){
            renderDataview('dialog-um-object', function($dialog){
                dataViewCustomSelectableGrid('UM_OBJECT_DV', 'multi', 'addUmObjectTo', '', this);
            });
        });

        initParentMetaEvent();
        initRemoveObject();
        initRemoveOneTarget();
        
        if (typeof metaValueRelationRows !== 'undefined') {
            drawRelationHtmlEditMode(metaValueRelationRows, function(groupMetaDataIdList){
                getMetaValueName(groupMetaDataIdList);
            });
        }
        
        if (typeof metaValueRelationRows1 !== 'undefined') {
            drawRelationHtmlEditMode(metaValueRelationRows1, function(groupMetaDataIdList){
                getMetaValueName(groupMetaDataIdList);
            });
        }
        
        drawHiddenParamForRelation();
    };

    var renderDataview = function(dialogName, callback){
        if ($("#" + dialogName).length === 0) {
            $('<div id="' + dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + dialogName);

        if (typeof callback === 'function') {
            callback($dialog);
        }
    };

    var drawRelationHtml = function(rows, callback) {
        var html = '', groupMetaDataIdList = '';

        $.each(rows, function(index, row) {
            if (row.metadataid !== null && typeof relationParamList[row.tablename] === 'undefined') {
                relationParamList[row.tablename] = [];
                groupMetaDataIdList += row.metadataid + ',';
                html += getRelationRowHtml(row.metadataid, row.tablename, row.name);
            }
        });

        $umObjectSelectedList.find('.form-body').append(html);

        if (typeof callback === 'function') {
            callback(groupMetaDataIdList);
        }
    };

    var getRelationRowHtml = function(metadataid, tablename, name) {
        var html = '<div class="form-group">' +
                '<div class="object-div">' +
                '<i class="fa fa-times remove-object-btn" data-meta-group-id="' + metadataid + '" data-tablename="' +
                tablename + '"></i>' +
                '<div class="object-name" data-object-name="' + name + '">' + name + '</div>' +
                '<div class="object-record">' +
                '<div class="parent-meta">' +
                '<ul class="metas-div col-md-12"></ul>' +
                '<input type="text" class="form-control form-control-sm autoCompleteUmMetaData" id="input_' + metadataid +
                '" data-meta-group-id="' + metadataid + '" data-tablename="' + tablename + '" placeholder=""/>' +
                '</div></div>' +
                '</div>' +
                '</div>';

        return html;
    };

    var drawRelationHtmlEditMode = function(rows, callback){
        var html = '', groupMetaDataIdList = '';

        if (rows !== null) {
            $.each(rows, function(index, row){
                if (row.id !== null && typeof relationParamList[row.tablename] !== 'undefined') {
                    relationParamList[row.tablename] = [];
                    groupMetaDataIdList += row.id + ',';
                    html+=getRelationRowHtmlEditMode(row);
                }
            });
        }

        $umObjectSelectedList.find('.form-body').append(html);

        if (typeof callback === "function") {
            callback(groupMetaDataIdList);
        }
    };

    var getRelationRowHtmlEditMode = function(row){
        var html = '<div class="form-group">' +
                '<div class="object-div">' +
                '<i class="fa fa-times remove-object-btn" data-meta-group-id="' + row.id +
                '" data-tablename="' + row.tablename + '"></i>' +
                '<div class="object-name" data-object-name="' + row.name + '" data-min-value="' + row.minvalue + '">' +
                (row.isrequired === '1' ? '<span class="required">*</span>' : '') + row.name + '</div>' +
                '<div class="object-record">' +
                '<div class="parent-meta">' +
                '<ul class="metas-div col-md-12">';

        if (typeof row.records !== "undefined" && row.records !== null) {
            $.each(row.records, function(index, record){
                if (typeof relationParamList[row.tablename][record.id] === "undefined") {
                    relationParamList[row.tablename][record.id] = record.id;

                    var addinText = '';
                    if (typeof row.rendertype !== 'undefined' && row.rendertype === '1') {
                        addinText = '<input type="text" class="form-control form-control-sm" name="relationValue[' + row.tablename +
                                '][]" style="text-align: right;width: 10%;display: inline-block;" value="' + ((record.semanticvalue) ? record.semanticvalue : '') + '"/>';
                    }

                    html += '<li class="one-target-meta col-md-12"><a href="javascript:;" style="width:80%" class="tag"><span class="dot"></span>' + record.name +
                            '<i class="fa fa-times remove-one-target-meta" data-tablename="' +
                            row.tablename + '" data-item-id="' + record.id + '" style="float: right; padding-top: 5px;"></i></a>' + addinText + '</li>';
                }
            });
        }

        html += '</ul>' +
                '<input type="text" class="form-control form-control-sm autoCompleteUmMetaData" id="input_' + row.id +
                '" data-dtl-id="' + row.dtlid + '" data-meta-group-id="' + row.id + '" data-tablename="' + row.tablename +
                '" placeholder="" ' +
                'data-max-value="' + row.maxvalue + '" data-srcdtlid="' + row.srcdtlid + '" data-srcparampath="' + row.srcparampath +
                '" data-trgparampath="' + row.trgparampath + '"' +
                '" data-rendertype="' + row.rendertype + '" />' +
                '</div></div>' +
                '</div>' +
                '</div>';

        return html;
    };

    var getMetaValueName = function(groupMetaDataIdList){
        if (groupMetaDataIdList !== '') {
            $.ajax({
                type: 'post',
                url: 'mdcommon/getMetaValueName',
                data: {groupMetaDataIdList: groupMetaDataIdList},
                dataType: 'json',
                success: function(response){
                    putMetaValueName(response);
                }
            }).complete(function(){
                Core.unblockUI();
            });
        }
    };

    var putMetaValueName = function(response) {
        if (response !== null) {
            
            $.each(response, function(key, res){
                $('#input_' + res.MAIN_META_DATA_ID).attr('data-meta-value-name', res.FIELD_PATH);
            });

            $umObjectSelectedList.find(".autoCompleteUmMetaData").each(function(i){
                var $targetAutoComplete = $(this);
                if (!$targetAutoComplete.hasClass('ui-autocomplete-input')) {
                    $(this).autocomplete({
                        minLength: 1,
                        maxShowItems: 30,
                        delay: 500,
                        highlightClass: "lookup-ac-highlight",
                        appendTo: "body",
                        position: {my: "left top", at: "left bottom", collision: "flip flip"},
                        autoSelect: false,
                        source: function(request, response){
                            var $elem=$($(this)[0].bindings[0]);

                            if(typeof $elem.data('max-value') !== "undefined"){
                                var $parentUl=$elem.parents('.parent-meta').find('ul.metas-div');
                                if($parentUl.find('li').length === $elem.data('max-value')){
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Хамгийн ихдээ ' + $elem.data('max-value') + ' утга оруулна!',
                                        type: 'warning',
                                        sticker: false
                                    });
                                    return false;
                                }
                            }

                            var data={
                                q: request.term,
                                metaGroupId: $elem.data('meta-group-id'),
                                metaValueName: $elem.data('meta-value-name')
                            };

                            var srcDtlVal=$umObjectSelectedList.find('input[data-dtl-id="' + $elem.data('srcdtlid') + '"]').attr('data-srcdtl-val');
                            if($elem.data('trgparampath') !== null && srcDtlVal){
                                var tmpData={};
                                tmpData[$elem.data('trgparampath')]=srcDtlVal;
                                $.extend(data, {
                                    tempParam: tmpData
                                });
                            }

                            if(typeof $elem.data('meta-value-name') !== "undefined"){
                                $.ajax({
                                    type: 'post',
                                    url: 'mdcommon/getDvResultAutoComplete',
                                    dataType: 'json',
                                    data: data,
                                    success: function(data){
                                        response($.map(data, function(item){
                                            var itemResult={
                                                label: item[$elem.data('meta-value-name')],
                                                name: item[$elem.data('meta-value-name')],
                                                id: item.id
                                            };

                                            if($elem.data('srcparampath') !== null && typeof item[$elem.data('srcparampath')] !== "undefined"){
                                                $.extend(itemResult, {
                                                    srcparampathVal: item[$elem.data('srcparampath')]
                                                });
                                            }

                                            return itemResult;
                                        }));
                                    }
                                });
                            } else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Стандарт талбар тохируулаагүй байна',
                                    type: 'warning',
                                    sticker: false
                                });
                            }
                        },
                        focus: function(event, ui){
                            return false;
                        },
                        open: function(){
                            $(this).autocomplete('widget').zIndex(99999999999999);
                            return false;
                        },
                        close: function(){
                            $(this).autocomplete("option", "appendTo", "body");
                        },
                        select: function(event, ui){
                            var $elem=$(this), trgTableName=$elem.data('tablename'), item=ui.item;
                            var rendertype=$elem.attr('data-rendertype');

                            if(typeof relationParamList[trgTableName] !== "undefined"){
                                if(typeof relationParamList[trgTableName][item.id] === "undefined"){
                                    relationParamList[trgTableName][item.id]=item.id;
                                    var addinText='';
                                    if(typeof rendertype !== 'undefined' && rendertype === '1'){
                                        addinText='<input type="text" class="form-control form-control-sm" name="relationValue[' + trgTableName +
                                                '][]" style="text-align: right;width: 10%;display: inline-block;"/>';
                                    }

                                    var html=' <li class="one-target-meta col-md-12"><a href="javascript:;" class="tag" style="width:80%"><span class="dot"></span>' + item.name +
                                            '<i class="fa fa-times remove-one-target-meta" data-tablename="' +
                                            trgTableName + '" data-item-id="' + item.id + '" style="float: right; padding-top: 5px;"></i></a> ' + addinText + '</li>';

                                    $elem.parents('.parent-meta').find('ul.metas-div').append(html);

                                    if(typeof item.srcparampathVal !== 'undefined'){
                                        $elem.attr('data-srcdtl-val', item.srcparampathVal);
                                    }


                                    drawHiddenParamForRelation();
                                }
                            }

                            $elem.val('');
                            return false;
                        }
                    }).autocomplete("instance")._renderItem=function(ul, item){
                        ul.addClass('lookup-ac-render');
                        var re=new RegExp("(" + this.term + ")", "gi"),
                                cls=this.options.highlightClass,
                                template="<span class='" + cls + "'>$1</span>",
                                label=item.label.replace(re, template);

                        return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' +
                                item.name +
                                '</div>').appendTo(ul);
                    };
                }
            });
        }
    };

    var drawHiddenParamForRelation = function(){
        var htmlRelationParams='';

        if (Object.keys(relationParamList).length > 0) {
            for (var trgTable in relationParamList) {
                if (Object.keys(relationParamList[trgTable]).length > 0) {
                    for (var trgRecord in relationParamList[trgTable]) {
                        htmlRelationParams+='<input type="hidden" name="relationParams[' + trgTable + '][]" value="' + trgRecord + '">';
                    }
                }
            }

            $wsForm.find('.relationParamsDiv').remove();
            $wsForm.append('<div class="relationParamsDiv">' + htmlRelationParams + '</div>');
        }
    };

    var initParentMetaEvent = function(){
        $umObjectSelectedList.on('click', '.object-div', function(){
            $(this).find('input.autoCompleteUmMetaData').focus();
        });
    };

    var initRemoveOneTarget = function(){
        $umObjectSelectedList.on('click', '.remove-one-target-meta', function(){
            var $target = $(this);
            if (typeof relationParamList[$target.data('tablename')] !== "undefined") {
                if (typeof relationParamList[$target.data('tablename')][$target.data('item-id')] !== "undefined") {
                    delete relationParamList[$target.data('tablename')][$target.data('item-id')];
                    $target.closest('.one-target-meta').remove();
                    drawHiddenParamForRelation();
                }
            } else
                $target.closest('.one-target-meta').remove();
        });
    };

    var initRemoveObject = function(){
        $umObjectSelectedList.on('click', '.remove-object-btn', function(){
            var $target = $(this);
            if (typeof relationParamList[$target.data('tablename')] !== "undefined") {
                delete relationParamList[$target.data('tablename')];
                $target.closest('.form-group').remove();
                drawHiddenParamForRelation();
            }
        });
    };

    return {
        init: function(uId, processId, metaValueRelationRows, metaValueRelationRows1){
            uniqId = uId;
            $wsForm = $("#bp-window-" + processId).find('#wsForm');
            initEvent(metaValueRelationRows, metaValueRelationRows1);
        },
        getMetaValueName: function(groupMetaDataIdList){
            getMetaValueName(groupMetaDataIdList);
        },
        drawHiddenParamForRelation: function(){
            drawHiddenParamForRelation();
        },
        drawRelationHtml: function(rows, callback){
            drawRelationHtml(rows, callback);
        }
    };
}();
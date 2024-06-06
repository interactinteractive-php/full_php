<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form mt10">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'changefolder-form', 'method' => 'post')); ?>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Сонгогдсон', 'class'=>'col-form-label col-md-3')); ?>
            <div class="col-md-9">
                <p class="form-control-plaintext font-weight-bold pt0">( <?php echo $this->countMeta; ?> )</p>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Бүлэг сонгох', 'class'=>'col-form-label col-md-3')); ?>
            <div class="col-md-9">
                <div class="meta-autocomplete-wrap">
                    <div class="input-group double-between-input">
                        <span class="input-group-btn">
                            <input id="_displayField" class="form-control form-control-sm md-folder-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                        </span>   
                            <?php echo Form::hidden(array('name' => 'folderId')); ?>
                        <span class="input-group-btn">
                            <?php echo Form::button(array('class' => 'btn purple-plum', 'value' => '<i class="fa fa-search"></i>', 'onclick' => 'commonFolderDataGrid(\'multi\', \'\', \'chooseMetaFolderByChange\', this);')); ?>
                        </span>   
                
                        <span class="input-group-btn">
                            <input id="_nameField" class="form-control form-control-sm md-folder-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                        </span>     
                    </div>
                </div>  
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>

<style>
#_displayField{
    width: 20px;
}
</style>

<script type="text/javascript">
function chooseMetaFolderByChange(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        var rows = $('#commonBasketFolderGrid').datagrid('getRows');
        var row = rows[0];
        $("input[name='folderId']", "#changefolder-form").val(row.FOLDER_ID);
        $("input#_nameField", "#changefolder-form").val(row.FOLDER_NAME);
        $("input#_displayField", "#changefolder-form").val(row.FOLDER_CODE);
    }        
}

    $("body").on("keydown", 'input.md-folder-code-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var $isName = 'code';
        var $value = _this.val();

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            }

            if (typeof _this.attr('data-ac-id') !== 'undefined') {
                $isName = 'idselect';
                $value = _this.attr('data-ac-id');
            }            

            $.ajax({
                type: 'post',
                url: 'mdfolder/metaFolderGridAutoComplete',
                data: {q: $value, type: $isName},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    _this.removeAttr('data-ac-id');

                    if (data.length === 1) {
                        var code = data[0].split("|#");
                        $('input[name="folderId"]').val(code[0]);
                        $("input[id*='_displayField']").val(code[1]);
                        $("input[id*='_nameField']").val(code[2]);
                    } else {
                        $('input[name="folderId"]').val('');
                        $("input[id*='_displayField']").val('');
                        $("input[id*='_nameField']").val('');
                    }
                    
                    _this.removeClass("spinner2");
                }
            });
            return false;
        } else {
            if (!_this.data("ui-autocomplete")) {
                metaFolderAutoComplete(_this, 'code');
            }
        }
    });   
    $("body").on("keydown", 'input.md-folder-name-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var $isName = 'name';
        var $value = _this.val();

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            }

            if (typeof _this.attr('data-ac-id') !== 'undefined') {
                $isName = 'idselect';
                $value = _this.attr('data-ac-id');
            }            

            $.ajax({
                type: 'post',
                url: 'mdfolder/metaFolderGridAutoComplete',
                data: {q: $value, type: $isName},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    _this.removeAttr('data-ac-id');

                    if (data.length === 1) {
                        var code = data[0].split("|#");
                        $('input[name="folderId"]').val(code[0]);
                        $("input[id*='_displayField']").val(code[1]);
                        $("input[id*='_nameField']").val(code[2]);
                    } else {
                        $('input[name="folderId"]').val('');
                        $("input[id*='_displayField']").val('');
                        $("input[id*='_nameField']").val('');
                    }
                    
                    _this.removeClass("spinner2");
                }
            });
            return false;
        } else {
            if (!_this.data("ui-autocomplete")) {
                metaFolderAutoComplete(_this, 'name');
            }
        }
    });   

    function metaFolderAutoComplete(elem, type) {
        var _this = elem;
        var _parent = _this.closest("div.meta-autocomplete-wrap");
        var params = _parent.attr('data-params');
        var isHoverSelect = false;

        _this.autocomplete({
            minLength: 1,
            maxShowItems: 30,
            delay: 500,
            highlightClass: "lookup-ac-highlight",
            appendTo: "body",
            position: { my: "left top", at: "left bottom", collision: "flip flip" },
            autoSelect: false,
            source: function(request, response) {
                $.ajax({
                    type: 'post',
                    url: 'mdfolder/metaFolderGridAutoComplete',
                    dataType: 'json',
                    data: {
                        q: request.term,
                        type: type,
                        params: params
                    },
                    success: function(data) {
                        if (type == 'code') {
                            response($.map(data, function(item) {
                                var code = item.split("|#");
                                return {
                                    value: code[1],
                                    label: code[1],
                                    name: code[2],
                                    id: code[0]
                                };
                            }));
                        } else {
                            response($.map(data, function(item) {
                                var code = item.split("|#");
                                return {
                                    value: code[2],
                                    label: code[1],
                                    name: code[2],
                                    id: code[0]
                                };
                            }));
                        }
                    }
                });
            },
            focus: function(event, ui) {
                if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                    isHoverSelect = false;
                } else {
                    if (event.keyCode == 38 || event.keyCode == 40) {
                        isHoverSelect = true;
                    }
                }
                return false;
            },
            open: function() {
                /*$(this).autocomplete('widget').zIndex(99999999999999);*/
                return false;
            },
            close: function() {
                $(this).autocomplete("option", "appendTo", "body");
            },
            select: function(event, ui) {
                var origEvent = event;

                if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                _parent.find('input[name="folderId"]').val(ui.item.id);
                    if (type === 'code') {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);            
                        _parent.find("input[id*='_displayField']").attr('data-ac-id', ui.item.id);        
                    } else {
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                        _parent.find("input[id*='_nameField']").attr('data-ac-id', ui.item.id);
                    }
                } else {
                    if (type === 'code') {
                        if (ui.item.label === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.find("input[id*='_nameField']").val(ui.item.name);
                        } else {
                            _parent.find("input[id*='_displayField']").val(_this.val());
                            event.preventDefault();
                        }
                    } else {
                        if (ui.item.name === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.find("input[id*='_nameField']").val(ui.item.name);
                        } else {
                            _parent.find("input[id*='_nameField']").val(_this.val());
                            event.preventDefault();
                        }
                    }
                }

                while (origEvent.originalEvent !== undefined) {
                    origEvent = origEvent.originalEvent;
                }

                if (origEvent.type === 'click') {
                    var e = jQuery.Event("keydown");
                    e.keyCode = e.which = 13;
                    _this.trigger(e);
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            ul.addClass('lookup-ac-render');

            if (type === 'code') {
                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
            } else {
                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    name = item.name.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
            }
        };
    }
</script>
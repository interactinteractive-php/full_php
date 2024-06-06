<div id="glEntryWindow_<?php echo $this->uniqId; ?>">
    <div class="form-body xs-form">
        <div class="generalledger-header-row row">
            <div class="col-md-10 generalledger-header-content">
                <input type="hidden" id="searchClickedTR" value="0">
                <?php 
                echo Form::hidden(array('name' => 'glbookId', 'value' => isset($this->paramList['id']) ? $this->paramList['id'] : '')); 
                echo Form::hidden(array('name' => 'glimportId', 'value' => isset($this->paramList['importid']) ? $this->paramList['importid'] : '')); 
                echo Form::hidden(array('name' => 'glBookTypeId', 'value' => isset($this->paramList['booktypeid']) ? $this->paramList['booktypeid'] : '2')); 
                echo Form::hidden(array('id' => 'gl_iscomplete', 'value' => isset($this->paramList['iscomplete']) ? $this->paramList['iscomplete'] : '')); 
                echo Form::hidden(array('name' => 'isglcopy', 'value' => issetParam($this->isglcopy))); 
                
                if (isset($this->importId)) {
                    echo Form::hidden(array('name' => 'hidden_importId', 'value' => $this->importId)); 
                }
                
                if (isset(Mdgl::$getDefaultValues) && Mdgl::$getDefaultValues) {
                    echo Form::textArea(array('name'=>'hidden_getDefaultValues','class'=>'d-none','value'=>json_encode(Mdgl::$getDefaultValues))); 
                }
                ?>
                <div class="row pl10 pr10">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <?php echo Form::label(array('text' => 'Огноо', 'for' => 'glbookDate', 'class' => 'col-form-label col-md-3 gl-label', 'required' => 'required')); ?>
                            <div class="col-md-6">
                                <div class="dateElement input-group">
                                    <?php echo Form::text(array('name' => 'glbookDate', 'id' => 'glbookDate', 'class' => 'form-control form-control-sm dateInit', 'value' => isset($this->paramList['bookdate']) ? Date::formatter($this->paramList['bookdate'], 'Y-m-d') : '', 'required' => 'required', 'tabindex' => 1)); ?>
                                    <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fal fa-calendar"></i></button></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(array('text' => 'Дугаар', 'for' => 'glbookNumber', 'class' => 'col-form-label col-md-3 gl-label', 'required' => 'required')); ?>
                            <div class="col-md-6">
                                <?php echo Form::text(array('name' => 'glbookNumber', 'id' => 'glbookNumber', 'class' => 'form-control form-control-sm readonly-white-bg', 'value'=>isset($this->paramList['booknumber']) ? $this->paramList['booknumber'] : '', 'required' => 'required', 'tabindex' => 2)); ?>
                            </div>
                        </div>                                                   
                    </div>
                    <div class="col-md-8">
                        <div class="form-group row">
                            <?php echo Form::label(array('text' => 'Утга', 'for' => 'gl_description_code', 'class' => 'col-form-label col-md-1 gl-label')); ?>
                            <div class="col-md-11">
                                <div class="input-group double-between-input">
                                   <?php echo Form::hidden(array('name' => 'gl_description_id', 'id' => 'gl_description_id'))?>
                                   <?php echo Form::text(array('name' => 'gl_description_code', 'id' => 'gl_description_code', 'class' => 'form-control form-control-sm glCode-autocomplete', 'placeholder'=>$this->lang->line('code_search'), 'value'=>(isset($this->descriptionCode['MESSAGE_CODE']) ? $this->descriptionCode['MESSAGE_CODE'] : ''))); ?>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('finMessageInfoDV', 'single', 'descriprionSelectableGrid', '', this);"><i class="fa fa-search"></i></button>
                                    </span>     
                                    <span class="input-group-btn">
                                         <?php echo Form::text(array('name' => 'gl_description_name', 'id' => 'gl_description_name', 'class' => 'form-control form-control-sm', 'placeholder'=>$this->lang->line('name_search'), 'value'=>isset($this->paramList['description']) ? $this->paramList['description'] : '')); ?>    
                                    </span>     
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo Form::label(array('class' => 'col-form-label col-md-1 gl-label', 'no_colon' => 'true')); ?>
                            <div class="col-md-11">
                                <div class="input-group">
                                <?php 
                                echo Form::text(array('name' => 'gldescription', 'id' => 'gldescription', 'class' => 'form-control', 'style' => 'height: 24px', 'required' => 'required', 'value'=>isset($this->paramList['description']) ? $this->paramList['description'] : '', 'tabindex' => 3, 'data-path' => 'gldescription', 'data-c-name' => 'DESCRIPTION')); 
                                if (isset($this->paramList['pftranslationvalue']) && $this->paramList['pftranslationvalue']) {
                                    $transvalue = json_decode($this->paramList['pftranslationvalue'], true);
                                    echo '<textarea name="gldescription_translation" style="display:none" data-translate-path="gldescription">'.json_encode($transvalue['value']['DESCRIPTION']).'</textarea>';
                                }
                                
                                echo '<span class="input-group-append">';
                                
                                if (Config::getFromCache('CONFIG_GL_ROW_DESC')) {
                                    echo '<button class="btn purple-plum glhdr-descr-to-dtl" type="button" title="Утга хуулах"><i class="far fa-sort-alpha-down"></i></button>';
                                }
                                
                                if (Lang::isUseMultiLang()) {
                                    echo '<button class="btn btn-primary" type="button" onclick="bpFieldTranslate(this);" title="Орчуулга"><i class="fa fa-language"></i></button>';
                                }                                
                                
                                echo '</span>';
                                
                                if (Config::getFromCache('isGLDescrEnglish')) {
                                    echo Form::text(array('name' => 'gldescription2', 'placeholder' => 'Гадаад', 'id' => 'gldescription2', 'class' => 'form-control', 'style' => 'height: 24px', 'required' => 'required', 'value'=>isset($this->paramList['description2']) ? $this->paramList['description2'] : '', 'tabindex' => 3)); 
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="col-md-2 generalledger-header-content generalledger-header-sum-price">
                <p style="margin-bottom: 7px"><?php echo Lang::lineDefault('FIN_1000', 'Гүйлгээний тэнцэл'); ?></p>
                <div class="clearfix w-100"></div>
                <span class="float-right" id="headerDebitTotal"><?php echo Lang::lineDefault('DT', 'ДТ'); ?>: 0.00</span>
                <div class="clearfix w-100"></div>
                <span class="float-right" id="headerCreditTotal"><?php echo Lang::lineDefault('KT', 'КТ'); ?>: 0.00</span>
                <div class="clearfix w-100"></div>
            </div>
        </div>
        <div<?php echo ($this->isPopup ? '' : ' class="row m-0"'); ?>>
            <div class="tabbable-line w-100">
                <ul class="gltab nav nav-tabs">
                    <li class="nav-item">
                        <a href="#glAccountTab_<?php echo $this->uniqId; ?>" class="nav-link active" data-toggle="tab"><?php echo Lang::lineDefault('FIN_00410', 'Гүйлгээ'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#glCommentTab_<?php echo $this->uniqId; ?>" data-toggle="tab" class="nav-link"><?php echo Lang::lineDefault('FIN_01168', 'Тайлбар'); ?></a>
                    </li>
                    <?php 
                    if (isset($this->isFileAttachTab) && $this->isFileAttachTab) { 
                    ?>
                    <li class="nav-item">
                        <a href="#glFileTab_<?php echo $this->uniqId; ?>" data-toggle="tab" class="nav-link"><?php echo Lang::lineDefault('file', 'Файл'); ?> (<?php echo $this->metaValueFileCount; ?>)</a>
                    </li>
                    <?php 
                    } 
                    ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="glAccountTab_<?php echo $this->uniqId; ?>">
                        <div id="glDetailGridByDesc">
                            <?php echo $this->gridDtl; ?>
                        </div>
                    </div>
                    <div class="tab-pane in" id="glCommentTab_<?php echo $this->uniqId; ?>">
                        <div class="bp-comment-wrap">
                            <div class="scrollerFalse">
                                <ul class="chats"></ul>
                            </div>
                            <div class="chat-form dialog-chat">
                                <div class="input-cont">   
                                    <?php
                                    echo Form::textArea(
                                        array(
                                            'name' => 'gl_comment',
                                            'id' => 'gl_comment',
                                            'class' => 'form-control col-md-12',
                                            'placeholder' => Lang::lineDefault('FIN_10005562', 'Тайлбар бичих'),
                                            'onkeypress' => 'if(event.keyCode == 13) appendBpComment(this);'
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    if (isset($this->isFileAttachTab) && $this->isFileAttachTab) { 
                    ?>
                    <div class="tab-pane" id="glFileTab_<?php echo $this->uniqId; ?>">
                        <?php echo $this->fileAttachTab; ?>
                    </div>
                    <?php 
                    } 
                    ?>
                </div>   
            </div>
        </div>
    </div>     
</div>    
<div id="loadAccount"></div>

<script type="text/javascript">
var glEntryWindowId_<?php echo $this->uniqId; ?> = '#glEntryWindow_<?php echo $this->uniqId; ?>';

Core.initDateInput($(glEntryWindowId_<?php echo $this->uniqId; ?>));

$(function() {
    
    <?php
    if (isset($this->errorGLMessage) && $this->errorGLMessage) {
    ?>
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: '<?php echo $this->errorGLMessage; ?>', 
            type: 'error',
            sticker: false
        });
    <?php
    }
    ?>
            
    /*if ($().enableEnterToTab) {
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).enableEnterToTab();
    }*/
    <?php
    if (Config::getFromCache('IS_GL_DESC_MASK') == '1' && $glMask = $this->db->GetOne("SELECT PATTERN_TEXT FROM META_FIELD_PATTERN WHERE LOWER(TRIM(PATTERN_NAME)) = 'gl_description'")) {
        /* ^[_A-Za-zА-Яа-яӨҮөү0-9-\+\\\'\"\s@$ ]{1,60}$ */
    ?>
    $('#gldescription', glEntryWindowId_<?php echo $this->uniqId; ?>).inputmask('Regex', {regex: '<?php echo $glMask; ?>'});        
    <?php        
    }
    ?>
    if ($("#gl_iscomplete").val() == '1' || $("#gl_iscomplete").val() == 'true'){
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).find("input,textarea,select").attr("readonly", "readonly");
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).find("button,div").attr("disabled", "disabled");
    }
    
    $(glEntryWindowId_<?php echo $this->uniqId; ?>).on("change", '#gldescription', function(){
        setDtlDescription_<?php echo $this->uniqId; ?>();
    });
    
    $(glEntryWindowId_<?php echo $this->uniqId; ?>).on('keydown', '#glbookDate', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) {
            $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('#gldescription').focus().select();
        }
    });
    
    $(glEntryWindowId_<?php echo $this->uniqId; ?>).on('keydown', '#gldescription', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) {
            $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('#glquickCode').focus().select();
        }
    });
    
    $(glEntryWindowId_<?php echo $this->uniqId; ?>).on("focus", 'input[name="gl_description_code"]', function(e){
        var _this = $(this);
        var isHoverSelect = false;
        
        _this.autocomplete({
            minLength: 1,
            maxShowItems: 7,
            delay: 100,
            highlightClass: "lookup-ac-highlight", 
            appendTo: "body",
            position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
            autoFocus: true,
            source: function(request, response) {
                $.ajax({
                    type: 'post',
                    url: 'mdgl/filterDescriptionInfo',
                    dataType: "json",
                    data: {
                        q: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.CODE,
                                name: item.NAME,
                                data: item
                            };
                        }));
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
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function (event, ui){
                $(this).autocomplete("option","appendTo","body"); 
            }, 
            select: function(event, ui) {
                var data = ui.item.data;   
                
                if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                    $("input#gl_description_id", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.ID);
                    $("input#gl_description_code", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.CODE);
                    $("input#gl_description_name", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.NAME);
                    $("input#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.NAME);
                    
                    setDtlDescription_<?php echo $this->uniqId; ?>();
                    
                } else {
                    if (ui.item.label === _this.val()) {
                        $("input#gl_description_id", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.ID);
                        $("input#gl_description_code", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.CODE);
                        $("input#gl_description_name", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.NAME);
                        $("input#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.NAME);
                        
                        setDtlDescription_<?php echo $this->uniqId; ?>();
                        
                    } else {
                        var origEvent = event;

                        while (origEvent.originalEvent !== undefined){
                            origEvent = origEvent.originalEvent;
                        }

                        if (origEvent.type === 'click') {
                            var e = jQuery.Event("keydown");
                            e.keyCode = e.which = 13;
                            _this.trigger(e);
                        }
                        event.preventDefault();
                    }
                }
                 
                return false;   
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            ul.addClass('lookup-ac-render');

            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                label = item.label.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
        };    
    });
    $(glEntryWindowId_<?php echo $this->uniqId; ?>).on("keydown", 'input[name="gl_description_code"]', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13) {
            var _this = $(this);
            _this.autocomplete("close");     
            _this.closest(".ui-menu-item").hide();
            
            $.ajax({
                type: 'post',
                url: 'mdgl/getDescriptionInfo',
                data: {q: _this.val()},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    if (!($.isArray(data))) {
                        $("input#gl_description_id", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.ID);
                        $("input#gl_description_code", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.CODE);
                        $("input#gl_description_name", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.NAME);
                        $("input#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val(data.NAME);
                    } else {
                        $("input#gl_description_id", glEntryWindowId_<?php echo $this->uniqId; ?>).val('');
                        $("input#gl_description_code", glEntryWindowId_<?php echo $this->uniqId; ?>).val('');
                        $("input#gl_description_name", glEntryWindowId_<?php echo $this->uniqId; ?>).val('');
                        $("input#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val('');
                    }
                    
                    setDtlDescription_<?php echo $this->uniqId; ?>();
                    
                    _this.removeClass("spinner2");
                }
            });
            return false;
        }
    });
});
function descriprionSelectableGrid(metaDataCode, chooseType, elem, rows){
    var row = rows[0];
    $("#gl_description_id", glEntryWindowId_<?php echo $this->uniqId; ?>).val(row.id);
    $("#gl_description_code", glEntryWindowId_<?php echo $this->uniqId; ?>).val(row.messagecode);
    $("#gl_description_name", glEntryWindowId_<?php echo $this->uniqId; ?>).val(row.messagedescl);
    $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val(row.messagedescl);
    
    setDtlDescription_<?php echo $this->uniqId; ?>();

    return;
}
function setDtlDescription_<?php echo $this->uniqId; ?>() {
    <?php 
    if (!Config::getFromCache('CONFIG_GL_ROW_DESC')) {
    ?>
    var descr = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();        
    $("input[name='gl_rowdescription[]']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(descr);          
    <?php    
    }
    ?>
            
    return;
}
</script>
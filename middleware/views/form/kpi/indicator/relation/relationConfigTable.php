<form method="post" id="kpiDataMartVisualConfigForm" style="height: 100%">
    
    <div class="row">
        <div class="col">
            <div class="mb10">
                <button type="button" class="btn btn-sm green-meadow" onclick="kpiDataMartAddObjectTable(this);">
                    <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?>
                </button>
            </div>
        </div>
    </div>
    <div class="row" style="height: 100%">
        <div class="col">
            <div class="" style="height: 100%">
                <div class="heigh-editor-table" id="relation-settings-gutter">
                    <div class="css-editor" id="datamart-editor" style=""></div>
                </div>              
                <div id="relation-settings-gutter-2">
                    <div class="d-flex justify-content-between" style="height: 35px;background-color: #F5F5F5;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                        <div class="mt7 ml10" style="color:rgba(0,0,0,0.6)">
                            <span class="show-indicator-column-count"></span> багана
                        </div>
                        <div>
                            <button type="button" style="padding: 2px 5px 0px 7px;" class="btn dropdown-toggle mt5 mr10" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-cog" style="color:rgba(0,0,0,0.6)"></i>
                            </button>                                  
                            <div class="dropdown-menu">              
                                <a class="showHideStructureColumn dropdown-item" href="javascript:;">Нуусан багана</a>
                            </div>
                        </div>
                    </div>
                    <div class="editor-bottom-layout d-flex">
                        <div class="col" style="flex: 0 0 500px;border-right: 1px solid #ccc;">    
                            <div class="editor-table-settings-area">
                            </div>    
                        </div>           
                        <div class="col pl10">    
                            <div class="mt10 d-none reload-datamart-btn" style="margin-left: -10px;">
                                <button type="button" class="btn btn-sm green-meadow" onclick="refreshLoadDataListMart()">
                                    <i class="far fa-database"></i> Датамарт шинэчлэх
                                </button>
                            </div>
                            <div class="editor-table-datalist-area">
                            </div>    
                        </div>    
                    </div>                      
                </div>
            </div>
        </div>
    </div>  
    
    <input type="hidden" name="id" value="<?php echo $this->id; ?>" data-kpidatamart-id="1"/>
</form>    

<style type="text/css">
    #kpiDataMartVisualConfigForm ._jsPlumb_overlay {
        display: none;
        width: 100px;
        background-color: rgba(223, 223, 223, 0.9);
        font-size: 11px;
        line-height: 12px;
        padding: 2px;
        border: 1px #b4b4b4 solid;
        color: #000;
        z-index: 2;
    }
    #kpiDataMartVisualConfigForm ._jsPlumb_overlay._jsPlumb_hover {
        display: block;
    }
    .relation-jtype.active::after {
        content: "\f00c";
        top: 0;
        font-family: "Font Awesome 5 Pro";
        position: absolute;
        margin-left: 60px;
        font-size: 18px;
        line-height: 1;
        color: #19d119;
        font-weight: bold; 
    }
    .editor-table-settings-area table tbody button {
        display: none;
    }
    .editor-table-settings-area table tbody tr:hover button {
        display: block;
    }
    .wfdmart.active {
        border: 1px solid #333;
    }
    .editor-table-settings-area {
        overflow-y: auto;
    }
    #dialog-dmart-relationconfig-table {
        overflow: hidden;
    }
    .gutter.gutter-vertical {
        height: 5px;
        cursor: row-resize !important;
    }
</style>

<script type="text/javascript">
//    if (!$("link[href='assets/custom/addon/plugins/jsplumb/css/style.v55.css']").length){
//        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v55.css"/>');
//    }    
//    if (typeof isKpiAddonScript === 'undefined') {
//        $.getScript('assets/custom/addon/plugins/jsplumb/jsplumb.min.js').done(function() {
//            $.getScript(URL_APP + 'middleware/assets/js/addon/kpinew.js').done(function() {
//            });
//        });
//    }    
//    var setHeight = $(window).height() - 550;
//    var $editor = $('#datamart-editor');
//
//    $editor.css({'height': setHeight - 2, 'max-height': setHeight - 2});    
</script>
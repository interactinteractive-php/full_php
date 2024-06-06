<div id="calculateSalarySheetDiv_<?php echo $this->uniqId; ?>">     
    <div class="row" id="calcInfoWindow_<?php echo $this->uniqId; ?>">
        <div class="col-md-12">
        <div class="col-md-12 pl0">
            <div class="col-md-12 jeasyuiTheme3 mt5 pl0">
                <table class="no-border" id="salaryDatagrid_<?php echo $this->uniqId; ?>" style="width: 100%;"></table>
            </div>
        </div>
        </div>
    </div>
</div>

<style type="text/css">
    .selectedDepartment_<?php echo $this->uniqId; ?> {
        background-color: #FFF;
        cursor: default !important;
    }    
    .departmentlist-jtree-<?php echo $this->uniqId; ?> {
        overflow: auto;
        z-index: 101;
        position:absolute;
        border-right: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
        max-height: 350px;
        padding-bottom: 10px;
        background: #FFF;
    }    
    .departmentlist-jtree-<?php echo $this->uniqId; ?> .jstree-container-ul {
        background: #FFF !important;
    }
    .search-tree-<?php echo $this->uniqId; ?> > span {
        float: left;
        margin-right: 5px;
        margin-left: 5px;
        padding-top: 5px;
        font-size: 12px;
    }
    .search-tree-<?php echo $this->uniqId; ?> {
        background: #FFF !important; 
        padding-top: 6px;
        padding-bottom: 2px;
        border-bottom: 1px solid #ccc;
        padding-left: 8px;
        padding-right: 8px;        
        font-size: 12px;
    }
    .search-tree-<?php echo $this->uniqId; ?> > input {
        border-radius: 0 !important;
        width: 100% !important;
    }    
    
    #calculateSalarySheetDiv .customLabel {
        padding-top: 3px;
        font-size: 14px;
        
        font-weight: 400;
        color: #444;
        text-align: right;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> #fieldExpressionSpan_<?php echo $this->uniqId; ?>, #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> #fieldSpan_<?php echo $this->uniqId; ?>{
        font-size: 14px !important;
        
        text-align: justify;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .form-control-sm {
        border-radius: 0px !important        
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell{
        padding: 0px !important;
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body td input[type="text"]:focus{
        border: 1px solid #999 !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-tile:hover {
        background-color: #ff6f55;
    }   
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-descr {
        font-size: 26px;
        line-height: 26px;
        margin-top: 18px;
    }   
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-tile {
        height: 180px;
    }   
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {
        border-bottom: 1px solid #ccc;
        font-size: 15px;
    }   
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body td{
        /*border-width: 0px 0px 0px 0px !important;*/
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body .form-control {
        border: 0px !important;
        font-size: 12px !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-footer .form-control {
        font-size: 12px !important;
    }

    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-footer input{
        border: 0px !important;
        font-weight: bold !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-footer .form-control[readonly]{
        background-color: #fff;
    }
    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-body{
        height: auto !important;
    }

    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-wrap{
        height: 240px !important;
    }
    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-view{
        height: 200px !important;
    }
    #dialog_for_employee_<?php echo $this->uniqId; ?>{
        height: 200px !important;
    }
    .datagrid-row-over td {
        background: #fff;
    }  
    .datagrid-row-selected td {
        background: #98ccff !important;
        border-bottom-color: #98ccff;
        color: #000;
    }  
    .datagrid-row-selected input {
        background: #98ccff !important;
        color: #000;
    }  
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell input.saved-log-data-cell {
        width: 76%;
        float: left;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell a.btn-secondary {
        float: right;
        border-bottom-style: none;
        border-right-style: none;
        border-top-style: none;
        border-radius: 50%;
        border-left-color: #333;
        margin-top: 1px;    
    }
    .jeasyuiTheme3 .datagrid-header .datagrid-cell span {
        font-size: 11px;
        color: #333;
    }
    .multipleAddFilterBtn {
        position: absolute;
        margin-top: -22px;
        right: 0;
        margin-right: 30px;        
    }        
    .context-menu-list {
        box-shadow: 5px 5px 5px -3px rgba(0,0,0,0.6);
    }
    .selectedDepartmentNamesContainer {
        max-height: 58px;
        overflow: hidden;
        color: #505050;
    }
    .selectedDepartmentNamesContainerBtn {
        text-align: center;
        font-size: 11px;
        text-transform: lowercase;
        color: #789e26;
        border-top: 1px solid #c2da8e;
    }
    .selectedDepartmentNamesContainerBtn:hover {
        color: #0057c7;
        cursor: pointer;
    }
    
    /* Custom Card CSS Start */
    .next-generation-input-wrap {
        width: 100%;
        height: 80px;
        -moz-box-shadow: 0px 0px 10px 0px #dadada;
        -webkit-box-shadow: 0px 0px 10px 0px #dadada;
        box-shadow: 0px 0px 10px 0px #dadada;        
    }
    @media screen and (min-width: 1220px) {
        .next-generation-input-label {
            width: 40%;
        }
        .next-generation-input-body {
            width: 60%;
        }
    }
    @media screen and (max-width: 1220px) {
        .next-generation-input-label {
            width: 60%;
        }
        .next-generation-input-body {
            width: 40%;
        }        
    }
    .next-generation-input-label {
        float: left;
        height: inherit;
        padding: 8px;
        padding-top: 5px;
        position: relative;
    }    
    .next-generation-input-label > .next-generation-input-group > .meta-autocomplete-wrap > .input-group > input {
        border: none;
    }
    .next-generation-input-body {
        background-color: #f3f7e8;
        float: left;
        height: inherit;
        padding: 8px;
        padding-top: 5px;
    }
    .next-generation-input-label.green {
        background-color: #e1ebcb;
        border-left: 5px solid #abc66a;
    }    
    .next-generation-input-label.green label {
        color: #666;
        font-size: 11px;
    }
    .next-generation-input-label.green .checker {
        margin-top: 0px !important;
        margin-left: 0px !important;
    }
    .next-generation-input-body.green {
        background-color: #f3f7e8;
    }
    .next-generation-input-label.blue {
        background-color: #c5e7f0;
        border-left: 5px solid #57bcd3;
    }    
    .next-generation-input-body.blue {
        background-color: #e4f5fa;
    }
    .next-generation-input-label.orange {
        background-color: #fbdfc4;
        border-left: 5px solid #f79b37;
    }    
    .next-generation-input-body.orange {
        background-color: #fef1e3;
    }
    .next-generation-input-label > .next-generation-input-group {
        position: absolute;
        bottom: 8px;
    }       
    .groupDepartmentId_<?php echo $this->uniqId; ?> {
        padding-right: 8px;
    }       
    .next-generation-input-label .input-group-btn {
        width: 0 !important;
        padding-right: 8px;
    }       
    .salarySheetActions {
        margin-top: -8px;
    }
    @media screen and (min-width: 1050px) {
        .input-icon.right .form-control {
            padding-right: 0px;
            margin-right: 85px;
        }
    }
    /* Custom Card CSS End */
    .select2-container-multi .select2-choices {
        min-height: 24px;
        height: 24px !important;
        padding-top: 0;
    }
    .select2-container-multi .select2-choices .select2-search-choice {
        margin: 1px 0 3px 1px;
    }
    .existSalaryBook {
        font-style: italic;
        color: #e80505; 
        text-align: center; 
        display: none; 
        position: relative; 
        top: 8px;        
        font-size: 12px;
    }
    .select2-container-multi .select2-choices .select2-search-field input {    
        padding: 2px;
    }
    .page-footer {
        height: 0px !important;
    }
    .jeasyuiTheme3 .datagrid-row {
      height: 25px;	
    }    
</style>

<script type="text/javascript">
    var configNumDec = '<?php echo Config::getFromCacheDefault('CONFIG_TNA_NUMBER_DEC', null, '2'); ?>';
    
    function sheetNumberFormatter_<?php echo $this->uniqId; ?>(val, row, index) {
        if(typeof val === 'undefined')
            return;

        var value = 0;
        if (val !== null && val !== '') {
            value = val;
        }
        value = value.toString();
        var html = pureNumberFormat(parseFloat(value).toFixed(configNumDec));

        return html;
    };    
</script>
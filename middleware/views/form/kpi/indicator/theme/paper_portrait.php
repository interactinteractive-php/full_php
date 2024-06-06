<div class="kpi-form-paper-portrait">
    <div class="kpi-form-paper-portrait-child">
        
        <?php 
        $titleClass = '';
        
        if ($this->logoImage && !issetParam($this->additionalInfo['LOGO_FILE'])) {
            $this->additionalInfo['LOGO_FILE'] = $this->logoImage;
        }
        
        if (isset($this->additionalInfo) && $this->additionalInfo) {
            
            $headerTxt = issetParam($this->additionalInfo['HEADER_TEXT']);
            $logoFile = issetParam($this->additionalInfo['LOGO_FILE']);
            
            if ($headerTxt) {
                echo '<div class="kpi-form-paper-header-text">'.$headerTxt.'</div>';
                $titleClass = 'kpi-form-paper-title';
            }
            
            if ($logoFile && file_exists($logoFile)) {
                echo '<img src="'.$logoFile.'" class="kpi-form-paper-logo">';
                $titleClass = 'kpi-form-paper-title';
            }
        }
        ?>
        
        <h1 class="<?php echo $titleClass; ?>"><?php echo Str::nlTobr($this->title); ?></h1>
        
        <?php 
        echo $this->form; 
        
        $bgImage = 'middleware/assets/img/process/background/paperclip.png';
        
        if (file_exists($this->bgImage)) {
            $bgImage = $this->bgImage;
        }
        ?>
    </div>
</div>

<style type="text/css">
.kpi-form-paper-portrait {
    background-image: url("<?php echo $bgImage; ?>"); 
    background-repeat: no-repeat; 
    background-position: top center;
    background-attachment: fixed;
    background-color: #ededed;
    margin: -10px -15px;
    padding-top: 11px;
    padding-bottom: 20px;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child {
    position: relative;
    width: 1040px;
    min-height: calc(100vh - 126px);
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;
    background: #FFF;
    padding: 20px;
    box-shadow: 0px 2px 6px 0 rgba(0,0,0,.5);
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-title {
    margin-bottom: -14px;
    /*margin-top: 10px;*/
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-logo {
    position: absolute;
    max-width: 150px;
    max-height: 50px;
    top: 8px;
    left: 20px;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-header-text {
    position: absolute;
    max-width: 180px;
    max-height: 70px;
    top: 20px;
    right: 20px;
    text-align: right;
    line-height: 14px;
}
.kpi-form-paper-portrait h1 {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    line-height: 28px;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
    max-width: 500px;
}
.kpi-form-paper-portrait .mv-main-tabs {
    margin-top: 1.25rem;
}
/*.kpi-form-paper-portrait .kpi-hdr-table .kpi-hdr-table-label {
    background-color: #fbd9a5 !important;
}*/
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-webkit-input-placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-ms-input-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait .bp-overflow-xy-auto {
    border: 0;
}
.kpi-form-paper-portrait table.kpi-dtl-table td, 
.kpi-form-paper-portrait table.kpi-dtl-table th {
    border: 1px solid transparent;
}
.kpi-form-paper-portrait table.kpi-dtl-table td {
    border-bottom: 1px #eee solid;
    border-right: 1px #eee solid;
    background-color: #fff;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr th {
    border-top: 2px #e9a22f solid !important;
    border-bottom: 2px #e9a22f solid !important;
    background: #fff !important;
    font-weight: bold;
    font-size: 13px!important;
}
.kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a.active {
    border-bottom: 2px solid #e9a22f !important;
    color: #e9a22f;
}
.kpi-form-paper-portrait .tabbable-line>.nav-tabs>li.open, 
.kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a:hover {
    border-bottom: 2px solid #e9a22f85;
    color: #e9a22f85;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link {
    background-color: #f5f5f5;
    border-top: 1px #ddd solid;
    border-left: 1px #ddd solid;
    border-bottom: 1px #ddd solid;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li:last-child a.nav-link {
    border-right: 1px #ddd solid;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link.active {
    background-color: #fff;
    border-top: 1px transparent solid;
    border-bottom: 1px solid transparent!important;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link:before {
    height: 2px;
    top: -1px;
    left: -1px;
    right: -1px;
    content: '';
    position: absolute;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link.active:before {
    background-color: #e9a22f;
}
.kpi-notfocus-readonly-input {
    cursor: text!important;
    background-color: inherit!important;
}
.kpi-notfocus-readonly-input:focus, 
table.table td.stretchInput input.kpi-notfocus-readonly-input:not(.select2-input):not(.error):focus {
    border: 1px solid transparent !important;
}
input.kpi-notfocus-readonly-input::-webkit-input-placeholder {
    color: transparent !important;
}
input.kpi-notfocus-readonly-input:-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input:-ms-input-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::placeholder {
    color: transparent !important;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice {
    background-color: #fff;
    background-image: none;
    border: 1px solid #fff;
    cursor: text;
    padding-left: 3px;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice .select2-arrow {
    display: none;
}
.kpi-form-paper-portrait table.table td.stretchInput input[type="text"]:not(.select2-input):not(.error):focus, 
.kpi-form-paper-portrait table.table td.stretchInput textarea:not(.error):focus {
    border: 1px solid #e9a22f !important;
}
.mv-rows-title .label-colon {
    display: none;
}
.mv-inline-field {
    display: inline-block;
    padding-left: 10px;
}
<?php
if ($this->kpiTypeId == '2013' && Config::getFromCache('IS_MV_ACTIVE_INPUT_COLOR')) {
?>
.kpi-form-paper-portrait input[name][type="text"][data-path]:not(:read-only), 
.kpi-form-paper-portrait input.bigdecimalInit[type="text"][data-path]:not(:read-only), 
.kpi-form-paper-portrait input.lookup-code-autocomplete[name][type="text"]:not(:read-only), 
.kpi-form-paper-portrait input.lookup-name-autocomplete[name][type="text"]:not(:read-only), 
.kpi-form-paper-portrait textarea[name][data-path]:not(:read-only), 
.kpi-form-paper-portrait .mv-ind-combo:not(.select2-container-disabled) .select2-choice, 
.kpi-form-paper-portrait .mv-ind-combo:not(.select2-container-disabled) .select2-choices {
    border: 2px #179d81 solid!important;
}
.kpi-form-paper-portrait textarea.description_autoInit {
    min-height: 28px!important;
}
<?php
}
?>
</style>
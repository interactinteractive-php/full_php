<?php
if (!$this->isAjax) {
    ?>
    <div class="col-md-12">
        <div class="card light shadow">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title">
                    <i class="fa fa-pencil-square"></i> <?php echo $this->title; ?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="fullscreen"></a>
                </div>
            </div>
            <div class="card-body">
<?php
}
?>  
            <div>     
                <div class="row" id="repFinConfigWindow_<?php $this->uniqId; ?>">
                    <div class="col-md-12 center-sidebar">
                        <form id="repFinConfigForm_<?php echo $this->uniqId; ?>" class="form-horizontal xs-form" method="post">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group row fom-row">
                                            <?php
                                            echo Form::label(array(
                                                'text' => 'Тэблийн нэр', 'for' => 'repFinTable', 'class' => 'customLabel col-md-4 text-right',
                                                'required' => 'required'));
                                            ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo Form::select(
                                                    array(
                                                        'name' => 'repFinTable',
                                                        'id' => 'repFinTable',
                                                        'class' => 'form-control select2 form-control-sm',
                                                        'data' => $this->repFinList,
                                                        'op_value' => 'id',
                                                        'op_text' => 'reportname',
                                                        'required' => 'required'
                                                    )
                                                );
                                                ?>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12 jeasyuiTheme3 mt5">
                                <table class="no-border" id="repFinConfigDatagrid_<?php echo $this->uniqId; ?>" style="width: 100%;"></table>
                            </div>
                        </div>
                    </div>
                </div>        
            </div>
<?php
if (!$this->isAjax) {
?>       
            </div>
        </div>
    </div>
<?php
}
?>

<script type="text/javascript">
    var PL_0130 = "<?php echo $this->lang->line('PL_0130'); ?>";
    var MET_99990771 = "<?php echo $this->lang->line('MET_99990771'); ?>";
    var MET_99990770 = "<?php echo $this->lang->line('MET_99990770'); ?>";
    var PL_0239 = "<?php echo $this->lang->line('PL_0239'); ?>";
    var lname_globecode = "<?php echo $this->lang->line('lname'); ?>";
    var fname_globecode = "<?php echo $this->lang->line('fname'); ?>";
    var code_globecode = "<?php echo $this->lang->line('code'); ?>";
    var template_excel_output = "<?php echo $this->lang->line('template_excel_output'); ?>";
    var varWindowId = "<?php echo $this->uniqId; ?>";
    
    /**
     * Binding Class
     */
    
    $.ajax({
        url: "middleware/assets/js/repFinConfig/rep-fin-config.js",
        dataType: "script",
        cache: false,
        async: false
    }).done(function(){
        window['salaryObj' + varWindowId] = new FinReportConfig('<?php echo $this->uniqId; ?>');
        window['salaryObj' + varWindowId].initEventListener();
    });
    
    function selectedEmployeeSalary(metaDataCode, chooseType, elem, rows) {
        window['salaryObj' + varWindowId].selectedEmployeeSalary(rows);
    }
    
</script>
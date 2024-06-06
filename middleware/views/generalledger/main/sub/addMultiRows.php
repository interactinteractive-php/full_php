<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
echo Form::create(array('class' => 'form-horizontal', 'id' => 'gl-multi-add-rows-form', 'method' => 'post', 'autocomplete' => 'off')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Багцын дугаар', 'for' => 'gmSubId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-3">
            <?php 
            echo Form::text(
                array(
                    'name' => 'gmSubId', 
                    'id' => 'gmSubId', 
                    'class' => 'form-control form-control-sm longInit', 
                    'required' => 'required', 
                    'value' => '1'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Данс', 'for' => 'gmAccountId_displayField', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <div class="meta-autocomplete-wrap" data-section-path="gmAccountId">
                <div class="input-group double-between-input">
                    <input type="hidden" name="gmAccountId" id="gmAccountId_valueField" data-path="gmAccountId" class="popupInit">
                    <input type="text" name="gmAccountId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="gmAccountId" id="gmAccountId_displayField" data-processid="1454315883636" data-lookupid="1454379109682" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered mr0" onclick="dataViewSelectableGrid('gmAccountId', '1454315883636', '1454379109682', 'single', 'gmAccountId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="gmAccountId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="gmAccountId" id="gmAccountId_nameField" data-processid="1454315883636" data-lookupid="1454379109682" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                    </span>   
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Дүн', 'for' => 'gmAmount', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-3">
            <?php 
            echo Form::text(
                array(
                    'name' => 'gmAmount', 
                    'id' => 'gmAmount', 
                    'class' => 'form-control form-control-sm bigdecimalInit', 
                    'required' => 'required'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Мөрийн тоо', 'for' => 'gmRowCount', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-3">
            <?php 
            echo Form::text(
                array(
                    'name' => 'gmRowCount', 
                    'id' => 'gmRowCount', 
                    'class' => 'form-control form-control-sm longInit', 
                    'required' => 'required', 
                    'value' => $this->rowCount
                )
            ); 
            ?>
        </div>
    </div>
    <fieldset class="collapsible">
        <legend>Үзүүлэлт</legend>
        <div id="account-meta-div"></div>
    </fieldset>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
var gmIsDebit = 0;    
$(function(){
    
    $(document.body).on('change', 'input[data-path="gmAccountId"]', function(){
        
        var $this = $(this);
        var accountId = $this.val();
        var row = JSON.parse($this.attr('data-row-data'));
        
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glbookNumber = $("input[name='glbookNumber']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var gldescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        if (typeof bookDate === 'undefined') {
            bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            glbookNumber = $("input[name='hidden_glbookNumber']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            gldescription = $("input[name='hidden_gldescription']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }
        var glbookId = $("input[name='glbookId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        var glBookTypeId = $("input[name='glBookTypeId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        
        var selectedRow = {
            'bookid': glbookId,
            'booktypeid': glBookTypeId,
            'bookdate': bookDate,
            'booknumber': glbookNumber,
            'description': gldescription,
            'accountid': accountId,
            'accountcode': row.accountcode,
            'accountname': row.accountname,
            'accounttypeid': row.accounttypeid,
            'accounttypecode': row.accounttypecode,
            'subid': $('#gmSubId').val(),
            'usedetail': row.isusedetailbook,
            'objectid': row.objectid,
            'invoices': '',
            'defaultinvoices': '',
            'isdebit': 1,
            'customerid': '',
            'dtlid': '',
            'detailvalues': ''
        };
        
        var $opMetaAttr = $('#glDtl tr[data-sub-id="'+selectedRow.subid+'"][data-op-meta]:not([data-op-meta=""]):eq(0)', glBpMainWindow_<?php echo $this->uniqId; ?>);
        
        if ($opMetaAttr.length) {
            var $opMetaAttrIsDebit = $opMetaAttr.find("input[name='gl_isdebit[]']").val();
            selectedRow['isdebit'] = ($opMetaAttrIsDebit == '1' ? 0 : 1);
            gmIsDebit = selectedRow['isdebit'];    
        }
        
        var $rowElem = $('<tr data-op-meta=""/>');
        
        var opMeta = fillOpMeta_<?php echo $this->uniqId; ?>($rowElem, selectedRow['accountid'], selectedRow['subid'], selectedRow['isdebit']);
        if (opMeta !== '') {
            selectedRow['opMeta'] = opMeta;
        } else {
            selectedRow['opMeta'] = 'cashFlowSubCategoryId';
        }

        if (checkAccountTypeId_<?php echo $this->uniqId; ?>($rowElem, selectedRow['accountid'], selectedRow['subid'], selectedRow['isdebit'])) {
            selectedRow['checkAccountTypeId'] = 1;
        }
        
        $.ajax({
            type: 'post',
            url: 'mdgl/getAccountDtlMeta',
            data: {selectedRow: selectedRow, paramData: paramGLData_<?php echo $this->uniqId; ?>, isIgnoreExp: true},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                if (data.isemptymeta != '1') {
                    $('#account-meta-div').empty().append(data.html);
                    Core.initSelect2($('#account-meta-div'));
                }
                Core.unblockUI();
            }
        });
        
    });
});    
</script>    
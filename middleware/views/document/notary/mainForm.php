<div class="erl-parent" id="windowid-erl-<?php echo $this->uniqid; ?>" data-id="<?php echo $this->id; ?>" data-name="<?php echo $this->name; ?>" data-prepare-file-count="<?php echo issetVar($this->row['preparedfilecount']); ?>">    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-7">
                <div class="btn-group btn-group-devided">
                    <button type="button" class="btn btn-sm btn-circle blue" onclick="erlSaveContentParams(this, '<?php echo $this->saveProcessCode; ?>', '<?php echo $this->refStructureId; ?>');"><i class="fa fa-save"></i> Хадгалах</button>                    
                </div>
            </div>
            <div class="col-md-5">
                <span class="workflow-buttons-<?php echo $this->id; ?> float-right"></span>
            </div>
        </div>
    </div>

    <table style="table-layout: fixed; width: 100%; border: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 720px; vertical-align: top; padding: 0 10px 0 10px;">
                    <table class="table table-sm mb0">
                        <thead>
                            <tr>
                                <th style="width: 23px">№</th>
                                <th style="width: 87px">Файлын нэр</th>
                                <th style="width: 200px">Нотлох баримтын төрөл</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="ecl-height">
                        <form>
                            <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 mb0 erl-content-tbl">
                                <tbody>
                                    <?php echo $this->fileRender; ?>
                                </tbody>
                            </table>
                            <?php echo $this->hiddenInputs; ?>
                        </form>
                    </div>
                    <hr style="margin: 8px 0;">
                    <div class="mt5" style="font-size: 11px;">Бэлтгэсэн: <strong><?php echo issetVar($this->row['preparedfilecount']); ?></strong> &nbsp;&nbsp;&nbsp;Скандсан: <strong id="erl-file-count"><?php echo $this->fileCount; ?></strong></div>
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<style type="text/css">
    .erl-image-preview {
        padding: 20px;
        overflow: auto;
        border: 1px #999 solid;
        border-top: 0;
        border-bottom: 0;
        border-right: 0;
        background: #ccc;
        text-align: center;
    }    
    .erl-image-preview img {
        -webkit-box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        -moz-box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        max-width: 100%;
    }
    .ecl-height {
        overflow: auto;
    }
    .erl-content-tbl > tbody > tr {
        cursor: pointer;
    }
    .erl-content-tbl > tbody > tr.selected-row > td {
        background-color: #a8d3f3;
    }
    table.bprocess-theme1 > tbody > tr > td {
        padding-left: 3px !important;
    }
    
    div[aria-describedby="dialog-changeWfmStatus-1540202714291"] {
        z-index: 1183 !important;
    }

</style>

<script type="text/javascript">

    var $erlWindow_<?php echo $this->uniqid; ?> = $('#windowid-erl-<?php echo $this->uniqid; ?>');        
    $(function() {
        
        var inputOldVal = '';
        
        $erlWindow_<?php echo $this->uniqid; ?>.on('focus', '.erl-bookdate', function(){
            var $this = $(this);
            inputOldVal = $this.val();
            $this.inputmask('y-m-d');
        });

        $erlWindow_<?php echo $this->uniqid; ?>.on('change', '.erl-bookdate', function(e){
            var $t = $(this), _thisVal = $t.val(), $row = $t.closest('tr'),
                trindex = $row.index(), setVal = false;
                
            var $trLoop = $('.erl-content-tbl > tbody > tr');
            var dte = new Date(_thisVal);
                
            if (dte < new Date('1990-01-01') || dte > new Date()) {
                $t.val('');
                return;
            }

            $t.val(_thisVal);
            $trLoop.each(function(k, v){
                var $this = $(this);
                if (k > trindex) {
                    if (($this.find('input[name="bookDate[]"]').val() == '' || $this.find('input[name="bookDate[]"]').val() == inputOldVal) && !setVal) {              
                        $this.find('input[name="bookDate[]"]').val(_thisVal);
                    } else {
                        setVal = true;
                    }
                }
            });
        });        
        
        var row = <?php echo $this->rowJson; ?>;
          
    });
</script>
<style  type="text/css">
    div[aria-describedby="dialog-changeWfmStatus-<?php echo $this->metaDataId ?>"] {
        z-index: 1052;
    }
</style>
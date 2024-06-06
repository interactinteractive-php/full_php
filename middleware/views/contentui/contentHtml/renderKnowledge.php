<?php if ($this->isRender) { ?>
    <div class="knowlegde_<?php echo $this->uniqId ?>">
<?php } ?>
    <style>
        .tempEditor {
            display: inline-block !important;
        }
    </style>
    <span style="line-height: 14px;
        
        font-size: 22px;
        text-transform: uppercase;
        font-weight: 700;
        border-bottom: 3px solid #FF5722;
        margin-top: 2px;"><?php echo $this->processName.' '. ' ТУСЛАМЖ' ?></span>
    <div id="accordion1" class="panel-group">
        <?php
        if ($this->contentData) {
            
            foreach ($this->contentData as $key => $row) {
                $rowJson = htmlentities(json_encode(Arr::changeKeyLower($row)), ENT_QUOTES, 'UTF-8');
            ?>
                <div class="panel panel-default mt20" style="border-radius: 0; border: none !important;">
                    <div class="panel-heading knowledge_<?php echo $this->uniqId ?>" style="padding: 0; padding-bottom: 5px; background-color: #FFF; border: none !important;" data-row="<?php echo $rowJson; ?>">
                        <h4 class="panel-title" style="font-weight: 600; font-size: 14px; ">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_<?php echo $row['KNOWLEDGE_ID'] ?>" data-knowledgeid="<?php echo $row['KNOWLEDGE_ID'] ?>" style="    color: #FF5722; margin-left: 10px;"><i class="fa fa-chevron-right mr10"></i><?php echo $row['KNOWLEDGE_NAME'] ?> </a>
                        </h4>
                    </div>
                    <div id="accordion1_<?php echo $row['KNOWLEDGE_ID'] ?>" class="panel-collapse collapse <?php echo ($key===0) ? 'in' : '' ?>" aria-expanded="<?php echo ($key===0) ? 'true' : 'false' ?>">
                        <div class="panel-body" style=" font-size: 15px;">
                            <div class="row">
                                <div class="col-md-12"><?php echo Str::htmltotext($row['KNOWLEDGE_DESCRIPTION']) ?></div>
                            </div>
                            <div class="row mt20">
                                <?php if ($row['CONTENTS']) { ?>
                                    <div class="col-md-12 pl0 pr0">
                                        <div class="mt10">
                                            <?php 
                                                foreach ($row['CONTENTS'] as $key => $srow) {
                                                    echo '<a href="javascript:;" title="'. $srow['ATTACH_NAME'] .'" onclick="dataViewFileViewer(this, \'1\', \''. $srow['FILE_EXTENSION'] .'\', \''. $srow['ATTACH'] .'\', \'http://192.168.100.68/new_portal/' . $srow['ATTACH'] .'\', \'undefined\')" class="active" >'
                                                            . '<div class="knowLedgeFileClass">'
                                                                . '<img alt="'. $srow['ATTACH_NAME'] .'" src="assets/core/global/img/grid_layout/'.$srow['FILE_EXTENSION'] .'.png" height="50px">'
                                                                . '<span>' . $srow['ATTACH_NAME'] .'</span>'
                                                            . '</div>'
                                                        .'</a>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php  }
        } ?>
    </div>
    <script type="text/javascript">
        $(function () {
            $.contextMenu({
                selector: '.knowledge_<?php echo $this->uniqId ?>', 
                callback: function (key, opt) {
                    var $elem = $(this);
                    if (key === 'delete') {
                        var mainRow = $(this).attr('data-row');
                        transferProcessAction('', '1487297305245', '1455285318408', '200101010000011', 'toolbar', this, {callerType: 'KNOWLEDGE_DV_BP_HELP'}, undefined, undefined, mainRow, undefined, '', undefined, undefined, 'callbackfunction_<?php echo $this->uniqId ?>');
                    }
                    if (key === 'edit') {
                        var mainRow = $(this).attr('data-row');
                        transferProcessAction('', '1487297305245', '1487297310941', '200101010000011', 'toolbar', this, {callerType: 'KNOWLEDGE_DV_BP_HELP'}, undefined, undefined, mainRow, undefined, undefined, undefined, undefined, 'callbackfunction_<?php echo $this->uniqId ?>');
                    }
                },
                items: {
                    "edit": {name: "Засах", icon: "edit"},
                    "delete": {name: "Устгах", icon: "trash"}
                }
            });
        });
    </script>
<?php if ($this->isRender) { ?>
</div>
<script type="text/javascript">
    function callbackfunction_<?php echo $this->uniqId ?> () {
        $.ajax({
            type: 'post',
            url: 'mdcontentui/contentHtmlRender/1',
            data: {
                processMetaDataId: '<?php echo $this->processMetaDataId ?>',
                processName:  '<?php echo $this->processName ?>',
                renderHtml: '1',
            },
            beforeSend: function() {
              Core.blockUI({
                  message: 'Loading...', 
                  boxed: true
              });
            },
            dataType: 'json',
            success: function(response) {
                $('.knowlegde_<?php echo $this->uniqId ?>').html(response.html);
            }
        }).complete(function() {
            Core.unblockUI();
        });
        ;
    }
</script>
<?php } ?>
<style type="text/css">
    .knowLedgeFileClass {
        float: left;
        width: 60px;
    }
    .knowLedgeFileClass span {
        height: 20px;
        overflow: hidden;
        word-wrap: break-word;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        float: left;
        width: 100%;
        font-size: 12px;
        
    }
</style>
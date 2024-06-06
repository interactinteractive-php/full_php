<?php
if ($this->packageChildMetas) {
    
    $content = Form::button(
        array(
            'class' => 'btn btn-light btn-sm float-right', 
            'value' => '<i class="icon-sync"></i> '.$this->lang->line('refresh_btn'), 
            'onclick' => 'packageReload(this);'
        ), issetParam($this->row['IS_REFRESH'])
    );
    
    $content .= Form::button(
        array(
            'class' => 'btn btn-light btn-sm float-right', 
            'value' => '<i class="icon-printer"></i> '.$this->lang->line('print_btn'), 
            'onclick' => 'packagePrint(this);'
        ), issetParam($this->row['IS_EXPORT'])
    );
    
    foreach ($this->packageChildMetas as $k => $row) {
        
        if ($row['OPEN_BP_COUNT'] != 0) {
            $labelName = '';
        } else {
            $labelName = $this->lang->line((new Mdobject())->getNameByType($row['META_DATA_ID'], $row['META_TYPE_ID'], $row['META_DATA_NAME']));
        }
        $conditionForPackage = '';

        if (isset($row['PARAM_PATH']) && strpos($row['PARAM_PATH'], '#condition#') !== false) {
            $conditionForPackage = str_replace('#condition#', '', $row['PARAM_PATH']);
        }
        
        $content .= '<a href="#package-tab-'.$row['META_DATA_ID'].'" data-metadataid="'.$row['META_DATA_ID'].'" data-metatypeid="'.$row['META_TYPE_ID'].'" data-metadatacode="'.$row['META_DATA_CODE'].'" class="hide" '
                    . ' data-package-condition="'.$conditionForPackage.'" data-packagecode="'.$this->packageCode.'">'.$labelName.'</a>';
        $content .= '<div class="package-div">';
        
        if (isset($this->isIgnorePackTitle) && $this->isIgnorePackTitle != '1') {
            $content .= ($labelName !== '&nbsp;' ? '<div class="package-tab-name">'.$labelName.'</div>' : '');
        }
        
        $content .= '<div class="package-tab" id="package-tab-'.$row['META_DATA_ID'].'"></div></div>';
    }
    
    echo $content;
?>

<script type="text/javascript">
    var package_metaDataId = <?php echo $this->metaDataId; ?>;
    $(function() {
        <?php if (isset($this->workSpaceId)) { ?>
            renderCondition_<?php echo $this->workSpaceId; ?> ('<?php echo $this->metaDataId; ?>', 'show');
        <?php } else { ?>
            $("div#package-meta-<?php echo $this->metaDataId; ?>").find('a[data-metadataid]').each(function() {
                var $this = $(this);
                var metadataid = $this.attr('data-metadataid');
                var metatypeid = $this.attr('data-metatypeid'),
                    pCondition = $this.attr('data-package-condition');

                if ($this.closest("div.ws-area").length > 0 && pCondition != '') {
                    var wsArea = $this.closest("div.ws-area");
                    workSpaceParams = $('#workspace-id-<?php echo $this->workSpaceId ?>').find("div.ws-hidden-params", wsArea).find("input[type=hidden]").serializeArray();

                    for (var fdata = 0; fdata < workSpaceParams.length; fdata++) {
                        var mPath = /workSpaceParam\[([\w.]+)\]/g.exec(workSpaceParams[fdata].name);
                        if(mPath === null) continue;

                        var regExp = new RegExp(mPath[1], "g"), criVal = null;
                        if (workSpaceParams[fdata].value) {
                            criVal = workSpaceParams[fdata].value;
                        }
                        pCondition = pCondition.trim().replace(regExp, criVal);
                    }
                }

                if (pCondition != '' && eval(pCondition)) {
                    packageRenderType(metadataid, metatypeid, this, '', '<?php echo $this->metaDataId; ?>');
                } else if (pCondition != '' && !eval(pCondition)) {
                    $this.closest('.package-meta').find('div'+$this.attr('href')).parent().hide();
                } else if (pCondition == '') {
                    packageRenderType(metadataid, metatypeid, this, '', '<?php echo $this->metaDataId; ?>', '', '<?php echo $this->drillDownDefaultCriteria; ?>');
                }
            });
        <?php } ?>
    });
    
    function renderCondition_<?php echo $this->workSpaceId; ?> ($mainMetaDataId, $showType) {
        
        $("div#package-meta-<?php echo $this->metaDataId; ?>").find('a[data-metadataid]').each(function() {
            var $this = $(this);
            var metadataid = $this.attr('data-metadataid');
            var metatypeid = $this.attr('data-metatypeid'),
                pCondition = $this.attr('data-package-condition');

            if (pCondition) {
                
                if ($this.closest("div.ws-area").length > 0 && pCondition != '') {
                    var wsArea = $this.closest("div.ws-area");
                    workSpaceParams = $("div.ws-hidden-params", wsArea).find("input[type=hidden]").serializeArray();

                    for (var fdata = 0; fdata < workSpaceParams.length; fdata++) {
                        var mPath = /workSpaceParam\[([\w.]+)\]/g.exec(workSpaceParams[fdata].name);
                        if(mPath === null) continue;

                        var regExp = new RegExp(mPath[1], "g"), criVal = null;
                        if (workSpaceParams[fdata].value) {
                            criVal = workSpaceParams[fdata].value;
                        }

                        pCondition = pCondition.trim().replace(regExp, criVal);
                    }
                }

                $this.closest('.package-meta').find('div' + $this.attr('href')).hide();

                if (pCondition != '' && eval(pCondition)) {
                    packageRenderType(metadataid, metatypeid, this, '', $mainMetaDataId, 'show');
                    $this.closest('.package-meta').find('div' + $this.attr('href')).show();
                } else if (pCondition != '' && !eval(pCondition)) {
                    packageRenderType(metadataid, metatypeid, this, '', $mainMetaDataId, 'hide');
                } else if (pCondition == '') {
                    $this.closest('.package-meta').find('div' + $this.attr('href')).show();
                    packageRenderType(metadataid, metatypeid, this, '', $mainMetaDataId, $showType);
                }
                
            } else {
                packageRenderType(metadataid, metatypeid, this, '', $mainMetaDataId, 'show', '<?php echo $this->drillDownDefaultCriteria; ?>', undefined, html_entity_decode('<?php echo issetParam($this->uriParams) ?>', 'ENT_QUOTES'));
            }
            
        });
    }
    
</script>

<style type="text/css">
    .web-process .merge-column .merge-column-content .row {
        display: inherit;
    }
</style>
<?php    
}
?>
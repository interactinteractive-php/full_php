<div class="kpiFormSection col-md-12 mb10">
    <?php 
    echo $this->templateName; 
    
    $tabAddonLi = $tabAddonPane = '';
    
    if (isset($this->graphTabs) && $this->graphTabs) {
        
        foreach ($this->graphTabs as $gId => $graphTab) {
            
            $tabAddonLi .= '<li class="nav-item"><a href="#kpi-hform'.$this->templateId.'-tab'.$gId.'" class="nav-link p-2" data-toggle="tab">'.$graphTab['tabName'].'</a></li>';
            $tabAddonPane .= '<div class="tab-pane fade" id="kpi-hform'.$this->templateId.'-tab'.$gId.'">'.$graphTab['control'].'</div>';
        }
    }
    
    if (isset($this->defaultTemplateForm)) {
        $tabAddonLi .= '<li class="nav-item"><a href="#kpi-hform'.$this->templateId.'-tab3" class="nav-link p-2" data-toggle="tab" data-langcode="'.$this->lang->getCode().'">'.$this->lang->line('kpi_defaultTpl_title').'</a></li>';
        $tabAddonPane .= '<div class="tab-pane fade" id="kpi-hform'.$this->templateId.'-tab3">'.$this->defaultTemplateForm.'</div>';
    }
    ?>
    <div id="kpiDmDtl-<?php echo $this->templateId; ?>" data-parent-path="kpiDmDtl" class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs nav-tabs-bottom mb-2 <?php echo (Mdform::$defaultTplId ? 'd-none' : ''); ?>">
                <li class="nav-item"><a href="#kpi-hform<?php echo $this->templateId; ?>-tab1" class="nav-link p-2 active" data-toggle="tab"><?php echo $this->lang->line('kpi_tab_general'); ?></a></li>
                
                <?php
                if ($this->objectTabs || $this->relationList) {
                ?>
                <li class="nav-item"><a href="#kpi-hform<?php echo $this->templateId; ?>-tab2" class="nav-link p-2" data-toggle="tab"><?php echo $this->lang->line('kpi_tab_relation'); ?></a></li>
                <?php 
                } 
                
                echo $tabAddonLi; 
                ?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active <?php echo (Mdform::$defaultTplId ? 'pl0 pr0' : ''); ?>" id="kpi-hform<?php echo $this->templateId; ?>-tab1">
                    <table style="border: none; width: 100%" data-table-path="kpiDmDtl" data-kpi-code="<?php echo $this->templateCode; ?>" data-group-path="<?php echo Mdform::$pathPrefix; ?>">
                        <tbody>
                            <?php echo $this->gridBody; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php
                if ($this->objectTabs || $this->relationList) {
                ?>
                
                <div class="tab-pane fade" id="kpi-hform<?php echo $this->templateId; ?>-tab2">
                    <?php
                    
                    $objectTabRender = '';
                    
                    if ($this->objectTabs) {

                        $objectTabRender .= '<div class="tabapp">';

                        foreach ($this->objectTabs as $objectTab) {
                            
                            $objectTabRender .= '<div class="col reldetail">';
                                $objectTabRender .= '<div class="d-flex align-items-center align-items-md-start flex-column flex-md-row" style="border-bottom: 1px #ddd solid;">';
                                    $objectTabRender .= '<h5 class="reltitle line-height-normal" style="-ms-flex: 1;flex: 1;">'.$this->lang->line($objectTab['tabName']).'</h5>';
                                    $objectTabRender .= '<a href="javascript:;" onclick="addChooseKpiObjectData(this);" data-objtype-action="add"><i class="icon-plus3 relicon"></i></a>';
                                $objectTabRender .= '</div>';
                                $objectTabRender .= $objectTab['control'];
                            $objectTabRender .= '</div>';
                        }

                        $objectTabRender .= '</div>';
                    }

                    if ($this->relationList) {

                        $checkGroupKey = array();
                        $tabPane = '';

                        foreach ($this->relationList as $relation) {
                            
                            $subTmpId = (new Mdform_Model)->getTemplateIdByDtlId($relation['SRC_TEMPLATE_DTL_ID']);
                            $tr = '';
                            
                            $showName = $relation['NAME'];
                            $relation['NAME'] = htmlentities($relation['NAME'], ENT_QUOTES, 'UTF-8');
                            
                            if ($relation['NAME']) {
                                
                                $kpiControlIndex = issetDefaultVal($checkGroupKey[$relation['TEMPLATE_NAME']], Mdform::$kpiControlIndex);
                                $count = '<i class="fa fa-tag bgicon"></i>';
                                
                                if (issetParam($relation['SHOW_COUNT'])) {
                                    $countArr = explode(',', $relation['SHOW_COUNT']);
                                    $count = '<button type="button" class="bgicon border-0 font-weight-bold">'.Number::amount($countArr[0]).'</button>';
                                }
                                
                                if ($relation['IS_SHOW_ONLY'] != '1') {
                        
                                    $tr .= '<tr tr-status="0" data-basketrowid="'.$relation['SRC_OBJECT_ID'].'" data-relationid="'.$relation['E_ID'].'">';

                                        $tr .= '<td style="height: 25px; max-width: 0;" class="text-left text-truncate" title="'.$relation['NAME'].'">';
                                            $tr .= '<input type="hidden" name="param['.Mdform::$pathPrefix.'kpiDmDtl.fact1]['.$kpiControlIndex.'][]" data-path="'.Mdform::$pathPrefix.'kpiDmDtl.fact1" value="'.$relation['TRG_OBJECT_ID'].'~~~'.$relation['NAME'].'~~~'.$relation['SRC_TEMPLATE_ID'].'~~~'.$relation['MART_ID'].'|'.$relation['E_ID'].'">';
                                            $tr .= '<input type="hidden" name="param['.Mdform::$pathPrefix.'kpiDmDtl.subTmpKeyId]['.$kpiControlIndex.'][]" data-path="'.Mdform::$pathPrefix.'kpiDmDtl.subTmpKeyId" value="'.$relation['SRC_OBJECT_ID'].'">';
                                            $tr .= '<a href="javascript:;" onclick="drillLinkKpiMenu(\''.$relation['SRC_OBJECT_ID'].'\');">'.$count.$showName.'</a>';
                                        $tr .= '</td>';

                                        $tr .= '<td style="width: 60px" class="text-right" data-dmmartid="'.$relation['MART_ID'].'">';
                                            if ($subTmpId) {
                                                $tr .= '<a href="javascript:;" onclick="bpKpiObjectSubTemplate(this, \''.$subTmpId.'\');" class="mr10 font-size-14"><i style="color:#5c6bc0;" class="fa fa-external-link-square"></i></a> ';
                                            }
                                            $tr .= '<a href="javascript:;" onclick="deleteKpiObjectData(this);" data-objtype-action="remove" class="font-size-14"><i style="color:red" class="fa fa-trash"></i></a>';
                                        $tr .= '</td>';

                                    $tr .= '</tr>';
                                    
                                } else {
                                    
                                    $tr .= '<tr class="ea-tmp-row">';
                                        $tr .= '<td style="height: 25px; max-width: 0;" class="text-left text-truncate" title="'.$showName.'">';
                                            $tr .= '<a href="javascript:;" onclick="drillLinkKpiMenu(\''.$relation['SRC_OBJECT_ID'].'\');">'.$count.$showName.'</a>';
                                        $tr .= '</td>';
                                        $tr .= '<td style="width: 60px;" class="text-right">';
                                        $tr .= '</td>';
                                    $tr .= '</tr>';
                                }
                            }
                                            
                            if (!isset($checkGroupKey[$relation['TEMPLATE_NAME']])) {

                                $checkGroupKey[$relation['TEMPLATE_NAME']] = Mdform::$kpiControlIndex;
                                
                                if ($relation['IS_SHOW_ONLY'] != '1') {
                                    
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.mainRowCount][]" data-path="kpiDmDtl.rowCount">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.id]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.id" value="'.$relation['MART_ID'].'" data-field-name="id">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.indicatorId]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.indicatorId" value="'.$relation['INDICATOR_ID'].'" data-field-name="indicatorId">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.templateDtlId]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.templateDtlId" value="'.$relation['SRC_TEMPLATE_ID'].'" data-field-name="templateDtlId">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.rowState]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.rowState" value="modified" data-field-name="rowState">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.rootTemplateId]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.rootTemplateId" data-field-name="rootTemplateId">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.factType]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.factType" value="object">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.kpiTemplateId]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.kpiTemplateId" value="'.$relation['TRG_TEMPLATE_ID'].'">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.pdfColumnName]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.pdfColumnName" value="'.$relation['COLUMN_NAME'].'">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.defaultTplId]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.defaultTplId">';
                                    $tabPane .= '<input type="hidden" name="param[kpiDmDtl.dimensionId]['.Mdform::$kpiControlIndex.'][]" data-path="kpiDmDtl.dimensionId" value="100">';
                                    $tabPane .= '<input type="hidden" name="param['.Mdform::$pathPrefix.'kpiDmDtl.kpiObjectType]['.Mdform::$kpiControlIndex.'][]" data-path="'.Mdform::$pathPrefix.'kpiDmDtl.kpiObjectType" value="reverse"/>';
                                }
                                
                                $tabPane .= '<div class="col reldetail" style="background-color: #f1f8e9">';
                                    
                                    $tabPane .= '<div class="d-flex align-items-center align-items-md-start flex-column flex-md-row" style="border-bottom: 1px #ddd solid;">';
                                        $tabPane .= '<h5 class="reltitle line-height-normal" style="-ms-flex: 1;flex: 1;">';
                                            $tabPane .= $this->lang->line($relation['NAME1']); 
                                            $tabPane .= ' <span class="text-grey">'.$this->lang->line($relation['NAME2']).'</span> ';
                                            $tabPane .= $this->lang->line($relation['NAME3']);
                                        $tabPane .= '</h5>';
                                        $tabPane .= '<a href="javascript:;" data-name="param['.Mdform::$pathPrefix.'kpiDmDtl.fact1]['.Mdform::$kpiControlIndex.'][]" onclick="dataViewCustomSelectableGrid(\'kpiRelatedObjectList_Reverse\', \'multi\', \'chooseKpiObjectData\', \'criteriaCondition[trgTemplateId]==&param[trgTemplateId]='.$relation['SRC_TEMPLATE_ID'].'&criteriaCondition[filterId]==&param[filterId]='.Mdform::$recordId.'\', this, \'\', 1);" data-subtmpid="'.$subTmpId.'" data-trgobjid="'.$relation['TRG_OBJECT_ID'].'" data-objtype-action="add" data-kindex="'.Mdform::$kpiControlIndex.'"><i class="icon-plus3 relicon"></i></a>';
                                    $tabPane .= '</div>';
                                    
                                    $tabPane .= '<table class="table table-sm table-hover" data-dtlcode="" data-kpi-index="'.Mdform::$kpiControlIndex.'">';
                                        $tabPane .= '<tbody>';

                                            $tabPane .= $tr;
                                            $tabPane .= '<!--'.$relation['TEMPLATE_NAME'].'-->';

                                        $tabPane .= '</tbody>';
                                    $tabPane .= '</table>';
                                $tabPane .= '</div>';
                                
                                Mdform::$kpiControlIndex ++;

                            } else {
                                
                                $tabPane = str_replace('<!--'.$relation['TEMPLATE_NAME'].'-->', $tr . '<!--'.$relation['TEMPLATE_NAME'].'-->', $tabPane);
                            }
                        }

                        $objectTabRender .= '<div class="tabapp mt10">';
                            $objectTabRender .= $tabPane;
                        $objectTabRender .= '</div>';
                    }
                    
                    if (Mdform::$isSavedKpiForm) {
                    ?>    
                    
                    <div class="text-right mb10 kpi-form-view-toolbar">
                        Харагдац:
                        <div class="btn-group btn-group-toggle kpi-obj-view-controller metaSystemView-controller">
                            <button class="btn btn-sm default tooltips active" type="button" data-value="list" title="List view"><i class="icon-list2 font-size-12"></i></button>
                            <button class="btn btn-sm default tooltips" type="button" data-value="box" title="Box view"><i class="icon-grid6 font-size-12"></i></button>
                            <button class="btn btn-sm default tooltips" type="button" data-value="graph" title="Graph view" data-record-id="<?php echo Mdform::$recordId; ?>"><i class="icon-tree6 font-size-12"></i></button>
                            <button class="btn btn-sm default tooltips" type="button" data-value="dependencymap" title="Dependency map" data-record-id="<?php echo Mdform::$recordId; ?>"><i class="icon-tree5 font-size-12"></i></button>
                        </div>
                        <button class="btn btn-sm default kpi-form-fullsreen-btn" type="button" title="Fullscreen"><i class="fa fa-expand"></i></button>
                    </div>
                    
                    <div data-section="list">
                        <?php echo $objectTabRender; ?>
                    </div>
                    
                    <div data-section="box" class="d-none">
                        <?php echo $this->groupedObject; ?>
                    </div>
                    
                    <div data-section="graph" class="d-none">
                    </div>
                    
                    <div data-section="dependencymap" class="d-none">
                    </div>
                        
                    <?php    
                    } else {
                        echo $objectTabRender;
                    }
                    ?>
                </div>
                
                <?php 
                } 
                echo $tabAddonPane; 
                ?>
            </div>
        </div>
        
        <input type="hidden" name="param[pfKpiTemplateId][]" value="<?php echo $this->templateId; ?>" data-kpiheader-input="kpiTemplateId">
        <input type="hidden" name="param[pfKpiTemplateCode][]" value="<?php echo $this->templateCode; ?>" data-kpiheader-input="kpiTemplateCode">
        <?php echo Mdform::$pfTranslationValueTextarea; ?>
    </div>    
</div>

<?php echo $this->scripts; ?>
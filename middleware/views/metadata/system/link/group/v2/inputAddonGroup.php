<div class="panel panel-default bg-inverse mb0">
    <table class="table sheetTable" style="table-layout: fixed">
        <tbody>
            <tr>
                <td style="width: 118px; height: 30px;" class="left-padding">Path:</td>
                <td class="pl5" style="word-wrap: break-word;"><?php echo $this->paramPath; ?></td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Exp/Criteria:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="setProcessExpressionCriteriaGroup(this);">...</button>
                    <?php
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][visibleCriteria]',
                            'id' => 'visibleCriteria',
                            'value' => $this->paramRow['VISIBLE_CRITERIA']
                        )
                    );
                    echo Form::hidden(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][isGroupAddon]',
                            'value' => 1
                        )
                    );
                    ?>
                    <div class="lookup-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupParamConfig($this->groupMetaDataId, $this->paramPath, true); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="joinType_<?php echo $this->paramPath; ?>">Join type:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][joinType]',
                            'id' => 'joinType_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => (new Mddatamodel())->joinType(), 
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'value' => $this->paramRow['JOIN_TYPE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Relation config:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="setColumnRelation(this);">...</button>
                    <div class="relation-param-configs display-none">
                        <?php echo (new Mdmetadata())->getGroupRelationConfig($this->groupMetaDataId, $this->paramPath); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="relationType_<?php echo $this->paramPath; ?>">Relation type:</label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupParam['.$this->paramPath.'][relationType]',
                            'id' => 'relationType_'.$this->paramPath,
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'id' => 'soft',
                                    'name' => 'Soft'
                                ),
                                array(
                                    'id' => 'hard',
                                    'name' => 'Hard'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'value' => $this->paramRow['RELATION_TYPE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup /config/:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramDefaultValuesLookup(this);" title="Утга тохируулах">...</button>
                    <button type="button" class="btn btn-sm red-sunglo mr0" onclick="groupLookupFieldsMapping(this);" title="Lookup Field тохируулах">...</button>
                    <div class="param-values-config display-none">
                        <?php echo (new Mdmetadata())->getParamDefaultValues($this->groupMetaDataId, $this->paramPath, $this->paramRow['LOOKUP_META_DATA_ID']); ?>
                    </div>
                    <div class="param-lookup-field-config display-none"></div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup key:</td>
                <td class="pl5">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="lookupKeyMetaDataId" name="groupParam[<?php echo $this->paramPath; ?>][lookupKeyMetaDataId]" type="hidden" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_ID']; ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_CODE']; ?>" title="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_CODE']; ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_NAME']; ?>" title="<?php echo $this->paramRow['LOOKUP_KEY_META_DATA_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding">Lookup key /config/:</td>
                <td class="pl5">
                    <button type="button" class="btn btn-sm purple-plum" onclick="paramDefaultValuesLookup(this, 'key');" title="Утга тохируулах">...</button>
                    <button type="button" class="btn btn-sm red-sunglo mr0" onclick="groupLookupFieldsMapping(this, 'key');" title="Lookup Field тохируулах">...</button>
                    <div class="param-values-config-key display-none">
                        <?php echo (new Mdmetadata())->getParamDefaultValues($this->groupMetaDataId, $this->paramPath, $this->paramRow['LOOKUP_KEY_META_DATA_ID'], true); ?>
                    </div>
                    <div class="param-lookup-field-config-key display-none"></div>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="sidebarName_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00122'); ?></label></td>
                <td>
                    <input type="text" name="groupParam[<?php echo $this->paramPath; ?>][sidebarName]" value="<?php echo $this->paramRow['SIDEBAR_NAME']; ?>" id="sidebarName_<?php echo $this->paramPath; ?>" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('META_00122'); ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isSkipUniqueError_<?php echo $this->paramPath; ?>"><?php echo $this->lang->line('META_00165'); ?></label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isSkipUniqueError]', 'id' => 'isSkipUniqueError_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SKIP_UNIQUE_ERROR'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isSave_<?php echo $this->paramPath; ?>">Is save:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isSave]', 'id' => 'isSave_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SAVE'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isButton_<?php echo $this->paramPath; ?>">Is button:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isButton]', 'id' => 'isButton_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_BUTTON'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isSidebar_<?php echo $this->paramPath; ?>">Is sidebar:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isSidebar]', 'id' => 'isSidebar_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_SIDEBAR'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isCrypted_<?php echo $this->paramPath; ?>">Is crypted:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isCrypted]', 'id' => 'isCrypted_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_CRYPTED'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isBasket_<?php echo $this->paramPath; ?>">Is basket:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isBasket]', 'id' => 'isBasket_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_BASKET'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="isBasketEdit_<?php echo $this->paramPath; ?>">Is basket edit:</label></td>
                <td class="pl5">
                    <?php
                    echo Form::checkbox(array('name' => 'groupParam['.$this->paramPath.'][isBasketEdit]', 'id' => 'isBasketEdit_'.$this->paramPath, 'value' => '1', 'saved_val' => $this->paramRow['IS_BASKET_EDIT'], 'class' => 'notuniform'));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="tableName_<?php echo $this->paramPath; ?>">Object:</label></td>
                <td class="pl0">
                    <div class="input-group">
                        <?php                        
                        echo Form::textArea(
                            array(
                                'name' => 'groupParam['.$this->paramPath.'][tableName]', 
                                'id' => 'tableName_'.$this->paramPath, 
                                'class' => 'form-control',
                                'value' => (new Mdmetadata())->objectDeCompress($this->paramRow['TABLE_NAME']), 
                                'style' => 'min-height: 31px; height: 31px; resize:vertical; display: block; white-space: pre-wrap;'
                            )
                        );
                        echo Form::textArea(
                            array(
                                'name' => 'groupParam['.$this->paramPath.'][postgreSql]',
                                'id' => 'postgreSql', 
                                'style' => 'display: none',
                                'value' => (new Mdmetadata())->objectDeCompress($this->paramRow['POSTGRE_SQL'])
                            )
                        );
                        echo Form::textArea(
                            array(
                                'name' => 'groupParam['.$this->paramPath.'][msSql]',
                                'id' => 'msSql', 
                                'style' => 'display: none',
                                'value' => (new Mdmetadata())->objectDeCompress($this->paramRow['MS_SQL'])
                            )
                        );
                        ?>
                        <span class="input-group-append">
                            <button type="button" class="btn btn-sm blue mr0" onclick="dvSqlViewEditor(this);" title="SQL Editor">
                                <i class="far fa-edit"></i>
                            </button>
                            <button type="button" class="btn purple-plum btn-icon" onclick="groupTypeQryToParams(this);" title="Query - ээс талбар үүсгэх">
                                <i class="far fa-sort-alpha-down"></i>
                            </button>
                        </span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
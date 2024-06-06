<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px;" class="left-padding">Render type:</td>
                <td>
                    <?php
                    $renderType = Arr::get($this->bpRow, 'RENDER_TYPE');
                    echo Form::select(
                        array(
                            'name' => 'renderType',
                            'id' => 'renderType',
                            'data' => array(
                                array(
                                    'id' => 'tab',
                                    'name' => 'Tab'
                                ),
                                array(
                                    'id' => 'onepage',
                                    'name' => 'Onepage'
                                ), 
                                array(
                                    'id' => 'column',
                                    'name' => 'Column'
                                ),
                                array(
                                    'id' => 'column12',
                                    'name' => 'Column-1-2'
                                ),
                                array(
                                    'id' => 'leftside',
                                    'name' => 'Left side'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'class' => 'form-control select2', 
                            'value' => ($renderType ? $renderType : 'tab') 
                        )
                    );
                    ?>  
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isIgnoreMainTitle">
                        <?php echo $this->lang->line('metadata_notitle'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnoreMainTitle',
                                'id' => 'isIgnoreMainTitle',
                                'value' => '1',
                                'saved_val' => $this->bpRow['IS_IGNORE_MAIN_TITLE']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Mobile theme:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'mobileTheme',
                            'id' => 'mobileTheme',
                            'data' => array(
                                array(
                                    'id' => 'PC_theme1',
                                    'name' => 'PC theme1'
                                ),
                                array(
                                    'id' => 'PC_theme2',
                                    'name' => 'PC theme2'
                                ),
                                array(
                                    'id' => 'PC_theme3',
                                    'name' => 'PC theme3'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'class' => 'form-control select2', 
                            'value' => Arr::get($this->bpRow, 'MOBILE_THEME') 
                        )
                    );
                    ?>      
                </td>
            </tr>
            <tr id="">
                <td class="left-padding">Tab background color:</td>
                <td>
                    <div class="input-group color chart-colorpicker-default" data-color="<?php echo Arr::get($this->bpRow, 'TAB_BACKGROUND_COLOR'); ?>">
                        <input type="text" name="tabBackgroundColor" id="tabBackgroundColor" class="form-control" value="<?php echo Arr::get($this->bpRow, 'TAB_BACKGROUND_COLOR'); ?>">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo Arr::get($this->bpRow, 'TAB_BACKGROUND_COLOR'); ?>;"></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>         
            <tr>
                <td class="left-padding">
                    <label for="isPermission">
                        Is permission
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isPermission',
                                'id' => 'isPermission',
                                'value' => '1',
                                'saved_val' => $this->bpRow['IS_CHECK_PERMISSION']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>       
            <tr>
                <td class="left-padding" style="height: 32px;">Colomn / first column size - bootstrap grid / </td>
                <td>
                    <div class="metapackegegrid">
                        <?php
                            echo Form::text(
                                array(
                                    'name' => 'split_column',
                                    'id' => 'split_column',
                                    'class' => 'form-control', 
                                    'value' => $this->bpRow['SPLIT_COLUMN'],
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>            
            <tr>
                <td class="left-padding" style="height: 32px;">ADDIN CLASS NAME </td>
                <td>
                    <div class="metapackegegrid">
                        <?php
                            echo Form::text(
                                array(
                                    'name' => 'package_class',
                                    'id' => 'package_class',
                                    'class' => 'form-control', 
                                    'value' => $this->bpRow['PACKAGE_CLASS'],
                                )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isIgnorePackageTitle">
                        <?php echo $this->lang->line('package_notitle'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnorePackageTitle',
                                'id' => 'isIgnorePackageTitle',
                                'value' => '1',
                                'saved_val' => $this->bpRow['IS_IGNORE_PACKAGE_TITLE']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="defaultMetaDataId">
                        <?php echo $this->lang->line('package_defaultmetadataid'); ?>
                    </label>
                </td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1">
                        <div class="input-group double-between-input">
                            <input id="defaultMetaDataId" name="defaultMetaDataId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'DEFAULT_META_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'DEFAULT_META_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'DEFAULT_META_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isFilterShowButton">
                        <?php echo $this->lang->line('package_isfiltershowbutton'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isFilterShowButton',
                                'id' => 'isFilterShowButton',
                                'value' => '1',
                                'saved_val' => $this->bpRow['IS_FILTER_BTN_SHOW']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isRefresh">
                        <?php echo $this->lang->line('package_isrefresh'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isRefresh',
                                'id' => 'isRefresh',
                                'value' => '1',
                                'saved_val' => $this->bpRow['IS_REFRESH']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="countMetaDataId">
                        <?php echo $this->lang->line('package_count_dv'); ?>
                    </label>
                </td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1">
                        <div class="input-group double-between-input">
                            <input id="defaultMetaDataId" name="countMetaDataId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'COUNT_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'COUNT_META_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'COUNT_META_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        $('.chart-colorpicker-default').colorpicker({
            format: 'hex'
        });        
    });
</script>
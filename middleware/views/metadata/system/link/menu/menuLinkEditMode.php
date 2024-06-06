<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="globeCode">
                        Орчуулга:
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'globeCode',
                            'id' => 'globeCode',
                            'class' => 'form-control textInit globeCodeInput', 
                            'value' => $this->menuRow['GLOBE_CODE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="menuCode">
                        Код:
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'menuCode',
                            'id' => 'menuCode',
                            'class' => 'form-control textInit', 
                            'value' => $this->menuRow['MENU_CODE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Хэлбэр:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'menuPosition',
                            'id' => 'menuPosition',
                            'data' => array(
                                array(
                                    'code' => 'horizontal', 
                                    'name' => 'Хэвтээ'
                                ), 
                                array(
                                    'code' => 'vertical', 
                                    'name' => 'Босоо'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control select2', 
                            'value' => $this->menuRow['MENU_POSITION']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Зэрэгцүүлэлт:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'menuAlign',
                            'id' => 'menuAlign',
                            'data' => array(
                                array(
                                    'code' => 'meta-menu-center', 
                                    'name' => 'Төв'
                                ), 
                                array(
                                    'code' => 'meta-menu-left', 
                                    'name' => $this->lang->line('META_00082')
                                ), 
                                array(
                                    'code' => 'meta-menu-right', 
                                    'name' => $this->lang->line('META_00055')
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control select2', 
                            'value' => $this->menuRow['MENU_ALIGN']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Theme:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'menuTheme',
                            'id' => 'menuTheme',
                            'data' => $this->widgetData,
                            'op_value' => 'ID',
                            'op_text' => 'NAME',
                            'class' => 'form-control select2', 
                            'value' => $this->menuRow['MENU_THEME']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Color:</td>
                <td>
                    <?php
                    $colorSet = '- '.Lang::line('select_btn').' -';
                    $colorSet .= '<option value="">' . $colorSet . '</option>';                    
                    if ($this->menuColorData) {
                        foreach ($this->menuColorData as $mrow) {
                            if ($this->menuRow['MENU_COLOR'] == $mrow['name']) {
                                $colorSet .= '<option selected style="background:'.$mrow['name'].'" value="'.$mrow['name'].'">' . $mrow['name'] . '</option>';                    
                            } else {
                                $colorSet .= '<option style="background:'.$mrow['name'].'" value="'.$mrow['name'].'">' . $mrow['name'] . '</option>';                    
                            }
                        }
                    }
                    echo '<select name="menuColor" id="menuColor" class="form-control">'.$colorSet.'</select>';
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Target:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'urlTarget',
                            'id' => 'urlTarget',
                            'data' => array(
                                array(
                                    'code' => '_self', 
                                    'name' => 'Энэ хуудсанд'
                                ), 
                                array(
                                    'code' => '_blank', 
                                    'name' => 'Шинэ хуудсанд'
                                ), 
                                array(
                                    'code' => '_alwaysself', 
                                    'name' => 'Байнга энэ хуудсанд'
                                ),
                                array(
                                    'code' => '_alwaysblank', 
                                    'name' => 'Байнга шинэ хуудсанд'
                                ), 
                                array(
                                    'code' => 'iframe', 
                                    'name' => 'Iframe'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control select2', 
                            'value' => $this->menuRow['URL_TARGET']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Веб хаяг:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'webUrl',
                            'id' => 'webUrl',
                            'class' => 'form-control', 
                            'value' => $this->menuRow['WEB_URL'] 
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="menuTooltip">
                        Tooltip:
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'menuTooltip',
                            'id' => 'menuTooltip',
                            'class' => 'form-control textInit globeCodeInput', 
                            'value' => $this->menuRow['MENU_TOOLTIP'] 
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Action Meta:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1">
                        <div class="input-group double-between-input">
                            <input id="menuActionMetaDataId" name="menuActionMetaDataId" type="hidden" value="<?php echo Arr::get($this->menuRow, 'ACTION_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->menuRow, 'ACTION_META_DATA_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->menuRow, 'ACTION_META_DATA_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>   
                </td>
            </tr>
            <tr>
                <td class="left-padding">Count Meta:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="menuCountMetaDataId" name="menuCountMetaDataId" type="hidden" value="<?php echo Arr::get($this->menuRow, 'COUNT_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->menuRow, 'COUNT_META_DATA_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->menuRow, 'COUNT_META_DATA_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00197'); ?></td>
                <td>
                    <?php echo Form::hidden(array('name' => 'menuIconName', 'value' => $this->menuRow['ICON_NAME'])); ?>
                    
                    <!-- <button id="menu-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="icomoon" data-cols="6" data-rows="6" data-icon="<?php echo $this->menuRow['ICON_NAME']; ?>" name="name" role="iconpicker">
                    </button> -->
                    <button id="menu-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo $this->menuRow['ICON_NAME']; ?>" name="name" role="iconpicker">
                    </button>
                   
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00072'); ?></td>
                <td>
                    <div class="d-flex">
                        <?php echo Form::file(array('name' => 'menuPhotoName', 'class' => 'form-control', 'onchange' => 'hasPhotoExtension(this)')); ?>
                        <?php 
                        echo Form::hidden(array('name' => 'oldMenuPhotoName', 'value' => $this->menuRow['PHOTO_NAME'])); 
                        echo '<a href="javascript:;" class="btn btn-sm btn-danger d-none remove-meta-photo-icon" onclick="removeMetaPhotoIcon(this);"><i class="fa fa-trash mt-2"></i></a>';
                        ?>
                    </div>
                    <div class="d-flex">
                        <?php
                        if (file_exists($this->menuRow['PHOTO_NAME'])) {
                            echo '<img src="'.$this->menuRow['PHOTO_NAME'].'" style="max-height: 120px;" class="mt5">';
                            echo '<a href="javascript:;" class="btn btn-sm btn-danger mt5" style="height: 30px;" onclick="removeMetaPhotoIcon(this);"><i class="fa fa-trash"></i></a>';
                        }                        
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Дэвсгэр зураг</td>
                <td>
                    <div class="d-flex">
                        <?php echo Form::file(array('name' => 'menuBgPhotoName', 'class' => 'form-control', 'onchange' => 'hasPhotoExtension(this)')); ?>
                        <?php 
                        echo Form::hidden(array('name' => 'oldMenuBgPhotoName', 'value' => $this->menuRow['BG_PHOTO_NAME'])); 
                        echo '<a href="javascript:;" class="btn btn-sm btn-danger d-none remove-meta-photo-icon" onclick="removeMetaPhotoIcon(this);"><i class="fa fa-trash mt-2"></i></a>';
                        ?>
                    </div>
                    <div class="d-flex">
                        <?php
                        if (file_exists($this->menuRow['BG_PHOTO_NAME'])) {
                            echo '<img src="'.$this->menuRow['BG_PHOTO_NAME'].'" style="max-height: 120px;" class="mt5">';
                            echo '<a href="javascript:;" class="btn btn-sm btn-danger mt5" style="height: 30px;" onclick="removeMetaPhotoIcon(this);"><i class="fa fa-trash"></i></a>';
                        }                        
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">View type:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'viewType',
                            'id' => 'viewType',
                            'data' => array(
                                array(
                                    'code' => 'newarea', 
                                    'name' => 'Шинэ талбар'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control select2', 
                            'value' => $this->menuRow['VIEW_TYPE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isShowCard">
                        Картаар харах эсэх:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isShowCard',
                            'id' => 'isShowCard',
                            'value' => '1',
                            'saved_val' => $this->menuRow['IS_SHOW_CARD']
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isMonpassKey">
                        Гарын үсэгтэй эсэх:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isMonpassKey',
                            'id' => 'isMonpassKey',
                            'value' => '1',
                            'saved_val' => $this->menuRow['IS_MONPASS_KEY']
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isContentUi">
                        Контэнт эсэх:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isContentUi',
                            'id' => 'isContentUi',
                            'value' => '1',
                            'saved_val' => $this->menuRow['IS_CONTENT_UI']
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isModuleSidebar">
                        Is module sidebar:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isModuleSidebar',
                            'id' => 'isModuleSidebar',
                            'value' => '1',
                            'saved_val' => $this->menuRow['IS_MODULE_SIDEBAR']
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isDefaultOpen">
                        Is default open:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isDefaultOpen',
                            'id' => 'isDefaultOpen',
                            'value' => '1',
                            'saved_val' => $this->menuRow['IS_DEFAULT_OPEN']
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isOfflineMode">
                        Is offline mode:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isOfflineMode',
                            'id' => 'isOfflineMode',
                            'value' => '1',
                            'saved_val' => $this->menuRow['IS_OFFLINE_MODE']
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Дэд модул руу ороход харагдах жагсаалт:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1">
                        <div class="input-group double-between-input">
                            <input id="viewMetaDataId" name="viewMetaDataId" type="hidden" value="<?php echo Arr::get($this->menuRow, 'VIEW_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->menuRow, 'META_DATA_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->menuRow, 'META_DATA_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>   
                </td>
            </tr>            
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function(){

    $('#iconLibrary').change(function(){
        var selected = $(this).children('option:selected').val();
        $('#menu-iconpicker').attr('data-iconset', selected);
        // // $('#menu-iconpicker').trigger('click');
        // var htmlbtn = '<button id="menu-iconpicker" class="btn btn-secondary btn-sm iconpicker dropdown-toggle" data-search-text="Хайх" data-placement="top" data-iconset="icomoon" data-cols="6" data-rows="6" data-icon="" role="iconpicker"><i></i><input type="hidden"></button>'
        // $('.iconpickerDiv').empty().append(htmlbtn);
    });

    $('button[role="iconpicker"]').iconpicker({
        arrowPrevIconClass: 'fa fa-arrow-left',
        arrowNextIconClass: 'fa fa-arrow-right'
    });
    $('#menu-iconpicker').on('change', function(e){ 
        var $this = $(this);
        if (e.icon === 'empty' || e.icon === 'fa-empty') {
            // if ($this.data('icon')) {
            //     $("input[name='menuIconName']").val($this.data('icon'));
            //     $this.find('i').attr('class', 'fa ' + $this.data('icon'));
            // } else {
            //    $("input[name='menuIconName']").val("");
            // }
            $("input[name='menuIconName']").val("");
        } else {
            $("input[name='menuIconName']").val(e.icon);
        }
    });
    $('input[name="menuPhotoName"]').on('change', function(){
        if (!$(this).closest('td').find('img').length) {
            $('.remove-meta-photo-icon').removeClass('d-none');
        }
    });
}); 
function removeMetaPhotoIcon(elem) {
    $(elem).closest('td').find('input').val('');
    $(elem).closest('td').find('img').remove();
    $(elem).addClass('d-none');
}
</script>
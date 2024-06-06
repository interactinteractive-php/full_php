<link rel="stylesheet" type="text/css" href="<?php echo autoVersion('middleware/assets/css/gridlayout/bank_card.css'); ?>"/>
<style type="text/css">
.div-objectdatagrid-<?php echo $this->dataViewId; ?>.explorer-table-cell {
    background-color: transparent!important;
    border: 0!important;
}
</style>

<div class="row dv-bank-card d-none">
    <div class="col">
        <ul class="dv-explorer" id="main-item-container">
            <?php
            $fields = $this->row['dataViewLayoutTypes']['explorer']['fields'];
            
            if ($this->recordList) {

                if (isset($this->recordList['status'])) {
                    echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: ' . $this->recordList['message']);
                    exit();
                }

                $firstRow = $this->recordList[0];
                
                $backgroundField = strtolower(issetParam($fields['backgroundImage']));
                $photoField = strtolower(issetParam($fields['photo']));
                $name1Field = strtolower(issetParam($fields['name1']));
                $name2Field = strtolower(issetParam($fields['name2']));
                $name3Field = strtolower(issetParam($fields['name3']));
                $name4Field = strtolower(issetParam($fields['name4']));
                $name5Field = strtolower(issetParam($fields['name5']));
                
                $name6Field = strtolower(issetParam($fields['name6']));
                $name7Field = strtolower(issetParam($fields['name7']));
                $name8Field = strtolower(issetParam($fields['name8']));
                $name9Field = strtolower(issetParam($fields['name9']));
                $name10Field = strtolower(issetParam($fields['name10']));
                $name11Field = strtolower(issetParam($fields['name11']));
                $name12Field = strtolower(issetParam($fields['name12']));
                $name13Field = strtolower(issetParam($fields['name13']));
                $name14Field = strtolower(issetParam($fields['name14']));
                $name15Field = strtolower(issetParam($fields['name15']));

                $background = $photo = $name1 = $name2 = $name3 = $name4 = $name5 = $name6 = ' echo "";';
                
                if ($backgroundField && isset($firstRow[$backgroundField])) {
                    $background = 'echo $recordRow[$backgroundField];';
                }
                
                if ($photoField && isset($firstRow[$photoField])) {
                    $photo = 'echo $recordRow[$photoField];';
                }

                if ($name1Field && isset($firstRow[$name1Field])) {
                    $name1 = 'echo $recordRow[$name1Field];';
                }

                if ($name2Field && isset($firstRow[$name2Field])) {
                    $name2 = 'echo $recordRow[$name2Field];';
                }

                if ($name3Field && isset($firstRow[$name3Field])) {
                    $name3 = 'echo $recordRow[$name3Field];';
                }

                if ($name4Field && isset($firstRow[$name4Field])) {

                    $name4 = 'echo $recordRow[$name4Field];';

                    if ($name4FieldLabelName = issetParam($fields['name4_labelname'])) {
                        $name4FieldLabelName = Lang::line($name4FieldLabelName);
                    }
                }

                if ($name5Field && isset($firstRow[$name5Field])) {
                    $name5 = 'echo $recordRow[$name5Field];';

                    if ($name5FieldLabelName = issetParam($fields['name5_labelname'])) {
                        $name5FieldLabelName = Lang::line($name5FieldLabelName);
                    }
                }
                
                $drillCount = 0;
                
                for ($i = 6; $i <= 16; $i++) {
                    
                    if (isset(${'name'.$i.'Field'}) 
                        && ${'name'.$i.'Field'} 
                        && isset($firstRow[${'name'.$i.'Field'}]) 
                        && issetParam($fields['name'.$i.'_labelname']) 
                        && isset($this->drillDownLink[${'name'.$i.'Field'}])) {
                        
                        $drillCount ++;
                    }
                }
                
                $nameFieldLink = $nameFieldDropLink = '';
                
                for ($i = 6; $i <= 16; $i++) {
                    
                    if (isset(${'name'.$i.'Field'}) 
                        && ${'name'.$i.'Field'} 
                        && isset($firstRow[${'name'.$i.'Field'}]) 
                        && issetParam($fields['name'.$i.'_labelname']) 
                        && isset($this->drillDownLink[${'name'.$i.'Field'}])) {
                        
                        $drillLabelName = Lang::line($fields['name'.$i.'_labelname']);
                        
                        if ($i >= 8 && $drillCount > 3) {
                            
                            $nameFieldDropItem = html_tag('a', array(
                                'href' => 'javascript:;', 
                                'class' => 'dropdown-item', 
                                'onclick' => $this->drillDownLink[${'name'.$i.'Field'}]['link']
                            ), '<i class="far fa-chevron-circle-right"></i>' . $drillLabelName);

                            $nameFieldDropItem = str_replace("'", "\'", $nameFieldDropItem);
                            $nameFieldDropItem = str_replace("$\\'", "'", $nameFieldDropItem);
                            $nameFieldDropLink .= $nameFieldDropItem;
                            
                            $isNameFieldDrop = true;
                        
                            continue;
                        }
                        
                        $nameFieldBtn = html_tag('button', array(
                            'type' => 'button', 
                            'class' => 'btn rounded-round ' . $this->drillDownLink[${'name'.$i.'Field'}]['linkStyle'], 
                            'onclick' => $this->drillDownLink[${'name'.$i.'Field'}]['link'], 
                            'title' => $drillLabelName
                        ), $drillLabelName);

                        $nameFieldBtn = str_replace("'", "\'", $nameFieldBtn);
                        $nameFieldLink .= $nameFieldBtn . '<div class="clearfix"></div>';
                        
                        $isNameFieldLink = true;
                    }
                }

                $nameFieldLink = 'echo \''.$nameFieldLink.'\';';
                $nameFieldDropLink = 'echo \''.$nameFieldDropLink.'\';';

                foreach ($this->recordList as $recordRow) {
                    $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
            ?>
            <li class="dv-explorer-row" style="background-image: url(<?php eval($background); ?>)">	
                <div class="selected-row-link" data-row-data="<?php echo $rowJson; ?>">
                    <div class="media">
                        <div class="mr-2">
                            <img src="<?php eval($photo); ?>" onerror="onUserImageError(this);" class="rounded-circle">
                        </div>

                        <div class="media-body">
                            <div class="bank-card-name1"><?php eval($name1); ?></div>
                            <div class="bank-card-name2"><?php eval($name2); ?></div>
                        </div>

                        <div class="ml-3">
                            <span class="badge bg-white badge-pill"><?php eval($name3); ?></span>
                        </div>
                    </div>
                    <div class="media-info">
                        <div style="flex: 1;">
                            <?php
                            if (isset($name4FieldLabelName) && $name4FieldLabelName) {
                            ?>
                            <div class="bank-card-name3"><?php echo $name4FieldLabelName; ?></div>
                            <?php
                            }
                            ?>
                            <div class="bank-card-name4"><?php eval($name4); ?></div>
                            <?php
                            if (isset($name5FieldLabelName) && $name5FieldLabelName) {
                            ?>
                            <div class="bank-card-name3"><?php echo $name5FieldLabelName; ?></div>
                            <?php
                            }
                            ?>
                            <div class="bank-card-name4"><?php eval($name5); ?></div>
                        </div>
                        
                        <div class="bank-card-drill-part d-flex align-items-center flex-column">
                            <div class="mt-auto">
                                <?php
                                if (isset($isNameFieldLink)) {
                                    eval($nameFieldLink);
                                }
                                
                                if (isset($isNameFieldDrop)) {
                                ?>
                                <div class="btn-group dv-bank-card-dropdown">
                                    <button type="button" class="btn btn-danger rounded-round dropdown-toggle" data-toggle="dropdown">
                                        <i class="far fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php 
                                        eval($nameFieldDropLink); 
                                        ?>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        
                    </div>
                </div>	
            </li>
        <?php
                }
            }
        ?>
            <div class="clearfix"></div>
        </ul>
    </div>
    
    <?php
    $sidebar1title_labelname = issetParam($fields['sidebar1_title_labelname']);
    $sidebar2title_labelname = issetParam($fields['sidebar2_title_labelname']);
    $sidebar3title_labelname = issetParam($fields['sidebar3_title_labelname']);
    
    if ($sidebar1title_labelname || $sidebar2title_labelname || $sidebar3title_labelname) {
        
        $sidebar1_name1 = strtolower(issetParam($fields['sidebar1_name1']));
        $sidebar1_name2 = strtolower(issetParam($fields['sidebar1_name2']));
    ?>
    <div class="col-md-auto dv-bank-card-sidebar">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><?php echo Lang::line($sidebar1title_labelname); ?></h6>
            </div>
            <div class="card-body">
                
                <?php
                if ($sidebar1_name1) {
                ?>
                <div class="dv-bank-card-sidebar-item d-flex align-items-center" data-field="<?php echo $sidebar1_name1; ?>">
                    <div class="btn rounded-round btn-icon mr-2">
                        <i class="far fa-briefcase"></i>
                    </div>
                    <div>
                        <div class="dv-bank-card-sidebar-item-1">0</div>
                        <div class="dv-bank-card-sidebar-item-2"><?php echo $this->lang->line($this->allField[$sidebar1_name1]['LABEL_NAME']); ?></div>
                    </div>
                </div>
                <?php
                }
                
                if ($sidebar1_name2) {
                ?>
                <div class="dv-bank-card-sidebar-item d-flex align-items-center" data-field="<?php echo $sidebar1_name2; ?>">
                    <div class="btn rounded-round btn-icon mr-2">
                        <i class="far fa-briefcase"></i>
                    </div>
                    <div>
                        <div class="dv-bank-card-sidebar-item-1">0</div>
                        <div class="dv-bank-card-sidebar-item-2"><?php echo $this->lang->line($this->allField[$sidebar1_name2]['LABEL_NAME']); ?></div>
                    </div>
                </div>
                <?php
                }
                ?>
                
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><?php echo Lang::line($sidebar2title_labelname); ?></h6>
            </div>
            <div class="card-body">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td class="pt-3" style="width: 80px">
                                <div class="dv-bank-card-sidebar-item d-flex align-items-center mt0">
                                    <div class="btn rounded-round btn-icon mr-2">
                                        <i class="far fa-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="dv-bank-card-sidebar-item-3">USD</div>
                                    </div>
                                </div>
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-3">
                                2,848.65
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-4">
                                0.013
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3">
                                <div class="dv-bank-card-sidebar-item d-flex align-items-center mt0">
                                    <div class="btn rounded-round btn-icon mr-2">
                                        <i class="far fa-euro-sign"></i>
                                    </div>
                                    <div>
                                        <div class="dv-bank-card-sidebar-item-3">EUR</div>
                                    </div>
                                </div>
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-3">
                                2,848.65
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-4">
                                0.013
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3">
                                <div class="dv-bank-card-sidebar-item d-flex align-items-center mt0">
                                    <div class="btn rounded-round btn-icon mr-2">
                                        <i class="far fa-ruble-sign"></i>
                                    </div>
                                    <div>
                                        <div class="dv-bank-card-sidebar-item-3">RUB</div>
                                    </div>
                                </div>
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-3">
                                2,848.65
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-4">
                                0.013
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3">
                                <div class="dv-bank-card-sidebar-item d-flex align-items-center mt0">
                                    <div class="btn rounded-round btn-icon mr-2">
                                        <i class="far fa-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="dv-bank-card-sidebar-item-3">CNY</div>
                                    </div>
                                </div>
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-3">
                                2,848.65
                            </td>
                            <td class="pt-3 middle text-right dv-bank-card-sidebar-item-4">
                                0.013
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><?php echo Lang::line($sidebar3title_labelname); ?></h6>
            </div>
            <div class="card-body">
                
                <div class="dv-bank-card-sidebar-item d-flex align-items-center">
                    <div class="btn rounded-round btn-icon mr-2">
                        <i class="far fa-database"></i>
                    </div>
                    <div>
                        <div class="dv-bank-card-sidebar-item-1">56,000,000₮</div>
                        <div class="dv-bank-card-sidebar-item-2">Сарын эхний үлдэгдэл</div>
                    </div>
                </div>
                
                <div class="dv-bank-card-sidebar-item d-flex align-items-center">
                    <div class="btn rounded-round btn-icon mr-2">
                        <i class="far fa-briefcase"></i>
                    </div>
                    <div>
                        <div class="dv-bank-card-sidebar-item-1">56,000,000₮</div>
                        <div class="dv-bank-card-sidebar-item-2">Сарын эхний үлдэгдэл</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    
</div>    

<script type="text/javascript">
$(function() {
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?> .dv-bank-card').removeClass('d-none');
    
    var allField_<?php echo $this->dataViewId; ?> = <?php echo json_encode($this->allField, JSON_UNESCAPED_UNICODE); ?>;
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('show.bs.dropdown', '.dv-bank-card-dropdown', function() {
        var $this = $(this);
        $this.closest('li.dv-explorer-row').click();
    });
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', 'li.dv-explorer-row', function(){
        var $this = $(this);
        var $parent = $this.closest('.dv-bank-card');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
        
        var $sidebar = $parent.find('.dv-bank-card-sidebar');
        
        if ($sidebar.length) {
            
            var rowData = $this.find('.selected-row-link').data('row-data');
            var $fields = $sidebar.find('[data-field]');
            
            $fields.each(function() {
                var $this = $(this), 
                    field = $this.attr('data-field');
                    
                var $setField = $this.find('.dv-bank-card-sidebar-item-1');
                var fieldConfig = allField_<?php echo $this->dataViewId; ?>[field];
                
                if (fieldConfig.META_TYPE_CODE == 'bigdecimal') {
                    $setField.html(gridAmountField(rowData[field]));
                } else {
                    $setField.html(rowData[field]);
                }
            });
        }
    });
});
</script>
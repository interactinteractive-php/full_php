<style type="text/css">
.mv_card_with_employeelist_widget {
    display: inline-block;
    width: 90px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}
.mv_card_with_employeelist_widget:hover {
    box-shadow: none;
}
.mv_card_with_employeelist_widget:hover .no-dataview {
    display: block !important;
}
.mv_card_with_employeelist_widget .card {
    margin-bottom: 0;
}
.mv_card_with_employeelist_widget .card-body .card-img {
    border-radius: 0;
    border-bottom: 1px #eee solid;
}
.mv_card_with_employeelist_widget h5 {
    display: block;
    padding: 0 10px;
    font-size: 12px;
    color: #333;
    line-height: 20px;
    text-align: center;
    overflow: hidden;
}
#objectdatacustomgrid-<?php echo $this->indicatorId ?> {
/*    background-color: transparent;
    border-bottom: 5px solid;*/
    /*border-image: linear-gradient(to right, #519157 25%, #de6e0a 25%, #de6e0a 50%,#33a9ca 50%, #33a9ca 75%, #189e6b 75%) 5;*/
}
.mv_card_with_employeelist_widget_main {
/*    background-image: url('middleware/assets/img/layout-themes/image/card/mv_card_with_employeelist_widget_group_21984.png');
    background-size: 230px 180px;
    background-repeat: no-repeat;
    background-position: right bottom;    */
}
.mv_card_with_employeelist_widget_main .mv_card_with_employeelist_widget.active img {
    border-color: #ca3361 !important;
}
</style>

<div class="dv-process-buttons mt-2 ml-2">
    <div class="btn-group btn-group-devided">
        <?php echo implode('', $this->actions['buttons']); ?>
    </div>        
</div>        
        
<div class="mv_card_with_employeelist_widget_main">
    <?php                                    
    $dataResult = $this->response['rows'];
    ?>
    <?php
    foreach ($dataResult as $row) {
        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
    ?>
        <a href="javascript:;" style="width: 170px;margin: 15px;" class="mv_card_with_employeelist_widget no-dataview" data-rowdata="<?php echo $rowJson; ?>">
            <div class="card" style="border: none;box-shadow: none;padding: 0;background: transparent;">
                <div class="card-body">
                    <div class="card-img-actions mb-2 mt-2 pl-2">
                        <?php if (file_exists(issetParam($row[$this->relationViewConfig['position-1']]))) { ?>
                            <img class="directory-img" style="height: 150px;width: 150px;border-radius: 150px;border: 3px solid #33a9ca;background: #fff;" src="<?php echo issetParam($row[$this->relationViewConfig['position-1']]) ?>"/>
                        <?php } else { ?>
                            <img class="directory-img" style="height: 150px;width: 150px;border-radius: 150px;border: 3px solid #33a9ca;background: #fff;" src="assets/core/global/img/noimage.png"/>
                        <?php } ?>
                    </div>
                    <h5>
                        <?php echo issetParam($row[$this->relationViewConfig['position-2']]) ?>
                    </h5>
                    <h5 style="color:#01a8ff">
                        <?php echo issetParam($row[$this->relationViewConfig['position-3']]) ?>
                    </h5>
                </div>
            </div>
        </a>
    <?php
    } 
    ?>
</div>

<script type="text/javascript">
$('.mv_card_with_employeelist_widget').click(function() {
    var $this = $(this);
    if ($this.hasClass('active')) {
        $this.removeClass('active');
        return;
    }
    $this.closest('.mv_card_with_employeelist_widget_main').find('.active').removeClass('active');
    $this.addClass('active');
}); 
function mvWidgetFileViewCreateCallback() {
    $('div[data-menu-id="164723019841110"]').remove();
    $('a[data-menu-id="164723019841110"]').trigger('click');
}    
function mvWidgetFileViewDeleteCallback(elem) {
    mvWidgetFileViewCreateCallback();
}
<?php 
$menuCallBack = $menuItems = '';

foreach ($this->actions['contextMenu'] as $menu) {
    $menu['onClick'] = str_replace('this', '$a', $menu['onClick']);
    
    $menuCallBack .= 'if (key === \''.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'\') { ';
        
        $menuCallBack .= ' $(this).closest(\'.objectdatacustomgrid\').find(\'.active\').removeClass(\'active\'); $(this).addClass(\'active\'); ';
        $menuCallBack .= 'var $a = $(\'<a />\'); ';
        $menuCallBack .= '$a.attr(\'data-actiontype\', \''.$menu['data-actiontype'].'\')';
        $menuCallBack .= '.attr(\'data-main-indicatorid\', \''.$menu['data-main-indicatorid'].'\')';
        $menuCallBack .= '.attr(\'data-structure-indicatorid\', \''.$menu['data-structure-indicatorid'].'\')';
        $menuCallBack .= '.attr(\'data-crud-indicatorid\', \''.$menu['data-crud-indicatorid'].'\')';
        $menuCallBack .= '.attr(\'data-mapid\', \''.$menu['data-mapid'].'\'); ';
        
        $menuCallBack .= $menu['onClick'];
    $menuCallBack .= '} ';
    
    $menuItems .= '"'.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'": {name: \''.$menu['labelName'].'\', icon: \''.$menu['iconName'].'\'}, ';
}

if ($menuCallBack) { ?>
    $.contextMenu({
        selector: 'div#object-value-list-<?php echo $this->indicatorId; ?> .mv_card_with_employeelist_widget',
        callback: function (key, opt) {
            <?php echo $menuCallBack; ?>
        },
        items: {
            <?php echo $menuItems; ?> 
        }
    }); 
<?php } ?>
</script>
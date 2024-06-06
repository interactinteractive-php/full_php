<style type="text/css">
.mv_card_status_widget {
    display: inline-block;
    width: 90px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}

.mv_card_status_widget:hover .no-dataview {
    display: block !important;
}
.mv_card_status_widget {
    .card {
       margin-bottom: 0;
        border-radius: 10px !important;
        padding: 0 !important;
    }
    .card-body .card-img {
        border-radius: 0;
        border-bottom: 1px #eee solid;
    }
}

.mv_card_status_widget h5 {
    display: block;
    padding: 0 10px;
    font-size: 12px;
    color: #333;
    line-height: 20px;
    text-align: center;
    overflow: hidden;
}

.mv_card_status_widget_main {
    gap: 20px;
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px 0;

    .mv_card_status_widget.active > .card,
    .mv_card_status_widget:hover > .card {
        border-color: #2196f3;
    }

    .left-sidewidget {
        /* gap: 16px;
        display: inline-flex;
        flex-wrap: wrap;
        justify-content: center;
        width: 280px; */
        width: 270px;
        max-height: max-content;
        min-height: max-content;
        min-width: 200px;
    }

    .position-1 {
        margin: 0;
        max-height: 47px;
        margin-bottom: 16px;
        .badge {
            font-size: 20px;
            color: #282A30;
            background: #F9F8F9;
            border-radius: 4px;
            padding: 12px;
            
        }
    }

    .position-2 {
        font-size: 16px; 
        color: #282A30;
        margin: 0;
        max-height: 47px;
        margin-bottom: 16px;
    }
    .position-3 {
        font-size: 14px;
        color: #707579;
        margin: 0;
        max-height: 47px;
        margin-bottom: 16px;
    }
    .position-4 {
        font-size: 14px;
        margin: 0;
        max-height: 47px;
    }

    .right-sidewidget {
        height: 180px !important;
    }
}

</style>

<div class="dv-process-buttons mt-2 ml-2">
    <div class="btn-group btn-group-devided">
        <?php echo implode('', $this->actions['buttons']); ?>
    </div>     
</div>        
        
<div class="mv_card_status_widget_main ">
    <?php                                            
    $dataResult = $this->response['rows'];
    foreach ($dataResult as $row) {
        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
    ?>
        <a href="javascript:;" style="width: 315px;" class="mv_card_status_widget no-dataview" data-rowdata="<?php echo $rowJson; ?>" data-clickprocess="<?php echo issetParam($this->relationViewConfig['position-indicator-1']) ?>">
            <div class="card">
                <div class="card-body p-2 d-flex">
                    <div class="left-sidewidget ">
                        <div class="w-100 pull-left position-1 ">
                            <span class="badge badge-warning badge-icon"><i class="<?php echo issetParam($row[$this->relationViewConfig['position-1']]) ?>"></i></span>
                        </div>
                        <p class="w-100 pull-left text-left text-one-line position-2">
                            <?php echo issetParam($row[$this->relationViewConfig['position-2']]) ?>
                        </p>
                        <p class="w-100 pull-left text-left text-three-line position-3">
                            <?php echo issetParam($row[$this->relationViewConfig['position-3']]) ?>
                        </p>
                        <p class="w-100 pull-left text-left text-three-line position-4">
                            <span class="badge badge-primary rounded-pill py-1 px-3" style="background: '<?php echo issetParam($this->relationViewConfig['position-5']) ? checkDefaultVal($row[$this->relationViewConfig['position-5']], '...') : ''; ?>'"><?php echo checkDefaultVal($row[$this->relationViewConfig['position-4']], '...') ?></span>
                        </p>
                    </div>
                    <div class="right-sidewidget pull-right ml-auto">
                        <button href="javascript:;" <?php echo ($buttonClickAction) ? 'onclick="' . $buttonClickAction . '"' : ''; ?> data-rowdata="<?php echo $rowJson; ?>"  class="btn btn-outline bg-indigo-400 text-indigo-400 btn-icon h-100 no-dataview"><i class="icon-arrow-right15"></i></button>
                    </div>
                </div>
            </div>
        </a>
    <?php
    } 
    ?>
</div>

<script type="text/javascript">
$('.mv_card_status_widget').click(function() {
    var $this = $(this);
    if ($this.hasClass('active')) {
        $this.removeClass('active');
        return;
    }
    $this.closest('.mv_card_status_widget_main').find('.active').removeClass('active');
    $this.addClass('active');
}); 
function mvWidgetFileViewCreateCallback() {
    $('div[data-menu-id="164723019841110"]').remove();
    $('a[data-menu-id="164723019841110"]').trigger('click');
}    

function mvWidgetFileViewDeleteCallback(elem) {
    mvWidgetFileViewCreateCallback();
}
</script>
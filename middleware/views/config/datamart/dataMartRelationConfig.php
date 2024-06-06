<form method="post" id="dataMartVisualConfigForm">
    
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a href="#datamartConfig-link" class="nav-link pb8 active" data-toggle="tab"><?php echo $this->lang->line('dmart_link_tab'); ?></a></li>
        <li class="nav-item"><a href="#datamartConfig-pivot" class="nav-link pb8" data-toggle="tab"><?php echo $this->lang->line('dmart_pivot_tab'); ?></a></li>
        <?php 
        if (!$this->isDialog) {
            echo '<li class="nav-item ml-auto"><button type="button" class="btn btn-sm green" onclick="saveDataMartRelationConfig(this);">'.$this->lang->line('save_btn').'</button></li>';
        }
        ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="datamartConfig-link">
            <div class="row">
                <div class="col pl0">
                    <div class="mb10">
                        <button type="button" class="btn btn-sm bg-success-400" onclick="dataMartAddObject(this);"><?php echo $this->lang->line('add_btn'); ?></button>
                    </div>
                    <div class="heigh-editor">
                        <div class="css-editor" id="datamart-editor" style="height: 400px; position: relative;"></div>
                    </div>
                </div>
                <div style="display: none; flex: 0 0 350px; overflow: auto" id="datamart-attributes" data-service-id="<?php echo $this->serviceId; ?>">
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="datamartConfig-pivot">
            <div class="row">
                <div class="col pl0">
                    <i class="fa fa-th"></i> Талбарууд
                    <ul class="dmart-selectable-list" id="dmart-selectable-alllist" data-pivot-type="FIELD">
                    </ul>  
                </div>
                <div class="col">
                    <i class="fa fa-bars"></i> <?php echo $this->lang->line('row'); ?>
                    <ul class="dmart-selectable-list" id="dmart-selectable-rowlist" data-pivot-type="ROW">
                    </ul>
                </div>
                <div class="col">
                    <i class="fa fa-columns"></i> <?php echo $this->lang->line('column'); ?>
                    <ul class="dmart-selectable-list" id="dmart-selectable-columnlist" data-pivot-type="COLUMN">
                    </ul>   
                </div>
                <div class="col">
                    <i class="fa fa-database"></i> <?php echo $this->lang->line('value'); ?>
                    <ul class="dmart-selectable-list" id="dmart-selectable-datalist" data-pivot-type="DATA">
                    </ul>  
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="serviceId" data-dmart-serviceid="1" value="<?php echo $this->serviceId; ?>"/>
</form>    

<style type="text/css">
    .dmart-selectable-list {
        list-style: none;
        display: block;
        margin: 0;
        background-color: #eee;
        padding: 10px 10px 5px 10px;
        width: 100%;
        overflow: auto;
    }
    .dmart-selectable-list li {
        border: 1px solid #ddd;
        background-color: #fff;
        margin-bottom: 5px;
        font-size: 12px;
        min-height: 36px;
        line-height: 16px;
        cursor: move;
    }
    .dmart-selectable-list li:hover {
        background-color: #f7f7f7;
    }
    .dmart-selectable-list.drop-hover {
        background-color: #ddd;
    }
    ._jsPlumb_overlay {
        display: none;
        width: 100px;
        background-color: rgba(223, 223, 223, 0.9);
        font-size: 11px;
        line-height: 12px;
        padding: 2px;
        border: 1px #b4b4b4 solid;
        color: #000;
        z-index: 2;
    }
    ._jsPlumb_overlay._jsPlumb_hover {
        display: block;
    }
</style>
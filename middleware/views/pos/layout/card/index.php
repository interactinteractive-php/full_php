<div class="pos-wrap pos-card-layout d-flex justify-content-between">
    <div class="pos-cardleft">
        <?php echo $this->leftSidebar; ?>
    </div>
    <div class="pos-cardmiddle">
        <div class="card-options d-flex pt10 pl10 pb8 justify-content-center" style="position: sticky;top: 0;background-color: #f3f3f3;z-index: 100;">
            <a href="javascript:;" class="back-item-btn" data-actiontype="" style="display: none"><i class="icon-arrow-left8"></i></a>
            <div class="item-card-toptitle">Ангилалууд</div>
            <a href="javascript:;" class="back-item-btn change-view ml-auto" title="Жагсаалтаар харах" data-actiontype="list" style="display: none"><i class="icon-list2"></i></a>
            <a href="javascript:;" class="back-item-btn change-view ml10 active mr10" title="Картаар харах" data-actiontype="card" style="display: none"><i class="icon-grid2"></i></a>
        </div>
        <?php // require 'carditem.php'; ?>
        <div class="d-flex flex-wrap p-2 card-data-container pos-card-view pt0">     
        </div>
        <?php // require 'cardgroup.php'; ?>
    </div>
    <div class="pos-cardright">
        <div class="pos-cardcenter" style="display: none">
            <?php echo $this->centerSidebar; ?>
        </div>
        <div class="pos-cardright" style="display: none">
            <?php echo $this->rightSidebar; ?>
        </div>
    </div>
</div>
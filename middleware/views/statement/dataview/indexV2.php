<div class="col-md-12" id="statement-form-<?php echo $this->metaDataId; ?>">
    <div class="row">
        <div class="tabbable-line" id="editMetaTabDiv">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a aria-expanded="false" href="#filter_tab_<?php echo $this->metaDataId; ?>" class="nav-link active" data-toggle="tab">Шүүлтүүр</a>
                </li>
                <li class="nav-item">
                    <a aria-expanded="false" href="#report_tab_<?php echo $this->metaDataId; ?>" data-toggle="tab" class="nav-link">Тайлан</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="filter_tab_<?php echo $this->metaDataId; ?>">
                    <?php echo $this->searchForm; ?>
                </div>
                <div class="tab-pane" id="report_tab_<?php echo $this->metaDataId; ?>">
                    <?php echo $this->reportPreview; ?>
                </div>
            </div>
        </div>
    </div>
</div>


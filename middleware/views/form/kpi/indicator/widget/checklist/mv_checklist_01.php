<div class="row mv-checklist-render-parent mv-checklist1-render-parent" id="mv-checklist-render-parent-<?php echo $this->uniqId; ?>">
    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md">
        <div class="sidebar-content">

            <div class="card">
                <div class="card-body">
                    <div class="mv-checklist-title"><?php echo $this->indicatorName; ?></div>
                    <div class="mv-checklist-description">This is header description</div>
                    <div class="d-flex mt-3 mb-2" style="gap:8px;">
                        <div class="step active"></div>
                        <div class="step active"></div>
                        <div class="step"></div>
                        <div class="step"></div>
                        <div class="step"></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body mv-checklist-menu">
                    <ul class="nav nav-sidebar" data-nav-type="accordion">
                        <?php
                        foreach ($this->relationList as $k => $row) {

                            $kpiTypeId = $row['KPI_TYPE_ID'];

                            if ($kpiTypeId == 2008) {
                                $name = $row['STRUCTURE_NAME'];
                            } elseif ($row['META_DATA_ID']) {
                                $name = $row['META_DATA_NAME'];
                            } else {
                                $name = $row['NAME'];
                            }

                            $rowJson = json_encode(array(
                                'mapId'          => $row['MAP_ID'], 
                                'indicatorId'    => $row['ID'], 
                                'strIndicatorId' => $row['STRUCTURE_INDICATOR_ID'], 
                                'kpiTypeId'      => $kpiTypeId, 
                                'metaDataId'     => $row['META_DATA_ID'], 
                                'metaTypeId'     => $row['META_TYPE_ID'], 
                                'isMartRender'   => $row['IS_DATAMART_RENDER'] 
                            ));
                            $rowJson = htmlentities($rowJson, ENT_QUOTES, 'UTF-8');
                        ?>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link<?php echo ($k == 0 ? ' active' : ''); ?>" data-indicatorid="<?php echo $this->indicatorId; ?>" data-uniqid="<?php echo $this->uniqId; ?>" data-json="<?php echo $rowJson; ?>">
                                <i class="fas fa-check-circle"></i> <span><?php echo $name; ?></span>
                            </a>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="content-wrapper pt-2 pl-3 pr-3 mv-checklist-render">
        
    </div>
</div>

<style type="text/css">
.mv-checklist1-render-parent {
    margin: -10px -15px 0px -15px!important;
}
.mv-checklist1-render-parent > .sidebar {
    width: 16.875rem;
    padding: 0;
    background-color: rgb(243, 244, 246);
}
.mv-checklist1-render-parent > .sidebar .sidebar-content {
    padding: 15px 10px;
}
.mv-checklist1-render-parent .mv-checklist-title {
    color: #3C3C3C;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 700;
}
.mv-checklist1-render-parent .mv-checklist-description {
    color: #67748E;
    margin-top: 10px;
}
.mv-checklist1-render-parent > .sidebar > .sidebar-content > .card > .card-body .step {
    background: #A0A0A0;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist1-render-parent > .sidebar > .sidebar-content > .card > .card-body .step.active {
    background: #468CE2;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist1-render-parent .mv-checklist-menu {
    height: 70vh;
    padding: 0;
    margin-left: -10px;
    margin-right: -10px;
    overflow: auto;
}
.mv-checklist1-render-parent > .sidebar .card-body .nav-sidebar a.nav-link {
    display: flex;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    padding: 10px 22px 10px 10px;
    overflow: hidden;
    font-size: 11px;
    text-transform: none;
}
.mv-checklist1-render-parent > .sidebar .card-body .nav-sidebar a.nav-link:hover {
    background-color: #E8EBF0;
    color: #468CE2;
}
.mv-checklist1-render-parent > .sidebar .card-body .nav-sidebar a.nav-link i {
    font-size: 18px;
    margin-right: 10px;
}
.mv-checklist1-render-parent > .sidebar .card-body .nav-sidebar a.nav-link span {
    font-size: 12px;
    font-weight: 600;
}
.mv-checklist1-render-parent > .sidebar .card-body .nav-sidebar a.nav-link.active {
    background-color: #E8EBF0;
    color: #468CE2;
}
</style>

<?php require getBasePath() . 'middleware/views/form/kpi/indicator/checklist/scripts.php'; ?>
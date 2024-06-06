<?php 
$colSplit = '12';
if (isset($this->getDataview)) { ?>
    <style type="text/css">
        #load-news-dataview-<?php echo $this->uniqId; ?> {
            margin-top: 35px;
            padding-left: 20px;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .table.table-light > tbody > tr > td {
            border-bottom-color: #f2f5f840;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .viewer-container .card.light .card-header {
            border: none;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .explorer-table-cell {
            background-color: #ffffff5c !important;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .object-height-row2-minus-<?php echo $this->getDataview ?> {
            background-color: #ffffff5c !important;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .meta-toolbar {
            background-color: #ffffff5c !important;
            background: none;
            min-height: 26px;
            /*height: 39px;*/
            padding: 6px 0 6px 0;
            margin-left: -10px;
            margin-right: -10px;
            margin-bottom: 0;
            border-bottom-color: #f2f5f840;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .meta-toolbar span {
            color: #333;
            padding-left: 6px;
        }
        div.vr-card-submenu {
            padding: 0 !important;
            margin: 30px -20px 20px 0px;
        }
    </style>
<?php
    $colSplit = '9';
    echo '<div class="col-md-3" id="load-news-dataview-' . $this->uniqId . '"></div>';
} ?>
<div class="col-md-<?php echo $colSplit ?>">
    <?php
    if ($this->menuList['status'] == 'success' && isset($this->menuList['menuData'])) {
    ?>
    <div class="vr-card-menu vr-card-submenu animated zoomIn">
        <?php
        $metaDataId = isset($this->metaDataId) ? $this->metaDataId : '';
        foreach ($this->menuList['menuData'] as $row) {
            
            $linkHref = 'javascript:;';
            $linkTarget = '_self';
            $linkOnClick = '';
            $backLinkTarget = '';
            if ($row['isshowcard'] == 'true') {
                $linkHref = 'appmenu/sub/'.$row['code'];
                $linkTarget = '_self';
                $linkOnClick = '';
            } elseif (!empty($row['weburl'])) {
                if(strtolower(substr($row['weburl'], 0, 4)) == 'http') {
                    $linkHref = $row['weburl'];
                }else{
                    $linkHref = $row['weburl'].'&mmid='.$row['metadataid'];                
                }
                $linkTarget = $row['urltrg'];
                $linkOnClick = '';
            } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {
                $linkHref = 'appmenu/module/'.$row['metadataid'].'?mmid='.$row['metadataid'];
                $linkTarget = '_self';
                $linkOnClick = '';
            } else {
                if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                    $linkHref = 'appmenu/module/'.$row['metadataid'].'/'.$row['actionmetadataid'].'?mmid='.$row['metadataid'];
                    $linkTarget = '_self';
                    $linkOnClick = '';
                } else {
                    $linkMeta = Mdmeta::menuServiceAnchor($row, $row['metadataid'], $row['metadataid'], $this->isTab, $this->uniqId, $metaDataId);
                    $linkHref = $linkMeta['linkHref'];
                    $backLinkTarget = $linkMeta['backLinkTarget'];
                    $linkTarget = $linkMeta['linkTarget'];
                    $linkOnClick = $linkMeta['linkOnClick'];
                }
            }
            if ($row['metadataid'] == '1484275008377720') {
                echo Form::create(array('id' => 'etoken-form', 'class' => 'etoken-form', 'method' => 'post', 'action' => 'login/runEToken', 'autocomplete' => 'off', 'style' => 'display: none;')); 
                echo Form::text(array('name' => 'seasonId', 'id' => 'seasonId', 'class' => 'form-control placeholder-no-fix', 'required' => 'required', 'autocomplete' => 'off')); 
                echo Form::text(array('name' => 'redirecturl', 'class' => 'form-control placeholder-no-fix', 'required' => 'required', 'autocomplete' => 'off', 'value' => $linkMeta['linkHref'])); 
                echo Form::close();   
                
                $linkHref = 'javascript:;';
                $linkTarget = '_self';
                $linkOnClick = 'ShowTokenLoginWin();';
            }
            
            $imgSrc = 'assets/custom/addon/admin/layout4/img/entrustment_05.png';
            if ($row['photoname'] != '' && file_exists($row['photoname'])) {
                $imgSrc = $row['photoname'];
            }
        ?>
        <a href="<?php echo $linkHref; ?>" target="<?php echo $linkTarget; ?>" onclick="<?php echo $linkOnClick; ?>" back-target-metadataid="<?php echo $backLinkTarget; ?>" class="vr-submenu-tile animated" data-metadataid="<?php echo $row['metadataid']; ?>" data-pfgotometa="1">
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <img class="dv-directory-img" src="<?php echo $imgSrc; ?>" data-default-image="assets/custom/addon/admin/layout4/img/entrustment_05.png" onerror="onDataViewImgError(this);">
                </div>
            </div>
            <div class="second-title">
                <h4>
                    <?php echo $this->lang->line($row['name']); ?>
                </h4>
            </div>
        </a> 
        <?php
        }
        ?>
        <div class="clearfix"></div>
    </div>
    <?php
    } else {
        echo html_tag('div', array('class' => 'alert alert-danger'), $this->menuList['message']);
    }
    ?>
</div>
    
<?php if (isset($this->getDataview)) { ?>
    <script type="text/javascript">    
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/<?php echo $this->getDataview; ?>/0/json',
            data: {
            },
            dataType: 'json',
            success: function (data) {
                $('#load-news-dataview-<?php echo $this->uniqId; ?>').append(data.Html);
            }
        });     
    </script>
<?php 
} 
echo (new Mduser())->startupMetaScriptFooter();
?> 
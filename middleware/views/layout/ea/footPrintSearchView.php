<?php 
if (isset($this->dataRow['data']) && $this->dataRow['data']) {
    foreach ($this->dataRow['data'] as $gkey => $gRow) { ?>
        <div class="card">
            <div class="card-header header-elements-inline" style="background: #e26a6a; padding: 10px; color: #FFF !important;">
                <h5 class="card-title" style="color: #FFF !important;"><?php echo $gRow['groupName'] ?></h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" style="min-height: 500px; overflow-x: auto; overflow-y: hidden; padding-bottom: 10px;">
            <?php if (isset($gRow['children']) && $gRow['children']) { 
                foreach ($gRow['children'] as $ckey => $cRow) { ?>
                    <div style="width: 186020px;">
                        <div class="vlayout-body-<?php echo $this->uniqId; ?>">
                            <div class="col-md-12 pl0 pr0" style="border-bottom: 2px solid #CCC; padding-bottom: 10px; width: 100%; float:left">
                                <div class="col-md-12 mt35">
                                    <span style="font-weight: bold;"><?php echo $cRow['name'] ?></span>
                                </div>
                                <?php if (isset($cRow['children']) && $cRow['children']) {  ?>
                                <div class="col-md-12">
                                    <div class="threeColModel_header_<?php echo $this->dataViewId ?>">
                                        <?php foreach ($cRow['children'] as $ckKey => $ckRow) { ?>
                                            <div class="threeColModel_valueChainObject small bg-primary8" style="" id="">
                                                <div style="padding-top: 11.5px;" class="a-title"><?php echo $ckKey.'. '.$ckRow['name'] ?></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php 
                                $maxLen = sizeOf($cRow['children']);
                                    for ($i = 0; $i < sizeof($cRow['children']); $i++) { ?>
                                        <div class="col-md-12">
                                            <div class="threeColModel_footer_<?php echo $this->dataViewId ?> mb5">
                                                <?php  for ($j = 0; $j < $maxLen; $j++) { 
                                                    if (isset($cRow['children'][$j]['children']) && sizeOf($cRow['children'][$j]['children']) > $maxLen) {
                                                        $maxLen = $cRow['children'][$j]['children'];
                                                    }

                                                    if (isset($cRow['children'][$j]['children'][$i]['name']) && $cRow['children'][$j]['children'][$i]['name']) { ?>
                                                        <div class="threeColModel_footer_body_<?php echo $this->dataViewId ?>">
                                                            <div class="add"></div>
                                                            <div class="threeColModel_valueChainObject small bg-primary8" style="" id="">
                                                                <div style="padding-top: 11.5px;" class="a-title"><?php echo $cRow['children'][$j]['children'][$i]['name']; ?></div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
            </div>
        </div>

<?php 
    }
} ?>
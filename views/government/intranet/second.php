<?php if ($this->firstLoadDv || $this->ispinpostDv) {
    if ($this->ispinpostDv) { ?>
            <div class="date-filter" data-toggle="collapse" href="#collapse_pinpost">
                <?php echo 'Онцлох'; ?>
                <i class="icon-arrow-down5"></i>
            </div>
            <div class="collapse show" id="collapse_pinpost">
                <?php foreach ($this->ispinpostDv as $row) {
                    $dataRow = $row;
                    $dataRow['body'] = '';
                    $contextmenu = $iscreateduser = '';
                    if ((defined('IS_ADMIN') && IS_ADMIN == '1') || $row['userid'] === Ue::sessionUserId() || (Ue::sessionUserId() === '2' || Ue::sessionUserId() === '1')) {
                        $contextmenu = 'dv-twocol-remove-li-' . $this->otherUniqId;
                    }

                    $rowJson = htmlentities(json_encode($dataRow), ENT_QUOTES, 'UTF-8'); ?>
                    <li class="<?php echo (issetParam($row['isread']) == '0') ? 'unread' : '' ?> dv-twocol-remove-li <?php echo $contextmenu; ?>" data-id="<?php echo $row['id'] ?>">
                        <a href="javascript:void(0);" onclick="getNewsContent_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>')" data-second-id="<?php echo $row['id'] ?>" data-secondprocessid="1560141744604" data-secondtypecode="process" class="media d-flex align-items-center justify-content-center" id="edit<?php echo $row['id'] ?>" data-rowdata="<?php echo $rowJson ?>">
                            <span class="line-height-0"></span>
                            <span class="mr-2 text-right" style="width:29px;">
                                <?php 
                                    $htmli = '';
                                    
                                    if (issetParam($row['isurgent']) === '1') {
                                        $htmli .= '<i class="fa fa-bolt text-warning-300 mr5"></i>';
                                    }
                                    if (issetParam($row['ispin']) === '1') {
                                        $htmli .= '<i class="icon-pushpin text-warning-300"></i>';
                                    } else { 
                                        $htmli .= '<i class="icon-file-text2 text-gray"></i>';
                                    }
                                    
                                    echo $htmli;
                                ?>
                            </span>
                            <div class="media-body">
                                <div id="removal<?php echo $row['id'] ?>" class="media-title d-flex flex-row mb-0" style="line-height: normal;font-size: 12px;">
                                    <div class="<?php echo (issetParam($row['isread']) == '0') ? 'font-weight-bold' : '' ?>"><?php echo $row['description'] ?></div>
                                    <?php if (issetParam($row['isattached'])) { ?>
                                        <span class="ml-auto ">
                                            <i class="icon-attachment text-gray font-size-15" style="top: 0;"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted font-size-sm mr-1 <?php echo (issetParam($row['isread']) == '0') ? 'font-weight-bold' : '' ?>"><?php echo issetParam($row['tsag']) ?></span>
                                    <span class="text-muted font-size-sm <?php echo (issetParam($row['isread']) == '0') ? 'font-weight-bold' : '' ?>"><?php echo issetParam($row['minute']); ?></span>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            </div>
        <?php
    }
    $index = 1;
    if ($this->firstLoadDv) {
        foreach ($this->firstLoadDv as $key => $grouped) { ?>
            <div class="date-filter" data-toggle="collapse" href="#collapse<?php echo $index.'_'.$this->otherUniqId ?>" data-target222="<?php echo $key; ?>" aria-expanded="true">
                <?php echo $key; ?>
                <i class="icon-arrow-down5"></i>
            </div>
            <div class="collapse show" id="collapse<?php echo $index.'_'.$this->otherUniqId ?>" data-source222="<?php echo $key; ?>">
                <?php foreach ($grouped as $row) {
                    $dataRow = $row;
                    $dataRow['body'] = '';
                    $contextmenu = $iscreateduser = '';

                    if ((defined('IS_ADMIN') && IS_ADMIN == '1') || $row['userid'] === Ue::sessionUserId() || (Ue::sessionUserId() === '2' || Ue::sessionUserId() === '1')) {
                        $contextmenu = 'dv-twocol-remove-li-' . $this->otherUniqId;
                    }

                    $rowJson = htmlentities(json_encode($dataRow), ENT_QUOTES, 'UTF-8'); ?>
                    <li class="<?php echo (issetParam($row['isread']) == '0') ? 'unread' : '' ?> dv-twocol-remove-li <?php echo $contextmenu; ?>" data-id="<?php echo $row['id'] ?>">
                        <a href="javascript:void(0);" onclick="getNewsContent_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>')" data-second-id="<?php echo $row['id'] ?>" data-secondprocessid="1560141744604" data-secondtypecode="process" class="media d-flex align-items-center justify-content-center" id="edit<?php echo $row['id'] ?>" data-rowdata="<?php echo $rowJson ?>">
                            <span class="line-height-0"></span>
                            <span class="mr-2 text-right" style="width:29px;">
                                <?php 
                                    $htmli = '';
                                    
                                    if (issetParam($row['isurgent']) === '1') {
                                        $htmli .= '<i class="fa fa-bolt text-warning-300 mr5"></i>';
                                    }
                                    
                                    if (issetParam($row['ispin']) === '1') {
                                        $htmli .= '<i class="icon-pushpin text-warning-300"></i>';
                                    } else {
                                        $htmli .= '<i class="icon-file-text2 text-gray"></i>';
                                    }
                                    echo $htmli;
                                ?>
                            </span>
                            <div class="media-body">
                                <div id="removal<?php echo $row['id'] ?>" class="media-title d-flex flex-row mb-0" style="line-height: normal;font-size: 12px;">
                                    <div class="<?php echo (issetParam($row['isread']) == '0') ? 'font-weight-bold' : '' ?>"><?php echo issetParam($row['description']) ?></div>
                                    <?php if (issetParam($row['isattached'])) { ?>
                                        <span class="ml-auto">
                                            <i class="icon-attachment text-gray font-size-15" style="top: 0;"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted font-size-sm mr-1 <?php echo (issetParam($row['isread']) == '0') ? 'font-weight-bold' : '' ?>"><?php echo issetParam($row['tsag']) ?></span>
                                    <span class="text-muted font-size-sm <?php echo (issetParam($row['isread']) == '0') ? 'font-weight-bold' : '' ?>"><?php echo issetParam($row['minute']); ?></span>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php 
                } ?>
            </div>
        <?php
        $index++;
        }
    }
} else {
    echo '<center class="text-grey mt-3">Тохирох үр дүн олдсонгүй</center>';
} ?>
<style type="text/css">
    .w-190px  {
        width: 190px;
    }
    .text-ellipsis-d {
        width: 100%;
        word-wrap: break-word;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: "li.dv-twocol-remove-li-<?php echo $this->otherUniqId ?>",
            callback: function(key, opt) {
                
                var $this = $(opt['$trigger'][0]);
                var id = $this.data('id');
                var element = $this.closest('li');
                var rowData = JSON.parse($this.find('a').attr('data-rowData'));
                
                if (key === 'edit') {
                    if (rowData.canedit !== '1') {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Анхааруулга',
                            text: 'Та засах эрхгүй байна',
                            type: 'warning',
                            sticker: false
                        });
                    } else {
                        editPost_<?php echo $this->uniqId ?>(id, element);    
                    }
                }
                if (key === 'trash') {
                    var $selector = appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]');
                    dialogConfirm_<?php echo $this->uniqId ?> ('deletePost_<?php echo $this->uniqId; ?>', 'Та устгахдаа итгэлтэй байна yy?', id, $selector);
                    //deletePost_<?php echo $this->uniqId ?>(id, element);
                }
                
            },
            items: {
                "edit": {name: plang.get('Edit'), icon: "edit"},
                "trash": {name: plang.get('Trash'), icon: "trash"},
            }
        });
    });
</script>
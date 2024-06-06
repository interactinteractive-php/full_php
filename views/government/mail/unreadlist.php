<?php if ($this->unreadLoadDv) {
    $index = 1;
    foreach ($this->unreadLoadDv as $key => $grouped) { ?>
        <div class="date-filter" data-toggle="collapse" href="#collapse<?php echo $index ?>">
            <?php echo $key; ?>
            <i class="icon-arrow-down5"></i>
        </div>
        <div class="collapse show" id="collapse<?php echo $index ?>">
            <?php foreach ($grouped as $row) { 
                $dataRow = $row;
                $dataRow['body'] = '';
                $rowJson = htmlentities(json_encode($dataRow), ENT_QUOTES, 'UTF-8'); ?>
                <li data-mailid="<?php echo $row['id'] ?>" class="<?php echo $row['isread'] == '0' ? 'unread' : '' ?> contextmenu-li-tag-<?php echo $this->otherUniqId ?>" data-rowdata="<?php echo $rowJson; ?>" data-getprocesscode="<?php echo $row['getprocesscode'] ?>" style="min-height: 60px;">
                    <a href="javascript:void(0);" class="media" style="min-height: 60px;">
                        <div class="media-body">
                            <div class="d-flex flex-row align-items-center">
                                <div class="media-title mb-0 line-height-normal w-190px <?php echo $row['isread'] == '0' ? 'font-weight-bold' : '' ?>"><div class="text-ellipsis-d"><?php echo $row['username'] ?></div></div>
                                <div class="ml-auto">
                                    <?php 
                                    if ($row['isattached'] == '1') {
                                        echo '<span class="ml-auto mr-1"><i class="icon-attachment text-gray"></i></span>';
                                    } ?>
                                    <span class="date-time"><?php echo $row['createddate'] ?></span>
                                </div>
                            </div>
                            <span class="title w-190px <?php echo $row['isread'] == '0' ? 'font-weight-bold' : '' ?>"><div class="text-ellipsis-d"><?php echo $row['title'] ?></div></span>
                            <span class="desc w-190px "><div class="text-ellipsis-d"><?php echo Str::htmltotext($row['body']) ?></div></span>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </div>
    <?php
    $index++;
    }
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
            selector: "li.contextmenu-li-tag-<?php echo $this->otherUniqId ?>",
            callback: function(key, opt) {
                if (key === 'reply') {
                    replyforward_<?php echo $this->uniqId ?>(opt['$trigger'][0], 'r');
                }
                if (key === 'forward') {
                    replyforward_<?php echo $this->uniqId ?>(opt['$trigger'][0], 'f');
                }
                if (key === 'trash') {
                    trash_<?php echo $this->uniqId ?>(opt['$trigger'][0]);
                }
            },
            items: {
                <?php if ($this->isinbox == '1') { ?>
                    "reply": {name: plang.get('Reply'), icon: "mail-reply"},
                <?php } ?>
                "forward": {name:  plang.get('Forward'), icon: "mail-forward"},
                "trash": {name: plang.get('Trash'), icon: "trash"},
            }
        });
    });
</script>
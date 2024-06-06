<div class="sidebar sidebar-light sidebar-secondary sidebar-secondary-2 sidebar-expand-md">
    <div class="sidebar-content">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-m font-weight-bold"><i class="icon-info22 mr-1 small"></i> Үндсэн мэдээлэл</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-toggle="collapse" href="#card-collapse-info" role="button" aria-expanded="false" aria-controls="card-collapse-info">
                            <i class="icon-chevron-down"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="collapse show height-scroll" id="card-collapse-info">
                <div class="pl-2 pr-2">
                    <table class="table table-borderless table-xs my-2">
                        <tbody>
                            <tr>
                                <td class="widthst"><i class="icon-file-text mr-2"></i> Чиглэл:</td>
                                <td class="text-right dvdetail"><a href="javascript:void(0);"><?php echo Arr::get($this->getRow, 'directionname'); ?></a></td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-files-empty mr-2"></i> Нэр төрөл:</td>
                                <td class="text-right dvdetail"><?php echo Arr::get($this->getRow, 'documenttypename'); ?></td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-file-check mr-2"></i> Цахим дугаар:</td>
                                <td class="text-right dvdetail"><?php echo Arr::get($this->getRow, 'documentcode'); ?></td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-file-plus mr-2"></i> Баримтын дугаар:</td>
                                <td class="text-right dvdetail">
                                    <a href="javascript:void(0);">
                                        <?php if (!empty(Arr::get($this->getRow, 'documentnumber'))) {
                                            echo Arr::get($this->getRow, 'documentnumber');
                                            } else {
                                                echo "&nbsp;";
                                            }
                                        ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-file-eye mr-2"></i> Үүсгэгч:</td>
                                <td class="text-right text-muted dvdetail"><?php echo Arr::get($this->getRow, 'customername'); ?></td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-file-text2 mr-2"></i> Огноо:</td>
                                <td class="text-right text-muted dvdetail"><?php echo Arr::get($this->getRow, 'createddate'); ?></td>
                            </tr>

                            <tr>
                                <td class="widthst"><i class="icon-file-text2 mr-2"></i> Хариуны огноо:</td>
                                <td class="text-right text-muted dvdetail"><?php echo Arr::get($this->getRow, 'responsedate'); ?></td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-file-spreadsheet mr-2"></i> Тэргүү:</td>
                                <td class="text-right text-muted dvdetail"><?php echo Arr::get($this->getRow, 'documentname'); ?></td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-copy2 mr-2"></i> Цаасан суурьтай:</td>
                                <td class="text-right text-muted dvdetail">
                                    <?php $edoc = Arr::get($this->getRow, 'isedoc');
                                        if ($edoc == 1) { echo 'Тийм';} else { echo 'Үгүй'; } ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="widthst"><i class="icon-file-eye mr-2"></i> Нууцлал:</td>
                                <td class="text-right text-muted dvdetail"><?php echo Arr::get($this->getRow, 'priorityname'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (isset($this->getRow['dm_wfm_assignment_dv']) && $this->getRow['dm_wfm_assignment_dv']) {
                    foreach ($this->getRow['dm_wfm_assignment_dv'] as $key => $row) {
                        if ($row['userid'] == $this->sessionuserkeyid) { ?>
                            <div class="card-header bg-transparent header-elements-inline">
                                <span class="text-uppercase font-size-m font-weight-bold"><i class="icon-file-empty2 mr-1 small"></i> Удирдлагын заалт</span>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-toggle="collapse" href="#card-collapse-udirdlaga" role="button" aria-expanded="false" aria-controls="card-collapse-udirdlaga">
                                            <i class="icon-chevron-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse show" id="card-collapse-udirdlaga">
                                <table class="table table-borderless table-xs my-2">
                                    <tbody>
                                        <tr>
                                            <td class="widthst"><i class="icon-files-empty mr-2"></i> Карт хаасан:</td>
                                            <td class="text-right dvdetail"><a href="javascript:void(0);"><?php echo $row['username']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td style="width:160px;" class="widthst"><i class="icon-files-empty mr-2"></i> Удирдлагын заалт:</td>
                                            <td class="text-right dvdetail"><a href="javascript:void(0);"><?php echo $row['assignedtablename']; ?></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        } else {
                            //haragdahgui
                        }
                        ?>
                        <?php
                    }
                }
                ?>
        </div>
    </div>
</div>
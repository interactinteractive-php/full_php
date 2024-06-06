<?php if (issetParamArray($this->layoutPositionArr['pos_11'])) {
    $currentDate = Date::currentDate();
    $dd = Date::format('D', $currentDate);
    if (Config::getFromCache('addWorkin7Days') === '1') {
        $a7 = Date::weekdayAfter('Y-m-d', $currentDate, ' -6 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 6, false);
        $a6 = Date::weekdayAfter('Y-m-d', $currentDate, ' -5 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 5, false);
        $a1 = Date::weekdayAfter('Y-m-d', $currentDate, ' -4 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 4, false);
        $a2 = Date::weekdayAfter('Y-m-d', $currentDate, ' -3 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 3, false);
        $a3 = Date::weekdayAfter('Y-m-d', $currentDate, ' -2 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 2, false);
        $a4 = Date::weekdayAfter('Y-m-d', $currentDate, ' -1 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 1, false);
        $a5 = Date::weekdayAfter('Y-m-d', $currentDate, ' -0 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 0, false);
        ?>
        <table class="table task-table task-<?php echo $this->uniqId; ?>" id="example<?php echo $this->uniqId ?>">
            <thead style="background-color: #f9f9fd;">136
                <tr>
                    <th rowspan="2" style="vertical-align:bottom;">Нэр</th>
                    <th class="text-center border-0" colspan="7" style="vertical-align:bottom;"></th>
                    <th rowspan="2" style="vertical-align:bottom;width: 150px;">Ажлын гүйцэтгэл</th>
                    <th class="text-right" rowspan="2" style="vertical-align:bottom;width: 150px;">Баримт бичиг шийдвэрлэлт</th>
                    <!-- <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th> -->
                </tr>
                <tr>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a7); ?>"><?php echo Date::format('m/d', $a7) . ' ('. Lang::line(Date::format('D', $a7)) .')' ?></th>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a6); ?>"><?php echo Date::format('m/d', $a6) . ' ('. Lang::line(Date::format('D', $a6)) .')' ?></th>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a1); ?>"><?php echo Date::format('m/d', $a1) . ' ('. Lang::line(Date::format('D', $a1)) .')' ?></th>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a2); ?>"><?php echo Date::format('m/d', $a2) . ' ('. Lang::line(Date::format('D', $a2)) .')' ?></th>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a3); ?>"><?php echo Date::format('m/d', $a3) . ' ('. Lang::line(Date::format('D', $a3)) .')' ?></th>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a4); ?>"><?php echo Date::format('m/d', $a4) . ' ('. Lang::line(Date::format('D', $a4)) .')' ?></th>
                    <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a5); ?>"><?php echo Date::format('m/d', $a5) . ' ('. Lang::line(Date::format('D', $a5)) .')' ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($this->layoutPositionArr['pos_11'] as $key => $row) { ?>
                        <tr data-id="<?php echo issetParam($row['userid']) ?>">
                            <td style="width: 350px;">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <a href="javascript:void(0);">
                                            <img src="<?php echo issetParam($row['picture']) ?>" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="<?php echo issetParam($row['userid']) ? $row['userid'] : '' ?>">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="text-blue font-weight-bold" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);"><?php echo issetParam($row['employeename']) ?></a>
                                        <div class="text-muted font-size-sm">
                                            <div class="line-height-normal font-size-11"><?php echo $row['positionname'] ?></div>
                                            <span class="line-height-normal font-size-11"><?php echo $row['departmentname'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="<?php echo Date::format('D', $a7); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a7)) . 'outtime']) ?></div></td>
                            <td class="<?php echo Date::format('D', $a6); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a6)) . 'outtime']) ?></div></td>
                            <td class="<?php echo Date::format('D', $a1); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a1)) . 'outtime']) ?></div></td>
                            <td class="<?php echo Date::format('D', $a2); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a2)) . 'outtime']) ?></div></td>
                            <td class="<?php echo Date::format('D', $a3); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a3)) . 'outtime']) ?></div></td>
                            <td class="<?php echo Date::format('D', $a4); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a4)) . 'outtime']) ?></div></td>
                            <td class="<?php echo Date::format('D', $a5); ?>"><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a5)) . 'outtime']) ?></div></td>
                            <td style="width: 150px;">
                                <?php echo issetParam($row['taskpercent']) ?>
                                <div class="progress mt-1" style="height: 0.375rem;">
                                    <div class="progress-bar bg-success" style="width: <?php echo issetParam($row['taskpercent2']) ?>%">
                                        <span class="sr-only"><?php echo issetParam($row['taskpercent2']) ?>% Complete</span>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 150px;">
                                <div class="d-flex align-items-center justify-content-end">
                                    <span class="text-black font-weight-bold mr-2"><?php echo (issetParam($row['documentcc']) == '0/0') ? '' : issetParam($row['documentcc']) ?></span>
                                    <span class="badge border-radius-100 <?php echo (int) issetParam($row['totalpercent']) > '50' ? 'bg-success' : 'bg-danger';  ?> "><?php echo (issetParam($row['documentcc']) == '0/0') ? '' : issetParam($row['totalpercent']) ?></span>
                                </div>
                            </td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php 
    } else {
        
        switch ($dd) {
            case 'Sun':
                $currentDate = date('Y-m-d', strtotime($currentDate . ' - 2 days')); Date::lastDay('Y-m-d', $currentDate, '2');
                break;
            case 'Sat':
                $currentDate = date('Y-m-d', strtotime($currentDate . ' - 1 days')); Date::lastDay('Y-m-d', $currentDate, '1');
                break;
            default:
                break;
        }
		$a1 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 4);
		$a2 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 3);
		$a3 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 2);
		$a4 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 1);
		$a5 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 0);  ?>
    
    <table class="table task-table task-<?php echo $this->uniqId; ?>" id="example<?php echo $this->uniqId ?>">
        <thead style="background-color: #f9f9fd;">
            <tr>
                <th rowspan="2" style="vertical-align:bottom;">Нэр</th>
                <th class="text-center border-0" colspan="5" style="vertical-align:bottom;"></th>
                <th rowspan="2" style="vertical-align:bottom;width: 150px;">Ажлын гүйцэтгэл</th>
                <th class="text-right" rowspan="2" style="vertical-align:bottom;width: 150px;">Баримт бичиг шийдвэрлэлт</th>
                <!-- <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th> -->
            </tr>
            <tr>
                <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a1) . ' ('. Lang::line(Date::format('D', $a1)) .')' ?></th>
                <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a2) . ' ('. Lang::line(Date::format('D', $a2)) .')' ?></th>
                <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a3) . ' ('. Lang::line(Date::format('D', $a3)) .')' ?></th>
                <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a4) . ' ('. Lang::line(Date::format('D', $a4)) .')' ?></th>
                <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a5) . ' ('. Lang::line(Date::format('D', $a5)) .')' ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($this->layoutPositionArr['pos_11'] as $key => $row) {
                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr data-id="<?php echo issetParam($row['userid']) ?>" data-row="<?php echo $rowJson; ?>">
                        <td style="width: 350px;">
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <a href="javascript:void(0);">
                                        <img src="<?php echo issetParam($row['picture']) ?>" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="<?php echo issetParam($row['userid']) ? $row['userid'] : '' ?>">
                                    </a>
                                </div>
                                <div>
                                    <a href="javascript:void(0);" class="text-blue font-weight-bold" onclick="gridDrillDownLink(this, 'GOV_NEW_DASHBOARD_LIST', 'metagroup', '1', '', '1587095552879524', 'firstname', '1533787071544', 'employeeid=<?php echo issetParam($row['employeeid']); ?>', true, undefined,  '',  '')"><?php echo issetVar($row['employeename']) ?></a>
                                    <div class="text-muted font-size-sm">
                                        <div class="line-height-normal font-size-11"><?php echo issetVar($row['positionname']) ?></div>
                                        <span class="line-height-normal font-size-11"><?php echo issetVar($row['departmentname']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a1)) . 'outtime']) ?></div></td>
                        <td><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a2)) . 'outtime']) ?></div></td>
                        <td><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a3)) . 'outtime']) ?></div></td>
                        <td><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a4)) . 'outtime']) ?></div></td>
                        <td><div class="text-black" style="width: 80px;"><?php echo issetParam($row[Str::lower(Date::format('l', $a5)) . 'outtime']) ?></div></td>
                        <td style="width: 150px;">
                            <?php echo issetParam($row['taskpercent']) ?>
                            <div class="progress mt-1" style="height: 0.375rem;">
                                <div class="progress-bar bg-success" style="width: <?php echo issetParam($row['taskpercent2']) ?>%">
                                    <span class="sr-only"><?php echo issetParam($row['taskpercent2']) ?>% Complete</span>
                                </div>
                            </div>
                        </td>
                        <td style="width: 150px;">
                            <div class="d-flex align-items-center justify-content-end">
                                <span class="text-black font-weight-bold mr-2"><?php echo (issetParam($row['documentcc']) == '0/0') ? '' : issetParam($row['documentcc']) ?></span>
                                <span class="badge border-radius-100 <?php echo (int) issetParam($row['totalpercent']) > '50' ? 'bg-success' : 'bg-danger';  ?> "><?php echo ($row['documentcc'] == '0/0') ? '' : $row['totalpercent'] ?></span>
                            </div>
                        </td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } 
} ?>
<div class="col-md-12 pl0">
    <?php
    if (isset($this->connectionData['connection']) && $this->connectionData['connection']) {
        ?>
        <table class="table table-bordered connection-table">
            <thead>
                <tr>
                    <th class="ta-c" style="width: 15%;">Порт</th>
                    <th class="ta-c" style="width: 5%;">Портын дугаар</th>
                    <th class="ta-c" style="width: 15%;"><?php echo $this->lang->line('source_address'); ?></th>
                    <th class="ta-c" style="width: 15%;"><?php echo $this->lang->line('destination_address'); ?></th>
                    <th class="ta-c" style="width: 10%;"><?php echo $this->lang->line('circuit_id'); ?></th>
                    <th class="ta-c" style="width: 10%;"><?php echo $this->lang->line('installation_date'); ?></th>
                    <th class="ta-c" style="width: 25%;">Төхөөрөмж</th>
                    <th class="ta-c" style="width: 5%;">Портын дугаар</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->connectionData['connection'] as $key => $row) { ?>
                    <?php
                    for ($index = $row['START_PORT_NUM']; $index <= $row['PORT_QTY']; $index++) {
                        $i = $index - 1;
                        if ($row['START_PORT_NUM'] == 0 && $index == $row['PORT_QTY']) {
                            break;
                        }
                        $row['PORT_ORDER'] = $index;
                        $isConnected = '';
                        $installation = '';
                        $isSrc = false;
                        if (!empty($this->installation)) {
                            foreach ($this->installation as $key => $ins) {
                                if ($ins['SRC_PORT_ID']) {
                                    if (($row['ASSET_PORT_ID'] == $ins['SRC_PORT_ID'] ) && ($row['PORT_TYPE_ID'] == $ins['SRC_PORT_TYPE_ID'] ) && ($row['PORT_ORDER'] == $ins['SRC_PORT'] )) {
                                        $isConnected = 'connected';
                                        $installId = $ins['INSTALLATION_ID'];
                                        $installation = $ins;
                                        $isSrc = true;
                                    }
                                }
                                if ($ins['TRG_PORT_ID']) {
                                    if (($row['ASSET_PORT_ID'] == $ins['TRG_PORT_ID'] ) && ($row['PORT_TYPE_ID'] == $ins['TRG_PORT_TYPE_ID'] ) && ($row['PORT_ORDER'] == $ins['TRG_PORT'] )) {
                                        $isConnected = 'connected';
                                        $installId = $ins['INSTALLATION_ID'];
                                        $installation = $ins;
                                        $isSrc = false;
                                    }
                                }
                            }
                        }
                        $installId = ($isConnected) ? $installId : '0';
                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                        $info = '';
                        $port = '';
                        if ($isConnected) {
                            $info = isset($installation['INSTALLATION_DTL'][0]) ? (($isSrc) ? $installation['INSTALLATION_DTL'][0]['PORT_INFO'] : $installation['INSTALLATION_DTL'][0]['SR_PORT_INFO']) : '';
                            $port = isset($installation['INSTALLATION_DTL'][0]) ? (($isSrc) ? $installation['INSTALLATION_DTL'][0]['PORT'] : $installation['INSTALLATION_DTL'][0]['SRC_PORT']) : '';
                        }
                        ?>
                        <tr>
                            <?php if ($index == $row['START_PORT_NUM']) { ?>
                                <td class="ta-c" rowspan="<?php echo $row['PORT_QTY'] ?>">
                                    <div class="connection-table">
                                        <div class="connection-table-row">
                                            <div class="connection-table-cell-right mix-grid">
                                                <h2><?php echo $row['PORT_TYPE_NAME'] ?></h2>
                                                <div class="vr-menu-img">
                                                    <img  src="<?php echo $row['ICON'] ?>" width="50px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                            <td class="ta-c" id="<?php echo $row['ASSET_PORT_ID'] . '-' . $row['PORT_ORDER']; ?>">
                                <a href="javascript:;" target="_self" data-row-data="<?php echo $rowJson ?>" onclick="callConnectionPort_<?php echo $this->uniqId ?>(this, '<?php echo $this->assetId ?>', '<?php echo $this->uniqId ?>', '<?php echo $this->locationId ?>', '<?php echo $this->directorypath ?>', '<?php echo $this->checkkeyid ?>', <?php echo isset($installation['INSTALLATION_DTL'][0]) ? (($isConnected) ? $installation['INSTALLATION_DTL'][0]['CONNECTION_ID'] : '0') : '0' ?>, '',<?php echo ($isConnected) ? $installId : '0'; ?>)" 
                                   class="vr-connection-path mix 002 mix_all <?php echo $isConnected ?>" data-assetportid="<?php echo $row['ASSET_PORT_ID'] ?>" data-installId="<?php echo $installId ?>" style=" display: block; opacity: 1;">
                                    <div class="vr-index-cell"><?php echo $index; ?></div>
                                </a>
                            </td>
                            <td class="ta-c">
                                <div class="vr-menu-name target-remove" data-app-name="true"><?php echo ($isConnected) ? isset($installation['INSTALLATION_DTL'][0]['SOURCE_ADDRESS']) ? $installation['INSTALLATION_DTL'][0]['SOURCE_ADDRESS'] : '' : ''; ?></div>
                            </td>
                            <td class="ta-c">
                                <div class="vr-menu-name target-remove" data-app-name="true"><?php echo ($isConnected) ? isset($installation['INSTALLATION_DTL'][0]['DESTINATION_ADDRESS']) ? $installation['INSTALLATION_DTL'][0]['DESTINATION_ADDRESS'] : '' : ''; ?></div>
                            </td>
                            <td class="ta-c">
                                <div class="vr-menu-name target-remove" data-app-name="true"><?php echo ($isConnected) ? isset($installation['INSTALLATION_DTL'][0]['CIRCUIT_ID']) ? $installation['INSTALLATION_DTL'][0]['CIRCUIT_ID'] : '' : ''; ?></div>
                            </td>
                            <td class="ta-c">
                                <div class="vr-menu-name target-remove" data-app-name="true"><?php echo ($isConnected) ? isset($installation['INSTALLATION_DTL'][0]['INSTALLATION_DATE']) ? Date::format('Y/m/d', $installation['INSTALLATION_DTL'][0]['INSTALLATION_DATE']) : '' : ''; ?></div>
                            </td>
                            <td class="ta-c">
                                <div class="vr-menu-name target-remove" data-app-name="true"><?php echo ($isConnected) ? $info : '' ?></div>
                            </td>
                            <td class="ta-c">
                                <div class="vr-menu-name pull-left target-remove" data-app-name="true" style="width: 20px; height: 20px"><?php echo ($isConnected) ? $port : '' ?></div>
                                <?php if ($isConnected) { ?>
                                    <a href="javascript:;" class="btn btn-danger btn-circle btn-sm"  onclick="deleteConnectionPort_<?php echo $this->uniqId ?>(this, '<?php echo $this->assetId ?>', '<?php echo $this->uniqId ?>', '<?php echo $this->locationId ?>', '<?php echo $this->directorypath ?>', '<?php echo $this->checkkeyid ?>', <?php echo isset($installation['INSTALLATION_DTL'][0]) ? (($isConnected) ? $installation['INSTALLATION_DTL'][0]['CONNECTION_ID'] : '0') : '0' ?>, '',<?php echo ($isConnected) ? $installId : '0'; ?>)"><i class="fa fa-trash"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?> 

                <?php } ?>
            </tbody>
        </table>
    <?php } else {
        ?>
        <div class="note note-warning">
            <h4 class="block">Warning! <?php echo $this->lang->line('warning'); ?></h4>
            <p><?php echo $this->lang->line('not_found_data'); ?></p>
        </div>
    <?php }
    ?>
</div>
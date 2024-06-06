<div class="bg-white">
    <div class="ecommerce-breadcumb header-elements-md-inline p-0 ">
        <div class="ecommerce-buttons">
            <a href="javascript:;" class="btn btn-success btn-sm sidebar-control d-md-block back-register-<?php echo $this->uniqId ?>">
                <i class="icon-reply"></i> <?php echo Lang::line('BACK_BTN') ?>
            </a>
        </div>
    </div>
    <?php
    if ($this->fillData) { ?>
        <div class="option-<?php echo $this->uniqId ?>">
            <?php
            foreach ($this->fillData as $key => $fillData) { ?>
                <div class="card-group-control card-group-control-right">
                    <div class="card px-2">
                        <div class="card-header">
                            <h6 class="card-title">
                                <a data-toggle="collapse" class="text-default" href="#collapsible-group<?php echo $this->uniqId . '-' . $key ?>"><?php echo $fillData['registrytypename'] ?></a>
                            </h6>
                        </div>
                        <div id="collapsible-group<?php echo $this->uniqId . '-' . $key ?>" class="collapse show">
                            <div class="card-body">
                                <?php if ($fillData['data']) { ?>
                                    <div class="table-responsive">
                                        <table class="basictable table table-striped table-bordered table-dashboard-two mg-b-0" md-dataviewid="<?php echo $fillData['dataviewid'] ?>">
                                            <thead>
                                                <tr style="background-color: #e5e5e5;">
                                                    <th style="width: 30px;">№</th>
                                                <?php foreach ($fillData['data'][0] as $fkey => $fRow) {
                                                    if ($fkey !== 'civilid' && $fkey !== 'id' && $fkey !== 'rowcolor') { ?>
                                                        <th><?php echo Lang::line($fkey); ?></th>
                                                    <?php
                                                    }
                                                } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $order = 1;
                                                foreach ($fillData['data'] as $fkey => $fRow) { 
                                                    $jsonRow = htmlentities(json_encode(array($fRow)), ENT_QUOTES, 'UTF-8'); ?>
                                                    <tr data-rowdata="<?php echo $jsonRow ?>" style="<?php echo issetParam($fRow['rowcolor']) !== '' ? 'background: ' . $fRow['rowcolor'] : '';  ?>">
                                                        <?php 
                                                        echo '<td>'. $order++ .'</td>';
                                                        foreach ($fRow as $fk =>  $values) {
                                                            if ($fk !== 'civilid'  && $fk !== 'id'   && $fk !== 'rowcolor' ) {
                                                                echo '<td>'. $values .'</td>';
                                                            }
                                                        } ?>
                                                    </tr>    
                                                    <?php
                                                } ?>  
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else { ?>
                                    <p><?php echo Lang::line('no_record'); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="ecommerce-buttons">
            <p><?php echo Lang::line('no_record'); ?></p>
        </div>
    <?php } ?>
</div>

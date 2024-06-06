<?php if (isset($this->getRow['closetypeid']) && $this->getRow['closetypeid'] != 0 && !empty($this->getRow['closetypeid']) && in_array($this->getRow['wfmstatusid'], array(1550224334141730, 1560685299260565, 16100320577136))  ){ ?>
    <div style="background-color: lightgoldenrodyellow">
        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
            <li class="nav-item d-flex flex-row align-items-center">
                <a href="javascript:;" class="nav-link v2 active-hide" data-toggle="tab">
                    <span><i class="icon-vcard mr-1"></i></span>
                    <span>Карт хаасан мэдээлэл</span>
                </a>
            </li>
        </ul>
        <div class="">
            <table class="table-responsive table-striped double-td-width">
                <tbody>
                    <tr>
                        <td><i class="fa fa-calendar-o mr-1"></i> Хариу өгсөн огноо:</td>
                        <td><?php echo Arr::get($this->getRow, 'introducedate'); ?> 
                        <?php if(!empty($this->getRow['closeddaysago'])){ 
                            if($this->getRow['isneedreply'] == 1){ ?>
                             <br> <?php echo ' (' . Arr::get($this->getRow, 'closeddaysago') . ' хоногийн өмнө)' ?> 
                        <?php }} ?>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="icon-file-text mr-1"></i> Хариулсан хэлбэр:</td>
                        <td><a href="javascript:void(0);"><?php echo Arr::get($this->getRow, 'closetypename'); ?></a> 
                        <?php if( !empty($this->getRow['replydocnumber']) ){ 
                            if($this->getRow['closetypeid'] == 1){ ?>
                        <a class="label label-sm"><i class="fa" style="background-color: cornflowerblue;line-height:15px;font-family:Tahoma,Geneva,sans-serif;text-shadow:0 -1px 0 rgba(0,0,0,0.25);white-space:nowrap;color:#ffffff;font-size:13px;padding:2px 4px;"> <?php echo Arr::get($this->getRow, 'replydocnumber'); ?> </i></a>
                        <?php }} ?>
                        </td>
                    </tr>

                    <?php if($this->getRow['closetypeid'] == 2 || $this->getRow['closetypeid'] == 3){ ?>
                        <tr>
                            <td><i class="icon-file-text mr-1"></i> Харилцсан утас:</td>
                            <td><?php echo Arr::get($this->getRow, 'closephonenumber'); ?></td>
                        </tr>
                    <?php } ?>

                    <?php if($this->getRow['closetypeid'] == 5){ ?>
                        <tr>
                            <td><i class="fa fa-calendar-o mr-1"></i> Харилцсан имэйл:</td>
                            <td><?php echo Arr::get($this->getRow, 'closeemail'); ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td><i class="icon-file-text mr-1"></i> Хэрхэн шийдвэрлэсэн:</td>
                        <td><?php echo Arr::get($this->getRow, 'description'); ?></td>
                    </tr>

                    <?php if(isset($this->getRow['closedusername'])){ ?>
                        <tr>
                            <td><i class="icon-file-eye mr-1"></i> Картыг хаасан:</td>
                            <td><?php echo Arr::get($this->getRow, 'closedusername'); ?>
                                <br> <?php echo Arr::get($this->getRow, 'closeddate'); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
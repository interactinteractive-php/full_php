    <?php if ($this->modulId === "1") { ?>
        <div class="col-lg-8 col-md-8 member m-auto governmentReviewData_<?php echo $this->uniqId ?>_<?php echo $this->id ?>">
            <div class="ml0 memeber-list">
                <h1 class="d-none districtreviewTitle<?php echo $this->uniqId ?>"><?php echo (isset($this->mapDuration) && $this->mapDuration) ? $this->mapDuration : '00:00:00' ?></h1>
                <div class="row mx-auto my-0 justify-content-center">
                    <div class="col-md-6 col-sm-6 max-w-20">
                        <div class="row">
                            <div class="col description">
                                <div class="d-grid align-items-center">
                                    <div class="text-center mb-4 huraldaan-color" style="font-size: 16px;">Хугацаа</div>
                                    <div class="text-center mb-4 text-primary" style="font-size: 18px;">
                                        <span class="border border-primary rounded-lg px-2 py-1 mr-1" data-name="reviewMinit">00</span>:<span class="border border-primary rounded-lg px-2 py-1 ml-1" data-name="reviewSeconds">00</span>
                                    </div>
                                    <div class="text-center mb-2 font-weight-bold">
                                        <span class="p-1" style="font-size: 14px; color:#007859">Дэмжсэн</span>
                                        <div class="approvedparticipant">
                                            <span class="p-1 mr-2" style="font-size: 14px; color:#034591;"><?php echo checkDefaultVal($this->data['attendance']['approvedparticipant'], '0/0') ?> <?php echo checkDefaultVal($this->data['attendance']['approvedpercent'], '0%') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 max-w-20">
                        <div class="row">
                            <div class="col description">
                                <div class="d-grid align-items-center">
                                    <div class="text-center mb-4 huraldaan-color" style="font-size: 16px;">Ирц</div>
                                    <div class="text-center mb-4 text-primary reviewTotal" style="font-size: 18px;">
                                        <span class="px-2 py-1"><?php echo checkDefaultVal($this->data['attendance']['attendance'], '00') ?></span>/<span class="px-2 py-1"><?php echo (issetParam($this->data['attendance']['total']) < 10 ? '0'. issetParamZero($this->data['attendance']['total']) : $this->data['attendance']['total']); ?></span>
                                    </div>
                                    <div class="text-center mb-2 font-weight-bold">
                                        <span class="p-1" style="font-size: 14px; color:#CD240E">Татгалзсан</span>
                                        <div class="declinedpercent">
                                            <span class="p-1 mr-2" style="font-size: 14px; color:#034591;"><?php echo checkDefaultVal($this->data['attendance']['declinedparticipant'], '0/0') ?> <?php echo checkDefaultVal($this->data['attendance']['declinedpercent'], '0%') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto my-0 justify-content-center" style="overflow-y:auto; overflow-x:hidden; max-height:450px;" >
                    <div class="col-md-6 col-sm-6 max-w-20 col-6">
                        <div class="row">
                            <div class="col description">
                                <div class="d-grid align-items-center">
                                    <div style="display: grid; max-height: 450px;" id="approved" class="text-center">
                                        <?php if (issetParamArray($this->data['approved'])) {
                                            foreach ($this->data['approved'] as $key => $row) { ?>
                                                <span style="font-size: 14px;"><?php echo issetParam($row['fullname']) ?></span>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 max-w-20 col-6">
                        <div class="row">
                            <div class="col description">
                                <div class="d-grid align-items-center">
                                    <div style="display: grid; max-height: 450px;" class="text-center declined">
                                        <?php if (issetParamArray($this->data['declined'])) {
                                            foreach ($this->data['declined'] as $key => $row) { ?>
                                                <span style="font-size: 14px; color:#585858;"><?php echo issetParam($row['fullname']) ?></span>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    <?php } else { ?>
        <div class="col-lg-8 col-md-8 member m-auto">
            <div class="ml0 memeber-list">
                <div class="row mx-auto my-0 justify-content-center">
                    <div class="col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col">
                                <div class="d-grid align-items-center">
                                    <div class="text-center mb-2 font-weight-bold">
                                        <span class="p-1" style="font-size: 14px; color:#007859">Дэмжсэн</span>
                                        <div class="approvedparticipantSum">
                                            <span class="p-1 mr-2" style="font-size: 14px; color:#034591;"><?php echo checkDefaultVal($this->data['attendance']['approvedparticipant'], '0/0') ?> <?php echo checkDefaultVal($this->data['attendance']['approvedpercent'], '0%') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col">
                                <div class="d-grid align-items-center">
                                    <div class="text-center mb-2 font-weight-bold">
                                        <span class="p-1" style="font-size: 14px; color:#CD240E">Татгалзсан</span>
                                        <div class="declinedpercentSum">
                                            <span class="p-1 mr-2" style="font-size: 14px; color:#034591;"><?php echo checkDefaultVal($this->data['attendance']['declinedparticipant'], '0/0') ?> <?php echo checkDefaultVal($this->data['attendance']['declinedpercent'], '0%') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto my-0 justify-content-center" style="overflow-y:auto; overflow-x:hidden; max-height:450px;" >
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="row">
                            <div class="col">
                                <div class="d-grid align-items-center">
                                    <div style="display: grid; max-height: 450px;" class="text-center">
                                        <?php if (issetParamArray($this->data['approved'])) {
                                            foreach ($this->data['approved'] as $key => $row) { ?>
                                                <span style="font-size: 14px;"><?php echo issetParam($row['fullname']) ?></span>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="row">
                            <div class="col">
                                <div class="d-grid align-items-center">
                                    <div style="display: grid; max-height: 450px;" class="text-center">
                                        <?php if (issetParamArray($this->data['declined'])) {
                                            foreach ($this->data['declined'] as $key => $row) { ?>
                                                <span style="font-size: 14px; color:#585858;"><?php echo issetParam($row['fullname']) ?></span>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    <?php } ?>
    <?php echo isset($this->defaultCss) ? $this->defaultCss : ''; ?>
   



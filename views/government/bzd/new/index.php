<span class="font-weight-bold h3 pl-3 pt-5 huraldaan-color">Санал хураалт</span>
    <div class="col-md-10 m-auto">
        <div class="ml0">
            <div class="row mx-auto my-0 justify-content-center">
                <div class="col-md-6 governmentReview_<?php echo $this->uniqId ?>">
                    <div class="row">
                        <div class="col description">
                            <div class="d-grid align-items-center totalReview districtreview<?php echo $this->uniqId ?>">
                                <h1 class="d-none"><?php echo (isset($this->mapDurationIndex) && $this->mapDurationIndex) ? $this->mapDurationIndex : '00:00:00' ?></h1>
                                <div class="text-center mb-2 huraldaan-color" style="font-size: 28px;">Хугацаа</div>
                                <div class="text-center mb-2 text-primary" style="font-size: 42px;">
                                    <span class="border border-primary rounded-lg px-2 py-1 mr-1" data-name="reviewMinit">00</span>:<span class="border border-primary rounded-lg px-2 py-1 ml-1" data-name="reviewSeconds">00</span>
                                </div>
                                <div class="text-center mb-2 font-weight-bold d-flex align-items-center justify-content-center">
                                    <span class="p-1" style="font-size: 24px; color:#007859">Дэмжсэн</span>
                                    <div class="approvedparticipant">
                                        <span class="p-1 mr-2 text-secondary" style="font-size: 28px"><?php echo checkDefaultVal($this->data['attendance']['approvedparticipant'], '0/0') ?> <?php echo checkDefaultVal($this->data['attendance']['approvedpercent'], '0%') ?></span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col description">
                            <div class="d-grid align-items-center">
                                <div class="text-center mb-2 huraldaan-color" style="font-size: 28px;">Ирц</div>
                                <div class="text-center mb-2 text-primary reviewTotal" style="font-size: 42px;">
                                    <span class="px-2 py-1" ><?php echo checkDefaultVal($this->data['attendance']['attendance'], '00') ?></span>/<span class="px-2 py-1"><?php echo (issetParam($this->data['attendance']['total']) < 10 ? '0'. issetParamZero($this->data['attendance']['total']) : $this->data['attendance']['total']); ?></span>
                                </div>
                                <div class="text-center mb-2 font-weight-bold d-flex align-items-center justify-content-center">
                                    <span class="p-1" style="font-size: 24px; color:#CD240E">Татгалзсан</span>
                                    <div class="declinedpercent">
                                        <span class="p-1 mr-2 text-secondary" style="font-size: 28px"><?php echo checkDefaultVal($this->data['attendance']['declinedparticipant'], '0/0') ?> <?php echo checkDefaultVal($this->data['attendance']['declinedpercent'], '0%') ?></span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-10 m-auto reviewData">
        <div class="row mx-auto justify-content-center" style="min-height: 340px; max-height:340px; overflow-y: auto;overflow-x: hidden; margin: 0 auto;">
            <div class="col-md-6 col-sm-6">
                <div class="row">
                    <div class="col description">
                        <div style="display: grid;" id="approved" class="text-center">
                            <?php if (issetParamArray($this->data['approved'])) {
                                foreach ($this->data['approved'] as $key => $row) { ?>
                                    <span style="font-size: 28px;" class="text-primary"><?php echo issetParam($row['fullname']) ?></span>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
                    
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="row">
                    <div class="col description">
                        <div style="display: grid;" id="declined" class="text-center">
                            <?php if (issetParamArray($this->data['declined'])) {
                                foreach ($this->data['declined'] as $key => $row) { ?>
                                    <span style="font-size: 28px;" class="text-primary"><?php echo issetParam($row['fullname']) ?></span>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <?php echo isset($this->defaultCss) ? $this->defaultCss : ''; ?>

    <style>
        .reviewData ::-webkit-scrollbar {
            width:3px;
        }
        .reviewData ::-webkit-scrollbar-thumb {
            background: #034591;
            border-radius: 15px;
        }

        .reviewData ::-webkit-scrollbar-track{
            background: #FFF;
        }

        .reviewData ::-webkit-scrollbar-thumb:hover {
            background: #034591;
        }
    </style>
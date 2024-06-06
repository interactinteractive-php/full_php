
<?php echo Form::create(array('class' => 'form form-horizontal', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
    <?php 
        echo Form::hidden(array('name' => 'puul_id', 'value' => issetParam($this->data['id'])));
        echo Form::hidden(array('name' => 'puul_mapid', 'value' => issetParam($this->data['mapid'])));
        echo Form::hidden(array('name' => 'puul_meetingbookid', 'value' => issetParam($this->data['meetingbookid'])));
     ?>
    <div class="row">
        <div class="card-body">
            <form method="post" action="">
                <div class="form-body row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="h4 font-weight-bold huraldaan-color" for="requesterName"><?php echo Lang::lineCode('Pool_district_Subtitle', $this->langCode) ?></label>
                            <input type="text" class="form-control form-control-sm" placeholder="" name="puul_name"  value="<?php echo issetParam($this->data['name']) ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php echo Form::close(); ?>
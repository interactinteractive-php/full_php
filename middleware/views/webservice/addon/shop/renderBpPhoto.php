<div class="product-image-detial" id="ws-carousel-<?php echo $this->methodId; ?>">
    <?php
    $addBpImageUrl = '';
    if ($this->isEditMode) {
        if ($this->metaValuePhotoRows) {
            foreach ($this->metaValuePhotoRows as $key => $row) {
                if (empty($key)) {
                    $this->defaultPhoto = $row['ATTACH'];
                }
                $this->owlCarouselphotoBtn .= '<div class="item fadein ' . (empty($key) ? 'active' : '') . '" data-attach-id="'.$row['ATTACH_ID'].'">';
                $this->owlCarouselphotoBtn .= '<div data-img="' . $row['ATTACH'] . '" class="image">';
                $this->owlCarouselphotoBtn .= '<img src="' . $row['ATTACH_THUMB'] . '" class="thumb">';
                $this->owlCarouselphotoBtn .= '</div>';
                $this->owlCarouselphotoBtn .= '</div>';
            }
        }
    } else {
        echo Form::hidden(array('name' => 'isProcessMultiFile', 'id' => 'isProcessMultiFile', 'value' => '1'));
        echo Form::hidden(array('name' => 'refMetaGroupId', 'id' => 'refMetaGroupId', 'value' => $this->refMetaGroupId));
    }
    ?>
    <div class="dopelessrotate zoom">
        <img src="<?php echo $this->defaultPhoto; ?>" class="product-image-big">
        <div class="product-image-btn">
            <span class="btn btn-sm green add-photo-btn">
                <input type="file" name="bp_photo" class="add-photo" onchange="<?php echo ($this->isEditMode == '1' ? 'editModeAddProcessImage_'.$this->methodId.'(this)' : 'addModeAddProcessImage_'.$this->methodId.'(this)')?>">
                <i class="icon-plus3 font-size-12"></i>
            </span>
            <span class="btn btn-sm red" onclick="<?php echo ($this->isEditMode == '1' ? 'editModeRemoveProcessImage_'.$this->methodId.'(this);' : 'addModeRemoveProcessImage_'.$this->methodId.'(this);')?>"><i class="fa fa-trash"></i></span>
        </div>
    </div>
    <div class="owl-btn img-prev"><i class="fa fa-angle-left"></i></div>
    <div class="owl-btn img-next"><i class="fa fa-angle-right"></i></div>
    <div class="owl-image-detail owl-carousel owl-theme">
        <?php echo $this->owlCarouselphotoBtn; ?>
    </div>
</div>
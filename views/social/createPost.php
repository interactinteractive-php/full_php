<form method="post" class="create-post-form" enctype="multipart/form-data">
    <div class="panel panel-default panel-create"> 
        <div class="panel-heading">
            <div class="heading-text">Нийтлэл оруулах</div>
        </div>
        <div class="panel-body">        
            <textarea name="description" class="form-control createpost-form" rows="3" id="createPost" placeholder="Энд нийтлэлээ бичнэ үү..." required="required"></textarea>

            <div class="users-results-wrapper"></div>
            <div class="youtube-iframe"></div>

            <div class="video-addon post-addon" style="display: none">
                <span class="post-addon-icon"><i class="fa fa-film"></i></span>
                <div class="form-group row fom-row">
                    <input type="text" name="youtubeText" id="youtubeText" class="form-control youtube-text" placeholder="Та энд YOUTUBE хаягыг оруулна уу">
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="file-addon post-addon" style="display: none">
                <span class="post-addon-icon"><i class="fa fa-files-o"></i></span>
                <div class="form-group row fom-row">
                    <input type="file" name="postFile[]" accept=".jpg, .jpeg, .png, .gif, .bmp, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .zip, .rar" class="form-control youtube-text">
                    <div class="clearfix"></div>
                </div>
                <div class="form-group row fom-row">
                    <input type="file" name="postFile[]" accept=".jpg, .jpeg, .png, .gif, .bmp, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .zip, .rar" class="form-control youtube-text">
                    <div class="clearfix"></div>
                </div>
                <div class="form-group row fom-row">
                    <input type="file" name="postFile[]" accept=".jpg, .jpeg, .png, .gif, .bmp, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .zip, .rar" class="form-control youtube-text">
                    <div class="clearfix"></div>
                </div>
                <div class="form-group row fom-row">
                    <input type="file" name="postFile[]" accept=".jpg, .jpeg, .png, .gif, .bmp, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .zip, .rar" class="form-control youtube-text">
                    <div class="clearfix"></div>
                </div>
                <div class="form-group row fom-row">
                    <input type="file" name="postFile[]" accept=".jpg, .jpeg, .png, .gif, .bmp, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .zip, .rar" class="form-control youtube-text">
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="location-addon post-addon" style="display: none">
                <span class="post-addon-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                <div class="form-group row fom-row">
                   <input type="text" name="location" id="pac-input" class="form-control" placeholder="Where are you?" autocomplete="off" value="" onkeypress="return initMap(event)" style="position: relative; overflow: hidden;"><div class="clearfix"></div>
                </div>                 
            </div>

            <!-- Hidden elements  -->
            <input type="hidden" name="youtube_title">
            <input type="hidden" name="youtube_video_id">
            <input type="hidden" name="location">
            <input type="hidden" name="group_id" value="<?php echo issetParam($this->groupId); ?>">
            <input type="file" class="post-images-upload hidden" multiple="multiple" accept="image/jpeg,image/png,image/gif" name="post_images_upload[]" id="post_images_upload[]">
            <input type="file" class="post-video-upload hidden" accept="video/mp4" name="post_video_upload">
            <div id="post-image-holder"></div>
        </div><!-- panel-body -->

        <div class="panel-footer">
            <ul class="list-inline left-list">
                <!--<li><a href="javascript:;" id="imageUpload"><i class="fa fa-photo"></i></a></li>-->
                <li><a href="javascript:;" id="videoUpload"><i class="fa fa-youtube"></i></a></li>
                <li><a href="javascript:;" id="fileUpload"><i class="fa fa-cloud-upload"></i></a></li>
                <!--<li><a href="javascript:;" id="locationUpload"><i class="fa fa-map-marker"></i></a></li>-->
            </ul>
            <ul class="list-inline right-list">
                <li><button type="button" class="btn btn-submit btn-success post-button">Нийтлэх</button></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</form>
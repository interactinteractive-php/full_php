<form method="post" id="chat-create-group-form" class="chat-edit-group">
    <div class="card mt-n10 ml-n15 mr-n15 mb-4 border-radius-0">
        <div class="card-body text-center height-70 d-flex justify-content-center">
            <div class="card-img-actions d-inline-block position-absolute">
                <div class="group-picture img_cont d-flex align-items-center justify-content-center mt-2">
                    <i class="icon-users4 font-size-26"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column align-items-center">
        <div class="d-flex align-items-center justify-content-center flex-row group-options mb-2 w-100">
            <input type="text" name="groupName" class="form-control border-radius-50" required="required" placeholder="Группын нэр">
        </div>
    </div>
    
    <hr />

    <div class="form-group">
        <?php echo $this->control; ?>
    </div>
</form>

<script type="text/javascript">
$(function() {
    $('#sendChatUsers').attr('data-placeholder', '- Хэрэглэгч сонгох -');
});    
</script>
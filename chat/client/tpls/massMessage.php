<form method="post" id="chat-massmsg-form" enctype="multipart/form-data">
    <div class="bg-gray-100 p-2 mb-3 d-flex align-items-center">
        <div class="col">
            <i class="icon-alert text-red"></i>
        </div>
        <div class="w-100 line-height-normal text-black">
            Доорх талбарт бичсэн таны мессэж сонгогдсон хэрэглэгчдэд нэгэн зэрэг илгээгдэнэ!
        </div>
    </div>
    <div class="form-group row mb-3">
        <?php echo Form::label(array('text' => 'Илгээх мессэж', 'for' => 'sendMsg', 'class' => 'col-md-2 text-right line-height-normal', 'required'=>'required')); ?>
        <div class="col-md-10">
            <textarea id="sendMsg" name="sendMsg" rows="3" cols="3" class="form-control" required="required"></textarea>
        </div>
    </div>
    <div class="form-group row mb-3">
        <?php echo Form::label(array('text' => 'Файл', 'for' => 'attachFile', 'class' => 'col-md-2 text-right')); ?>
        <div class="col-md-10">
            <input type="file" id="attachFile" name="attachFile" class="form-control">
        </div>
    </div>
    <div class="masschat-user-type org-choice mt-3">
        <div class="form-group mb-0">
            <div class="form-check form-check-inline">
                <label class="form-check-label align-items-center" data-type="all">
                    <input class="mr-1" type="radio" id="all-and-oth" name="all-and-oth" value="all" checked>
                    <label class="mb-0" for="all-and-oth">Бүх хүнд</label>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label align-items-center" data-type="user">
                    <input class="mr-1" type="radio" id="all-and-oth1" name="all-and-oth" value="user">
                    <label class="mb-0" for="all-and-oth1">Тухайн хэрэглэгчдэд</label>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row mb-2" id="masschat-user" style="display: none">
        <div class="col-md-12">
            <?php echo $this->control; ?>
        </div>
    </div>
    <input type="hidden" name="massChatSendUserType" value="all">
</form>

<script type="text/javascript">
$(function() {
    $('.masschat-user-type').on('click', 'input[name="all-and-oth"]', function () {
        var $this = $(this);
        var type = $this.attr('data-type');
        
        if ($this.val() == 'all') {
            $('#masschat-user').hide();
            $('input[name="massChatSendUserType"]').val('all');
        } else {
            $('#masschat-user').show();
            $('input[name="massChatSendUserType"]').val('user');
        }
    });
    
});   
</script>
<div class="col-md-12">
    <div class="bg-white">	
        <div class="card-header card-header-no-padding bg-white header-elements-inline pb5">
            <div class="card-title"><i class="fa fa-envelope-o"></i> И-мейл тохируулах</div>
        </div>
        <div class="card-body pt20">
            <?php echo Form::create(array('id'=>'mail-form', 'method'=>'post')); ?>    
            <div class="form-group row">
                <?php echo Form::label(array('text' => 'И-мейл хаяг', 'for' => 'webmail_email', 'class' => 'col-form-label col-lg-2 text-right pt5', 'required' => 'required')); ?>
                <div class="col-lg-4">
                    <?php echo Form::text(array('name' => 'webmail_email', 'id' => 'webmail_email', 'class' => 'form-control email', 'required' => 'required')); ?>
                </div>
            </div>
            
            <div class="form-group row">
                <?php echo Form::label(array('text' => 'Нууц үг', 'for' => 'webmail_password', 'class' => 'col-form-label col-lg-2 text-right pt5', 'required' => 'required')); ?>
                <div class="col-md-4">
                    <?php echo Form::password(array('name' => 'webmail_password', 'id' => 'webmail_password', 'class' => 'form-control', 'required' => 'required')); ?>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-lg-10 ml-lg-auto">
                    <?php echo Form::button(array('class' => 'btn green-meadow testmail', 'value' => 'Нэвтрэх <i class="icon-paperplane ml-2"></i>')); ?>
                </div>
            </div>
            <?php 
            echo Form::hidden(array('name' => 'isAjax', 'value' => $this->isAjax));
            echo Form::close(); 
            ?>
        </div>
    </div>   
</div>

<script type="text/javascript">
$(function(){
    
    $('#webmail_password').on('keydown', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) {
            $('.testmail').click();
        }
    });
    
    $('.testmail').on('click', function(){
        
        var $form = $('form#mail-form');
        
        $form.validate({ errorPlacement: function(){} });
        
        if ($form.valid()) {
            $.ajax({
                type: 'post',
                url: 'webmail/testmail',
                data: $form.serialize(),
                dataType: 'json',
                beforeSend:function(){
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success:function(data){
                    
                    PNotify.removeAll(); 
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    
                    <?php
                    if (!$this->isAjax) {
                    ?>
                    if (data.status === 'success') {
                        setTimeout(function(){
                            location.reload(); 
                        }, 1000); 
                    } else {
                        Core.unblockUI();
                    }
                    <?php
                    } else {
                    ?>
                    if (data.status === 'success') {
                        var $parent = $form.closest('.col-md-12');
                        $parent.addClass('d-none');
                        $parent.after(data.iframe);
                    } 
                    Core.unblockUI();
                    <?php
                    }
                    ?>
                }
            });
        }
    }); 
});    
</script>
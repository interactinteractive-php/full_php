<div class="content">
    <div class="container">
        <div class="mt80 text-center" id="ukeys">
            <div data-load="0">
                <?php 
                if (isset($this->userkeys[0]['rootname'])) {
                    echo '<div class="ukey-parent-name">'.$this->userkeys[0]['rootname'].'</div>';
                }
                
                echo $this->userKeysRender; 
                ?>
            </div>    
        </div>
    </div>
</div>

<style type="text/css">
    body {
        background-color: #f4f6fa;
    }
    .ukey-card {
        float: left;
        width: 200px;
        border: 1px #f1f1f1 solid;
        background-color: #fff;
        margin: 0 10px 20px 10px;
        padding: 15px 15px 10px 15px;
    }
    .ukey-card.selected {
        border: 1px #222 solid;
    }
    .ukey-h-line {
        border-top: 1px #f1f1f1 solid;
        height: 1px;
        margin-left: -15px;
        margin-right: -15px;
        margin-top: 14px;
    }
    .ukey-logo-wrap {
        position: relative;
        height: 140px;
        text-align: center;
    }
    .ukey-logo-wrap > * {
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        display: block;
        max-width: 100%;
        width: unset !important;
        max-height: 100%;
    }
    .ukey-logo {
        max-height: 120px;
        max-width: 200px;
        margin-left: auto; 
        margin-right: auto; 
        background-color: #fff;
    }
    .ukey-title {
        color: #acacac;
        margin-top: 15px;
        font-size: 12px;
        font-weight: bold;
        height: 55px;
        max-height: 55px;
        line-height: 19px;
        overflow: hidden;
    }
    .ukey-title a {
        display: block;
    }
    .ukey-footer {
        height: 19px;
        text-align: right;
    }
    .ukey-footer a {
        color: #666;
        display: inline-block;
        float: right;
    }
    .ukey-parent-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #444;
    }
</style>

<script type="text/javascript">
    $(function () {
        
        $(document.body).on('click', '.ukey-child', function() {
            var $this = $(this), id = $this.attr('data-id'), $ukeys = $('#ukeys');
            
            if ($ukeys.find('[data-load="'+id+'"]').length) {
                
                $ukeys.find('[data-load]').hide();
                $ukeys.find('[data-load="'+id+'"]').show();
                
            } else {
                
                $.ajax({
                    type: 'post',
                    url: 'login/getUserKeyChild',
                    data: {parentId: id},
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    }, 
                    success: function (data) {
                        
                        if (data.status = 'success') {
                            
                            $ukeys.find('[data-load]').hide();
                            
                            $('#ukeys').append(
                                '<div data-load="'+id+'">'+
                                    '<button type="button" class="btn btn-light mb10 ukey-back" data-prev-load="'+$this.closest('[data-load]').attr('data-load')+'">Буцах</button>'+
                                    '<div class="ukey-parent-name">'+$this.closest('.ukey-card').find('.ukey-title').text()+'</div> '+
                                    '<div class="w-100"></div>' + data.html + 
                                '</div>');
                        
                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message, 
                                type: data.status,
                                sticker: false
                            });
                        }
                        Core.unblockUI();
                    }
                });
            }
        });
        
        $(document.body).on('click', '.ukey-back', function() {
            var $this = $(this), loadId = $this.attr('data-prev-load'), $ukeys = $('#ukeys');
            
            $ukeys.find('[data-load]').hide();
            $ukeys.find('[data-load="'+loadId+'"]').show();
        });
        
        $(document.body).on('click', 'a.ukey-redirect', function(e) {
            var $this = $(this);
            e.preventDefault();

            Core.blockUI({message: 'Loading...', boxed: true});
            
            $this.closest('#ukeys').find('.selected').removeClass('selected');
            $this.closest('.ukey-card').addClass('selected');

            setTimeout(function() {
                window.location.href = $this.attr('href');
            }, 100);
        });
    });
</script>
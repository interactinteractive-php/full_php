<div id="mdCommentMetaValue_<?php echo $this->uniqId; ?>" style="overflow: hidden"></div>
<style type="text/css">
    .scrollerFalse > .media-list > .media > .media-body .media:last-child {
        border-bottom: none !important;
    }
    .scrollerFalse .list-inline-dotted .list-inline-item:not(:last-child):after {
        width: 0;
        margin-left: .4rem;
    }
/*    .emojiChooser {
        bottom: 252px !important;
    }*/
</style>
<script type="text/javascript">
    mdCommentMetaProcessLoad_<?php echo $this->uniqId; ?>('<?php echo $this->processId; ?>', '<?php echo $this->metaDataId; ?>', '<?php echo $this->metaValueId; ?>', '<?php echo $this->listMetaDataId; ?>');
    
    function saveMdCommentProcessValue_<?php echo $this->uniqId; ?>(elem, textPhoto) {
        
        var $this = $(elem), text = '';
        
        if (typeof textPhoto === 'undefined') {
            text = encodeEmojis($.trim($this.val()));
            textPhoto = '';
            if (text.length === 0) {
                return;
            }             
        }
        
        var $thisParent = $this.closest('.media-body'), 
            $commentRow = $this.closest('[data-comment-id]'), 
            $commentStructure = $this.closest('[data-commentstructureid]'), 
            $processForm = $this.closest('form'),
            $listMetaDataId = $thisParent.find("input[name=listMetaDataId]"), 
            commentData = {
                commentText: text,
                commentBase64File: textPhoto,
                metaDataId: $thisParent.find("input[name=metaDataId]").val(),
                metaValueId: $thisParent.find("input[name=metaValueId]").val(), 
                processMetaDataId: $processForm.find('input[name="methodId"]').val(), 
                listMetaDataId: $processForm.find('input[name="dmMetaDataId"]').val()
            };
        
        if ($commentRow.length) {
            commentData['parentId'] = $commentRow.attr('data-comment-id');
            commentData['replyUserId'] = $commentRow.attr('data-user-id');
        }
        
        if ($commentStructure.length) {
            commentData['commentStructureId'] = $commentStructure.attr('data-commentstructureid');
        }
        
        if ($listMetaDataId.length && $listMetaDataId.val() != '') {
            commentData['listMetaDataId'] = $listMetaDataId.val();
        }
        
        var mmid = Core.getURLParameter('mmid');

        if (mmid) {
            commentData['moduleMetaDataId'] = mmid;
        }
        
        $this.mentionsInput('getMentions', function(data) {
            commentData['mentionData'] = JSON.stringify(data);
        });
        
        $.ajax({
            type: 'post',
            url: 'mdcomment/saveCommentProcess',
            data: commentData,
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },            
            dataType: 'json',
            success:function(data) {
                if (data.status === 'success') {
                    $this.val('');
                    $.when(mdCommentMetaProcessLoad_<?php echo $this->uniqId; ?>('<?php echo $this->processId; ?>', $thisParent.find("input[name=metaDataId]").val(), $thisParent.find("input[name=metaValueId]").val(), $thisParent.find("input[name=listMetaDataId]").val())).then(function(){
                        $('#mdCommentMetaValue_<?php echo $this->uniqId; ?>').animate({"scrollTop": $('#mdCommentMetaValue_<?php echo $this->uniqId; ?>')[0].scrollHeight}, "slow");
                        Core.unblockUI();
                    });
                }
            },
            error:function() { alert('Error'); }
        });
    }
    
    function saveBtnMdCommentProcessValue_<?php echo $this->uniqId; ?>(elem) {
        var $this = $(elem).closest('.chat-addcontrol').find('.bpaddon-mention-autocomplete');
        saveMdCommentProcessValue_<?php echo $this->uniqId; ?>($this);
    }
    
    function mdCommentMetaProcessLoad_<?php echo $this->uniqId; ?>(processId, metaDataId, metaValueId, listMetaDataId){
        var $commentContainer = $('#mdCommentMetaValue_<?php echo $this->uniqId; ?>');
        $.ajax({
            type: 'post',
            url: 'mdcomment/loadMetaProcess',
            data: {uniqId: '<?php echo $this->uniqId; ?>', processId: processId, metaDataId: metaDataId, metaValueId: metaValueId, listMetaDataId: listMetaDataId},
            dataType: "json",
            /*beforeSend: function() {
                $commentContainer.text('Loading...');
            },*/
            success: function(data) {
                $commentContainer.empty().append(data.html).promise().done(function() {
                    bpCommentMentionsInputInit($commentContainer);  
                    
                    if (typeof pfFocusComment_<?php echo $this->uniqId; ?> !== 'undefined' && pfFocusComment_<?php echo $this->uniqId; ?>) {
                        
                        var $commentElement = $commentContainer.find('[data-comment-id="'+pfFocusComment_<?php echo $this->uniqId; ?>+'"]');
                        
                        $commentElement.css('background-color', '#def7ff');
                        pfFocusComment_<?php echo $this->uniqId; ?> = null;
                        
                        $('html,body').animate({scrollTop: $commentElement.offset().top}, 'fast');
                    }
                });
                if (typeof window['mdCommentCallback_' + metaDataId] === 'function') {
                    window['mdCommentCallback_' + metaDataId](data.total, metaValueId);
                }
            },
            error: function() { alert('Error'); }
        });
    }
    
    function emojiPickerMode_<?php echo $this->uniqId; ?>(input) {
        
        var $this = $(input), $parent = $this.closest('.chat-addcontrol'), $textarea = $parent.find('textarea');
        var parentOffset = $(input).offset(); 
        var relY = parentOffset.top;                        
        var wTop = $(window).scrollTop() - 30;
        
        if ($().emojiChooser) {
            
            if ($parent.hasAttr('data-emoji')) {
                $textarea.emojiChooser('toggle');
            } else {
                $textarea.emojiChooser({
                    mode: 'chat', 
                    width: '250px',
                    height: '270px', 
                    recentCount: 10, 
                    button: false, 
                    footer: false
                });
                $textarea.emojiChooser('toggle');
                $parent.attr('data-emoji', '1');
                
                setTimeout(function () {
                  $('.emojiChooser').css('top', relY - wTop+'px');
                }, 50);                
            }
            
        } else {
            
            $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/emoji-picker/css/jquery.emojichooser.css?v=4"/>');
            
            $.cachedScript('assets/custom/addon/plugins/emoji-picker/js/jquery.emojichooser.js').done(function(script, textStatus) {
                $textarea.emojiChooser({
                    mode: 'chat', 
                    width: '250px',
                    height: '270px', 
                    recentCount: 10, 
                    button: false, 
                    footer: false
                });
                $textarea.emojiChooser('toggle');
                $parent.attr('data-emoji', '1');
                setTimeout(function () {
                  $('.emojiChooser').css('top', relY - wTop+'px');
                }, 50);                                
            });
        }
    }
    
    function onChangeAttachFIleAddMode_<?php echo $this->uniqId; ?>(input) {

        var ext = input.value.match(/\.([^\.]+)$/)[1], i = 0;

        if (typeof ext !== "undefined") {
            var fileName = input.files[0].name;
            ext = fileName.match(/\.([^\.]+)$/)[1];

            var extension = ext.toLowerCase();

            if (extension == 'png' ||
                            extension == 'gif' ||
                            extension == 'jpeg' ||
                            extension == 'pjpeg' ||
                            extension == 'jpg' ||
                            extension == 'x-png' ||
                            extension == 'bmp') {

                var reader=new FileReader();
                reader.onload=function(e){
                    var $this = $(input).closest('.chat-addcontrol').find('.bpaddon-mention-autocomplete');
                    saveMdCommentProcessValue_<?php echo $this->uniqId; ?>($this, e.target.result);                    
                };
                reader.readAsDataURL(input.files[0]);

            } else {
                alert('Зурган файл оруулна уу!');
                return;
            }

            var $this = $(input), $clone = $this.clone();
            $this.after($clone).appendTo($('.hiddenFileDiv'));          
            $('.hiddenFileDiv > input').each(function(){
               $(this).attr('name', 'bp_file[]');
            });

        }
    }  
</script>
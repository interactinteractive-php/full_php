<div class="bp-comment-wrap" id="mdCommentMetaValue_<?php echo $this->uniqId; ?>">
    
    <div class="scrollerFalse" data-scroll-panel="1">    
        <ul class="media-list"></ul>
    </div>
    
    <div class="chat-form p-0 dialog-chat" style="overflow: visible">
        <div class="media flex-column flex-sm-row mt-2 mb-2 chat-addcontrol">
            <div class="mr-md-1 mb-2 mb-md-0">
                <?php echo Ue::getSessionPhoto('class="rounded-circle avatar" width="36" height="36"'); ?>
            </div>
            <div class="media-body">
                <?php 
                echo Form::textArea(
                    array(
                        'id' => 'bpcomment_text', 
                        'class' => 'form-control p-1 bpaddon-mention-autocomplete mention',
                        'placeholder' => $this->lang->line('task_comment_write'),
                        'onkeypress' => 'if(event.keyCode == 13) appendBpComment_'.$this->uniqId.'(this);'
                    )
                ); 
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
bpCommentMentionsInputInit($('#mdCommentMetaValue_<?php echo $this->uniqId; ?>'));    

function appendBpComment_<?php echo $this->uniqId; ?>(elem) {

    var $this = $(elem), text = $.trim($this.val()), mentionData = '';

    if (text.length === 0) {
        return;
    } 

    $this.mentionsInput('getMentions', function(data) {
        mentionData = JSON.stringify(data);
    });

    var $parent = $this.closest('div.bp-comment-wrap'), 
        $chats = $parent.find('[data-scroll-panel] > .media-list');
    
    $.ajax({
        type: 'post',
        url: 'mdwebservice/bpCommentTempSave',
        data: {comment: text, mentionData: mentionData},
        beforeSend: function () {
            Core.blockUI({animate: true});
        },
        success: function (data) {
            $this.val('');
            $chats.append(data);
            Core.unblockUI();
        },
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}
</script>
<li class="media flex-column flex-md-row border-top-1 mt0 border-gray pt-2 pb-1" data-temp-comment="1">
    <div class="mr-md-1 mb-2 mb-md-0">
        <?php 
        $currentDate = Date::currentDate('Y/m/d H:i');
        echo Ue::getSessionPhoto('class="rounded-circle avatar" width="36" height="36"'); 
        ?>
    </div>
    <div class="media-body">
        <div class="media-title">
            <a href="javascript:;" class="font-weight-semibold" tabindex="-1"><?php echo Ue::getSessionPersonName(); ?></a>
            <span class="text-muted ml-3 font-size-sm"><?php echo $currentDate; ?></span>
        </div>
        <p class="mb-2 line-height-normal"><?php echo $this->commentHtmlTag; ?></p>
        <ul class="list-inline list-inline-dotted font-size-sm">
            <li class="list-inline-item">
                <a href="javascript:;" tabindex="-1" class="text-secondary" data-bp-comment-edit="1"><?php echo $this->lang->line('edit_btn'); ?></a>
            </li>
            <li class="list-inline-item">
                <a href="javascript:;" tabindex="-1" class="text-secondary" data-bp-comment-remove="1"><?php echo $this->lang->line('delete_btn'); ?></a>
            </li>
        </ul>
    </div>
    <?php 
    echo Form::hidden(array('name'=>'bpCommentText[]','value'=>$this->comment)); 
    echo Form::hidden(array('name'=>'bpMentionData[]','value'=>$this->mentionData)); 
    echo Form::hidden(array('name'=>'bpCommentDate[]','value'=>$currentDate)); 
    ?>
</li>
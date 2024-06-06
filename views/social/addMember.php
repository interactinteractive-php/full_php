<form method="post" action="social/addMember" id="scl-add-member">
    <div class="input-icon input-icon-sm">
        <i class="fa fa-search"></i>
        <input type="text" class="form-control input-sm" placeholder="Нэрээр хайх" id="scl-add-member-filter">
    </div>
    
    <div style="max-height: 450px; overflow: auto" class="mt20">
        <ul class="widget w-friend-pages-added notification-list friend-requests" id="scl-add-member-list">
            <?php
            if ($this->users) {
                foreach ($this->users as $row) {
            ?>
            <li class="inline-items" data-user-id="<?php echo $row['USER_ID']; ?>">
                <div class="author-thumb">
                    <?php echo Ue::getProfilePhoto($row['PICTURE']); ?>
                </div>
                <div class="notification-event">
                    <a href="javascript:;" class="h6 notification-friend"><?php echo $row['FIRST_NAME']; ?></a>
                    <span class="chat-message-item"><?php echo $row['LAST_NAME']; ?></span>
                </div>
                <div class="right-check">
                    <button type="button" class="btn btn-icon-only btn-circle default group-member-toggle"><i class="fa fa-check"></i></button>
                </div>
            </li>
            <?php
                }
            } 
            ?>
        </ul>
    </div>  
    
    <input type="hidden" name="groupId" value="<?php echo $this->groupId; ?>">
</form>

<script type="text/javascript">
$(function(){
    
    $('.group-member-toggle').on('click', function(){
        var $this = $(this);
        var $parent = $this.closest('.right-check');
        
        if ($this.hasClass('blue')) {
            
            $parent.find('input').remove();
            $this.removeClass('blue').addClass('default');
            
        } else {
            
            var $li = $this.closest('.inline-items');
            
            $parent.append('<input type="hidden" name="userId[]" value="'+$li.attr('data-user-id')+'">');
            $this.removeClass('default').addClass('blue');
        }
    });
    
    $('#scl-add-member-filter').on('keyup', function(e){
        
        var code = e.keyCode || e.which;
        if (code == '9') return;
        
        var inputVal = $(this).val().toLowerCase(), 
        $table = $('#scl-add-member-list'), 
        $rows = $table.find('li');

        var $filteredRows = $rows.filter(function(){
            var $rowElem = $(this);
            var value = $rowElem.find('.notification-friend').text().toLowerCase();
            return value.indexOf(inputVal) === -1;
        });

        $rows.show();
        $filteredRows.hide();
    });
});
</script>    
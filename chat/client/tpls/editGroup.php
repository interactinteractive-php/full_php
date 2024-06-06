<form method="post" id="chat-edit-group-form" class="chat-edit-group">
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
        <div class="d-flex align-items-center justify-content-center flex-row group-options mb-2 w-80">
            <?php
            if ($this->isAdmin) {
            ?>
                <input type="text" name="groupName" class="form-control border-radius-50" required="required" value="<?php echo $this->row['NAME']; ?>">
                <button type="button" class="btn alpha-pink text-pink-800 btn-icon rounded-round ml-1 chat-group-delete-btn" title="Группыг устгах">
                    <i class="icon-trash-alt"></i>
                </button>
            <?php
            } else {
            ?>
                <div class="group-name"><?php echo $this->row['NAME']; ?></div>
                <button type="button" class="btn alpha-pink text-pink-800 btn-icon rounded-round ml-2 chat-group-exit-btn" title="Группээс гарах">
                    <i class="icon-exit"></i>
                </button>
            <?php
            }
            ?>
        </div>
        <div>
            <span class="d-block opacity-75 font-size-12 font-weight-bold text-uppercase text-gray"><font class="mb-0 letter-spacing-08 text-black chat-group-createdby"></font> Үүсгэсэн</span>
        </div>
    </div>
    
    <hr />

    <div class="mb-1">
        <h5 class="font-weight-bold text-uppercase font-size-11 text-gray letter-spacing-08"><?php echo $this->membersCount; ?> гишүүнтэй</h5>
    </div>
    <div class="form-group">
        <select name="addGroupChatUsers[]" multiple="multiple" class="form-control">
        </select>
    </div>
    <ul class="media-list media-list-linked chat-group-height-scroll chat-group-members"></ul>
    <input type="hidden" name="id" value="<?php echo $this->row['ID']; ?>" required="required">
</form>

<script type="text/javascript">
var chatGroupUsers = <?php echo $this->members; ?>;
var addMembers = mgChat.users;    

$(function(){
    
    var $members = $('.chat-group-members');
    var $addGroupChatUsers = $('select[name="addGroupChatUsers[]"]');
    var chatUsersOption = '';
    var isOptionAdd = true;
    var connections = rtc.connections;
    var removeBtn = '';
    
    if (addMembers.hasOwnProperty('_<?php echo $this->row['CREATED_USER_ID']; ?>')) {
        $('.chat-group-createdby').text(mgChat.users['_<?php echo $this->row['CREATED_USER_ID']; ?>']['fullName']);
    }
    
    <?php
    if ($this->isAdmin) {
    ?>
    removeBtn = '<div class="trash-btn align-self-center ml-3">'+
        '<a href="javascript:;" class="text-default chat-group-user-remove">'+
            '<i class="icon-trash-alt text-pink-800"></i>'+
        '</a>'+
    '</div>';
    <?php
    }
    ?>
                    
    for (var key in chatGroupUsers) {   

        if (addMembers['_' + chatGroupUsers[key]['USERID']]) {

            var userRow = addMembers['_' + chatGroupUsers[key]['USERID']], isOnline = '', rowRemoveBtn = removeBtn;
            
            if (connections.hasOwnProperty(chatGroupUsers[key]['USERID']) || chatGroupUsers[key]['USERID'] == mgChat.connectionId) {
                isOnline = '<span class="is-online online_icon"></span>';
            }
            
            if (chatGroupUsers[key]['USERID'] == mgChat.connectionId) {
                rowRemoveBtn = '';
            }
            
            $members.append('<li class="edit-group-user-list" data-uid="'+chatGroupUsers[key]['USERID']+'">'+
                '<div class="media p-0 d-flex align-items-center pr-2">'+
                    '<div class="position-relative mr-2">'+
                        '<img src="api/image_thumbnail?src='+userRow['picture']+'&width=40" width="40" height="40" onerror="onChatUserImgError(this);" class="rounded-circle">'+
                        isOnline +
                    '</div>'+
                    '<div class="media-body">'+
                        '<div class="media-title font-weight-bold mb-0 line-height-normal">'+userRow['fullName']+'</div>'+
                        '<span class="text-gray font-size-11">'+userRow['departmentName']+'</span>'+
                    '</div>'+
                    rowRemoveBtn+ 
                '</div>'+
            '</li>');
        }
    }
    
    for (var key in addMembers) {
        
        isOptionAdd = true;
        
        for (var k in chatGroupUsers) {   
            if (chatGroupUsers[k]['USERID'] == mgChat.users[key]['userId']) {
                isOptionAdd = false;
            }
        }
        
        if (isOptionAdd) {
            chatUsersOption += '<option value="'+mgChat.users[key]['userId']+'">'+mgChat.users[key]['fullName']+'</option>';
        }
    }
    
    $addGroupChatUsers.append(chatUsersOption).select2({
        placeholder: 'Гишүүн нэмэх',
        allowClear: true, 
        closeOnSelect: false
    });
    
    $members.on('click', '.chat-group-user-remove', function(){

        var $li = $(this).closest('li');
        
        if ($li.find('input[type="hidden"]').length == 0) {
            var userId = $li.attr('data-uid');
            $li.append('<input type="hidden" name="removeUserId[]" value="'+userId+'">');
            $li.addClass('alpha-danger');
        } else {
            $li.find('input[type="hidden"]').remove();
            $li.removeClass('alpha-danger');
        }
    });
    
});
</script>
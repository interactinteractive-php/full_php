var IS_LOAD_MENUMETA_SCRIPT = true;

function menuMetaAddByUserInit(elem, metaDataId) {
    _processPostParam = 'mainId=' + metaDataId;
    callWebServiceByMeta('1642479399106553', true, '', false, { callerType: 'usermenu', isMenu: 1 }, undefined, undefined, undefined, function() {
        $('.pf-module-sidebar').find('a[data-moduleid="'+metaDataId+'"]').trigger('click');
    });
}
function moduleMetaAddByUserInit(elem) {
    callWebServiceByMeta('1584699723066', true, '', false, { callerType: 'usermodule', isMenu: 1 });
}






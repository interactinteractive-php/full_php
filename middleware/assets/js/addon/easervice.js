var isEaServiceMetaRender = true;

function eaServiceMetaRender(elem, workSpaceId, metaDataId, paramResolveData, $appendElement) {
    $.ajax({
        type: 'post',
        url: 'mddatamodel/getDataViewByEaServiceId',
        data: paramResolveData, 
        dataType: 'json', 
        success: function (data) {
            
            PNotify.removeAll();
            
            if (data.hasOwnProperty('Html')) {

                $appendElement.empty().append(data.Html);
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
        }
    });
}

function eaServicePivotRender(elem, workSpaceId, metaDataId, paramResolveData, $appendElement) {
    
    setTimeout(function(){
        
        paramResolveData.push({name: 'windowHeight', value: $(window).height() - parseInt($appendElement.offset().top) + 25});
    
        $.ajax({
            type: 'post',
            url: 'mddatamodel/getPivotByEaServiceId',
            data: paramResolveData, 
            dataType: 'json', 
            success: function (data) {

                PNotify.removeAll();

                if (data.hasOwnProperty('html')) {

                    $appendElement.empty().append(data.html);

                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
            }
        });
        
    }, 10);
}
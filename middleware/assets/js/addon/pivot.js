var isPivotToolAddonScript = true;

function dataViewPivotTool(elem, selectedRow, paramData) {
    
    var obj = paramDataToObject(paramData);
    var tabName = obj.hasOwnProperty('tabname') ? obj.tabname : 'Pivot';
    
    paramData.push({ name: 'windowHeight', value: $(window).height() - 30 });
    
    $.ajax({
        type: 'post',
        url: 'mdpivot/dataViewPivotView',
        data: paramData, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        }, 
        success: function(data) {
            appMultiTabByContent({metaDataId: '1570170226232110', title: tabName, type: 'dataview', content: data.html, tabNameReload: true});
        }
    });
}
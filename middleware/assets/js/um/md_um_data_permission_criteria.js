/* global Core, PNotify */

var mdUmDataPermissionCriteria=function(){

  var $dataPermissionCriteriaForm,
          $batchFieldSet,
          $batchDiv;

  var initEvent=function(selectedRow){
    $dataPermissionCriteriaForm=$('#dataPermissionCriteriaForm');
    $batchFieldSet=$('#batchFieldSet');
    $batchDiv=$('#batchDiv');

    $('#addCriteriaBatchBtn').click(function(){

      $.ajax({
        url: "mdum/datePermissionCriteriaRender",
        type: "POST",
        data: {
          selectedRow: selectedRow,
          isSingleAddrow: true
        },
        dataType: "JSON",
        success: function(response){
          if(typeof response.html !== "undefined"){
            var cnt=$batchDiv.find('fieldset').length;
            $batchDiv.append(response.html);
            var $lastBatch=$batchDiv.find('fieldset').last();
            $lastBatch.find('input[name="paramName[0][]"]').attr('name', 'paramName[' + cnt + '][]');
            $lastBatch.find('input[name="paramAction[0][]"]').attr('name', 'paramAction[' + cnt + '][]');
            $lastBatch.find('input[name="paramValue[0][]"]').attr('name', 'paramValue[' + cnt + '][]');
          }
        },
        error: function(jqXHR, exception){
          Core.unblockUI();
        }
      }).complete(function(){
        Core.unblockUI();
      });
    });

    $('.um-meta-permission').on('click', '.remove-batch-btn', function(){
      var $this=$(this);
      $this.closest('fieldset').remove();
      if($this.hasClass('has-data')){
        saveDataPermissionCriteria(false);
      }
    });
  };

  var saveDataPermissionCriteria=function(isClose){
    var data=$dataPermissionCriteriaForm.serialize();
    Core.blockUI();
    $.ajax({
      url: "mdum/saveDataPermissionCriteria",
      type: "POST",
      data: data,
      dataType: "JSON",
      success: function(response){
        PNotify.removeAll();
        if(typeof response.status !== "undefined"){
          new PNotify({
            title: response.status,
            text: response.message,
            type: response.status,
            sticker: false
          });

          if(response.status === 'success' && typeof isClose === 'undefined'){
            $('#dialog-datePermissionCriteriaRender').dialog('close');
          }
        }
      },
      error: function(jqXHR, exception){
        Core.unblockUI();
      }
    }).complete(function(){
      Core.unblockUI();
    });
  };

  return {
    init: function(selectedRow){
      initEvent(selectedRow);
    },
    saveDataPermissionCriteria: function(){
      saveDataPermissionCriteria();
    }
  };
}();
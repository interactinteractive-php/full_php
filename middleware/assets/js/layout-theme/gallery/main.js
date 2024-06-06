var gallery=function(){
  var $gallery;

  var initEvent=function(){
    $gallery=$('.show-type-gallery');
    $.each($gallery, function(key, value){
      var $gParent=$(value).parent(),
              $gChild=$(value).find('.gallery-child');
      if($gParent.hasClass('col-md-12') || $gParent.hasClass('col-md-11') ||
              $gParent.hasClass('col-md-10')){
        $gChild.addClass('col-md-2 col-sm-3 col-xs-4');
      } else if($gParent.hasClass('col-md-9') || $gParent.hasClass('col-md-8') ||
              $gParent.hasClass('col-md-7')){
        $gChild.addClass('col-md-3 col-sm-4 col-xs-6');
      } else if($gParent.hasClass('col-md-6') || $gParent.hasClass('col-md-5')){
        $gChild.addClass('col-md-4 col-sm-4 col-xs-6');
      } else {
        $gChild.addClass('col-md-6 col-sm-4 col-xs-6');
        if($gParent.hasClass('col-md-1') || $gParent.hasClass('col-md-2')){
          $(value).find('.photo').addClass('photo-min');
        } else {
          $(value).find('.photo').addClass('photo-medium');
        }
      }
    });
    $gallery.show();
  };

  return {
    init: function(){
      initEvent();
    }
  };
}();
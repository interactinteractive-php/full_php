$(document.body).on('click', '.appmarket_menu_wrapper .nav-link', function() {
    $('.appmarket_menu_wrapper').find('.nav-item-selected').removeClass('nav-item-selected');
    $(this).addClass('nav-item-selected');
});

$(document.body).on('click', '.appmarket-back-btn', function() {
    $('.appmarket-body').find('.appmarket-home-page').removeClass('d-none');
    $('.appmarket-body').find('.appmarket-single-page').empty();
});

$(document.body).on('change', '#appmarket-file-upload', function() {
    var input = $(this)[0];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.appmarket-preview-image').css('background-image', 'url("'+e.target.result+'")');
        }
        reader.readAsDataURL(input.files[0]);
    }
});    

$(document.body).on('click', '#appmarket-delete-file-upload', function() {
    $('.appmarket-preview-image').css('background-image', '');
    $('#appmarket-file-upload').val('');
});    

if (selectedModuleId) {
    getUsingModule(selectedModuleId);
    getRecommendedModule(selectedModuleId);    
}

$(document.body).on('click', '.appmarket-filter-typemenu', function() {
    var $self = $(this);
    if ($('.appmarket-single-page').length) {
        $('.appmarket-single-page').empty();
    }
    $('.appmarket-home-page').removeClass('d-none');
    getUsingModule($self.data('id'));
    getRecommendedModule($self.data('id'));
});    

$('a[href="#menu-active2"]').on('shown.bs.tab', function(e) {    
    $('.appmarket-slick-carousel4').slick({
        infinite: true,
        slidesToShow: 1,
        arrows: true,
        variableWidth: true,
        autoplay: false,
        dots: false,
        prevArrow:'<div style="left: -12px;flex-shrink: 0;width: 40px;height: 40px;background: transparent;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer;position: absolute;z-index: 10;margin-top: 125px;" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: transparent;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer;position: absolute;margin-top: -135px;right: -14px;z-index: 10;" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });   
    setTimeout(function() {
        $(".appmarket-slick-carousel4").css("width", $(window).width() - 470);
    }, 10);  

    $('.appmarket-slick-carousel5').slick({
        infinite: true,
        slidesToShow: 1,
        arrows: true,
        variableWidth: true,
        autoplay: false,
        dots: false,
        prevArrow:'<div style="left: -12px;flex-shrink: 0;width: 40px;height: 40px;background: transparent;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer;position: absolute;z-index: 10;margin-top: 125px;" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: transparent;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer;position: absolute;margin-top: -135px;right: -14px;z-index: 10;" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });   
    setTimeout(function() {
        $(".appmarket-slick-carousel5").css("width", $(window).width() - 470);
    }, 10);          
});       

function getUsingModule(criteria) {
    var requestParam = {
        dataviewId: "1700116960752470",
        pageSize: 20
    };

    if (typeof criteria !== 'undefined') {
        requestParam['criteriaData'] = {
            categoryid: [{
                operator: "=",
                operand: criteria
            }]                
        };
    }

    $.ajax({
        type: "post",
        url: "api/callDataview",
        data: requestParam,
        beforeSend: function () {
            $('.appmarket-card').html('<img src="assets/core/global/img/loading.gif" class="pl-1" />');
        },            
        dataType: "json",
        success: function (data) {
            if (data.status === "success" && data.result[0]) {
                var htmlData = '';

                for (var i = 0; i < data.result.length; i++) {
                    htmlData += '<div class="card card-style"><a onclick="appMarketGetProduct(\''+data.result[i]['id']+'\')" href="javascript:;">'+
                        '<div class="card-body p-3">'+
                            '<div class="d-flex justify-content-between">'+
                                '<div class="rounded-circle module-img" style="background-color:'+standartColors[i]+'"><img style="padding: 16px;" src="https://process.veritech.mn/'+(data.result[i]['profilephoto'] ? data.result[i]['profilephoto'] : 'assets/core/global/img/veritech-erp.png')+'" class="module-img mr-1 img-fluid my-auto" alt="img"></div>'+
                            '</div>'+
                            '<p class="card-text h5 pt-3 font-bold">'+data.result[i]['name']+'</p>'+
                            '<p class="py-3 card-text-description">'+(data.result[i]['description'] ? data.result[i]['description'] : '')+'</p>'+
                        '</div>'+
                    '</a></div>';
                }

                $('.appmarket-card').html(htmlData);
            } else {
                $('.appmarket-card').html(plang.get('msg_no_record_found'));                
            }
        }
    });    
};
if (!selectedModuleId) {
    getUsingModule();
}

function getRecommendedModule(criteria) {
    var requestParam = {
        dataviewId: "170012458513110",
        pageSize: 20
    };

    if (typeof criteria !== 'undefined') {
        requestParam['criteriaData'] = {
            categoryid: [{
                operator: "=",
                operand: criteria
            }]                
        };
    }

    $.ajax({
        type: "post",
        url: "api/callDataview",
        data: requestParam,
        beforeSend: function () {
            $('.appmarket-slick-carousel4').html('<img src="assets/core/global/img/loading.gif" class="pl-1" />');
        },            
        dataType: "json",
        success: function (data) {
            if (data.status === "success" && data.result[0]) {
                var htmlData = '';

                for (var i = 0; i < data.result.length; i++) {
                    htmlData += '<div class="">'+
                        '<div class="card card-style"><a onclick="appMarketGetProduct(\''+data.result[i]['id']+'\')" href="javascript:;">'+
                            '<div class="card-body p-3">'+
                                '<div class="d-flex justify-content-between">'+
                                    '<div class="rounded-circle module-img" style="background-color:'+standartColors[i]+'"><img style="padding: 16px;" src="https://process.veritech.mn/'+(data.result[i]['profilephoto'] ? data.result[i]['profilephoto'] : 'assets/core/global/img/veritech-erp.png')+'" class="module-img mr-1 img-fluid my-auto" alt="img"></div>'+
                                '</div>'+
                                '<p class="card-text h5 pt-3 font-bold">'+data.result[i]['name']+'</p>'+
                                '<p class="py-3 card-text-description">'+(data.result[i]['description'] ? data.result[i]['description'] : '')+'</p>'+
                            '</div>'+
                        '</a></div>'+
                    '</div>';
                }

                $('.appmarket-slick-carousel4').html(htmlData);
                $('.appmarket-slick-carousel4').slick('refresh');
            } else {
                $('.appmarket-slick-carousel4').html(plang.get('msg_no_record_found'));                
            }
        }
    });    
};
if (!selectedModuleId) {
    getRecommendedModule();
}

function appMarketGetProduct(id) {
    $.ajax({
        type: "post",
        url: "appmarket/detail/"+id,
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true
          });
        },            
        success: function (data) {
            $('.appmarket-body').find('.appmarket-home-page').addClass('d-none');
            $('.appmarket-body').find('.appmarket-single-page').empty().append(data);
            Core.unblockUI();
            $('html, body').animate({scrollTop: 0}, 'fast');
        }
    });        
}
<div class="transferprocessdv">
	<form>
		<div style="min-height: 570px;" class="col-md-12">
			<div style="position: absolute;right: 40px;top: -51px;" class="btn-group">
				<button type="button" class="btn btn-sm bg-purple-300 btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="icon-file-text"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(56px, 36px, 0px);">
					<a href="javascript:;" class="dropdown-item">Баримт бичгийн гарчиг 01</a>
					<a href="javascript:;" class="dropdown-item">Барим бичгийн гарчиг 02 ба энэ бол нилээн урт нэр байх болно.</a>
				</div>
			</div>
			<div class="col-md-3 modalleftsidebar">
					<input style="width: 212px;margin-bottom: 20px;" type="text" name="q" placeholder="Байрлал" class="pb20"><button onclick="return false;"><i class="fa fa-search"></i></button>
				<div class="tabbable-line docassigntabbable">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a href="#tab_16_2" class="nav-link active" data-toggle="tab" aria-expanded="false">
								Байгууллага
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab_16_1" data-toggle="tab" aria-expanded="true" class="nav-link">
								Хүн
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_16_2">
							<div id="treedepartment-<?php echo $this->uniqid; ?>" style="overflow: auto;height: 250px;overflow-x: hidden;">
							</div>
						</div>
						<div class="tab-pane" id="tab_16_1">
							<div style="max-height: 310px;overflow: auto;">
								<!--<h4 class="text-transform-none">Албан тушаал</h4>-->
								<span class="position-container"></span>
								<!-- <a href="javascript:void(0);">
									<div class="float-right colorpink">Илүү...</div>
								</a> -->
							</div>
							<!-- <div class="mt20">
								<h4 class="text-transform-none">Хүйс</h4>
								<a href="javascript:void(0);">
									<div class="list2">Эрэгтэй<font class="float-right">9</font></div>
								</a>
								<a href="javascript:void(0);">
									<div class="list2">Эмэгтэй<font class="float-right">65</font></div>
								</a>
							</div> -->
						</div>
					</div>
				</div>
			</div>
			<div style="padding-right: 0;" class="col-md-9 modalcenter">
				<div class="main-box">
					<div class="tab-content">
						<div class="breadcrumb">
							<div class="float-left assign-breadcrumb">
								<a href="javascript:;" onclick="assignBreadCrumb(this)" data-id="">Бүгд</a>
							</div>
						</div>
						<div class="tab-pane active department-user-list" id="tab_1_1">
							<?php if ($this->departmentList) {
								foreach ($this->departmentList as $row) {
								?>
									<div class="box dlist department iconhover" data-id="<?php echo $row['departmentid']; ?>">
										<p class="dood"><?php echo $row['departmentcode']; ?></p>
										<div class="imgdlist">
											<center>
												<img style="width: 60px;" class="dlist" src="assets/core/global/img/metaicon/big/125.png">
											</center>
										</div>
										<p class="font-weight-bold"><?php echo $row['departmentname']; ?></p>
										<div class="middle">
											<div class="imgicon">
												<a href="javascript:void(0);"><i style="margin-right:15px;" class="fa fa-download"></i></a>
												<a href="javascript:void(0);"><i class="fa fa-shopping-cart"></i></a>
											</div>
										</div>
									</div>
							<?php }
							} ?>
						</div>
					</div>
					<!-- <div class="mainrightbtn">
						<img src="assets/core/global/img/document/rightbtn.png">
					</div> -->
				</div>
			</div>
			<!--<div class="bagboxbtn">
				<a href="javascript:void(0);" class="btn btn-circle btn-lg border float-right">
					<div>Сагсанд хийх</div>
				</a>
			</div>-->
		</div>
		<div class="col-md-12">
			<div class="transferprocessfooter">
				<div class="col-8 foothuman-container">
					<!--<h4 class="mb20">Сагс</h4>-->
				</div>
				<div class="col-4">
					<a href="javascript:void(0);" class="btn btn-sm btn-primary footer float-right continue">
						<div>Шилжүүлэх</div>
					</a>
					<a href="javascript:void(0);" class="btn btn-sm btn-light footer float-right" data-dismiss="modal">
						<div>Хаах</div>
					</a>
					<a href="javascript:;" class="btn btn-sm btn-link footer float-right continue" onclick="dataViewWfmStatusFlowViewerDocAssign(this);">
						<div>Дэлгэрэнгүй</div>
					</a>
				</div>
			</div>
		</div>
	</form>
</div>
<style type="text/css">
	.modal-body {
		height: 650px !important;
	}
	.transferprocessdv .main-box {
		max-height: 530px;
	}
	.transferprocessdv .modalleftsidebar {
		height: 530px;
	}
	.transferprocessdv .jstree.jstree-1.jstree-default {
		height: 400px !important;
	}
	.modal-footer {
	    display: none;
	}
	.transferprocessdv .nav-tabs.gridlist>li {
		width: 30px;
	}
	.transferprocessdv .nav-tabs.gridlist>li > a, 
	.transferprocessdv .nav-tabs.gridlist>li > a:focus, 
	.transferprocessdv .nav-tabs.gridlist>li > a:hover,
	.transferprocessdv .nav-tabs.gridlist {
		background: none;
		border: 0;
	}
	.transferprocessdv .nav-tabs.gridlist>li {
		cursor: pointer;
	}
	.transferprocessdv .btn-group {
		cursor: pointer;
	}
</style>

<script>
	var windowId = ".transferprocessdv";		

	$(function () {

		$(windowId).on('click', '.box.department', function(){
			var $this = $(this);

            Core.blockUI({
                message: 'Loading...', 
                boxed: true
			});
						
			$.ajax({
				type: 'post',
				url: 'mddoc/documentassignDepartment',
				data: {id: $this.data('id')},
				dataType: 'json', 
				success: function (data) {
					if (data.departmentList.length || data.employeeList.length) {
						var html = '';		

						html = docPrintDepartmentList(data);
						html += docPrintEmployeeList(data);
						
						$this.closest('.department-user-list').empty().append(html);
						$('.assign-breadcrumb', windowId).append('<a href="javascript:;" onclick="assignBreadCrumb(this)" data-id="'+$this.data('id')+'"> / '+$this.find('.font-weight-bold').text()+'</a>');

					}
					Core.unblockUI();
				}
			});
						
			docGetFilterPosition($this);

		}).on('dblclick', '.box.employee', function (e) {
			var $this = $(this);

			if (!$(windowId).find('.foothuman-container').find('.foothuman-'+$this.data('id')).length) {
				var html = '<div class="foothuman foothuman-'+$this.data('id')+'">'+
								'<center><img src="'+$this.find('img').attr('src')+'"></center>'+
								/*'<div class="btn-group mt30">'+
									'<font class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'+
										'<img class="footer3line" src="assets/core/global/img/document/footer3line.png">'+
									'</font>'+
									'<ul class="dropdown-menu ml10" role="menu"><li class="dropdown-submenu"><a href="javascript:void(0);"><i class="fa fa-sitemap"></i> Эрх</a><ul class="dropdown-menu"><li><a href="javascript:void(0);"><i class="fa fa-edit"></i> Засах</a></li><li><a href="javascript:void(0);"><i class="fa fa-eye"></i> Харах</a></li><li><a href="javascript:void(0);"><i class="fa fa-comments-o"></i> Санал өгөх</a></li><li><a href="javascript:void(0);"><i class="fa fa-mail-reply-all"></i> Бүгд</a></li></ul></li><li class="sanuulga"><a href="javascript:void(0);"><i class="fa fa-crosshairs"></i> Үүрэг</a></li></ul>'+
								'</div>'+*/
								'<p>'+$this.find('.font-weight-bold').text()+'</p>'+
								'<input type="hidden" name="description[]">'+
								'<input type="hidden" name="rowdata[]" value="'+$this.data('rowdata')+'">'+
								'<input type="hidden" name="userId[]" value="'+$this.data('id')+'">'+
							'</div>';
				$(windowId).find('.foothuman-container').append(html);
			}
		});

		$(windowId).on('click', '.sanuulga', function(){
			var $this = $(this);
            var $dialognameConfirm = 'dialog-docassign-newcalculate-confirm';
            if (!$('#'+$dialognameConfirm).length) {
                $('<div id="' + $dialognameConfirm + '"></div>').appendTo(windowId);
            }

            var $confirmText = '<textarea rows="3" autofocus class="form-control">'+$this.closest('.foothuman').find('input[name="description[]"]').val()+'</textarea><br>';

            $("#" + $dialognameConfirm).empty().html($confirmText);
            $("#" + $dialognameConfirm).dialog({
                appendTo: "body",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулга',
                width: 400,
                height: 'auto',
                modal: true,
                close: function () {
                    $("#" + $dialognameConfirm).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-circle btn-sm btn-success', click: function () {
						$this.closest('.foothuman').find('input[name="description[]"]').val($("#" + $dialognameConfirm).find('textarea').val());
                        $("#" + $dialognameConfirm).dialog('close');
                    }}
                ]
            });
            $("#" + $dialognameConfirm).dialog('open');			
		});

		$(windowId).on("keydown", 'input[name="q"]', function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			var $this = $(this);

			if (code === 13) {
				Core.blockUI({
					message: 'Loading...', 
					boxed: true
				});
						
				$.ajax({
					type: 'post',
					url: 'mddoc/documentassignDepartment',
					data: {departmentName: $this.val()},
					dataType: 'json', 
					success: function (data) {
						if (data.departmentList.length || data.employeeList.length) {
							var html = '';		

							html = docPrintDepartmentList(data);
							html += docPrintEmployeeList(data);
							
							$('.department-user-list', windowId).empty().append(html);

						}
						Core.unblockUI();
					}
				});				

				return false;
			}
		});		

        $("#treedepartment-<?php echo $this->uniqid; ?>").jstree({
            "core": {
				'themes': {
					'responsive': false
				},
                "check_callback": true,
				'data': {
					url: URL_APP + 'mddoc/getDeparmentListJtreeData',
					dataType: "json",
					data: function (node) {
						return {
							parentId: (node.id === "#" ? '' : node.id),
							parentNode: 0,
							pSelected: '0'
						};
					}
				}
            },
            "types": {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                }
            },
            "plugins": ["types", "cookies"]
        }).bind("select_node.jstree", function (e, data) {
			var nid = data.node.id === 'null' ? '' : data.node.id;
			
			Core.blockUI({
				message: 'Loading...', 
				boxed: true
			});
						
			$.ajax({
				type: 'post',
				url: 'mddoc/documentassignDepartment',
				data: {id: nid},
				dataType: 'json',
				success: function (data) {
					if (data.departmentList.length) {
						var html = '';		

						html = docPrintDepartmentList(data);
						html += docPrintEmployeeList(data);

						$('.department-user-list', windowId).empty().append(html);

					} else {
						$('.department-user-list', windowId).empty();
					}
					Core.unblockUI();
				}
			});

			// if ($(elem).data('id')) {
			// 	docGetFilterPosition($(elem));
			// } else {
			// 	$(windowId).find('.position-container').empty();
			// }
        });	

	});

	function onUserImgErrorDocDoc(source) {
		source.src = "assets/core/global/img/user.png";
		source.onerror = "";
		return true;
	}	

	function assignBreadCrumb(elem) {
		Core.blockUI({
			message: 'Loading...', 
			boxed: true
		});
					
		$.ajax({
			type: 'post',
			url: 'mddoc/documentassignDepartment',
			data: {id: $(elem).data('id')},
			dataType: 'json',
			success: function (data) {
				if (data.departmentList.length) {
					$(elem).nextAll().remove();

					var html = '';		

					html = docPrintDepartmentList(data);
					html += docPrintEmployeeList(data);

					$('.department-user-list', windowId).empty().append(html);

				}
				Core.unblockUI();
			}
		});

		if ($(elem).data('id')) {
			docGetFilterPosition($(elem));
		} else {
			$(windowId).find('.position-container').empty();
		}
	}

	function docFilterByPosition(elem) {
		Core.blockUI({
			message: 'Loading...', 
			boxed: true
		});
		
		$.ajax({
			type: 'post',
			url: 'mddoc/documentassignDepartment',
			data: {id: $(elem).data('id'), positionId: $(elem).data('position')},
			dataType: 'json',
			success: function (data) {
				if (data.departmentList.length) {
					var html = '';		

					html = docPrintDepartmentList(data);
					html += docPrintEmployeeList(data);

					$('.department-user-list', windowId).empty().append(html);

				}
				Core.unblockUI();
			}
		});
	}

	function docPrintEmployeeList(data) {
		var dataEmpLen = data.employeeList.length;
			i = 0,
			html = '';

		for (i; i < dataEmpLen; i++) {
			html += '<div class="box dlist employee" data-id="'+data.employeeList[i].id+'" data-rowdata="'+encodeURIComponent(JSON.stringify(data.employeeList[i]))+'">'+
					'<p class="dood">'+(data.employeeList[i].statusname ? data.employeeList[i].statusname : '')+'</p>'+
					'<div class="imgdlist">'+
						'<center><img class="dlist" onerror="onUserImgErrorDocDoc(this)" src="'+data.employeeList[i].picture+'" style="width: 60px;"></center>'+
					'</div>'+
					'<p class="font-weight-bold">'+data.employeeList[i].employeename+'</p>'+
				'</div>';						
		}

		return html;
	}

	function docPrintDepartmentList(data) {
		var dataLen = data.departmentList.length, i = 0, html = '';		

		for (i; i < dataLen; i++) {
			html += '<div class="box dlist department iconhover" data-id="'+data.departmentList[i].departmentid+'">'+
					'<p class="dood">'+data.departmentList[i].departmentcode+'</p>'+
					'<div class="imgdlist">'+
						'<center><img class="dlist" src="assets/core/global/img/metaicon/big/125.png" style="width: 60px;"></center>'+
					'</div>'+
					'<p class="font-weight-bold">'+data.departmentList[i].departmentname+'</p>'+
					'<div class="middle">'+
						'<div class="imgicon">'+
							'<a href="javascript:void(0);"><i style="margin-right:15px;" class="fa fa-download"></i></a>'+
							'<a href="javascript:void(0);"><i class="fa fa-shopping-cart"></i></a>'+
						'</div>'+
					'</div>'+
				'</div>';						
		}	

		return html;
	}

	function docGetFilterPosition(elem) {
		$.ajax({
			type: 'post',
			url: 'mddoc/documentassignPosition',
			data: {id: elem.data('id')},
			dataType: 'json', 
			success: function (data) {
				$(windowId).find('.position-container').empty();

				if (data.length) {
					var dataLen = data.length, i = 0, html = '';		

					for (i; i < dataLen; i++) {
						html += '<a href="javascript:void(0);" onclick="docFilterByPosition(this)" data-id="'+elem.data('id')+'" data-position="'+data[i].positionid+'">'+
								'<div class="list2">'+data[i].positionname+'</div>'+
							'</a>';
					}
					
					$(windowId).find('.position-container').append(html);

				}
				Core.unblockUI();
			}
		});		
	}
	//$(".tab2aclass").click(function() {
	//  $(".bodydd").show(0);
	//});
	//$(".tab1aclass").click(function() {
	//  $(".bodydd").hide(0);
	//});
	function dataViewWfmStatusFlowViewerDocAssign(elem) {
		var selectedrow = <?php echo json_encode($this->selectedRow); ?>;
		var serializeData = $(elem).closest('.transferprocessfooter').find('.foothuman-container').find('input[type="hidden"]').serialize();

        $.ajax({
            type: 'post',
            url: 'mdobject/getRowWfmStatusForm',
            data: {
                refStructureId: '1447239000602',
                dataViewId: '1472715413889',
                metaDataId: '1472715413889',
                rowId: selectedrow.id,
                dataRow: selectedrow,
                wfmStatusId: selectedrow.wfmstatusid,
                wfmStatusName: selectedrow.wfmstatusname,
                wfmstatuscolor: selectedrow.wfmstatuscolor,
                isSee: true,
				serializeData: serializeData
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                var $dialogName = 'dialog-wfmstatus-user-' + selectedrow.id;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: 'auto',
                    maxHeight: $(window).height() - 50,
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.close_btn, "class": 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        });		
	}
</script>
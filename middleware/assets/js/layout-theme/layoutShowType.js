/* global Core */

var layoutShowType = function () {

	var $showTypeLayout,
		$showTypeLayoutParameterList,
		$themeViewer,
		dataViewColumnNameList = [],
		bpMetaDataRow;

	var initEvent = function () {
		$showTypeLayout = $("#showTypeLayout"),
			$showTypeLayoutParameterList = $showTypeLayout.find("#showTypeLayoutParameterList");

		$showTypeLayout.find("#showTypeThemeCode").change(function () {
			var thisVal = $(this).val();
			if (thisVal.length > 0) {
				Core.blockUI();
				$.ajax({
					url: "mdlayoutrender/getShowTypeParameters",
					type: "POST",
					data: {
						themeCode: thisVal
					},
					dataType: "JSON",
					success: function (response) {
						if (response && response !== null && typeof response[0] !== "undefined") {
							setParameters(response[0]);
						}
					},
					error: function (jqXHR, exception) {
						Core.unblockUI();
					}
				}).complete(function () {
					Core.unblockUI();
				});
			}
		});

		$showTypeLayout.find('#viewShowTypeThemeBtn').click(function () {
			viewTheme();
		});
	};

	var setParameters = function (response) {
		var sections = $(response).find('[data-section]'),
			parameterObject,
			html = '';

		if (sections.length === 1) {
			parameterObject = $(response).find('[data-parameter]');
		} else {
			parameterObject = $(sections[0]).find('[data-parameter]');
			$showTypeLayout.find("#widgetLinkRowCount").val(sections.length).attr('readonly', true);
		}

		$.each(parameterObject, function () {
			var parameterId = $(this).attr('data-parameter');
			html += '<tr>';
			html += '<td class="left-padding first">Parameter ' +
				parameterId +
				':</td>';
			html += '<td>';
			html += '<select name="listParamName[]" class="form-control">';
			html += '<option value="">-Сонгох-</option>';
			$.each(dataViewColumnNameList, function (k, v) {
				html += '<option value="' + v.META_DATA_CODE + '">' + v.META_DATA_NAME + '</option>';
			});
			html += '</select>';
			html += '</td>';
			html += '</tr>';
		});

		$showTypeLayoutParameterList.find('tbody').empty().append(html);
	};

	var viewTheme = function () {
		var themeCode = $showTypeLayout.find("#showTypeThemeCode").val();
		if (themeCode.length > 0) {
			$('body').append("<div id='themeViewerModal'></div>  ");
			$themeViewer = $("#themeViewerModal");
			$themeViewer.empty().html(
				"<img class='img-fluid' style='display: block; margin: auto;' src=\"middleware/views/layoutrender/themes/dvtheme/thumb/" +
				themeCode + ".PNG\">");
			$themeViewer.dialog({
				appendTo: "body",
				cache: false,
				resizable: true,
				bgiframe: true,
				autoOpen: false,
				title: themeCode,
				width: 600,
				minWidth: 600,
				height: 500,
				modal: false,
				buttons: [{
					text: 'Хаах',
					class: 'btn btn-sm blue-hoki',
					click: function () {
						$themeViewer.empty().dialog('close');
						$themeViewer.dialog('destroy').remove();
					}
				}]
			});

			$themeViewer.dialog('open');
		}
	};

	var getShowTypeModal = function (metaDataId, metaTypeId) {
		Core.blockUI();
		$.ajax({
			url: "mdlayoutrender/getShowTypeModal",
			type: "POST",
			data: {
				metaDataId: metaDataId,
				metaTypeId: metaTypeId
			},
			dataType: "JSON",
			success: function (response) {
				if (response.dataViewColumnNameList) {
					dataViewColumnNameList = response.dataViewColumnNameList;
				}
				initShowTypeModal(response);
			},
			error: function (jqXHR, exception) {
				Core.unblockUI();
			}
		}).complete(function () {
			Core.unblockUI();
		});
	};

	var initShowTypeModal = function (data) {
		$('body').find("#showTypeModalDiv").empty().html(data.Html);
		applyCss(data);
		initEvent();
		$showTypeLayout.dialog({
			appendTo: "body",
			cache: false,
			resizable: false,
			bgiframe: true,
			autoOpen: false,
			title: data.Title,
			width: 800,
			height: "auto",
			modal: true,
			buttons: [{
					text: data.save_btn,
					class: 'btn green btn-sm',
					click: function () {
						putWidgetParams();
					}
				},
				{
					text: data.close_btn,
					class: 'btn blue-hoki btn-sm',
					click: function () {
						$showTypeLayout.dialog('close');
						$showTypeLayout.dialog('destroy').remove();
					}
				}
			]
		});
		$showTypeLayout.dialog('open');
	};

	var putWidgetParams = function () {
		var positionId = bpMetaDataRow.find('.layoutPathCell').attr('data-position'),
		html = '';
		html += '<input type="hidden" name="showTypeThemeCode[' + positionId + ']" value="' +
			$showTypeLayout.find("#showTypeThemeCode").val() + '">' +
			'<input type="hidden" name="widgetLinkRowCount[' + positionId + ']" value="' +
			$showTypeLayout.find("#widgetLinkRowCount").val() + '">' +
			'<input type="hidden" name="listMetaDataId[' + positionId + ']" value="' +
			$showTypeLayout.find("#listMetaDataId").val() + '">';


		$.each($showTypeLayout.find('select[name="listParamName[]"]'), function (lKey, lName) {
			html += '<input type="hidden" name="listParamName[' + positionId + '][]" value="' + $(lName).
			val() + '">';
		});

		bpMetaDataRow.find('.layoutPathCell').append(html);
		$showTypeLayout.dialog('close');
		$('body').find("#showTypeModalDiv").empty();
	};

	var applyCss = function (data) {
		if (typeof data.css !== "undefined") {
			$.each(data.css, function (key, cssPath) {
				$('head').append('<link rel="stylesheet" href="' + cssPath + '" />');
			});
		}
	};

	return {
		init: function () {
			initEvent();
		},
		getShowTypeModal: function (rows, row) {
			bpMetaDataRow = row;
			if (rows['META_TYPE_ID'] === '200101010000016') {
				getShowTypeModal(rows['META_DATA_ID'], rows['META_TYPE_ID']);
			}
		},
		getShowTypeModalUpdate: function (metaDataId, metaTypeId, thisEl) {
			bpMetaDataRow = $(thisEl).parents('tr');
			getShowTypeModal(metaDataId, metaTypeId);
		}
	};
}();
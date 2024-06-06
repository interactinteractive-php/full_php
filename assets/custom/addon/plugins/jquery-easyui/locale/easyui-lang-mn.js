if ($.fn.pagination){
	$.fn.pagination.defaults.beforePageText = 'Хуудас';
	$.fn.pagination.defaults.afterPageText = 'of {pages}';
	$.fn.pagination.defaults.displayMsg = 'Энэ хуудсанд {from} - {to}. Нийт <b>{total}</b>';
}
if ($.fn.datagrid){
	$.fn.datagrid.defaults.loadMsg = 'Түр хүлээнэ үү ...';
}
if ($.fn.treegrid && $.fn.datagrid){
	$.fn.treegrid.defaults.loadMsg = $.fn.datagrid.defaults.loadMsg;
}
if ($.messager){
	$.messager.defaults.ok = 'Тийм';
	$.messager.defaults.cancel = 'Цуцлах';
}
$.map(['validatebox','textbox','filebox','searchbox',
		'combo','combobox','combogrid','combotree',
		'datebox','datetimebox','numberbox',
		'spinner','numberspinner','timespinner','datetimespinner'], function(plugin){
	if ($.fn[plugin]){
		$.fn[plugin].defaults.missingMessage = 'Бөглөх шаардлагатай.';
	}
});
if ($.fn.validatebox){
	$.fn.validatebox.defaults.rules.email.message = 'Зөв мэйл хаяг оруулна уу.';
	$.fn.validatebox.defaults.rules.url.message = 'Зөв URL хаяг оруулна уу.';
	$.fn.validatebox.defaults.rules.length.message = 'Please enter a value between {0} and {1}.';
	$.fn.validatebox.defaults.rules.remote.message = 'Please fix this field.';
}
if ($.fn.calendar){
	$.fn.calendar.defaults.weeks = ['S','M','T','W','T','F','S'];
	$.fn.calendar.defaults.months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
}
if ($.fn.datebox){
	$.fn.datebox.defaults.currentText = 'Өнөөдөр';
	$.fn.datebox.defaults.closeText = 'Хаах';
	$.fn.datebox.defaults.okText = 'Тийм';
}
if ($.fn.datetimebox && $.fn.datebox){
	$.extend($.fn.datetimebox.defaults,{
		currentText: $.fn.datebox.defaults.currentText,
		closeText: $.fn.datebox.defaults.closeText,
		okText: $.fn.datebox.defaults.okText
	});
}

<?php
if (!defined('_VALID_PHP')) {
    exit('Direct access to this location is not allowed.');
}
?>
<div class="col-md-12">
    <div class="card light shadow">
        <div class="card-body">    
            <div class="jeasyuiTheme2 mt10">
                <table id="ntf"></table>
            </div>
        </div>
    </div>
</div>    
<script type="text/javascript">
    function change(checkbox, notificationId) {
        $.post("mdnotification/mark/" + notificationId + '/' + $(checkbox).is(":checked"));
        var a = $(checkbox).closest('tr').find("td[field=message]").find("a");
        if ($(checkbox).is(":checked"))
            a.addClass('line-through');
        else
            a.removeClass('line-through');
    }
    $(function() {
        $('table#ntf').datagrid({
            url: 'mdnotification/notificationList',
            fitColumns: true,
            rownumbers: false,
            singleSelect: true,
            pagination: true,
            pageSize: 20,
            height: 753,
            columns: [[
                    {field: 'ck', title: '', formatter: function(val, row) {
                            return '<input onchange="change(this,' + row.notificationId + ')" type="checkbox" ' + (row.isRead === true ? ' checked' : '') + '/>';
                        }},
                    {field: 'message', title: '<?php echo $this->lang->line('notification'); ?>', width: 10, formatter: function(val, row) {
                            var spanClass = '', iClass = '';
                            if (row.notificationTypeId === 4) {
                                spanClass = 'badge-important';
                                iClass = 'icon-bolt';
                            } else if (row.notificationTypeId === 3) {
                                spanClass = 'badge-warning';
                                iClass = 'icon-warning-sign';
                            } else if (row.notificationTypeId === 2) {
                                spanClass = 'badge-info';
                                iClass = 'icon-info-sign';
                            } else if (row.notificationTypeId === 1) {
                                spanClass = 'badge-success';
                                iClass = 'icon-check';
                            }                         
                            return '<div class="badge ' + spanClass + '"><i class="' + iClass + '"></i></div> <a' +
                                    (row.isRead === true ? ' class="muted"' : '') + ' target="_blank" href="' + (typeof row.directUrl === 'undefined' ? 'javascript:;' : row.directUrl) + '">' + val + '</a>';
                        }},
                    {field: 'notifyDate', title: '<?php echo $this->lang->line('ntf_notify_date'); ?>', align: 'center', formatter: function(val, row) {
                            var val = new Date(val);
                            return '<p' + (row.isRead === true ? ' class="muted"' : '') + '>' + val.getFullYear() + '-' + formatTwoDigit(val.getUTCMonth() + 1) + '-' + formatTwoDigit(val.getDate()) + ' ' + formatTwoDigit(val.getHours()) + ':'
                                    + formatTwoDigit(val.getMinutes()) + ':' + formatTwoDigit(val.getSeconds()) + '</p>';
                        }}
                ]]
        });
    });
    function formatTwoDigit(val) {
        return val > 9 ? val : '0' + val;
    }
</script>
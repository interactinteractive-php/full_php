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
                            if (row.notificationtypeid === '4') {
                                spanClass = 'badge-danger';
                                iClass = 'fa-times';
                            } else if (row.notificationtypeid === '3') {
                                spanClass = 'badge-warning';
                                iClass = 'fa-warning';
                            } else if (row.notificationtypeid === '2') {
                                spanClass = 'badge-info';
                                iClass = 'fa-info';
                            } else if (row.notificationtypeid === '1') {
                                spanClass = 'badge-success';
                                iClass = 'fa-check';
                            }
                            return '<div class="badge label-sm ' + spanClass + ' badge"><i class="fa ' + iClass + '"></i></div> <a' +
                                    (row.isread === '1' ? ' class="line-through"' : '') + ' target="_blank" href="' + row.directurl + '">' + val + '</a>';
                        }},
                    {field: 'notifydate', title: '<?php echo $this->lang->line('ntf_notify_date'); ?>', align: 'center', formatter: function(val) {
                            var val = new Date(val);
                            return val.getFullYear() + '-' + formatTwoDigit(val.getUTCMonth() + 1) + '-' + formatTwoDigit(val.getDate()) + ' ' + formatTwoDigit(val.getHours()) + ':'
                                    + formatTwoDigit(val.getMinutes()) + ':' + formatTwoDigit(val.getSeconds());
                        }},
//                    {field: 'lastTime', formatter: function(val, row) {
//                            var data = $('table#ntf').datagrid('getData');
//                            var now = new Date(data.now);
//                            var notifyDate = new Date(row.notifyDate);
//                            var diff = (now - notifyDate) / 1000;
//                            if (diff < 60)
//                                return Math.floor(diff) + ' <?php echo $this->lang->line('second'); ?>';
//                            else {
//                                diff = diff / 60;
//                                if (diff < 60)
//                                    return Math.floor(diff) + ' <?php echo $this->lang->line('date_minute'); ?>';
//                                else {
//                                    diff = diff / 60;
//                                    if (diff < 24)
//                                        return Math.floor(diff) + ' <?php echo $this->lang->line('date_hour'); ?>';
//                                    else
//                                        return Math.floor(diff / 24) + ' <?php echo $this->lang->line('day'); ?>';
//                                }
//                            }
//                        }}
                ]]
        });
    });
    function formatTwoDigit(val) {
        return val > 9 ? val : '0' + val;
    }
</script>
<script type="text/javascript">
var activeAjaxRequests = 0;
var $thisLayout = $('#layout-id-'+layoutLinkIdjs);
$.ajaxSetup({
    global: false, 
    async: true, 
    type: 'post', 
    data: {nult: 1, filterJson: '<?php echo $this->filterParams; ?>'},
    beforeSend: function(jqXHR) {
        activeAjaxRequests++;
    },
    complete: function(jqXHR) {
        activeAjaxRequests--;
        setTimeout(function () {
            $thisLayout.attr('data-loaded-layoutdata', !activeAjaxRequests);
        }, 2000);
    }
});
</script>
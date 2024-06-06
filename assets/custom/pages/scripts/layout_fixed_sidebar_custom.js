var FixedSidebarCustomScroll = function() {

    // Perfect scrollbar
    var _componentPerfectScrollbar = function() {
        if (typeof PerfectScrollbar == 'undefined') {
            console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
            return;
        }

        // Initialize
        var ps = new PerfectScrollbar('.sidebar-fixed .sidebar-left-menu', {
            wheelSpeed: 2,
            wheelPropagation: true
        });
    };

    return {
        init: function() {
            _componentPerfectScrollbar();
        }
    }
}();


document.addEventListener('DOMContentLoaded', function() {
  //  FixedSidebarCustomScroll.init();
});
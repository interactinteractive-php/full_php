<div id="width-orgchart-container-<?php echo $this->dataViewId; ?>"></div>

<div id="orgchart-container-<?php echo $this->dataViewId; ?>" class="orgchart-background"></div>

<script type="text/javascript">
    $(function(){

      if($('#orgchart-container-<?php echo $this->dataViewId; ?>').length){

        $.when(
                $.getStylesheet(URL_APP +
                        'assets/custom/addon/plugins/jquery-orgchart/1.1.4/css/jquery.orgchart.css'),
                $.getScript(URL_APP + 'assets/custom/addon/plugins/html2canvas.min.js'),
                $.getScript(URL_APP +
                        'assets/custom/addon/plugins/jquery-orgchart/1.1.4/js/jquery.orgchart.multiparent.js')
                ).then(function(){

          orgChartResizer_<?php echo $this->dataViewId; ?>();

//          var dataSource=<?php // echo $this->dataSource ? $this->dataSource : '{};'; ?>

          var dataSource={'parents': [{'name': 'OT001', 'title': 'Оюу толгой'}, {'name': 'OT01000',
                'title': 'Оюу толгой11'}, {'name': 'OT01000', 'title': 'Оюу толгой11'}, {
                'name': 'OT01000', 'title': 'Оюу толгой11'}, {'name': 'OT01000',
                'title': 'Оюу толгой11'}
            ],
            'children': [
              {'parents': [{'name': 'OT002', 'title': 'Ил уурхай'}]},
              {'parents': [{'name': 'OT003', 'title': 'Гүний уурхай'}]},
              {'parents': [{'name': 'OT01000', 'title': 'Оюу толгой11'}, {
                    'name': 'OT01000', 'title': 'Оюу толгой11'}, {'name': 'OT01000',
                                    'title': 'Оюу толгой11'}],
                'children': [
                  {'parents': [{'name': 'OT002', 'title': 'Ил уурхай'}]},
                  {'parents': [{'name': 'OT003', 'title': 'Гүний уурхай'}]},
                  {'parents': [{'name': 'OT003', 'title': 'Гүний уурхай'}]}
                ]}
            ]};

          $('#orgchart-container-<?php echo $this->dataViewId; ?>').orgchart({
            'data': dataSource,
            'depth': 20,
            'nodeContent': 'title',
            'toggleSiblingsResp': true,
            'pan': true,
            'zoom': true,
            'exportButton': true,
            'exportFilename': '<?php echo $this->title; ?>',
            /*'direction': 'l2r'*/
          });

        }, function(){
          console.log('an error occurred somewhere');
        });

        $('#orgchart-container-<?php echo $this->dataViewId; ?>').on('click', '.node', function(){
          var elem=this;
          var _this=$(elem);
          var _parent=_this.closest('.orgchart');
          _parent.find('.selected-row').removeClass('selected-row');
          _this.addClass('selected-row');
        });

        $('#orgchart-container-<?php echo $this->dataViewId; ?>').on('contextmenu', '.node', function(e){
          e.preventDefault();
          var elem=this;
          var _this=$(elem);
          var _parent=_this.closest('.orgchart');
          _parent.find('.selected-row').removeClass('selected-row');
          _this.addClass('selected-row');
        });

        $(window).bind('resize', function(){
          orgChartResizer_<?php echo $this->dataViewId; ?>();
        });
      }

    });

    function orgChartResizer_<?php echo $this->dataViewId; ?>(){
      var orgChartElement=$('#orgchart-container-<?php echo $this->dataViewId; ?>');

      var getHeight=$(window).height() - orgChartElement.offset().top - 40;
      orgChartElement.height(getHeight);

      var getWidth=$('#object-value-list-<?php echo $this->dataViewId; ?>').width();
      orgChartElement.width(getWidth);
    }
</script>

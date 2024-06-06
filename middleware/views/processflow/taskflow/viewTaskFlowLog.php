<div id="wizard-tf-log" class="pb10 wizard-tf-log">
    <?php
    foreach ($this->bpList as $k => $bpRow) {
    ?>
        <h3><?php echo $bpRow['metadataname']; ?></h3>
        <?php
        if ($k == 0) {
        ?>
        <section><?php echo $this->firstBpRender; ?></section>
        <?php
        } else {
        ?>
        <section data-bpid="<?php echo $bpRow['metadataid']; ?>" data-id="<?php echo $bpRow['id']; ?>"></section>
    <?php
        }
    }
    ?>
</div>
<style type="text/css">
.wizard-tf-log>.actions {
    padding: 0;
}
.wizard-tf-log>.steps {
    border-bottom: 1px #e0e0e0 solid;
    margin-bottom: 15px;
}
.wizard-tf-log>.steps>ul>li.done .number {
    background-color: #fff!important;
    color: var(--root-color1);
}
.wizard-tf-log>.steps>ul>li .number {
    font-size: 0;
    color: var(--root-color1);
}
.wizard-tf-log>.steps>ul>li.done .number:after, 
.wizard-tf-log>.steps>ul>li .number:after {
    content: "\ec67";
    font-family: icomoon;
    display: inline-block;
    font-size: 1rem;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    transition: all ease-in-out .15s;
}
.wizard-tf-log>.steps>ul>li.current .number {
    background-color: var(--root-color1);
    border-color: var(--root-color1);
    color: #fff!important;
}
.wizard-tf-log>.steps>ul>li.current>a {
    font-weight: bold;
}
.wizard-tf-log>.steps>ul>li>a {
    font-size: 13px;
}
.wizard-tf-log>.steps>ul>li.current .number:after {
    content: "\ed6f";
    font-family: icomoon;
    display: inline-block;
    font-size: 1rem;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    transition: all ease-in-out .15s;
}
</style>
<script type="text/javascript">
$(function() {
    
    $('#wizard-tf-log').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        transitionEffect: 'fade',
        autoFocus: true, 
        enableAllSteps: true,
        enableFinishButton: false, 
        enableCancelButton: true,
        titleTemplate: '<span class="number">#index#</span> #title#', 
        labels: {
            previous: '<i class="icon-arrow-left13 mr-2"></i> ' + plang.get('prev'),
            next: plang.get('next') + ' <i class="icon-arrow-right14 ml-2"></i>',
            cancel: plang.get('close_btn')
        }, 
        onInit: function(event, currentIndex) { 
            $(this).attr('data-step', currentIndex);
        },
        onStepChanging: function (event, currentIndex, newIndex) { 
            var $section = $(this).find('.content > section:eq('+newIndex+')');
            if ($section.children().length == 0) {
                Core.blockUI({boxed: true, message: 'Loading...'});
            }
            return true; 
        },
        onStepChanged: function(event, currentIndex, priorIndex) { 
            var $section = $(this).find('.content > section:eq('+currentIndex+')');
            if ($section.children().length == 0) {
                $.ajax({
                    type: 'post',
                    url: 'mdprocessflow/taskFlowLogBp/'+$section.attr('data-bpid')+'/'+$section.attr('data-id'),
                    success: function(data) {
                        $section.append(data).promise().done(function() {
                            Core.initBPAjax($section);
                            Core.unblockUI();
                        });
                    }
                });
            }
        }, 
        onCanceled: function() {
            $('#dialog-viewtaskflowlog').dialog('close');
        }
    });
});
</script>
<div class="intranet intranet_<?php echo $this->uniqId ?> pfix w-100">
    <div class="page-content file">
        <?php include_once 'menu.php'; ?>
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md" style="width:18.875rem;">
            <div class="sidebar-mobile-toggler text-center">
                <a href="javascript:void(0);" class="sidebar-mobile-secondary-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                <span class="font-weight-semibold">Secondary sidebar</span>
                <a href="javascript:void(0);" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="card">
                    <div class="card-header bg-white header-elements-inline">
                        <span class="title-style"><a href="javascript:void(0);" class="text-blue active mr-2">Гарчиг</a></span>
                        <!--<i class="icon-search4 font-size-base text-muted ml-auto"></i>-->
                    </div>
                    <div class="sidebar-content_<?php echo $this->uniqId ?>">
                        <ul id="content_<?php echo $this->uniqId ?>" class="media-list media-list-linked height-scroll file-list">
                            <!--content append here-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="page-header page-header-light bg-white mb-3">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:9.1px 20px;">
                    <div class="d-flex">
                        <span class="text-uppercase font-weight-bold">Файл</span>
                    </div>
                    <a href="javascript:;" id="play" data-toggle="modal" data-target="#modal_default" title="Видео үзэх">
                        <button type="button" class="btn btn-light bg-primary border-0 p-1 pl-2 pr-2">
                            <i class="icon-play"></i>
                        </button>
                    </a>
                </div>

                <div id="file_viewer_<?php echo $this->uniqId ?>"></div>              
            </div>
        </div>
    </div>
</div>
<div id="modalsection"></div>

<style type="text/css">
    #file_viewer_<?php echo $this->uniqId ?> .pdfobject { 
        border: 1px solid #666; 
        height: 100%;
    }
    
    .intranet_<?php echo $this->uniqId ?> .spinner {
        width: initial !important;
        height: initial !important;
    }
</style>  
<?php echo isset($this->defaultJs) ? $this->defaultJs : ''; ?>
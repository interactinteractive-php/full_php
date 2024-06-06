<div class="lifecycle-card dv-selection-parent bg-white">
    <div class="row">
        <?php echo $this->columnList; ?>
    </div>
</div>
<style type="text/css">
    .lifecycle-card .card-body > .row > .col-4 {
        padding: 0 5px;
    }
    .lifecycle-card .card-header .card-title {
        line-height: normal;
        font-size: 11px;
    }
    .lifecycle-card .card > .card-header {
        padding: .5375rem 0.8rem;
        border-radius: 5px 5px 0 0;
    }
    .lifecycle-card .card {
        background: none;
        border-radius: 5px;
    }
    .lifecycle-card .card-header {
        height: auto;
        border: 0;
    }
    .lifecycle-card .card-body {
        padding: 0;
    }
    .lifecycle-card .card > .card-body {
        margin-bottom: 15px !important;
    }
    .lifecycle-card .card > .card-body:last-child,
    .lifecycle-card .card > .card-body > .card:last-child  {
        margin-bottom: 0;
    }
    .lifecycle-card .card > .card-body > .row > .card.col-md-12:first-child {
        margin-top: 10px;
    }
    .lifecycle-card .card .sub-card {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        padding: 5px;
    }
    .lifecycle-card .card .card-title {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        max-height: 42px;
        text-align: center;
    }
</style>
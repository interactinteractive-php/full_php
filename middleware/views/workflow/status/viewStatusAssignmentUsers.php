<?php
if ($this->assignmentUsers) {
?>
<div class="portlet light bp-tmp-wfm-assigment-users" style="background-color: #fff !important; border: 1px #5c798e solid;">
    <div class="portlet-title">
        <div class="card-title">
            <i class="fa fa-users"></i>
            <span class="caption-subject bold uppercase">Баталгаажуулах хүмүүс</span>
        </div>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="portlet-body mt-actions pt0">
        <?php
        foreach ($this->assignmentUsers as $user) {
            
            $date = '';
            $icon = '<i class="icon-bell2" style="color: red"></i>';
            $imgSrc = 'assets/core/global/img/user.png';
            
            if (file_exists($user['picture'])) {
                $imgSrc = $user['picture'];
            }
            
            if ($user['createddate'] != '') {
                $date = Date::formatter($user['createddate'], 'Y/m/d H:i');
                $icon = '<span class="badge" style="background-color:'.$user['wfmstatuscolor'].'">'.$user['wfmstatusname'].'</span>';//'<i class="icon-check"></i>';
            }
        ?>
        <div class="mt-action">
            <div class="mt-action-img">
                <img src="<?php echo $imgSrc; ?>">
            </div>
            <div class="mt-action-body">
                <div class="mt-action-row">
                    <div class="mt-action-info">
                        <div class="mt-action-details">
                            <span class="mt-action-author"><?php echo $user['firstname']; ?></span>
                            <p class="mt-action-desc"><?php echo $user['departmentname']; ?></p>
                            <p class="mt-action-pos"><?php echo $user['positionname']; ?></p>
                        </div>
                    </div>
                    <div class="mt-action-datetime">
                        <div class="mt-action-icon">
                            <?php echo $icon; ?>
                        </div>
                        <span class="mt-action-date"><?php echo $date; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<style type="text/css">
.mt-actions .mt-action {
    margin: 0;
    padding: 8px 0;
    border-bottom: 1px solid #e8e8e8;
}
.mt-actions .mt-action:last-child {
    border-bottom: 0;
}
.mt-actions .mt-action .mt-action-img {
    width: 50px;
    padding-top: 2px;
    float: left;
}
.mt-actions .mt-action .mt-action-img>img {
    border-radius: 50%!important;
    margin-bottom: 2px;
    width: 50px;
	height: 50px;
    border: 1px #888 solid;
}
.mt-actions .mt-action .mt-action-body {
    padding-left: 10px;
    position: relative;
    overflow: hidden;
}
.mt-actions .mt-action .mt-action-body .mt-action-row {
    display: table;
    width: 100%;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info {
    display: table-cell;
    vertical-align: top;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime .mt-action-icon {
    padding: 0px 0px 11px 0px;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime .mt-action-icon>i {
    display: inline-block;
    position: relative;
    top: 10px;
    font-size: 25px;
    color: #4bca81;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details {
    display: table-cell;
    vertical-align: top;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details .mt-action-author {
    color: #060606;
    font-weight: 600;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details .mt-action-desc {
    margin-bottom: 0;
    color: #222;
    font-size: 11px;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info .mt-action-details .mt-action-pos {
    margin-bottom: 0;
    color: #999b9b;
    font-size: 11px;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime {
    vertical-align: top;
    display: table-cell;
    text-align: center;
    width: 90px;
    padding-top: 0;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime .mt-action-date {
    white-space: nowrap;
    color: #797979;
    font-size: 11px;
}
.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime .mt-action-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%!important;
    margin-left: 5px;
    margin-right: 5px;
}
@media (max-width:767px) {
    .mt-actions .mt-action .mt-action-body .mt-action-row,.mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-info {
        display: block;
    }
    .mt-actions .mt-action .mt-action-body .mt-action-row .mt-action-datetime {
        display: inline-block;
        margin-left: 40px;
    }
}
</style>
<?php
}
?>
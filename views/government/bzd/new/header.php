<!DOCTYPE html>
<html lang="en">
<body class="bg-light">
    <div class="header">
        <div class="topbar">
            <div class="container-fluid">
                <div class="row align-items-end justify-content-around p-2">
                    <div class="col-auto navbar-brand">
                        <a href="javascript:;" class="d-inline-block">
                            <img src="<?php echo Config::getFromCache('conference_header_logo'); ?>" width="100" alt="logo" onerror="onUserImgError(this);" class="logo">
                        </a>
                    </div>
                    <div class="col-6">
                        <h2><?php echo issetParam($this->data['name']); ?></h2>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <div class="ymd mr-4">
                            <span class="day mr-1"><i class="fa fa-calendar-o"></i></span>
                            <span class="day" id="day"><?php echo issetParam($this->currentDate); ?></span>
                        </div>
                        <div class="clockminute">
                            <span class="time mr-1"><i class="fa fa-clock-o"></i></span>
                            <span class="time mr-0" id="digital-clock" style="margin-right: 0"><?php echo issetParam($this->currentTime); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid p-3 pt-5">
        <div class="bg-white" style="border-radius:10px">


        
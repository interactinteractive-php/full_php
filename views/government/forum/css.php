<style type="text/css">
    
    .forum-<?php echo $this->uniqId ?> .product {
        font-family: Roboto,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji" !important;
    }
    
    .forum-<?php echo $this->uniqId ?> {
        padding: 1.25rem;
    }
    
    .forum-content-<?php echo $this->uniqId ?> {
        padding-top: 10px;
    }
    
    .forum-<?php echo $this->uniqId ?> #searchWrapper {
        position: relative;
    }
    
    .forum-<?php echo $this->uniqId ?> .searchAdvanced {
        margin: 0 auto;
        z-index: 999;
        display: block;
    }    
    
    .forum-<?php echo $this->uniqId ?> .search-big {
        margin-bottom: 20px;
        padding: 30px;
        background: url(../assets/core/icon/custom-icon/search-bg.gif) repeat;
        border: 0px solid transparent;
        border-radius: 0px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border-top: solid 3px #e0e0e0;
    }
    
    .forum-<?php echo $this->uniqId ?> .search-big .input-group .form-control {
        display: block;
        height: 40px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        background-color: #fff;
        background-image: none;
        border: 1px solid #919191;
        border-radius: 0px;
        -webkit-box-shadow: inset 0 0px 0px rgba(0,0,0,0.075);
        box-shadow: inset 0 0px 0px rgba(0,0,0,0.075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }

    .forum-<?php echo $this->uniqId ?> .space-2 {
        clear: both;
        height: 20px;
    }
    
    @media (min-width: 1320px) {
        .forum-<?php echo $this->uniqId ?> .search-big .col-lg-offset-3 {
            margin-left: 25%;
        }
    }

    @media (min-width: 1320px) {
        .forum-<?php echo $this->uniqId ?> .search-big .col-lg-6 {
            width: 50%;
        }
    }

    .forum-<?php echo $this->uniqId ?> .search-big .input-group .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: normal;
        text-align: center;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid #919191;
        white-space: nowrap;
        padding: 2px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 0px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        height: 40px !important;
    }
    
    .forum-<?php echo $this->uniqId ?> .search-big .btn-default {
        background: #919191;
        color: #FFF;
    }
    
    .forum-<?php echo $this->uniqId ?> .search-options .control-label {
        color: #333;
        font-weight: normal;
        font-size: 16px;
        line-height: 40px;
    }

    .forum-<?php echo $this->uniqId ?> .search-options .form-control {
        display: block;
        width: 100%;
        height: 40px !important;
        min-height: 40px !important;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        background-color: #fff;
        background-image: none;
        border: 1px solid #919191;
        border-bottom: 1px solid #919191;
        border-radius: 0px;
        -webkit-box-shadow: inset 0 0px 0px rgba(0,0,0,0.075);
        box-shadow: inset 0 0px 0px rgba(0,0,0,0.075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }
    
    .forum-<?php echo $this->uniqId ?> .letters{
        margin: 0px;
        padding: 0px;
    }

    .forum-<?php echo $this->uniqId ?> .letters li{
        list-style: none;
        display: inline;
    }

    .forum-<?php echo $this->uniqId ?> .letters li a{
        font-size: 20px;
        font-weight: bold;
        margin-left: 5px;
        margin-right: 5px;
        -webkit-transition: all 0.2s;
        -moz-transition: all 0.2s;
        -ms-transition: all 0.2s;
        -o-transition: all 0.2s;
        transition: all 0.2s;
        color: #012345;
    }

    .forum-<?php echo $this->uniqId ?>  .letters li a:hover, 
    .forum-<?php echo $this->uniqId ?> .letters li a:focus {
        color:  #176fac;
        text-decoration: none;
    }

    .forum-<?php echo $this->uniqId ?> .letters .active a{
        font-weight: bold;
        margin-left: 5px;
        margin-right: 5px;
        color:  #176fac;
    }

    .forum-<?php echo $this->uniqId ?> .product  {
        position:relative;
        border: 1px solid #f2f2f2;
        /*border-left: solid 3px #FFF;*/
        margin-bottom:1rem;
        transition:box-shadow .35s ease;
        background-color:#fff;
    }
    
    .product:not(.not-hover)  {
        min-height: 285px;
        max-height: 285px;    
    }
    
    .product.not-hover  {
        border-left: solid 1px #e3e3e3;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    
    .forum-<?php echo $this->uniqId ?> .product:not(.not-hover):hover  {
        opacity:1
        cursor: pointer;
        border-left: solid 3px #39f;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .16);
        transform: translate(0, -10px);
        transition-delay: 0s !important;
        transition: all 250ms cubic-bezier(.02, .01, .47, 1);
    }
    
    .forum-<?php echo $this->uniqId ?> .product-body > .product-price {
        display: none;
    }
    
    .forum-<?php echo $this->uniqId ?> .product-media {
        position:relative;display:block;margin-bottom:0;overflow:hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 150px;
    }
    
    .forum-<?php echo $this->uniqId ?> .product-media>a {
        display:block
    }
    
    .forum-<?php echo $this->uniqId ?> .product:hover .product-countdown,
    .forum-<?php echo $this->uniqId ?> .product:hover .product-countdown-container {
        opacity:0;visibility:hidden
    }
    
    .forum-<?php echo $this->uniqId ?> .product-body {
        position:relative;padding:1.6rem 2rem;transition:all .35s ease;background-color:#fff
    }
    
    .forum-<?php echo $this->uniqId ?> .product-title {
        font-weight:400;font-size:1.3rem;line-height:1.25;letter-spacing:-.01em;color:#333333;margin-bottom:.2rem;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        margin: 2.6rem 0;
    }
    
    .forum-<?php echo $this->uniqId ?> .product-title a {
        color:inherit
    }
    
    .forum-<?php echo $this->uniqId ?> .product-title a:hover,
    .forum-<?php echo $this->uniqId ?> .product-title a:focus {
        color:#c96
    }
    
    .forum-<?php echo $this->uniqId ?> .product-cat {
        color:#777;font-weight:400;font-size:0.8rem;line-height:1.2;letter-spacing:-.01em;margin-bottom:.3rem;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
    }
    
    .forum-<?php echo $this->uniqId ?> .product-cat a {
        color:inherit
    }
    
    .forum-<?php echo $this->uniqId ?> .product-cat a:hover,.product-cat a:focus {
        color:#666
    }
    
    .forum-<?php echo $this->uniqId ?> .product-label {
        position:absolute;z-index:0;top:2rem;left:2rem;font-weight:400;font-size:1.3rem;line-height:1.6rem;letter-spacing:-.01em;padding:.5rem .9rem;min-width:45px;text-align:center;color:#333333;background-color:#fff
    }
    
    .forum-<?php echo $this->uniqId ?> .product-label.label-top.second {
        margin-left: 80px;
        display: none;
    }
    
    .forum-<?php echo $this->uniqId ?> .product-label.label-secondary {
        color:#fff;background-color:#a6c76c
    }
    
    .forum-<?php echo $this->uniqId ?> .product-label-text {
        color:#c96;font-weight:400;font-size:1.3rem;line-height:1;letter-spacing:-.01em;margin-top:-.1rem
    }
    
    .forum-<?php echo $this->uniqId ?> .product-action {
        position:absolute;left:0;right:0;bottom:0;display:flex;align-items:center;background-color:rgba(255,255,255,0.95);z-index:10;transition:all .35s ease;opacity:0;visibility:hidden;transform:translateY(100%)
    }
    
    .forum-<?php echo $this->uniqId ?> .product:hover .product-action,
    .forum-<?php echo $this->uniqId ?> .product:focus .product-action {
        visibility:visible;opacity:1;transform:translateY(0)
    }
    
    .forum-<?php echo $this->uniqId ?> .btn-product {
        padding-top: 0.7rem;
        padding-bottom: 0.7rem;
        color: #39f;
        background-color: transparent;
        text-transform: uppercase;
        border-radius: .2rem;
        border: 0.1rem solid #39f;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        transition: all .35s ease;
        flex-grow: 1;
        flex-basis: 0;
    }
    
    .forum-<?php echo $this->uniqId ?> .product-action {
        left: 2rem;
        right: 2rem;
        transform: translateY(0);
        margin-bottom: 1rem;
    }
    
</style>
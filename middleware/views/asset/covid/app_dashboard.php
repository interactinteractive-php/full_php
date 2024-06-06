<!-- <article>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="title-section">
                    <h1>Basic Sticky Sidebar with Bootstrap 4</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-7">

                <div class="content-section">
                    <h2>Content Section</h2>
                </div>
            </div>
            <div class="col-5">

              <div class="sidebar-item">
                <div class="make-me-sticky">
                  <h3>Item 1</h3>
                </div>
              </div>
          

            </div>
        </div>
    </div>
</article>
<style>
.content-section {
  min-height: 2000px;
}
.sidebar-section {
  position: absolute;
  height: 100%;
  width: 100%;
}
.sidebar-item {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	/* Position the items */
	&:nth-child(2) { top: 25%; }
	&:nth-child(3) { top: 50%; }
	&:nth-child(4) { top: 75%; }
}
.make-me-sticky {
  position: -webkit-sticky;
	position: sticky;
	top: 0;
  padding: 0 15px;
}
body {
  background: #fff;
}

article {
  background: #f1f1f1;
  border-radius: 12px;
  padding: 25px 0 600px;
}
.title-section, .content-section, .sidebar-section {
  background: #fff;
  border-radius: 12px;
  border: solid 10px #f1f1f1; 
}

.title-section {
  text-align: center;
  padding: 50px 15px;
  margin-bottom: 30px;
}

.content-section h2 {
  text-align: center;
  margin: 0;
  padding-top: 200px;
}

.sidebar-item {
  text-align: center;
}
  h3 {
    background: gold;
    max-width: 100%;
    margin: 0 auto;
    padding: 50px 0 100px; 
    border-bottom: solid 1px #fff;
  }
</style> -->
<div class="app_dashboard">
    <div class="row">
        <div class="col-10">
            <div class="row">
                <div class="col-2">
                    <div class="box-mod announcement">
                        <h5>Шуурхай мэдээлэл</h5>
                        <div class="ann-box">
                            <h6 class="text-two-line font-weight-bold mb-1">Коронавируст халдвар (COVID-19)-аас сэргийлэх зөвлөмж</h6>
                            <p class="text-two-line line-height-normal">Эрүүл мэндийн яамнаас бэлтгэсэн шинэ коронавируст халдвараас сэргийлэх зөвлөмжийг хүргэж байна.</p>
                        </div>
                        <div class="ann-box">
                            <h6 class="text-two-line font-weight-bold mb-1">Шуурхай албаны мэдээ</h6>
                            <p class="text-two-line line-height-normal">Ихэнх нутгаар багавтар үүлтэй. Хур тунадас орохгүй. Салхи баруун аймгуудын нутгаар баруунаас, бусад нутгаар баруун хойноос 5-10 м/с, говь болон зүүн аймгуудын нутгийн зүүн хэсгээр 12-14 м/с хүрч ширүүснэ.</p>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="box-mod advice">
                        <h5>Удирдлагын зөвлөгөө</h5>
                        <div class="mod">
                            <div class="row">
                                <div class="ml-2 mr-2">
                                    <img src="https://www.tseneg.mn/media/4003_0eb52089716c60997a3b3a5c951cc1d3.jpg" style="height: 140px;">
                                </div>
                                <div class="col-7">
                                    <h6 class="font-weight-bold">In ultricies nunc mi</h6>
                                    <p class="font-size-13 text-justify">At 196m tall, Maungaвwhau or Mount Eden is the highest volcano in Auckland. From the summit, visitors can enjoy spectacular 360-degree views of the city and its harbours. A large, well-preserved crater, some 50 metres deep.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="box-mod newslast">
                        <h5>Явцын мэдээлэл /сүүлийн 30 хоног/</h5>
                        <div class="row align-items-center">
                            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="rounded-progress blue mb-1">
                                    <span class="progress-left">
                                        <span class="progress-bar"></span>
                                    </span>
                                    <span class="progress-right">
                                        <span class="progress-bar"></span>
                                    </span>
                                    <div class="progress-value">10%</div>
                                </div>
                                <span class="font-weight-bold newstitle">Баримт бичгийн шийдвэрлэлт</span>
                            </div>
                            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="rounded-progress yellow mb-1">
                                    <span class="progress-left">
                                        <span class="progress-bar"></span>
                                    </span>
                                    <span class="progress-right">
                                        <span class="progress-bar"></span>
                                    </span>
                                    <div class="progress-value">65%</div>
                                </div>
                                <span class="font-weight-bold newstitle">Ажил шийдвэрлэлт</span>
                            </div>
                            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="rounded-progress pink mb-1">
                                    <span class="progress-left">
                                        <span class="progress-bar"></span>
                                    </span>
                                    <span class="progress-right">
                                        <span class="progress-bar"></span>
                                    </span>
                                    <div class="progress-value">90%</div>
                                </div>
                                <span class="font-weight-bold newstitle">Цаг ашиглалт</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="box-mod">
                        <div class="row">
                            <div class="col-4">
                                <h5>Ирсэн бичиг</h5>
                                <div class="row">
                                    <div class="col-4">
                                        <span class="badge badge-mark border-blue mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">102</h1>
                                        <span>Шинэ бичиг</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge badge-mark border-orange mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">14</h1>
                                        <span>Хугацаа дөхсөн</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge badge-mark border-pink mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">9</h1>
                                        <span>Хугацаа хэтэрсэн</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <h5>Төлөвлөж буй бичиг</h5>          
                                <div class="row">
                                    <div class="col-4">
                                        <span class="badge badge-mark border-green mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">145</h1>
                                        <span>Хянах бичиг</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge badge-mark border-orange mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">98</h1>
                                        <span>Хүлээгдэж буй</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge badge-mark border-teal mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">67</h1>
                                        <span>Шинэ бичиг</span>
                                    </div>
                                </div>          
                            </div>
                            <div class="col-4">
                                <h5>Өргөдөл гомдол</h5>          
                                <div class="row">
                                    <div class="col-4">
                                        <span class="badge badge-mark border-violet mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">56</h1>
                                        <span>Шинэ бичиг</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge badge-mark border-grey mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">9</h1>
                                        <span>Хугацаа дөхсөн</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge badge-mark border-blue mr-1"></span>
                                        <h1 class="font-weight-bold mb-0">78</h1>
                                        <span>Хугацаа хэтэрсэн</span>
                                    </div>
                                </div>         
                            </div>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-7 pr-0">
                    <div class="box-mod work" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                        <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0 mb-3">
                            <li class="nav-item mr-3"><a href="#app-dashboard-work-tab" class="nav-link px-0 active" data-toggle="tab"><h5 class="text-white mb-1">Миний ажил</h5></a></li>
                            <li class="nav-item"><a href="#app-dashboard-event-tab" class="nav-link px-0" data-toggle="tab"><h5 class="text-white mb-1">Үйл явц</h5></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="app-dashboard-work-tab">
                                <ul class="nav nav-tabs border-bottom-0 mb-3">
									<li class="nav-item"><a href="#app-dashboard-work-cat-tab-1" class="nav-link p-2 border-radius-100 active" data-toggle="tab">Өнөөдөр</a></li>
									<li class="nav-item"><a href="#app-dashboard-work-cat-tab-2" class="nav-link p-2 border-radius-100" data-toggle="tab">Хугацаа дөхсөн</a></li>
									<li class="nav-item"><a href="#app-dashboard-work-cat-tab-3" class="nav-link p-2 border-radius-100" data-toggle="tab">Хугацаа хэтэрсэн</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="app-dashboard-work-cat-tab-1">
                                        <div class="work_col d-flex flex-row align-items-center">
                                            <div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">
                                                
                                                <span class="font-size-14">2020-04-05</span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-normal font-size-14">Дижитал оффисийн систем дизайн сайжруулалт</h5>
                                            </div>
                                        </div>
                                        <div class="work_col d-flex flex-row align-items-center">
                                            <div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">
                                                <span class="font-size-14">2020-04-05</span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-normal font-size-14">Тоон гарын үсгийн төхөөрөмж олгох</h5>
                                            </div>
                                        </div>
                                        <div class="work_col d-flex flex-row align-items-center">
                                            <div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">
                                                <span class="font-size-14">2020-04-05</span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-normal font-size-14">Цаг бүртгэлийн төхөөрөмж шинэчлэх</h5>
                                            </div>
                                        </div>
                                        <div class="work_col d-flex flex-row align-items-center">
                                            <div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">
                                                
                                                <span class="font-size-14">2020-04-05</span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-normal font-size-14">Дижитал оффисийн систем дизайн сайжруулалт</h5>
                                            </div>
                                        </div>
                                        <div class="work_col d-flex flex-row align-items-center">
                                            <div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">
                                                <span class="font-size-14">2020-04-05</span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-normal font-size-14">Тоон гарын үсгийн төхөөрөмж олгох</h5>
                                            </div>
                                        </div>
                                        <div class="work_col d-flex flex-row align-items-center mb25 border-0">
                                            <div class="d-flex flex-column justify-content-center mr-3" style="width:120px;">
                                                
                                                <span class="font-size-14">2020-04-05</span>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-normal font-size-14">Дижитал оффисийн систем дизайн сайжруулалт</h5>
                                            </div>
                                        </div>
                                        <div class="boxfooter d-flex justify-content-between align-items-center">
                                            <div class="time-footer-bg mr-auto d-flex flex-row">
                                                <div class="timesec mr-5">
                                                    <span>Ирсэн цаг</span>
                                                    <div class="timesec1">08:00</div>
                                                </div>
                                                <div class="timesec mr-5">
                                                    <span>Явсан цаг</span>
                                                    <div class="timesec1">16:55</div>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row align-items-center">
                                                <div class="conflict">
                                                    <span>Зөрчил</span>
                                                    <div class="timesec1">5 мин</div>
                                                </div>
                                                <div class="timesec icon-menu-bg">
                                                    <i class="icon-menu text-white"></i>
                                                </div>
                                                <div class="timesec icon-cross-bg">
                                                    <i class="icon-cross2 text-white"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="app-dashboard-work-cat-tab-2">
                                        Test Tab Sub Cat 2
                                    </div>
                                    <div class="tab-pane fade" id="app-dashboard-work-cat-tab-3">
                                        Test Tab Sub Cat 3
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="app-dashboard-event-tab">
                                Test Tab 2
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5 pl-0">
                    <div class="box-mod" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        <div>
                            <div class="calendar">
                            <p class="font-italic text-muted mb33">No events for this day.</p>
                            <ol class="day-names list-unstyled">
                                <li class="font-weight-bold text-uppercase">Sun</li>
                                <li class="font-weight-bold text-uppercase">Mon</li>
                                <li class="font-weight-bold text-uppercase">Tue</li>
                                <li class="font-weight-bold text-uppercase">Wed</li>
                                <li class="font-weight-bold text-uppercase">Thu</li>
                                <li class="font-weight-bold text-uppercase">Fri</li>
                                <li class="font-weight-bold text-uppercase">Sat</li>
                            </ol>

                            <ol class="days list-unstyled">
                                <li>
                                <div class="date">1</div>
                                <!-- <div class="event bg-success">Event with Long Name</div> -->
                                </li>
                                <li>
                                <div class="date">2</div>
                                </li>
                                <li>
                                <div class="date">3</div>
                                </li>
                                <li>
                                <div class="date">4</div>
                                </li>
                                <li>
                                <div class="date">5</div>
                                </li>
                                <li>
                                <div class="date">6</div>
                                </li>
                                <li>
                                <div class="date">7</div>
                                </li>
                                <li>
                                <div class="date">8</div>
                                </li>
                                <li>
                                <div class="date">9</div>
                                </li>
                                <li>
                                <div class="date">10</div>
                                </li>
                                <li>
                                <div class="date">11</div>
                                </li>
                                <li>
                                <div class="date">12</div>
                                </li>
                                <li>
                                <div class="date">13</div>
                                <!-- <div class="event all-day begin span-2 bg-warning">Event Name</div> -->
                                </li>
                                <li>
                                <div class="date">14</div>
                                </li>
                                <li>
                                <div class="date">15</div>
                                <!-- <div class="event all-day end bg-success">Event Name</div> -->
                                </li>
                                <li>
                                <div class="date">16</div>
                                </li>
                                <li>
                                <div class="date">17</div>
                                </li>
                                <li>
                                <div class="date">18</div>
                                </li>
                                <li>
                                <div class="date">19</div>
                                </li>
                                <li>
                                <div class="date">20</div>
                                </li>
                                <li>
                                <div class="date">21</div>
                                <!-- <div class="event bg-primary">Event Name</div> -->
                                <!-- <div class="event bg-success">Event Name</div> -->
                                </li>
                                <li>
                                <div class="date">22</div>
                                <!-- <div class="event bg-info">Event with Longer Name</div> -->
                                </li>
                                <li>
                                <div class="date">23</div>
                                </li>
                                <li>
                                <div class="date">24</div>
                                </li>
                                <li>
                                <div class="date">25</div>
                                </li>
                                <li>
                                <div class="date">26</div>
                                </li>
                                <li>
                                <div class="date">27</div>
                                </li>
                                <li>
                                <div class="date">28</div>
                                </li>
                                <li>
                                <div class="date">29</div>
                                </li>
                                <li>
                                <div class="date">30</div>
                                </li>
                                <li>
                                <div class="date">31</div>
                                </li>
                                <li class="outside">
                                <div class="date">1</div>
                                </li>
                                <li class="outside">
                                <div class="date">2</div>
                                </li>
                                <li class="outside">
                                <div class="date">3</div>
                                </li>
                                <li class="outside">
                                <div class="date">4</div>
                                </li>
                            </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="assets/custom/img/3bd0baf60a15b0117aaf45eda8c8e890.jpg" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 class="mb-0">Тусгаарлах хоногийг 21 болгож сунгалаа.</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="assets/custom/img/3bd0baf60a15b0117aaf45eda8c8e890.jpg" alt="Second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 class="mb-0">Тусгаарлах хоногийг 21 болгож сунгалаа. Slide 2</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="assets/custom/img/3bd0baf60a15b0117aaf45eda8c8e890.jpg" alt="Third slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 class="mb-0">Тусгаарлах хоногийг 21 болгож сунгалаа. Slide 3</h5>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="box-mod">
                        <h5>Хэлэлцүүлэг</h5>
                        <div class="box-forum">
							<div class="media flex-column flex-sm-row align-items-center">
								<div class="mr-sm-3 mb-2 mb-sm-0">
									<a href="#">
										<img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face1.jpg" class="rounded-circle" width="44" height="44" alt="">
									</a>
								</div>
								<div class="media-body">
									<h6 class="media-title font-weight-semibold">
										<a href="#">Interaction UX/UI Industrial Designer</a>
									</h6>
									<ul class="list-inline list-inline-dotted text-muted mb-2">
										<li class="list-inline-item"><a href="#" class="text-muted">Dell</a></li>
										<li class="list-inline-item">Amsterdam, Netherlands</li>
									</ul>
									<p class="text-justify mb-0">Extended kindness trifling remember he confined outlived if. Assistance sentiments yet unpleasing say. Open they an busy they my such high. An active dinner wishes at unable hardly no talked on.</p>
								</div>
								<div class="ml-sm-3 mt-2 mt-sm-0">
                                    <div class="mb-2">
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#" class="btn btn-icon btn-sm border-slate-300 text-slate rounded-round border-dashed" style="padding: 0 4px;">
                                            <i class="icon-menu font-size-12 pt2"></i>
                                        </a>
                                    </div>
                                    <div class="text-right">
                                        <i class="icon-bubble2"></i>
                                        25 comments
                                    </div>
								</div>
							</div>
                        </div>
                        <div class="box-forum">
							<div class="media flex-column flex-sm-row align-items-center">
								<div class="mr-sm-3 mb-2 mb-sm-0">
									<a href="#">
										<img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face1.jpg" class="rounded-circle" width="44" height="44" alt="">
									</a>
								</div>
								<div class="media-body">
									<h6 class="media-title font-weight-semibold">
										<a href="#">Interaction UX/UI Industrial Designer</a>
									</h6>
									<ul class="list-inline list-inline-dotted text-muted mb-2">
										<li class="list-inline-item"><a href="#" class="text-muted">Dell</a></li>
										<li class="list-inline-item">Amsterdam, Netherlands</li>
									</ul>
									<p class="text-justify mb-0">Extended kindness trifling remember he confined outlived if. Assistance sentiments yet unpleasing say. Open they an busy they my such high. An active dinner wishes at unable hardly no talked on.</p>
								</div>
								<div class="ml-sm-3 mt-2 mt-sm-0">
                                    <div class="mb-2">
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#" class="btn btn-icon btn-sm border-slate-300 text-slate rounded-round border-dashed" style="padding: 0 4px;">
                                            <i class="icon-menu font-size-12 pt2"></i>
                                        </a>
                                    </div>
                                    <div class="text-right">
                                        <i class="icon-bubble2"></i>
                                        25 comments
                                    </div>
								</div>
							</div>
                        </div>
                        <div class="box-forum">
							<div class="media flex-column flex-sm-row align-items-center">
								<div class="mr-sm-3 mb-2 mb-sm-0">
									<a href="#">
										<img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face1.jpg" class="rounded-circle" width="44" height="44" alt="">
									</a>
								</div>
								<div class="media-body">
									<h6 class="media-title font-weight-semibold">
										<a href="#">Interaction UX/UI Industrial Designer</a>
									</h6>
									<ul class="list-inline list-inline-dotted text-muted mb-2">
										<li class="list-inline-item"><a href="#" class="text-muted">Dell</a></li>
										<li class="list-inline-item">Amsterdam, Netherlands</li>
									</ul>
									<p class="text-justify mb-0">Extended kindness trifling remember he confined outlived if. Assistance sentiments yet unpleasing say. Open they an busy they my such high. An active dinner wishes at unable hardly no talked on.</p>
								</div>
								<div class="ml-sm-3 mt-2 mt-sm-0">
                                    <div class="mb-2">
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#"><img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face2.jpg" class="rounded-circle" width="22" height="22" alt=""></a>
                                        <a href="#" class="btn btn-icon btn-sm border-slate-300 text-slate rounded-round border-dashed" style="padding: 0 4px;">
                                            <i class="icon-menu font-size-12 pt2"></i>
                                        </a>
                                    </div>
                                    <div class="text-right">
                                        <i class="icon-bubble2"></i>
                                        25 comments
                                    </div>
								</div>
							</div>
						</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="box-mod">
                        <h5>Санал асуулга</h5>
                        <h6 class="line-height-normal">Таны бодлоор аль түвшний албан тушаалтан авлигад хамгийн их өртөх магадлалтай вэ?</h6>
                        <form action="">
                            <div class="form-group pt-2">
                                <div class="mb-1">
                                    <input type="radio" id="male" name="gender" value="male">
                                    <label for="male">Удирдах албан тушаалтан</label>
                                </div>
                                <div class="mb-1">
                                    <input type="radio" id="female" name="gender" value="female">
                                    <label for="female">Гүйцэтгэх албан тушаалтан</label>
                                </div>
                                <div class="mb-1">
                                    <input type="radio" id="other" name="gender" value="other">
                                    <label for="other">Туслах албан тушаалтан буюу ажилчид</label>
                                </div>
                                <div class="mb-1">
                                    <input type="radio" id="other" name="gender" value="other">
                                    <label for="other">Бүгд</label>
                                </div>
                                <div class="mt-3 d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn bg-orange rounded-round px-5 mr-2">Санал өгөх</button>
                                    <button type="button" class="btn btn-light rounded-round px-5">Үр дүн харах</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="box-mod">
                        <h5>Зургийн сан</h5>
                        <div class="gallery">
                            <a href="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" class="with-caption image-link">
                                <img src="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" width="172" height="105" />  
                            </a>
                            <a href="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" class="with-caption image-link">
                                <img src="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" width="172" height="105" />  
                            </a>
                            <a href="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" class="with-caption image-link">
                                <img src="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" width="172" height="105" />  
                            </a>
                            <a href="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" class="with-caption image-link">
                                <img src="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" width="172" height="105" />  
                            </a>
                            <a href="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" class="with-caption image-link">
                                <img src="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" width="172" height="105" />  
                            </a>
                            <a href="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" class="with-caption image-link">
                                <img src="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" width="172" height="105" />  
                            </a>
                            <a href="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" class="with-caption image-link">
                                <img src="http://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Christmas_flood_1717.jpg/1280px-Christmas_flood_1717.jpg" width="172" height="105" />  
                            </a>
                        </div>
                        <script>
                            $('.gallery').each(function() {
                                $(this).magnificPopup({
                                    delegate: 'a',
                                    type: 'image',
                                    gallery: {
                                     enabled:true
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="box-mod weather d-flex justify-content-center">
                <div class="weather_mod">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-muted">2020-04-03<br>09:51 AM</h6>
                            <span class="gradus">11°C</span>
                        </div>
                        <div class="col">
                            <img src="assets/custom/img/app_dashboard/weather.png" style="width:120px;">
                        </div>
                    </div>
                    <div class="row align-items-center threeday mt-2">
                        <div class="col-4 threebox">
                            <h6 class="text-muted mb-0">Лхагва</h6>
                            <span class="gradus">11°C</span>
                        </div>
                        <div class="col-4 threebox">
                            <h6 class="text-muted mb-0">Пүрэв</h6>
                            <span class="gradus">16°C</span>
                        </div>
                        <div class="col-4 threebox">
                            <h6 class="text-muted mb-0">Баасан</h6>
                            <span class="gradus">21°C</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-mod">
                <div class="news_mod">
                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Мэдээлэл</h5>
                        <span class="text-right">
                            <a href="javascript:void(0);" class="text-uppercase">Бүгд</a>
                        </span>
                    </div>
                    <ul class="media-list">
                        <li class="media d-flex align-items-center">
                            <div class="mr-2 position-relative">
                                <img src="http://www.mnb.mn/uploads/202003/news/thumb/39e3bf8d257cf6f295f0b75ef7091493_x3.jpg" alt="">
                            </div>
                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <a href="javascript:void(0);" class="title text-two-line">Коронавируст халдвар (COVID-19)-аас сэргийлэх зөвлөмж</a>
                                </div>
                                <div class="d-flex mt-1">
                                    <div class="mr-auto font-size-11">
                                        <div class="mb2 d-flex align-items-center">
                                            <span class="text-pink mr-1"><i class="icon-user"></i></span>
                                            <span class="mr-2 mt1">Б.Нарантуяа</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="pt1">2020-04-01</span>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);">
                                        <i class="icon-attachment"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="media d-flex align-items-center">
                            <div class="mr-2 position-relative">
                                <img src="https://vip76.mn/media/news/original/2019/09/23/3bd0baf60a15b0117aaf45eda8c8e890.jpg" alt="">
                            </div>
                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <a href="javascript:void(0);" class="title text-two-line">Төрөөс баримтлах бодлогын хэрэгжилтийн нэгдсэн тайланг төрийн захиргааны төв</a>
                                </div>
                                <div class="d-flex mt-1">
                                    <div class="mr-auto font-size-11">
                                        <div class="mb2 d-flex align-items-center">
                                            <span class="text-pink mr-1"><i class="icon-user"></i></span>
                                            <span class="mr-2 mt1">Г.Дашжид</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="pt1">2020-04-01</span>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);">
                                        <i class="icon-attachment"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="media d-flex align-items-center">
                            <div class="mr-2 position-relative">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSMCi8yOsiYru9et61NmZv9gGWeK3QmFGzU5N_FTivMENOEHuO0&usqp=CAU" alt="">
                            </div>
                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <a href="javascript:void(0);" class="title text-two-line">Улсын Их Хурал болон Засгийн газрын тогтоолоор баталсан</a>
                                </div>
                                <div class="d-flex mt-2">
                                    <div class="mr-auto font-size-11">
                                        <div class="mb2 d-flex align-items-center">
                                            <span class="text-pink mr-1"><i class="icon-user"></i></span>
                                            <span class="mr-2 mt1">Б.Нарантуяа</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="pt1">2020-04-01</span>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);">
                                        <i class="icon-attachment"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-mod">
                <div class="file_mod">
                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Файлын сан</h5>
                        <span class="text-right">
                            <a href="javascript:void(0);" class="text-uppercase">Бүгд</a>
                        </span>
                    </div>
                    <ul class="media-list">
                        <li class="media d-flex align-items-center">
                            <div class="mr-2">
                                <i class="icon-file-pdf text-danger-400 icon-2x"></i>
                            </div>
                            <div class="media-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="mr-2"><a href="javascript:void(0);" class="title">Шуурхай албаны мэдээ 2020.01.31</a></span>
                                    <span class="font-weight-bold font-size-13 text-right">10 MB</span>
                                </div>
                            </div>
                        </li>
                        <li class="media d-flex align-items-center">
                            <div class="mr-2">
                                <i class="icon-file-excel text-green icon-2x"></i>
                            </div>
                            <div class="media-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="mr-2"><a href="javascript:void(0);" class="title">Шуурхай албаны мэдээ 2020.02.31</a></span>
                                    <span class="font-weight-bold font-size-13 text-right">165 MB</span>
                                </div>
                            </div>
                        </li>
                        <li class="media d-flex align-items-center">
                            <div class="mr-2">
                                <i class="icon-file-word text-blue icon-2x"></i>
                            </div>
                            <div class="media-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="mr-2"><a href="javascript:void(0);" class="title">Шуурхай албаны мэдээ 2020.05.31</a></span>
                                    <span class="font-weight-bold font-size-13 text-right">106 KB</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-mod">
                <div class="birthday_mod">
                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Төрсөн өдөр</h5>
                        <span class="text-right">
                            <a href="javascript:void(0);" class="text-uppercase">Бүгд</a>
                        </span>
                    </div>
                    <ul class="media-list">
                        <a href="javascript:void(0);" class="d-block py-1">
                            <li class="media d-flex align-items-center">
                                <div class="mr-2 position-relative">
                                    <img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face1.jpg" class="rounded-circle" alt="">
                                </div>
                                <div class="media-body d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <span class="title text-two-line ml2 text-black">М.Болор-Эрдэнэ</span>
                                        <span class="text-pink"><i class="icon-user"></i></span>
                                        <span>Хэлтсийн дарга</span>
                                    </div>
                                    <div class="icon-gift-box">
                                        <i class="icon-gift"></i>
                                    </div>
                                </div>
                            </li>
                        </a>
                        <a href="javascript:void(0);" class="d-block py-1">
                            <li class="media d-flex align-items-center">
                                <div class="mr-2 position-relative">
                                    <img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face11.jpg" class="rounded-circle" alt="">
                                </div>
                                <div class="media-body d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <span class="title text-two-line ml2 text-black">А.Эрхэмбаяр</span>
                                        <span class="text-pink"><i class="icon-user"></i></span>
                                        <span>Сурвалжлагч</span>
                                    </div>
                                    <div class="icon-gift-box">
                                        <i class="icon-gift"></i>
                                    </div>
                                </div>
                            </li>
                        </a>
                        <a href="javascript:void(0);" class="d-block py-1">
                            <li class="media d-flex align-items-center">
                                <div class="mr-2 position-relative">
                                    <img src="http://demo.interface.club/limitless/demo/Template/global_assets/images/demo/users/face10.jpg" class="rounded-circle" alt="">
                                </div>
                                <div class="media-body d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <span class="title text-two-line ml2 text-black">М.Болор-Эрдэнэ</span>
                                        <span class="text-pink"><i class="icon-user"></i></span>
                                        <span>Хэлтсийн дарга</span>
                                    </div>
                                    <div class="icon-gift-box">
                                        <i class="icon-gift"></i>
                                    </div>
                                </div>
                            </li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .header-tab {
        display: none;
    }
    /*************************************************** App Dashboard START ***************************************************/
    .app_dashboard {
        margin-top: 20px;
    }
    .app_dashboard .box-mod {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        /* border-radius: 7px; */
        border: 0;
        margin-bottom: 1.5rem;
        width: 100%;
        padding: 20px;
    }
    .app_dashboard .shadow {
        box-shadow: 0 0 30px 0 rgba(115, 77, 191, 0.2) !important;
    }
    .app_dashboard h5 {
        font-weight: 600;
    }
    .app_dashboard .box-mod.announcement {
        border-radius: 0;
        color: #FFF;
    }
    .app_dashboard .box-mod.announcement .ann-box {
        margin-bottom: 10px;
    }
    .app_dashboard .box-mod.announcement .ann-box:last-child {
        margin-bottom: 0;
    }
    .app_dashboard .box-mod.announcement,
    .app_dashboard .box-mod.newslast,
    .app_dashboard .box-mod.newslast > .row,
    .app_dashboard .box-mod.advice,
    .app_dashboard .box-mod.weather {
        height: 220px;
    }
    .app_dashboard .box-mod.newslast .newstitle {
        line-height: normal;
        text-align: center !important;
        width: 80%;
        justify-content: center;
        height: 30px;
    }
    .app_dashboard .announcement {
        background: #ff7e79;
    }
    .app_dashboard .announcement h6 {
        font-size: 14px;
        line-height: normal;
    }
    .app_dashboard .work {
        background: #56b1e6;
        color: #FFF;
    }
    .app_dashboard .work .work_col {
        border-bottom: 1px solid #87c6ea;
        padding-bottom: 5px;
        margin-bottom: 10px;
    }
    .app_dashboard .work .work_col:last-child {
        border-bottom: 0;
        padding-bottom: 0;
        margin-bottom: 30px;
    }
    .app_dashboard .badge-mark {
        width: 12px;
        height: 12px;
    }
    .app_dashboard .weather_mod .threeday .gradus {
        font-size: 22px;
    }
    .app_dashboard .weather_mod .threeday .threebox {
        border-right: 1px solid #e0e0e0;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .app_dashboard .weather_mod .threeday .threebox:last-child {
        border-right: 0;
    }
    .app_dashboard .weather_mod .gradus {
        font-size: 44px;
        line-height: normal;
        font-weight: bold;
        color: #2a0a57;
    }
    .app_dashboard .weather_mod h3 {
        line-height: 28px;
        color: #AAA;
    }
    .app_dashboard .news_mod .media-list .media img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
    }
    .app_dashboard .news_mod .media-list .media a.title {
        font-size: 14px;
        line-height: normal;
        color: #000;
    }
    .app_dashboard .file_mod .media-list .media img {
        width: 70px;
        height: 70px;
        border-radius: 10px;
    }
    .app_dashboard .file_mod .media-list .media a.title {
        font-size: 14px;
        line-height: normal;
        color: #000;
    }
    .app_dashboard .birthday_mod .media-list .media img {
        width: 50px;
        height: 50px;
        border-radius: 10px;
    }
    .app_dashboard .birthday_mod .media-list .media a.title {
        font-size: 14px;
        line-height: normal;
        color: #000;
    }
    .app_dashboard .birthday_mod .media-list a:hover .icon-gift-box i {
        color: #CC0000;
    }
    .app_dashboard .carousel-item {
        height: 300px;
    } 
    .app_dashboard .carousel-item img {
        height: 300px;
    }
    .app_dashboard .clearfix::after,
    .app_dashboard .calendar ol::after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
    }
    .app_dashboard .calendar {
    border-radius: 10px;
    }
    .app_dashboard .month {
    font-size: 2rem;
    }
    @media (min-width: 992px) {
        .app_dashboard .month {
        font-size: 3.5rem;
    }
    }
    .app_dashboard .calendar ol li {
    float: left;
    width: 14.28571%;
    }
    .app_dashboard .calendar .day-names {
    border-bottom: 1px solid #eee;
    }
    .app_dashboard .calendar .day-names li {
    text-transform: uppercase;
    margin-bottom: 0.5rem;
    }
    .app_dashboard .calendar .days li {
    border-bottom: 1px solid #eee;
    min-height: 56px;
    }
    .app_dashboard .calendar .days li .date {
    margin: 0.5rem 0;
    }
    .app_dashboard .calendar .days li .event {
    font-size: 0.75rem;
    padding: 0.4rem;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-radius: 4rem;
    margin-bottom: 1px;
    }
    .app_dashboard .calendar .days li .event.span-2 {
    width: 200%;
    }
    .app_dashboard .calendar .days li .event.begin {
    border-radius: 1rem 0 0 1rem;
    }
    .app_dashboard .calendar .days li .event.end {
    border-radius: 0 1rem 1rem 0;
    }
    .app_dashboard .calendar .days li .event.clear {
    background: none;
    }
    .app_dashboard .calendar .days li:nth-child(n+29) {
    border-bottom: none;
    }
    .app_dashboard .calendar .days li.outside .date {
    color: #ddd;
    }
    .app_dashboard .rounded-progress {
        width: 100px;
        height: 100px;
        line-height: 100px;
        background: none;
        margin: 0 auto;
        box-shadow: none;
        position: relative;
    }
    .app_dashboard .rounded-progress:after {
        content: "";
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 6px solid #ddd;
        position: absolute;
        top: 0;
        left: 0;
    }
    .app_dashboard .rounded-progress > span {
        width: 50%;
        height: 100%;
        overflow: hidden;
        position: absolute;
        top: 0;
        z-index: 1;
    }
    .app_dashboard .rounded-progress .progress-left {
        left: 0;
    }
    .app_dashboard .rounded-progress .progress-bar {
        width: 100%;
        height: 100%;
        background: none;
        border-width: 6px;
        border-style: solid;
        position: absolute;
        top: 0;
    }
    .app_dashboard .rounded-progress .progress-left .progress-bar {
        left: 100%;
        border-top-right-radius: 80px;
        border-bottom-right-radius: 80px;
        border-left: 0;
        -webkit-transform-origin: center left;
        transform-origin: center left;
    }
    .app_dashboard .rounded-progress .progress-right {
        right: 0;
    }
    .app_dashboard .rounded-progress .progress-right .progress-bar {
        left: -100%;
        border-top-left-radius: 80px;
        border-bottom-left-radius: 80px;
        border-right: 0;
        -webkit-transform-origin: center right;
        transform-origin: center right;
        animation: loading-1 1.8s linear forwards;
    }
    .app_dashboard .rounded-progress .progress-value {
        width: 90%;
        height: 90%;
        border-radius: 50%;
        font-size: 28px;
        color: #000;
        font-weight: bold;
        line-height: 95px;
        text-align: center;
        position: absolute;
        top: 5%;
        left: 5%;
    }
    .app_dashboard .rounded-progress.blue .progress-bar {
        border-color: #049dff;
    }
    .app_dashboard .rounded-progress.blue .progress-left .progress-bar {
        animation: loading-2 1.5s linear forwards 1.8s;
    }
    .app_dashboard .rounded-progress.yellow .progress-bar {
        border-color: #fdba04;
    }
    .app_dashboard .rounded-progress.yellow .progress-left .progress-bar {
        animation: loading-3 1s linear forwards 1.8s;
    }
    .app_dashboard .rounded-progress.pink .progress-bar {
        border-color: #ed687c;
    }
    .app_dashboard .rounded-progress.pink .progress-left .progress-bar {
        animation: loading-4 0.4s linear forwards 1.8s;
    }
    .app_dashboard .rounded-progress.green .progress-bar {
        border-color: #1bbc9b;
    }
    .app_dashboard .rounded-progress.green .progress-left .progress-bar {
        animation: loading-5 1.2s linear forwards 1.8s;
    }
    @keyframes loading-1 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
        }
    }
    @keyframes loading-2 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(144deg);
            transform: rotate(144deg);
        }
    }
    @keyframes loading-3 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
        }
    }
    @keyframes loading-4 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(36deg);
            transform: rotate(36deg);
        }
    }
    @keyframes loading-5 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(126deg);
            transform: rotate(126deg);
        }
    }
    @media only screen and (max-width: 990px) {
        .app_dashboard .rounded-progress {
            margin-bottom: 20px;
        }
    }
    .app_dashboard .boxfooter {
        background: #4896c4;
        margin: -20px;
        border-radius: 0 0 0 10px;
    }
    .app_dashboard .boxfooter .time-footer-bg {
        background: #4896c4;
    }
    .app_dashboard .boxfooter .timesec,
    .app_dashboard .boxfooter .conflict {
        padding: 20px 30px;
    }
    .app_dashboard .boxfooter .timesec.icon-menu-bg {
        background: #3e87b2;
    }
    .app_dashboard .boxfooter .timesec.icon-cross-bg {
        background: #2674a2;
    }
    .app_dashboard .boxfooter .timesec.icon-menu-bg,
    .app_dashboard .boxfooter .timesec.icon-cross-bg {
        padding: 36px 30px 35px 30px;
    }
    .app_dashboard .boxfooter .conflict {
        background: #ff746f;
    }
    .app_dashboard .boxfooter .timesec .timesec1,
    .app_dashboard .boxfooter .conflict .timesec1 {
        font-size: 22px;
    }
    .app_dashboard .carousel-caption {
        background: rgba(0,0,0,0.8);
        padding: 10px 30px;
        bottom: 40px;
        text-align: left;
        margin: 0;
        right: inherit;
        left: 0;
    }
    .app_dashboard .carousel-control-next,
    .app_dashboard .carousel-control-prev {
        width: 5%;
    }
    .app_dashboard #app-dashboard-work-tab li a {
        padding: 6px 18px !important;
    }
    .app_dashboard #app-dashboard-work-tab li a:not(.active) {
        color: #FFF;
    }
    .app_dashboard #app-dashboard-work-tab li a {
        color: #56b1e6;
    }
    .app_dashboard .box-mod.work .nav-tabs-bottom .nav-link.active:before {
        background-color: #ffffff;
    }
    .app_dashboard .box-forum {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    /**************************************************** App Dashboard END ****************************************************/
</style>
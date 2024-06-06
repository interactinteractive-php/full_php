<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet"/>
<div class="intranet">
    <div class="page-content">
    <?php include_once "intranet_leftsidebar.php"; ?>
        <div class="content-wrapper dashboard">
            <div class="page-header page-header-light bg-white mb-3">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:10px 20px;">
                    <div class="d-flex">
                        <span class="text-uppercase font-weight-bold">Хянах самбар</span>
                    </div>
                    <div class="header-elements d-none">
                        <form action="#">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="search" class="form-control wmin-250" placeholder="Түлхүүр үгээр хайх...">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="content dashboard">
				<div>
					<div class="row">
						<div class="col-sm-6 col-xl-3">
							<div class="card card-body bg-blue-400 has-bg-image">
								<div class="media mb-3">
									<div class="media-body">
										<h6 class="font-weight-bold mb-0">Бичиг шийдвэрлэлт</h6>
										<span class="opacity-75">Сүүлийн 1 сараар</span>
									</div>

									<div class="ml-3 align-self-center">
										<i class="icon-cog3 icon-2x"></i>
									</div>
								</div>

								<div class="progress bg-blue mb-2" style="height: 0.125rem;">
									<div class="progress-bar bg-white" style="width: 67%">
										<span class="sr-only">67% Complete</span>
									</div>
								</div>
								
								<div>
									<span class="float-right">67%</span>
									<!-- Re-indexing -->
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-xl-3">
							<div class="card card-body bg-danger-400 has-bg-image">
								<div class="media mb-3">
									<div class="media-body">
										<h6 class="font-weight-semibold mb-0">Services status</h6>
										<span class="opacity-75">April, 19th</span>
									</div>

									<div class="ml-3 align-self-center">
										<i class="icon-pulse2 icon-2x"></i>
									</div>
								</div>

								<div class="progress bg-danger-600 mb-2" style="height: 0.125rem;">
									<div class="progress-bar bg-white" style="width: 80%">
										<span class="sr-only">80% Complete</span>
									</div>
								</div>
								
								<div>
									<span class="float-right">80%</span>
									Partly operational
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-xl-3">
							<div class="card card-body bg-success-400 has-bg-image">
								<div class="media mb-3">
									<div class="mr-3 align-self-center">
										<i class="icon-cog3 icon-2x"></i>
									</div>

									<div class="media-body">
										<h6 class="font-weight-semibold mb-0">Server maintenance</h6>
										<span class="opacity-75">Until 1st of June</span>
									</div>
								</div>

								<div class="progress bg-success mb-2" style="height: 0.125rem;">
									<div class="progress-bar bg-white" style="width: 67%">
										<span class="sr-only">67% Complete</span>
									</div>
								</div>
								
								<div>
									<span class="float-right">67%</span>
									Re-indexing
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-xl-3">
							<div class="card card-body bg-indigo-400 has-bg-image">
								<div class="media mb-3">
									<div class="mr-3 align-self-center">
										<i class="icon-pulse2 icon-2x"></i>
									</div>

									<div class="media-body">
										<h6 class="font-weight-semibold mb-0">Services status</h6>
										<span class="opacity-75">April, 19th</span>
									</div>
								</div>

								<div class="progress bg-indigo mb-2" style="height: 0.125rem;">
									<div class="progress-bar bg-white" style="width: 80%">
										<span class="sr-only">80% Complete</span>
									</div>
								</div>
								
								<div>
									<span class="float-right">80%</span>
									Partly operational
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<div class="generalbox">
							<div class="headertitle">
								<h3>Баримт бичиг</h3>
								<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">1113</a>
							</div>
							<div class="card border-radius-0 border-0">
								<div class="table-responsive v2">
									<table class="table text-nowrap">
										<!-- <thead>
											<tr>
												<th class="pt-3 pb-3 font-weight-bold text-uppercase">Файлын нэр</th>
												<th class="pt-3 pb-3 font-weight-bold text-uppercase">Хэмжээ</th>
											</tr>
										</thead> -->
										<tbody>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<a href="javascript:void(0);">
																<i class="icon-file-pdf text-pink-400 icon-2x"></i>
															</a>
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">ShM2019.08.07.pdf</a>
															<li class="list-inline-item">
																<span class="text-gray">2 цагын өмнө -</span>
																<span class="text-gray">Идэвхитэй</span>
															</li>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted"><span class="text-black">155.12</span> KB</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<a href="javascript:void(0);">
																<i class="icon-file-word text-blue-800 icon-2x"></i>
															</a>
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">ShM2019.08.07.pdf</a>
															<li class="list-inline-item">
																<span class="text-gray">2 цагын өмнө -</span>
																<span class="text-gray">Буцаасан</span>
															</li>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted"><span class="text-black">156.14</span> KB</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<a href="javascript:void(0);">
																<i class="icon-file-excel text-green-800 icon-2x"></i>
															</a>
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">ShM2019.08.07.pdf</a>
															<li class="list-inline-item">
																<span class="text-gray">2 цагын өмнө -</span>
																<span class="text-gray">Идэвхигүй</span>
															</li>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted"><span class="text-black">564.13</span> KB</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<a href="javascript:void(0);">
																<i class="icon-file-pdf text-pink-400 icon-2x"></i>
															</a>
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">ShM2019.08.07.pdf</a>
															<li class="list-inline-item">
																<span class="text-gray">4 цагын өмнө -</span>
																<span class="text-gray">Идэвхитэй</span>
															</li>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted"><span class="text-black">346.49</span> KB</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<a href="javascript:void(0);">
																<i class="icon-file-excel text-green-800 icon-2x"></i>
															</a>
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">ShM2019.08.07.pdf</a>
															<li class="list-inline-item">
																<span class="text-gray">12 цагын өмнө -</span>
																<span class="text-gray">Буцаасан</span>
															</li>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted"><span class="text-black">234.56</span> KB</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="generalbox">
							<div class="headertitle">
								<h3>Мэдээ мэдээлэл</h3>
								<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">123</a>
							</div>
							<div class="card border-radius-0 border-0">
								<div class="table-responsive v2">
									<table class="table text-nowrap">
										<!-- <thead>
											<tr>
												<th class="pt-3 pb-3 font-weight-bold text-uppercase">Файлын нэр</th>
												<th class="pt-3 pb-3 font-weight-bold text-uppercase">Хэмжээ</th>
											</tr>
										</thead> -->
										<tbody>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<img height="34" width="60" src="http://www.cabinet.gov.mn/userfiles/news/fb6df7fd1b24334cbc76f656b28eaf0c.jpg">
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">Тост, Тосон бумбын нурууны байгалийн нөөц<br>газрыг улсын тусгай хамгаалалтад бүрэн авлаа.</a>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted">2 цагын өмнө</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<img height="34" width="60" src="http://www.cabinet.gov.mn/userfiles/news/0d3ec092a94dbdb8fa63d1b18b4dac88.jpg">
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">Нийслэлийн өвөлжилтийн бэлтгэл ажил<br>70 гаруй хувьтай байна.</a>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted">2 цагын өмнө</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<img height="34" width="60" src="http://www.cabinet.gov.mn/userfiles/news/c2c2fce75b9cb9aeaa2f3a0a58e492ed.jpg">
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">Конгресст 43 орны 600 гаруй төлөөлөгч<br>оролцоно.</a>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted">2 цагын өмнө</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<img height="34" width="60" src="http://www.cabinet.gov.mn/userfiles/news/7afaa87780d7884b642c99a77fa3157a.jpg">
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">Тост, Тосон бумбын нурууны байгалийн нөөц<br>газрыг улсын тусгай хамгаалалтад бүрэн авлаа.</a>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted">2 цагын өмнө</span></td>
											</tr>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<img height="34" width="60" src="http://www.cabinet.gov.mn/userfiles/news/0d3ec092a94dbdb8fa63d1b18b4dac88.jpg">
														</div>
														<div class="d-flex flex-column line-height-normal">
															<a href="javascript:void(0);" class="text-default font-weight-semibold">Ерөнхий сайд У.Хүрэлсүх: Монгол Улс хөгжлийн<br>бэрхшээлтэй иргэн бүртээ анхаарал...</a>
														</div>
													</div>
												</td>
												<td class="text-right"><span class="text-muted">2 цагын өмнө</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-4">
							<div class="generalbox">
								<div class="headertitle">
									<h3>Ажил үүрэг</h3>
									<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">456</a>
								</div>
								<div class="card border-radius-0 border-0">
									<div class="table-responsive v2">
										<table class="table text-nowrap">
											<!-- <thead>
												<tr>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Файлын нэр</th>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Хэмжээ</th>
												</tr>
											</thead> -->
											<tbody>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<h1 class="rownumber mb-0">#12</h1>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">UX гүйцэтгэх ажил</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ажлын товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Хугацаа дөхсөн ажил</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<h1 class="rownumber mb-0">#22</h1>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Компьютеруудыг ажилтнуудад хуваарилах</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ажлын товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">2 цагын өмнө</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<h1 class="rownumber mb-0">#36</h1>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Veritech ERP пэйжид пост оруулах</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ажлын товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Хугацаа хэтэрсэн ажил</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<h1 class="rownumber mb-0">#59</h1>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">UX гүйцэтгэх ажил</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ажлын товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Дараа долоо хоногт</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<h1 class="rownumber mb-0">#26</h1>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Veritech ERP пэйжид пост оруулах</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ажлын товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Дараа долоо хоногт</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					
				</div>
				<!-- <div class="generalbox mt-3 pb-4">
						<div class="headertitle">
							<h3>Видео бичлэг</h3>
							<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon">569</a>
						</div>
						<div class="row">
							<div class="col-sm-6 col-xl-2">
								<div class="videoconf d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modal_default1">
									<div>
										<img src="http://www.parliament.mn/medias/7139bf43-b2df-4ffc-85f7-ed9d77bb6b78.jpg">
									</div>
									<div>
										<i class="icon-play3"></i>
									</div>
								</div>
								<div id="modal_default1" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Видео хурал 1</h5>
												<button type="button" class="close" data-dismiss="modal">×</button>
											</div>
											<div class="modal-body">
												<iframe src="https://www.youtube.com/embed/mwqXPe5jMhM" width="100%" height="550px" frameborder="0"></iframe>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
												<a href="javascript:void(0);" class="btn btn-sm btn-primary">Татаж авах</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-2">
								<div class="videoconf d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modal_default1">
									<div>
										<img src="http://www.parliament.mn/medias/76c8120a-abd7-4fc9-83e5-123b9b2c2247.jpg">
									</div>
									<div>
										<i class="icon-play3"></i>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-2">
								<div class="videoconf d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modal_default1">
									<div>
										<img src="http://www.parliament.mn/medias/ab9c9f6c-4aae-452d-8da1-295ea56b3e6e.JPG">
									</div>
									<div>
										<i class="icon-play3"></i>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-2">
								<div class="videoconf d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modal_default1">
									<div>
										<img src="http://montsame.mn/files/5bbe9ce5e9010.jpeg">
									</div>
									<div>
										<i class="icon-play3"></i>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-2">
								<div class="videoconf d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modal_default1">
									<div>
										<img src="http://www.parliament.mn/medias/7139bf43-b2df-4ffc-85f7-ed9d77bb6b78.jpg">
									</div>
									<div>
										<i class="icon-play3"></i>
									</div>
								</div>
								<div id="modal_default1" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Видео хурал 1</h5>
												<button type="button" class="close" data-dismiss="modal">×</button>
											</div>
											<div class="modal-body">
												<iframe src="https://www.youtube.com/embed/mwqXPe5jMhM" width="100%" height="550px" frameborder="0"></iframe>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
												<a href="javascript:void(0);" class="btn btn-sm btn-primary">Татаж авах</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-2">
								<div class="videoconf d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modal_default1">
									<div>
										<img src="http://www.parliament.mn/medias/76c8120a-abd7-4fc9-83e5-123b9b2c2247.jpg">
									</div>
									<div>
										<i class="icon-play3"></i>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<!-- <div class="row boxcol mt-4">
						<div class="col-xl-4 col-sm-6">
							<div class="card boxlist" style="height: 378px;">
								<div class="card-img-actions">
									<img class="card-img img-fluid" src="http://www.cabinet.gov.mn/userfiles/news/fb6df7fd1b24334cbc76f656b28eaf0c.jpg" alt="">
								</div>
								<div class="card-body">
									<a href="javascript:void(0);"><h3>Тост, Тосон бумбын нурууны байгалийн нөөц газрыг улсын тусгай хамгаалалтад бүрэн авлаа.</h3></a>
									<span class="font-size-m text-muted d-flex align-items-center"><i class="icon-file-empty mr-2"></i> Файлын сан</span>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="card boxlist" style="height: 378px;">
								<div class="card-img-actions">
									<img class="card-img img-fluid" src="http://www.cabinet.gov.mn/userfiles/news/0d3ec092a94dbdb8fa63d1b18b4dac88.jpg" alt="">
								</div>
								<div class="card-body">
									<a href="javascript:void(0);"><h3>Нийслэлийн өвөлжилтийн бэлтгэл ажил 70 гаруй хувьтай байна.</h3></a>
									<span class="font-size-m text-muted d-flex align-items-center"><i class="icon-file-empty mr-2"></i> Мэдээ Мэдээлэл</span>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="card boxlist" style="height: 378px;">
								<div class="card-img-actions">
									<img class="card-img img-fluid" src="http://www.cabinet.gov.mn/userfiles/news/c2c2fce75b9cb9aeaa2f3a0a58e492ed.jpg" alt="">
								</div>
								<div class="card-body">
									<a href="javascript:void(0);"><h3>Конгресст 43 орны 600 гаруй төлөөлөгч оролцоно.</h3></a>
									<span class="font-size-m text-muted d-flex align-items-center"><i class="icon-file-empty mr-2"></i> Зургын цомог</span>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="card boxlist" style="height: 378px;">
								<div class="card-img-actions">
									<img class="card-img img-fluid" src="http://www.cabinet.gov.mn/userfiles/news/7afaa87780d7884b642c99a77fa3157a.jpg" alt="">
								</div>
								<div class="card-body">
									<a href="javascript:void(0);"><h3>Ерөнхий сайд У.Хүрэлсүх: Монгол Улс хөгжлийн бэрхшээлтэй иргэн бүртээ анхаарал, халамж тавьж ажиллах ёстой.</h3></a>
									<span class="font-size-m text-muted d-flex align-items-center"><i class="icon-file-empty mr-2"></i> Зар мэдээ</span>
								</div>
							</div>
						</div>
					</div> -->
					<div class="generalbox mt-3">
						<div class="headertitle">
							<h3>Албан бичиг бүртгэл</h3>
							<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">789</a>
						</div>
						<div class="row documentfiles">
							<div class="col-xl-2 col-sm-6">
								<div class="card">
									<div class="card-img-actions mx-1 mt-1">
										<img class="card-img img-fluid" src="https://image.slidesharecdn.com/selfattestedcopyorder-181031003136/95/kerala-legal-sanctity-of-self-attested-copy-of-certificates-along-with-applications-go-uploaded-1-638.jpg?cb=1540945928" alt="">
										<div class="card-img-actions-overlay card-img">
											<a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
												<i class="icon-zoomin3"></i>
											</a>

											<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-download"></i>
											</a>
										</div>
									</div>

									<div class="card-body">
										<div class="d-flex align-items-start flex-wrap">
											<div class="font-weight-semibold">dashboard_draft.png</div>														
											<span class="font-size-sm text-muted ml-auto">378Kb</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-sm-6">
								<div class="card">
									<div class="card-img-actions mx-1 mt-1">
										<img class="card-img img-fluid" src="https://www.uscis.gov/sites/default/files/images/Verification/I9Central/RI-BirthCert.jpg" alt="">
										<div class="card-img-actions-overlay card-img">
											<a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
												<i class="icon-zoomin3"></i>
											</a>

											<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-download"></i>
											</a>
										</div>
									</div>

									<div class="card-body">
										<div class="d-flex align-items-start flex-wrap">
											<div class="font-weight-semibold">dashboard_draft.png</div>														
											<span class="font-size-sm text-muted ml-auto">378Kb</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-sm-6">
								<div class="card">
									<div class="card-img-actions mx-1 mt-1">
										<img class="card-img img-fluid" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQZHKIDp6QJ1T28l-Ci46bYlbu9VbqcPGaLdlVv4v2Y_8ScyrEvg" alt="">
										<div class="card-img-actions-overlay card-img">
											<a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
												<i class="icon-zoomin3"></i>
											</a>

											<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-download"></i>
											</a>
										</div>
									</div>

									<div class="card-body">
										<div class="d-flex align-items-start flex-wrap">
											<div class="font-weight-semibold">dashboard_draft.png</div>														
											<span class="font-size-sm text-muted ml-auto">378Kb</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-sm-6">
								<div class="card">
									<div class="card-img-actions mx-1 mt-1">
										<img class="card-img img-fluid" src="https://www.legal-deedpolls.co.uk/images/official-deedpoll-example-large.jpg" alt="">
										<div class="card-img-actions-overlay card-img">
											<a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
												<i class="icon-zoomin3"></i>
											</a>

											<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-download"></i>
											</a>
										</div>
									</div>

									<div class="card-body">
										<div class="d-flex align-items-start flex-wrap">
											<div class="font-weight-semibold">dashboard_draft.png</div>														
											<span class="font-size-sm text-muted ml-auto">378Kb</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-sm-6">
								<div class="card">
									<div class="card-img-actions mx-1 mt-1">
										<img class="card-img img-fluid" src="https://www.cucas.edu.cn/uploads/school/2018/1127/1543283833499426.jpg" alt="">
										<div class="card-img-actions-overlay card-img">
											<a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
												<i class="icon-zoomin3"></i>
											</a>

											<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-download"></i>
											</a>
										</div>
									</div>

									<div class="card-body">
										<div class="d-flex align-items-start flex-wrap">
											<div class="font-weight-semibold">dashboard_draft.png</div>														
											<span class="font-size-sm text-muted ml-auto">378Kb</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-sm-6">
								<div class="card">
									<div class="card-img-actions mx-1 mt-1">
										<img class="card-img img-fluid" src="https://preview-templates.biztreeapps.com/thumbnails_size/460px/26834.png" alt="">
										<div class="card-img-actions-overlay card-img">
											<a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
												<i class="icon-zoomin3"></i>
											</a>

											<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-download"></i>
											</a>
										</div>
									</div>

									<div class="card-body">
										<div class="d-flex align-items-start flex-wrap">
											<div class="font-weight-semibold">dashboard_draft.png</div>														
											<span class="font-size-sm text-muted ml-auto">378Kb</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row mt-3">
						<div class="col-4">
							<div class="generalbox">
								<div class="headertitle">
									<h3>Хурлын систем</h3>
									<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">56</a>
								</div>
								<div class="card border-radius-0 border-0">
									<div class="table-responsive v2">
										<table class="table text-nowrap">
											<!-- <thead>
												<tr>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Файлын нэр</th>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Хэмжээ</th>
												</tr>
											</thead> -->
											<tbody>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#23</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Засгийн газрын хуралдаан -25</a>
																<li class="list-inline-item">
																	<span class="text-gray">Хурлаар хэлэлцэх асуудал, товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Өнөөдөр</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#29</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Маркетингийн албаны ээлжит хурал</a>
																<li class="list-inline-item">
																	<span class="text-gray">Хурлаар хэлэлцэх асуудал, товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Маргааш</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#46</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Хувьцааны ханшийн тухай - Яаралтай</a>
																<li class="list-inline-item">
																	<span class="text-gray">Хурлаар хэлэлцэх асуудал, товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Маргааш</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#89</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Засгийн газрын хуралдаан -25</a>
																<li class="list-inline-item">
																	<span class="text-gray">Хурлаар хэлэлцэх асуудал, товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Дараа долоо хоногт</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#12</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Маркетингийн албаны ээлжит хурал</a>
																<li class="list-inline-item">
																	<span class="text-gray">Хурлаар хэлэлцэх асуудал, товч тайлбар</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">Дараа долоо хоногт</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-4">
							<div class="generalbox">
								<div class="headertitle">
									<h3>Видео бичлэг</h3>
									<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">578</a>
								</div>
								<div class="card border-radius-0 border-0">
									<div class="table-responsive v2">
										<table class="table text-nowrap">
											<!-- <thead>
												<tr>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Файлын нэр</th>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Хэмжээ</th>
												</tr>
											</thead> -->
											<tbody>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<i class="icon-file-video text-gray icon-2x"></i>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Видео бичлэг 01</a>
																<li class="list-inline-item">
																	<span class="text-gray">2 цагын өмнө</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">04:02:36</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<i class="icon-file-video text-gray icon-2x"></i>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Улсын их хуралдаан #2</a>
																<li class="list-inline-item">
																	<span class="text-gray">2 цагын өмнө</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">00:12:01</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<i class="icon-file-video text-gray icon-2x"></i>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Ээлжит бус хуралдаан</a>
																<li class="list-inline-item">
																	<span class="text-gray">2 цагын өмнө</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">01:05:49</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<i class="icon-file-video text-gray icon-2x"></i>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Жилийн эцсийн тайлан 2018</a>
																<li class="list-inline-item">
																	<span class="text-gray">4 цагын өмнө</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">02:56:12</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<i class="icon-file-video text-gray icon-2x"></i>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Видео бичлэг #3</a>
																<li class="list-inline-item">
																	<span class="text-gray">12 цагын өмнө</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">00:36:27</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-4">
							<div class="generalbox">
								<div class="headertitle">
									<h3>Нэгжийн удирдлага</h3>
									<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">13</a>
								</div>
								<div class="card border-radius-0 border-0">
									<div class="table-responsive v2">
										<table class="table text-nowrap">
											<!-- <thead>
												<tr>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Файлын нэр</th>
													<th class="pt-3 pb-3 font-weight-bold text-uppercase">Хэмжээ</th>
												</tr>
											</thead> -->
											<tbody>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#56</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Цалингийн зээлийн хүсэлт</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ганхүү захирал</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">13:00 - 14:00</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#24</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Цалингийн зээлийн хүсэлт</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ганхүү захирал</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">13:00 - 14:00</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#23</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Цалингийн зээлийн хүсэлт</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ганхүү захирал</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">13:00 - 14:00</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#67</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Цалингийн зээлийн хүсэлт</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ганхүү захирал</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">13:00 - 14:00</span></td>
												</tr>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);">
																	<h1 class="rownumber mb-0">#06</h1>
																</a>
															</div>
															<div class="d-flex flex-column line-height-normal">
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Цалингийн зээлийн хүсэлт</a>
																<li class="list-inline-item">
																	<span class="text-gray">Ганхүү захирал</span>
																</li>
															</div>
														</div>
													</td>
													<td class="text-right"><span class="text-muted">13:00 - 14:00</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="col">
							<div class="generalbox">
							<div class="headertitle">
								<h2>Хэлэлцүүлэг</h2>
								<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon"><i class="icon-list"></i></a>
							</div>
								<div>
									<div class="nav-tabs-responsive">
										<ul class="nav nav-tabs nav-tabs-highlight flex-nowrap mb-0">
											<li class="nav-item">
												<a href="#james" class="nav-link p-2 active p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" alt="" class="rounded-circle mr-2" width="20" height="20">
													Дашжид
													<span class="badge badge-mark ml-2 border-danger"></span>
												</a>
											</li>

											<li class="nav-item">
												<a href="#william" class="nav-link p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" alt="" class="rounded-circle mr-2" width="20" height="20">
													Батбаяр
													<span class="badge badge-mark ml-2 border-success"></span>
												</a>
											</li>

											<li class="nav-item">
												<a href="#jared" class="nav-link p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" alt="" class="rounded-circle mr-2" width="20" height="20">
													Азбаяр
													<span class="badge badge-mark ml-2 border-warning"></span>
												</a>
											</li>

											<li class="nav-item">
												<a href="#victoria" class="nav-link p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" alt="" class="rounded-circle mr-2" width="20" height="20">
													Тэмка
													<span class="badge badge-mark ml-2 border-grey-300"></span>
												</a>
											</li>
										</ul>
									</div>

									<div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-3 p-3">
										<div class="tab-pane fade show active" id="james">
											<ul class="media-list media-chat mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">Уншаагүй мессэжүүд</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>

										<div class="tab-pane fade" id="william">
											<ul class="media-list media-chat media-chat-inverse mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">New messages</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>

										<div class="tab-pane fade" id="jared">
											<ul class="media-list media-chat mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">New messages</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>

										<div class="tab-pane fade" id="victoria">
											<ul class="media-list media-chat media-chat-inverse mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">New messages</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>
									</div>
								</div>
							</div>
						</div> -->
					</div>

					<!-- <div class="row mt-3">
						<div class="col-xl-3">
							<div class="card blog-horizontal" style="border-left: 5px solid green;">
								<div class="card-body p-3 row d-flex align-items-center">
									<div class="col-3 card-img-actions mb-3 mb-sm-0 d-flex justify-content-center">
										<i class="icon-users" style="font-size: 44px; color: green;"></i>
									</div>
									<div class="col-9">
										<h5 class="d-flex font-weight-bold flex-nowrap">
											<a href="javascript:void(0);" class="text-default mr-2">Хүний нөөц</a>

											<span class="badge badge-secondary badge-pill ml-auto">37</span>
										</h5>
										<span class="text-muted">Байгууллагын удирдлагын бүтэц, Хүний нөөцийн удирдлагын програм удирдлагын програм <a href="javascript:void(0);">...</a></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="card blog-horizontal" style="border-left: 5px solid #5a4a9c;">
								<div class="card-body p-3 row d-flex align-items-center">
									<div class="col-3 card-img-actions mb-3 mb-sm-0 d-flex justify-content-center">
										<i class="icon-folder-open" style="font-size: 44px; color: #5a4a9c;"></i>
									</div>
									<div class="col-9">
										<h5 class="d-flex font-weight-bold flex-nowrap">
											<a href="javascript:void(0);" class="text-default mr-2">Ажил хэрэг</a>

											<span class="badge badge-secondary badge-pill ml-auto">169</span>
										</h5>
										<span class="text-muted">Албан болон хувийн ажлын төлөвлөлт удирдлагын програм удирдлагын програм<a href="javascript:void(0);">...</a></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="card blog-horizontal" style="border-left: 5px solid #f7a508;">
								<div class="card-body p-3 row d-flex align-items-center">
									<div class="col-3 card-img-actions mb-3 mb-sm-0 d-flex justify-content-center">
										<i class="icon-envelop" style="font-size: 44px; color: #f7a508;"></i>
									</div>
									<div class="col-9">
										<h5 class="d-flex font-weight-bold flex-nowrap">
											<a href="javascript:void(0);" class="text-default mr-2">Захидал</a>

											<span class="badge badge-secondary badge-pill ml-auto">79</span>
										</h5>
										<span class="text-muted">Дотоод, гадаад холболттой захидал харилцааны програм удирдлагын програм<a href="javascript:void(0);">...</a></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="card blog-horizontal" style="border-left: 5px solid green;">
								<div class="card-body p-3 row d-flex align-items-center">
									<div class="col-3 card-img-actions mb-3 mb-sm-0 d-flex justify-content-center">
										<i class="icon-users" style="font-size: 44px; color: green;"></i>
									</div>
									<div class="col-9">
										<h5 class="d-flex font-weight-bold flex-nowrap">
											<a href="javascript:void(0);" class="text-default mr-2">Хүний нөөц</a>

											<span class="badge badge-secondary badge-pill ml-auto">37</span>
										</h5>
										<span class="text-muted">Байгууллагын удирдлагын бүтэц, Хүний нөөцийн удирдлагын програм удирдлагын програм <a href="javascript:void(0);">...</a></span>
									</div>
								</div>
							</div>
						</div>
					</div> -->
				
					<!-- <div class="generalbox mt-4">
						<div class="headertitle">
							<h2>Хурлын систем</h2>
							<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon"><i class="icon-list"></i></a>
						</div>
						<div class="card border-radius-0">
							<div class="table-responsive v2">
								<table class="table table-striped table-borderless">
									<thead>
										<tr>
											<th class="font-weight-bold text-uppercase">#</th>
											<th class="font-weight-bold text-uppercase">Хугацаа</th>
											<th class="font-weight-bold text-uppercase">Хурал</th>
											<th class="font-weight-bold text-uppercase">Эрэмбэ</th>
											<th class="font-weight-bold text-uppercase">Хэзээ?</th>
											<th class="font-weight-bold text-uppercase">Төлөв</th>
											<th class="font-weight-bold text-uppercase">Модератор</th>
											<th class="text-center text-muted font-weight-bold text-uppercase" style="width: 30px;"><i class="icon-checkmark3"></i></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>#25</td>
											<td>Өнөөдөр</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Засгийн газрын хуралдаан -25</a></div>
												<div class="text-muted">Хурлаар хэлэлцэх асуудал, товч тайлбар</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-success dropdown-toggle" data-toggle="dropdown">Low</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 19">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Удахгүй</option>
													<option value="hold">Хүлээгдэх</option>
													<option value="resolved" selected="selected">Яг одоо</option>
													<option value="closed">Өнгөрсөн</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p100x100/44878537_2065035603517301_2177465517613252608_n.jpg?_nc_cat=109&_nc_oc=AQlQl2L-JzjmdYG1FC6qQxGnbErI45bCx7JMrL20-1C43zdIWDGhbOPkWHVqSrL0oKE&_nc_ht=scontent.fuln1-1.fna&oh=7272898891d7bc92b74269439d3d9590&oe=5DCE0F9F" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p100x100/68240738_394458704539269_6034241867615305728_n.jpg?_nc_cat=105&_nc_oc=AQnq3avzAiDpGdryDy2WveGqU6SxqgJSfPt5wswpMAJMzn87hr30h1POWd1Cu4vavJQ&_nc_ht=scontent.fuln1-1.fna&oh=b8dfab41fc9b9dc87723a39a669a6e5d&oe=5DD1B085" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#23</td>
											<td>Өнөөдөр</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Маркетингийн албаны ээлжит хурал</a></div>
												<div class="text-muted">Хурлаар хэлэлцэх асуудал, товч тайлбар</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Энгийн</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 September, 19">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Удахгүй</option>
													<option value="hold" selected="selected">Хүлээгдэх</option>
													<option value="resolved">Яг одоо</option>
													<option value="closed">Өнгөрсөн</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/11825073_413578528851539_5429478484378565405_n.jpg?_nc_cat=108&_nc_oc=AQlhA4qps1HwnDZN2uijxVPh0YzMeDfjyiDOtrDnH17EqpxLlKCdcRceol0ejzptM0g&_nc_ht=scontent.fuln1-2.fna&oh=d375cfdffc1d63563ac58ef475e0c03b&oe=5DE04615" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p100x100/50504677_10213932392805389_4139685948868788224_n.jpg?_nc_cat=101&_nc_oc=AQnsgc3EL43x7yvVO6mIBxhBW3Z-eBNBGZLTVge1TCaWDL5NQOqKFpPmKBz3RWfDg7s&_nc_ht=scontent.fuln1-1.fna&oh=9f06323d3364fb5279c284fbef7fc5fc&oe=5DE569D4" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#22</td>
											<td>Өнөөдөр</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">ТУЗ-ын хурал - Хувьцааны ханшийн тухай - Яаралтай хуралдаан</a></div>
												<div class="text-muted">Хурлаар хэлэлцэх асуудал, товч тайлбар</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Өндөр</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 September, 19">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Удахгүй</option>
													<option value="hold">Хүлээгдэх</option>
													<option value="resolved">Яг одоо</option>
													<option value="closed" selected="selected">Өнгөрсөн</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://www.efratnakash.com/galleries_l_pics/asia/mongolia_nomads/31-07175.jpg" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-9/33396408_1876161855763643_4549164484442193920_n.jpg?_nc_cat=105&_nc_oc=AQmYjD2uZVf2t7kAeUIs3gMVCTS_yZ3u35qgB5gC165saPfO2PbRg8WKyV0-OigpChw&_nc_ht=scontent.fuln1-1.fna&oh=2a1777e160d4719cebede86443933177&oe=5DE7FF72" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p100x100/29511612_180022046122715_6083798970732583364_n.jpg?_nc_cat=103&_nc_oc=AQkTx055U880fNiFYIEjghyWQMFaOQsD8xPxVrVQWcLKj-195BElwYrvuc2CFwM7kXc&_nc_ht=scontent.fuln1-1.fna&oh=ed0d12490c055e0840a72c19c4e63588&oe=5DDE2C19" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#21</td>
											<td>Маргааш</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Edit the draft for the icons</a></div>
												<div class="text-muted">You've got to get enough sleep. Other travelling salesmen..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="21 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid" selected="selected">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#20</td>
											<td>Маргааш</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Fix validation issues and commit</a></div>
												<div class="text-muted">But who knows, maybe that would be the best thing for me..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Blocker</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved" selected="selected">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#19</td>
											<td>Маргааш</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Support tickets list doesn't support commas</a></div>
												<div class="text-muted">I'd have gone up to the boss and told him just what i think..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="21 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed" selected="selected">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#18</td>
											<td>Маргааш</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Help Web devs with HTML integration</a></div>
												<div class="text-muted">Samsa was a travelling salesman - and above it there hung..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-success dropdown-toggle" data-toggle="dropdown">Low</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="21 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved" selected="selected">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>


										<tr>
											<td>#12</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Add updated responsive styles</a></div>
												<div class="text-muted">I should be incapable of drawing a single stroke at the present..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="17 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#11</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Merge latest changes</a></div>
												<div class="text-muted">When, while the lovely valley teems with vapour around me..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Blocker</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="16 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix" selected="selected">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#10</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Create landing page for a new app</a></div>
												<div class="text-muted">A few stray gleams steal into the inner sanctuary, I throw..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="17 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#9</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Update JS code in app.js file</a></div>
												<div class="text-muted">When I hear the buzz of the little world among the stalks..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="15 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold" selected="selected">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

										<tr>
											<td>#8</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Combine .js files in /app/js/</a></div>
												<div class="text-muted">Insects and flies, then I feel the presence of the Almighty..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="14 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved" selected="selected">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);"><img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt=""></a>
												<a href="javascript:void(0);" class="btn btn-icon bg-transparent btn-sm border-slate-300 text-slate rounded-round border-dashed"><i class="icon-plus22"></i></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</li>
												</div>
											</td>
										</tr>

									</tbody>
								</table>
							</div>
						</div>
					</div> -->
					<div class="mt-3">
						<div class="row">
							<div class="col-sm-6 col-xl-3">
								<div class="card card-body bg-blue-400 has-bg-image">
									<div class="media">
										<div class="mr-3 align-self-center">
											<i class="icon-pointer icon-3x"></i>
										</div>

										<div class="media-body text-right">
											<h3 class="font-weight-semibold mb-0">652,549</h3>
											<span class="text-uppercase font-size-sm">total clicks</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-xl-3">
								<div class="card card-body bg-danger-400 has-bg-image">
									<div class="media">
										<div class="mr-3 align-self-center">
											<i class="icon-enter6 icon-3x"></i>
										</div>

										<div class="media-body text-right">
											<h3 class="font-weight-semibold mb-0">245,382</h3>
											<span class="text-uppercase font-size-sm">total visits</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-xl-3">
								<div class="card card-body bg-success-400 has-bg-image">
									<div class="media">
										<div class="media-body">
											<h3 class="font-weight-semibold mb-0">54,390</h3>
											<span class="text-uppercase font-size-sm">total comments</span>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-bubbles4 icon-3x"></i>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-xl-3">
								<div class="card card-body bg-indigo-400 has-bg-image">
									<div class="media">
										<div class="media-body">
											<h3 class="font-weight-semibold mb-0">389,438</h3>
											<span class="text-uppercase font-size-sm">total orders</span>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-bag icon-3x"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom: 5000px;">
						<div class="col-6">
							<div class="generalbox">
								<div class="headertitle">
									<h3>Хэлэлцэх асуудлууд</h3>
									<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">1234</a>
								</div>
								<div class="card border-radius-0 p-0">
									<div class="card-header p-2 pl-3 pr-3 header-elements-md-inline">
										<h5 class="card-title">Асуудлууд</h5>
										<div class="header-elements">
											<ul class="list-inline list-inline-dotted mb-0 mt-2 mt-md-0">
												<li class="list-inline-item">Дундаж цаг: <span class="font-weight-semibold">4.85</span></li>
												<li class="list-inline-item">
													<i class="icon-star-full2 font-size-base text-warning-300"></i>
													<i class="icon-star-full2 font-size-base text-warning-300"></i>
													<i class="icon-star-full2 font-size-base text-warning-300"></i>
													<i class="icon-star-full2 font-size-base text-warning-300"></i>
													<i class="icon-star-full2 font-size-base text-warning-300"></i>
													<span class="text-muted ml-1">(17 цаг)</span>
												</li>
											</ul>
										</div>
									</div>
									<div class="nav-tabs-responsive bg-light border-top pl-2 pr-2">
										<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
											<li class="nav-item"><a href="#course-overview" class="nav-link p-2 " data-toggle="tab"><i class="icon-menu7 mr-2"></i> Асуудал</a></li>
											<li class="nav-item"><a href="#course-attendees" class="nav-link p-2 active p-2" data-toggle="tab"><i class="icon-people mr-2"></i> Оролцогчид</a></li>
											<li class="nav-item"><a href="#course-schedule" class="nav-link p-2" data-toggle="tab"><i class="icon-calendar3 mr-2"></i> Календар</a></li>
										</ul>
									</div>
									<div class="tab-content">
										<div class="tab-pane fade" id="course-overview">
											<div class="card-body">
												<div class="mt-1 mb-4">
													<h6 class="font-weight-semibold">Асуудлын тухай</h6>
													<p>Then sluggishly this camel learned woodchuck far stretched unspeakable notwithstanding the walked owing stung mellifluously glumly rooster more examined one that combed until a less less witless pouted up voluble timorously glared elaborate giraffe steady while grinned and got one beaver to walked.</p>
												</div>

												<h6 class="font-weight-semibold">Бас нэг параграф</h6>
												<p class="mb-3">Some cow goose out and sweeping wow the skillfully goodness one crazily far some jeez darn well so peevish pending nudged categorically in between about much alas handsome intolerable devotedly helpfully smiled momentously next much this this next sweepingly far.</p>

												<div class="row">
													<div class="col-sm-6">
														<div class="mb-4">
															<dl>
																<dt class="font-size-sm font-weight-bold text-uppercase">
																	<i class="icon-checkmark3 text-blue mr-2"></i>
																	Salamander much that on much
																</dt>
																<dd>Like partook magic this enthusiastic tasteful far crud otter this the ferret honey iguana. Together prim and limpet much extravagantly quail longing.</dd>

																<dt class="font-size-sm font-weight-bold text-uppercase">
																	<i class="icon-checkmark3 text-blue mr-2"></i>
																	Well far some raccoon
																</dt>
																<dd>Python laudably euphemistically since this copious much human this briefly hello ouch less one diligent however impotently made gave a slick up much.</dd>
															</dl>
														</div>
													</div>

													<div class="col-sm-6">
														<div class="mb-4">
															<dl>
																<dt class="font-size-sm font-weight-bold text-uppercase">
																	<i class="icon-checkmark3 text-blue mr-2"></i>
																	Misunderstood cuffed more depending
																</dt>
																<dd>And earthworm dear arose bald agilely sad so below cowered within ceremonially therefore via much this symbolically and newt capably.</dd>

																<dt class="font-size-sm font-weight-bold text-uppercase">
																	<i class="icon-checkmark3 text-blue mr-2"></i>
																	Voluble much saddled mechanic
																</dt>
																<dd>Much took between less goodness jay mallard kneeled gnashed this up strong cooperative. A collection of textile samples lay spread.</dd>
															</dl>
														</div>
													</div>
												</div>

											</div>

											<div class="table-responsive v2">
												<table class="table table-striped table-borderless">
													<thead>
														<tr>
															<th>#</th>
															<th>Асуудал</th>
															<th>Тайлбар</th>
															<th>Хугацаа</th>
															<th>Төлөв</th>
															<th>Огноо</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="p-3">1</td>
															<td><a href="javascript:void(0);">Соёрхол</a></td>
															<td>Монгол, Хятадын Замын-Үүд, Эрээний эдийн засгийн хамтын ажиллагааны бүс байгуулах тухай Монгол Улсын Засгийн газар, БНХАУ-ын хоорондын хэлэлцээрийг соёрхон батлах тухай</td>
															<td>10 цаг</td>
															<td><span class="badge bg-secondary">Closed</span></td>
															<td>2019.09.20</td>
														</tr>
														<tr>
															<td class="p-3">2</td>
															<td><a href="javascript:void(0);">Төсөл</a></td>
															<td>“Ажлын хэсэг байгуулах тухай” Улсын Их Хурлын тогтоолын төсөл;</td>
															<td>20 цаг</td>
															<td><span class="badge bg-primary">Registration</span></td>
															<td>2019.09.21</td>
														</tr>
														<tr>
															<td class="p-3">3</td>
															<td><a href="javascript:void(0);">Төсөл</a></td>
															<td>“Монгол Улсын Үндсэн хуульд оруулах нэмэлт, өөрчлөлтийн төслийг хэлэлцүүлэгт бэлтгэх хугацаа тогтоох тухай” Улсын Их Хурлын тогтоолын төсөл.</td>
															<td>35 цаг</td>
															<td><span class="badge bg-danger">On time</span></td>
															<td>2019.10.30</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>

										<div class="tab-pane fade show active" id="course-attendees">
											<div class="card-body">
												<div class="row">
													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/c0.0.100.100a/p100x100/50628643_541296479689789_516948736262275072_n.jpg?_nc_cat=107&_nc_oc=AQmeYourNR8rZBs1U5QSHH-avU8TeH4bOdBcx-P7FmprK9Ma_tqusZ9Nm-vcfm99Pro&_nc_ht=scontent.fuln1-2.fna&oh=47d6a4aec3988dd6f70a0b5977fe3939&oe=5DD09408" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">Б.Мөнхсайхан</h6>
																	<span class="text-muted">УИХ-ын гишүүн</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/c16.0.100.100a/p100x100/18739909_973860139417736_3496379808737740017_n.jpg?_nc_cat=108&_nc_oc=AQm1KjFJY4a1L-FX9GeKmjZ4KQKJxx9aUCbz9pt3BkADxfmuLOQqrI-rgQhDkEigQvA&_nc_ht=scontent.fuln1-2.fna&oh=770a4c5464a15be80da122ee9ba0eed7&oe=5DCCBBA0" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">М.Баасандаваа</h6>
																	<span class="text-muted">УИХ-ын гишүүн</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">Vanessa Aurelius</h6>
																	<span class="text-muted">Front end guru</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">William Brenson</h6>
																	<span class="text-muted">Chief officer</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">James Alexander</h6>
																	<span class="text-muted">Lead developer</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">Nathan Jacobson</h6>
																	<span class="text-muted">Lead UX designer</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">Margo Baker</h6>
																	<span class="text-muted">Sales manager</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-md-6">
														<div class="card card-body">
															<div class="media">
																<div class="mr-3">
																	<a href="javascript:void(0);">
																		<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="42" height="42" alt="">
																	</a>
																</div>

																<div class="media-body">
																	<h6 class="mb-0">Barbara Walden</h6>
																	<span class="text-muted">SEO specialist</span>
																</div>

																<div class="ml-3 align-self-center">
																	<div class="list-icons">
																		<div class="list-icons-item dropdown">
																			<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																			<div class="dropdown-menu dropdown-menu-right">
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-comment-discussion"></i> Start chat</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-phone2"></i> Make a call</a>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-mail5"></i> Send mail</a>
																				<div class="dropdown-divider"></div>
																				<a href="javascript:void(0);" class="dropdown-item"><i class="icon-statistics"></i> Statistics</a>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- <div class="d-flex justify-content-center mt-3 mb-3">
													<ul class="pagination">
														<li class="page-item"><a href="javascript:void(0);" class="page-link"><i class="icon-arrow-small-right"></i></a></li>
														<li class="page-item active"><a href="javascript:void(0);" class="page-link">1</a></li>
														<li class="page-item"><a href="javascript:void(0);" class="page-link">2</a></li>
														<li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
														<li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
														<li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li>
														<li class="page-item"><a href="javascript:void(0);" class="page-link"><i class="icon-arrow-small-left"></i></a></li>
													</ul>
												</div> -->
											</div>
										</div>

										<div class="tab-pane fade" id="course-schedule">
											<div class="card-body">
												<div class="schedule"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="generalbox">
								<div class="headertitle">
									<h3>Хэлэлцүүлэг</h3>
									<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon text-uppercase font-weight-bold">562</a>
								</div>
								<div>
									<div class="nav-tabs-responsive">
										<ul class="nav nav-tabs nav-tabs-highlight flex-nowrap mb-0">
											<li class="nav-item">
												<a href="#james" class="nav-link p-2 active p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" alt="" class="rounded-circle mr-2" width="20" height="20">
													Дашжид
													<span class="badge badge-mark ml-2 border-danger"></span>
												</a>
											</li>

											<li class="nav-item">
												<a href="#william" class="nav-link p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" alt="" class="rounded-circle mr-2" width="20" height="20">
													Батбаяр
													<span class="badge badge-mark ml-2 border-success"></span>
												</a>
											</li>

											<li class="nav-item">
												<a href="#jared" class="nav-link p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" alt="" class="rounded-circle mr-2" width="20" height="20">
													Азбаяр
													<span class="badge badge-mark ml-2 border-warning"></span>
												</a>
											</li>

											<li class="nav-item">
												<a href="#victoria" class="nav-link p-2" data-toggle="tab">
													<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" alt="" class="rounded-circle mr-2" width="20" height="20">
													Тэмка
													<span class="badge badge-mark ml-2 border-grey-300"></span>
												</a>
											</li>
										</ul>
									</div>

									<div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-3 p-3">
										<div class="tab-pane fade show active" id="james">
											<ul class="media-list media-chat mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">Уншаагүй мессэжүүд</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>

										<div class="tab-pane fade" id="william">
											<ul class="media-list media-chat media-chat-inverse mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">New messages</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>

										<div class="tab-pane fade" id="jared">
											<ul class="media-list media-chat mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">New messages</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>

										<div class="tab-pane fade" id="victoria">
											<ul class="media-list media-chat media-chat-inverse mb-3">
												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
														<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
														<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
														<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media content-divider justify-content-center text-muted mx-0">
													<span class="px-2">New messages</span>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
														<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>

												<li class="media">
													<div class="mr-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>

													<div class="media-body">
														<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
														<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
													</div>
												</li>

												<li class="media media-chat-item-reverse">
													<div class="media-body">
														<div class="media-chat-item"><i class="icon-menu"></i></div>
													</div>

													<div class="ml-3">
														<a href="javascript:void(0);">
															<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
														</a>
													</div>
												</li>
											</ul>

											<textarea name="enter-message" class="form-control mb-1" rows="3" cols="1" placeholder="Санал хүсэлтээ бичээд ENTER дарна уу..."></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="generalbox mt-2">
						<div class="headertitle">
							<h2>Ажил үүрэг</h2>
							<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon"><i class="icon-list"></i></a>
						</div>
						<div class="card border-radius-0">
							<div class="table-responsive v2">
								<table class="table text-nowrap">
									<thead>
										<tr>
											<th>#</th>
											<th>Хугацаа</th>
											<th>Ажил</th>
											<th>Эрэмбэ</th>
											<th>Хэзээ?</th>
											<th>Төлөв</th>
											<th>Дарга</th>
											<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>#25</td>
											<td>Хугацаа дөхсөн ажил</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">UX гүйцэтгэх ажил</a></div>
												<div class="text-muted">Ажлын товч тайлбар</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-success dropdown-toggle" data-toggle="dropdown">Low</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 19">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Хүлээгдэж байна</option>
													<option value="resolved" selected="selected">Гүйцэтгэж байна</option>
													<option value="closed">Гүйцэтгэсэн</option>
													<option value="after">Хойшлуулсан</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p100x100/44878537_2065035603517301_2177465517613252608_n.jpg?_nc_cat=109&_nc_oc=AQlQl2L-JzjmdYG1FC6qQxGnbErI45bCx7JMrL20-1C43zdIWDGhbOPkWHVqSrL0oKE&_nc_ht=scontent.fuln1-1.fna&oh=7272898891d7bc92b74269439d3d9590&oe=5DCE0F9F" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr>
											<td>#23</td>
											<td>Хугацаа дөхсөн ажил</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Veritech ERP пэйжид пост оруулах</a></div>
												<div class="text-muted">Ажлын товч тайлбар</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Энгийн</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 September, 19">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open" selected="selected">Хүлээгдэж байна</option>
													<option value="resolved">Гүйцэтгэж байна</option>
													<option value="closed">Гүйцэтгэсэн</option>
													<option value="after">Хойшлуулсан</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/11825073_413578528851539_5429478484378565405_n.jpg?_nc_cat=108&_nc_oc=AQlhA4qps1HwnDZN2uijxVPh0YzMeDfjyiDOtrDnH17EqpxLlKCdcRceol0ejzptM0g&_nc_ht=scontent.fuln1-2.fna&oh=d375cfdffc1d63563ac58ef475e0c03b&oe=5DE04615" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr>
											<td>#22</td>
											<td>Хугацаа дөхсөн ажил</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Компьютеруудыг ажилтнуудад хуваарилж өгөх</a></div>
												<div class="text-muted">Ажлын товч тайлбар</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Өндөр</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 September, 19">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Хүлээгдэж байна</option>
													<option value="resolved">Гүйцэтгэж байна</option>
													<option value="closed">Гүйцэтгэсэн</option>
													<option value="after" selected="selected">Хойшлуулсан</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="http://www.efratnakash.com/galleries_l_pics/asia/mongolia_nomads/31-07175.jpg" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr class="table-danger">
											<td>#21</td>
											<td>Хугацаа хэтэрсэн ажил</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Edit the draft for the icons</a></div>
												<div class="text-muted">You've got to get enough sleep. Other travelling salesmen..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="21 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid" selected="selected">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=c01181747220451d79559da3fbb3a696&oe=5DE7EEDB" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr class="table-danger">
											<td>#20</td>
											<td>Хугацаа хэтэрсэн ажил</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Fix validation issues and commit</a></div>
												<div class="text-muted">But who knows, maybe that would be the best thing for me..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Blocker</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved" selected="selected">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=c01181747220451d79559da3fbb3a696&oe=5DE7EEDB" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr class="table-danger">
											<td>#19</td>
											<td>Хугацаа хэтэрсэн ажил</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Support tickets list doesn't support commas</a></div>
												<div class="text-muted">I'd have gone up to the boss and told him just what i think..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="21 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed" selected="selected">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=c01181747220451d79559da3fbb3a696&oe=5DE7EEDB" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>


										<tr class="table-info">
											<td>#12</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Add updated responsive styles</a></div>
												<div class="text-muted">I should be incapable of drawing a single stroke at the present..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="17 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/18698148_1943229699289891_7805105627171536016_n.jpg?_nc_cat=107&_nc_oc=AQmQdyiBZ_z5oxqU1bkwwf7JzWVHFw-sdstdPoHY577c9uYfswkq0UO7lq6uypz4Uuk&_nc_ht=scontent.fuln1-2.fna&oh=a3e7a74f2e7cfbd7786ba5f2f8a75217&oe=5DD504B7" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr class="table-info">
											<td>#11</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Merge latest changes</a></div>
												<div class="text-muted">When, while the lovely valley teems with vapour around me..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Blocker</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="16 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix" selected="selected">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/18698148_1943229699289891_7805105627171536016_n.jpg?_nc_cat=107&_nc_oc=AQmQdyiBZ_z5oxqU1bkwwf7JzWVHFw-sdstdPoHY577c9uYfswkq0UO7lq6uypz4Uuk&_nc_ht=scontent.fuln1-2.fna&oh=a3e7a74f2e7cfbd7786ba5f2f8a75217&oe=5DD504B7" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

										<tr class="table-info">
											<td>#10</td>
											<td>Дараа долоо хоногт</td>
											<td>
												<div class="font-weight-semibold"><a href="javascript:void(0);">Create landing page for a new app</a></div>
												<div class="text-muted">A few stray gleams steal into the inner sanctuary, I throw..</div>
											</td>
											<td>
												<div class="btn-group">
													<a href="javascript:void(0);" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
														<a href="javascript:void(0);" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
														<a href="javascript:void(0);" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
													</div>
												</div>
											</td>
											<td>
												<div class="d-inline-flex align-items-center">
													<i class="icon-calendar2 mr-2"></i>
													<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="17 January, 15">
												</div>
											</td>
											<td>
												<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
													<option value="open">Open</option>
													<option value="hold">On hold</option>
													<option value="resolved">Resolved</option>
													<option value="dublicate">Dublicate</option>
													<option value="invalid">Invalid</option>
													<option value="wontfix">Wontfix</option>
													<option value="closed">Closed</option>
												</select>
											</td>
											<td>
												<a href="javascript:void(0);"><img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p100x100/18698148_1943229699289891_7805105627171536016_n.jpg?_nc_cat=107&_nc_oc=AQmQdyiBZ_z5oxqU1bkwwf7JzWVHFw-sdstdPoHY577c9uYfswkq0UO7lq6uypz4Uuk&_nc_ht=scontent.fuln1-2.fna&oh=a3e7a74f2e7cfbd7786ba5f2f8a75217&oe=5DD504B7" class="rounded-circle" width="32" height="32" alt=""></a>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-alarm-add"></i> Check in</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-attachment"></i> Attach screenshot</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-rotate-ccw2"></i> Reassign</a>
															<div class="dropdown-divider"></div>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-pencil7"></i> Edit task</a>
															<a href="javascript:void(0);" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
														</div>
													</div>
												</div>
											</td>
										</tr>

									</tbody>
								</table>
							</div>
						</div>
					</div> -->
					<!-- <div class="generalbox mt-2">
						<div class="headertitle">
							<h2>Нэгжийн удирдлага</h2>
							<a href="javascript:void(0);" class="btn bg-blue btn-sm btn-icon"><i class="icon-list"></i></a>
						</div>
						<div class="card border-radius-0">
							<div class="table-responsive v2">
								<div class="table table-striped table-borderless">
									<table class="table">
										<tbody>
											<tr class="table-active table-border-double">
												<td class="font-weight-bold" colspan="5">Миний гаргасан хүсэлтүүд (сүүлийнх)</td>
												<td class="text-right">
													<span class="badge bg-blue badge-pill">3</span>
												</td>
											</tr>
											<?php for ($i=0; $i < 3; $i++) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="mr-3">
																<a href="javascript:void(0);" class="btn bg-warning-400 rounded-round btn-icon btn-sm">
																	<span class="letter-icon">?</span>
																</a>
															</div>
															<div>
																<a href="javascript:void(0);" class="text-default font-weight-semibold">Цалингийн зээлийн хүсэлт</a>
																<div class="text-muted font-size-sm">
																	<span class="badge badge-mark border-danger mr-1"></span>
																	13:00 - 14:00
																</div>
															</div>
														</div>
													</td>
													<td><span class="text-muted">Ганхүү захирал</span></td>
													<td><span class="text-success-600"> 1 өдөр</span></td>
													<td><h6 class="font-weight-semibold mb-0">1 сая төг</h6></td>
													<td><span class="badge bg-warning">Нээлттэй</span></td>
													<td class="text-center">
														<div class="list-icons">
															<div class="list-icons-item dropdown">
																<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
																<div class="dropdown-menu dropdown-menu-right">
																	<a href="javascript:void(0);" class="dropdown-item"><i class="icon-file-stats"></i> Засах</a>
																	<a href="javascript:void(0);" class="dropdown-item"><i class="icon-file-text2"></i> Хүчингүй болгох</a>
																	<a href="javascript:void(0);" class="dropdown-item"><i class="icon-file-locked"></i> Устгах</a>
																	<div class="dropdown-divider"></div>
																	<a href="javascript:void(0);" class="dropdown-item"><i class="icon-gear"></i> Тохиргоо</a>
																</div>
															</div>
														</div>
													</td>
												</tr>

											<?php } ?>

											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<a href="javascript:void(0);" class="btn bg-success-400 rounded-round btn-icon btn-sm">
																<span class="letter-icon">O</span>
															</a>
														</div>

														<div>
															<a href="javascript:void(0);" class="text-default font-weight-semibold">Чөлөөний хүсэлт</a>
															<div class="text-muted font-size-sm">
																<span class="badge badge-mark border-success mr-1"></span>
																3 өдөр
															</div>
														</div>
													</div>
												</td>
												<td><span class="text-muted">Батбаяр дарга</span></td>
												<td><span class="text-success-600">2019.08.10</span></td>
												<td><h6 class="font-weight-semibold mb-0">3 өдөр</h6></td>
												<td><span class="badge bg-success">Шийдсэн</span></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="list-icons-item dropdown">
															<a href="javascript:void(0);" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" class="dropdown-item"><i class="icon-file-stats"></i> Засах</a>
																<a href="javascript:void(0);" class="dropdown-item"><i class="icon-file-text2"></i> Хүчингүй болгох</a>
																<a href="javascript:void(0);" class="dropdown-item"><i class="icon-file-locked"></i> Устгах</a>
																<div class="dropdown-divider"></div>
																<a href="javascript:void(0);" class="dropdown-item"><i class="icon-gear"></i> Тохиргоо</a>
															</div>
														</div>
													</div>
												</td>
											</tr>						


										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div> -->
				</div>
        </div>
        <?php include_once "intranet_rightsidebar.php"; ?>
    </div>
</div>
<script>
    $(function () {
        $('#tooltip-demo').tooltip()
    })
</script>


































<?php
    $my_tuluv01 = array(
        '<span class="badge badge-mark badge-float border-danger mt-1 mr-1" data-popup="tooltip" title="Засах эрхтэй"></span>',
        '<span class="badge badge-mark badge-float border-info mt-1 mr-1" data-popup="tooltip" title="Саналын эрхтэй"></span>',
        '<span class="badge badge-mark badge-float border-secondary mt-1 mr-1" data-popup="tooltip" title="Засах эрхтэй"></span>'
    );

    $my_tuluv_color = array(
        'danger',
        'info',
        'success',
        'secondary'
    );

    $my_process_name = array(
        'Үүсгэсэн',
        'Илгээсэн',
        'Хянасан',
        'Танилцсан',
        'Санал илгээсэн',
        'Баталсан'
    );
//echo $my_process_name[array_rand($my_process_name)];
?>
	<div class="page-content">
	<script src="../../../../global_assets/js/plugins/ui/fullcalendar/core/main.min.js"></script>
	<script src="../../../../global_assets/js/plugins/ui/fullcalendar/daygrid/main.min.js"></script>
	<script src="../../../../global_assets/js/plugins/ui/fullcalendar/timegrid/main.min.js"></script>
	<script src="../../../../global_assets/js/plugins/ui/fullcalendar/interaction/main.min.js"></script>
	<script src="../../../../global_assets/js/demo_pages/learning_detailed.js"></script>
	<script src="../../../../global_assets/js/demo_pages/widgets_stats.js"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="breadcrumb justify-content-center">
				<a href="javascript:void(0);" class="breadcrumb-elements-item">
					<span class="font-weight-semibold">Ажилтны нэр</span> Хэлтэс, Албан тушаал
				</a>
			</div>
			
			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<div class="breadcrumb-elements-item dropdown p-0">
						<a href="javascript:void(0);" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
							Бусад
						</a>

						<div class="dropdown-menu dropdown-menu-right">
							<a href="javascript:void(0);" class="dropdown-item"><i class="icon-bin mr-1"></i> Профайл засах</a>
							<a href="javascript:void(0);" class="dropdown-item"><i class="icon-diff-modified mr-1"></i> Архивлах</a>
							<a href="javascript:void(0);" class="dropdown-item"><i class="icon-printer4 mr-1"></i> Хэвлэх</a>
							<div class="dropdown-divider"></div>
							<a href="javascript:void(0);" class="dropdown-item"><i class="icon-share3 mr-1"></i> Share</a>
							<a href="javascript:void(0);" class="dropdown-item"><i class="icon-bubble-notification mr-1"></i> Сонордуулга</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<h2>Хувийн мэдээлэл</h2>
		<div class="card p-3">
			<div class="card-header header-elements-sm-inline">
				<h6 class="card-title">Б.Жаргалсайхан</h6>
				<div class="header-elements">
					<a class="text-default daterange-ranges font-weight-semibold cursor-pointer dropdown-toggle">
						<i class="icon-gear mr-1"></i>
						<span></span>
					</a>
            	</div>
			</div>
			<div class="card-body d-md-flex align-items-md-center justify-content-md-between">
				<div class="d-flex align-items-center mb-3 mb-md-0">
					<!-- <div id="tickets-status"></div> -->
                    <?php $wid=rand(200, 400); ?>
                    <img src="https://placeimg.com/<?php echo $wid; ?>/<?php echo $wid; ?>/any" class="img-fluid" width="48" height="48" alt="" data-popup="tooltip" title="Баримтын гарчиг">
					<div class="ml-2">
						<p class="font-weight-semibold mb-0">Зураг</p>
						<span class="badge badge-mark border-success mr-1"></span> <span class="text-muted">33 настай, Эрэгтэй</span>
					</div>
				</div>

				<div class="d-flex align-items-center mb-3 mb-md-0">
					<!-- <a href="javascript:void(0);" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
						<i class="icon-alarm-add"></i>
					</a> -->
                    <?php $wid=rand(200, 400); ?>
                    <img src="https://placeimg.com/<?php echo $wid; ?>/<?php echo $wid; ?>/any" class="img-fluid" width="48" height="48" alt="" data-popup="tooltip" title="Баримтын гарчиг">
					<div class="ml-2">
						<p class="font-weight-semibold mb-0">Програмист</p>
						<span class="badge badge-mark border-success mr-1"></span> <span class="text-muted">Ажилд: 2018.10.12</span>
					</div>
				</div>
				<div class="d-flex align-items-center mb-3 mb-md-0">
					<!-- <a href="javascript:void(0);" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
						<i class="icon-spinner11"></i>
					</a> -->
                    <?php $wid=rand(200, 400); ?>
                    <img src="https://placeimg.com/<?php echo $wid; ?>/<?php echo $wid; ?>/any" class="img-fluid" width="48" height="48" alt="" data-popup="tooltip" title="Баримтын гарчиг">
					<div class="ml-2">
						<p class="font-weight-semibold mb-0">Гэр бүлтэй</p>
						<span class="text-muted">Эхнэр, 2 хүүхэдтэй</span>
					</div>
				</div>
				<div class="d-flex align-items-center mb-3 mb-md-0">
                    <?php $wid=rand(200, 400); ?>
                    <img src="https://placeimg.com/<?php echo $wid; ?>/<?php echo $wid; ?>/any" class="img-fluid" width="48" height="48" alt="" data-popup="tooltip" title="Баримтын гарчиг">
					<div class="ml-2">
						<p class="font-weight-semibold mb-0">Бакалавр</p>
						<span class="text-muted">03.25 21:50</span>
					</div>
				</div>
			</div>
		</div>

		<h2>Баримт бичиг</h2>
		<div class="card card-body">

			<div class="mb-2">Ирсэн бичиг</div>


			<div class="row">
				<div class="col-sm-3">
					<div class="card p-3">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="https://www.imtrecruitment.org.uk/file/image/media/5bb20b3de5260_Evidence_of_founation_competence_signatory_guide_screenshot.JPG" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Тодорхойлолт №548</div>														
								<span class="font-size-sm text-muted ml-auto">378Kb</span>
								<span class="font-size-sm text-muted ml-auto">2018.10.10</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="card p-3">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="https://img.freepik.com/free-vector/illustration-document-icon_53876-28510.jpg?size=626&ext=jpg" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Өргөдөл гаргах нь</div>														
								<span class="font-size-sm text-muted ml-auto">1.2Mb</span>
								<span class="font-size-sm text-muted ml-auto">2018.12.13</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="card p-3">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Баримт бичиг</div>														
								<span class="font-size-sm text-muted ml-auto">1.8Mb</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="card p-3">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Ирсэн бичиг</div>														
								<span class="font-size-sm text-muted ml-auto">2.0Mb</span>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="mb-2 text-danger">Хугацаа дөхсөн бичиг</div>


			<div class="row">
				<div class="col-sm-3">
					<div class="card bg-danger-300">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="http://www.documentmanagementinc.com/files/6414/0266/9406/document.jpg" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Тодорхойлолт хүсэх тухай</div>														
								<span class="font-size-sm ml-auto">378Kb</span>
								<span class="font-size-sm ml-auto">2018.10.10</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="card bg-danger-300">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="http://agk-ks.org/wp-content/uploads/2019/02/Contract-Documents.jpg" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Хариу хүсэх тухай</div>														
								<span class="font-size-sm ml-auto">1.2Mb</span>
								<span class="font-size-sm ml-auto">2018.12.13</span>
							</div>
						</div>
					</div>
				</div>

			</div>


			<div class="mb-2 text-secondary">Сүүлд драфталсан бичиг</div>

			<div class="row">
				<div class="col-sm-3">
					<div class="card bg-secondary">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="https://media.pri.org/s3fs-public/styles/story_main/public/images/2019/04/20190417mueller.jpg?itok=Pn1516Ck" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Тодорхойлолт хүсэх тухай</div>														
								<span class="font-size-sm ml-auto">378Kb</span>
								<span class="font-size-sm ml-auto">2018.10.10</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="card bg-secondary">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="https://thevisualcommunicationguy.com/wp-content/uploads/2016/03/Document-Design3.jpg" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Хариу хүсэх тухай</div>														
								<span class="font-size-sm ml-auto">1.2Mb</span>
								<span class="font-size-sm ml-auto">2018.12.13</span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="card bg-secondary">
						<div class="card-img-actions">
							<img class="card-img img-fluid" src="https://7593905ebe364c1571aac60b-xeclftautua6y.netdna-ssl.com/wp-content/uploads/2016/02/maxresdefault.jpg" alt="">
							<div class="card-img-actions-overlay card-img">
								<a href="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
									<i class="icon-zoomin3"></i>
								</a>

								<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
									<i class="icon-download"></i>
								</a>
							</div>
						</div>

						<div class="card-body">
							<div class="d-flex align-items-start flex-wrap">
								<div class="font-weight-semibold">Хариу хүсэх тухай</div>														
								<span class="font-size-sm ml-auto">1.2Mb</span>
								<span class="font-size-sm ml-auto">2018.12.13</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<h2>Архив</h2>
		<div class="card-body border-top">
			<h6 class="mb-0">2 баримт байна.</h6>

			<ul class="list-inline mb-0">
				<li class="list-inline-item">
					<div class="card bg-light py-2 px-3 mt-3 mb-0">
						<div class="media my-1">
							<div class="mr-3 align-self-center"><i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
							<div class="media-body">
								<div class="font-weight-semibold">Шинэ хуулийн төсөл.pdf</div>

								<ul class="list-inline list-inline-condensed mb-0">
									<li class="list-inline-item text-muted">174 KB</li>
									<li class="list-inline-item"><a href="javascript:void(0);">Харах</a></li>
									<li class="list-inline-item"><a href="javascript:void(0);">Татах</a></li>
								</ul>
							</div>
						</div>
					</div>
				</li>
				<li class="list-inline-item">
					<div class="card bg-light py-2 px-3 mt-3 mb-0">
						<div class="media my-1">
							<div class="mr-3 align-self-center"><i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
							<div class="media-body">
								<div class="font-weight-semibold">Ажилд томилох тухай.pdf</div>

								<ul class="list-inline list-inline-condensed mb-0">
									<li class="list-inline-item text-muted">736 KB</li>
									<li class="list-inline-item"><a href="javascript:void(0);">Харах</a></li>
									<li class="list-inline-item"><a href="javascript:void(0);">Татах</a></li>
								</ul>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>

		<h2>Цаг бүртгэл</h2>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="card p-3">
					<div class="card-body">
						<div class="d-flex">
							Нийт хоцорсон цаг <h3 class="font-weight-semibold mb-0"> 1.5 цаг</h3>
							<div class="list-icons ml-auto">
		                		<a class="list-icons-item" data-action="reload"></a>
		                	</div>
	                	</div>

	                	<div>
							Өнгөрсөн сарын хоцорсон цаг
							<div class="text-muted font-size-sm">2.5 цаг</div>
						</div>
					</div>
					
					<div id="chart_bar_basic"></div>

				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="card bg-indigo-400 has-bg-image">
					<div class="card-body">
						<div class="d-flex">
							Нийт ажилласан цаг <h3 class="font-weight-semibold mb-0"> 49.5 цаг</h3>
							<div class="list-icons ml-auto">
		                		<a class="list-icons-item" data-action="reload"></a>
		                	</div>
	                	</div>

	                	<div>
							Өнгөрсөн сарын ажилласан цаг
							<div class="font-size-sm opacity-75">48 цаг</div>
						</div>
					</div>
					
					<div id="chart_bar_color"></div>
				</div>
			</div>
		</div>


		<div class="card p-3">
			<div class="card-header header-elements-inline">
				<h6 class="card-title font-weight-semibold">Өдөр тутмын цаг бүртгэл</h6>
				<div class="header-elements">
					<div class="list-icons">
						<div class="list-icons-item dropdown">
							<a href="javascript:void(0);" class="list-icons-item caret-0 dropdown-toggle" data-toggle="dropdown">
								<i class="icon-arrow-down12"></i>
							</a>

							<div class="dropdown-menu dropdown-menu-right">
								<a href="javascript:void(0);" class="dropdown-item"><i class="icon-user-lock"></i> Hide user posts</a>
								<a href="javascript:void(0);" class="dropdown-item"><i class="icon-user-block"></i> Block user</a>
								<a href="javascript:void(0);" class="dropdown-item"><i class="icon-user-minus"></i> Unfollow user</a>
								<div class="dropdown-divider"></div>
								<a href="javascript:void(0);" class="dropdown-item"><i class="icon-embed"></i> Embed post</a>
								<a href="javascript:void(0);" class="dropdown-item"><i class="icon-blocked"></i> Report this post</a>
							</div>
						</div>
                	</div>
            	</div>
			</div>
			<div class="card-body">
				<div class="my-schedule"></div>
			</div>
		</div>
		<h2>Интранэт</h2>
		<div class="card p-3">
			<div class="card-header bg-transparent header-elements-inline">
				<h6 class="card-title">Миний имэйл</h6>

				<div class="header-elements">
					Өнөөдрийн шинэ: <span class="badge ml-2 bg-blue"> 5</span>
            	</div>
			</div>
			<div class="bg-light">
				<div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2">
					<div class="text-center d-lg-none w-100">
						<button type="button" class="navbar-toggler w-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-multiple">
							<i class="icon-circle-down2"></i>
						</button>
					</div>

					<div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-multiple">
						<div class="mt-3 mt-lg-0">
							<div class="btn-group">
								<button type="button" class="btn btn-light btn-icon btn-checkbox-all">
									<input type="checkbox" class="form-input-styled" data-fouc>
								</button>

								<button type="button" class="btn btn-light btn-icon dropdown-toggle" data-toggle="dropdown"></button>
								<div class="dropdown-menu">
									<a href="javascript:void(0);" class="dropdown-item">Select all</a>
									<a href="javascript:void(0);" class="dropdown-item">Select read</a>
									<a href="javascript:void(0);" class="dropdown-item">Select unread</a>
									<div class="dropdown-divider"></div>
									<a href="javascript:void(0);" class="dropdown-item">Clear selection</a>
								</div>
							</div>

							<div class="btn-group ml-3 mr-lg-3">
								<button type="button" class="btn btn-light"><i class="icon-pencil7"></i> <span class="d-none d-lg-inline-block ml-2">Шинэ имэйл</span></button>
								<button type="button" class="btn btn-light"><i class="icon-bin"></i> <span class="d-none d-lg-inline-block ml-2">Устгах</span></button>
		                    	<button type="button" class="btn btn-light"><i class="icon-spam"></i> <span class="d-none d-lg-inline-block ml-2">Спам</span></button>
							</div>
						</div>

						<div class="navbar-text ml-lg-auto"><span class="font-weight-semibold">1-50</span> of <span class="font-weight-semibold">528</span></div>

						<div class="ml-lg-3 mb-3 mb-lg-0">
							<div class="btn-group">
								<button type="button" class="btn btn-light btn-icon disabled"><i class="icon-arrow-left7"></i></button>
		                    	<button type="button" class="btn btn-light btn-icon"><i class="icon-arrow-right13"></i></button>
							</div>

							<div class="btn-group ml-3">
								<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"><i class="icon-cog3"></i></button>
								<div class="dropdown-menu dropdown-menu-right">
									<a href="javascript:void(0);" class="dropdown-item">Action</a>
									<a href="javascript:void(0);" class="dropdown-item">Another action</a>
									<a href="javascript:void(0);" class="dropdown-item">Something else here</a>
									<a href="javascript:void(0);" class="dropdown-item">One more line</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-inbox">
					<tbody data-link="row" class="rowlink">
						<tr class="unread">
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="../../../../global_assets/images/brands/spotify.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Spotify</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">On Tower-hill, as you go down &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">To the London docks, you may have seen a crippled beggar (or KEDGER, as the sailors say) holding a painted board before him, representing the tragic scene in which he lost his leg</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								11:09 pm
							</td>
						</tr>

						<tr class="unread">
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<span class="btn bg-warning-400 rounded-circle btn-icon btn-sm">
									<span class="letter-icon"></span>
								</span>
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">James Alexander</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject"><span class="badge bg-success mr-2">Promo</span> There are three whales and three boats &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">And one of the boats (presumed to contain the missing leg in all its original integrity) is being crunched by the jaws of the foremost whale</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								10:21 pm
							</td>
						</tr>

						<tr class="unread">
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-full2 text-warning-300"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Nathan Jacobson</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">Any time these ten years, they tell me, has that man held up &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">That picture, and exhibited that stump to an incredulous world. But the time of his justification has now come. His three whales are as good whales as were ever published in Wapping, at any rate; and his stump</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								8:37 pm
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-full2 text-warning-300"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<span class="btn bg-indigo-400 rounded-circle btn-icon btn-sm">
									<span class="letter-icon"></span>
								</span>
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Margo Baker</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">Throughout the Pacific, and also in Nantucket, and New Bedford &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">and Sag Harbor, you will come across lively sketches of whales and whaling-scenes, graven by the fishermen themselves on Sperm Whale-teeth, or ladies' busks wrought out of the Right Whale-bone</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								4:28 am
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="../../../../global_assets/images/brands/dribbble.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Dribbble</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">The whalemen call the numerous little ingenious contrivances &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">They elaborately carve out of the rough material, in their hours of ocean leisure. Some of them have little boxes of dentistical-looking implements</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								Dec 5
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<span class="btn bg-brown-400 rounded-circle btn-icon btn-sm">
									<span class="letter-icon"></span>
								</span>
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Hanna Dorman</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">Some of them have little boxes of dentistical-looking implements &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">Specially intended for the skrimshandering business. But, in general, they toil with their jack-knives alone; and, with that almost omnipotent tool of the sailor</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								Dec 5
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="../../../../global_assets/images/brands/twitter.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Twitter</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject"><span class="badge bg-indigo-400 mr-2">Order</span> Long exile from Christendom &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">And civilization inevitably restores a man to that condition in which God placed him, i.e. what is called savagery</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								Dec 4
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-full2 text-warning-300"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<span class="btn bg-pink-400 rounded-circle btn-icon btn-sm">
									<span class="letter-icon"></span>
								</span>
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Vanessa Aurelius</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">Your true whale-hunter is as much a savage as an Iroquois &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">I myself am a savage, owning no allegiance but to the King of the Cannibals; and ready at any moment to rebel against him. Now, one of the peculiar characteristics of the savage in his domestic hours</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								Dec 4
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">William Brenson</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">An ancient Hawaiian war-club or spear-paddle &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">In its full multiplicity and elaboration of carving, is as great a trophy of human perseverance as a Latin lexicon. For, with but a bit of broken sea-shell or a shark's tooth</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								Dec 4
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="../../../../global_assets/images/brands/facebook.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Facebook</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">As with the Hawaiian savage, so with the white sailor-savage &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">With the same marvellous patience, and with the same single shark's tooth, of his one poor jack-knife, he will carve you a bit of bone sculpture, not quite as workmanlike</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								Dec 3
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-full2 text-warning-300"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Vicky Barna</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject"><span class="badge bg-pink-400 mr-2">Track</span> Achilles's shield &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">Wooden whales, or whales cut in profile out of the small dark slabs of the noble South Sea war-wood, are frequently met with in the forecastles of American whalers. Some of them are done with much accuracy</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								Dec 2
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="../../../../global_assets/images/brands/youtube.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Youtube</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">At some old gable-roofed country houses &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">You will see brass whales hung by the tail for knockers to the road-side door. When the porter is sleepy, the anvil-headed whale would be best. But these knocking whales are seldom remarkable as faithful essays</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								Nov 30
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/flat/9.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Tony Gurrano</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">On the spires of some old-fashioned churches &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">You will see sheet-iron whales placed there for weather-cocks; but they are so elevated, and besides that are to all intents and purposes so labelled with "HANDS OFF!" you cannot examine them</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								Nov 28
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-empty3 text-muted"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<span class="btn bg-danger-400 rounded-circle btn-icon btn-sm">
									<span class="letter-icon"></span>
								</span>
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Barbara Walden</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">In bony, ribby regions of the earth &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">Where at the base of high broken cliffs masses of rock lie strewn in fantastic groupings upon the plain, you will often discover images as of the petrified forms</span>
							</td>
							<td class="table-inbox-attachment"></td>
							<td class="table-inbox-time">
								Nov 28
							</td>
						</tr>

						<tr>
							<td class="table-inbox-checkbox rowlink-skip">
								<input type="checkbox" class="form-input-styled" data-fouc>
							</td>
							<td class="table-inbox-star rowlink-skip">
								<a href="javascript:void(0);">
									<i class="icon-star-full2 text-warning-300"></i>
								</a>
							</td>
							<td class="table-inbox-image">
								<img src="../../../../global_assets/images/brands/amazon.png" class="rounded-circle" width="32" height="32" alt="">
							</td>
							<td class="table-inbox-name">
								<a href="mail_read.html">
									<div class="letter-icon-title text-default">Amazon</div>
								</a>
							</td>
							<td class="table-inbox-message">
								<div class="table-inbox-subject">Here and there from some lucky point of view &nbsp;-&nbsp;</div>
								<span class="text-muted font-weight-normal">You will catch passing glimpses of the profiles of whales defined along the undulating ridges. But you must be a thorough whaleman, to see these sights; and not only that, but if you wish to return to such a sight again</span>
							</td>
							<td class="table-inbox-attachment">
								<i class="icon-attachment text-muted"></i>
							</td>
							<td class="table-inbox-time">
								Nov 27
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="card card-header bg-transparent header-elements-inline">
			<h6 class="card-title">Миний чат</h6>

			<div class="header-elements">
				Нээгээгүй: <span class="badge ml-2 bg-blue"> 5</span>
        	</div>
		</div>

		<!-- <div>
			<div class="nav-tabs-responsive">
				<ul class="nav nav-tabs nav-tabs-highlight flex-nowrap mb-0">
					<li class="nav-item">
						<a href="#james" class="nav-link p-2 active p-2" data-toggle="tab">
							<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" alt="" class="rounded-circle mr-2" width="20" height="20">
							Дашжид
							<span class="badge badge-mark ml-2 border-danger"></span>
						</a>
					</li>

					<li class="nav-item">
						<a href="#william" class="nav-link p-2" data-toggle="tab">
							<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" alt="" class="rounded-circle mr-2" width="20" height="20">
							Батбаяр
							<span class="badge badge-mark ml-2 border-success"></span>
						</a>
					</li>

					<li class="nav-item">
						<a href="#jared" class="nav-link p-2" data-toggle="tab">
							<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" alt="" class="rounded-circle mr-2" width="20" height="20">
							Азбаяр
							<span class="badge badge-mark ml-2 border-warning"></span>
						</a>
					</li>

					<li class="nav-item">
						<a href="#victoria" class="nav-link p-2" data-toggle="tab">
							<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" alt="" class="rounded-circle mr-2" width="20" height="20">
							Тэмка
							<span class="badge badge-mark ml-2 border-grey-300"></span>
						</a>
					</li>

					<li class="nav-item dropdown ml-md-auto">
						<a href="javascript:void(0);" class="nav-link p-2 dropdown-toggle" data-toggle="dropdown" data-boundary="window"><i class="icon-cog7 mr-2"></i> Options</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#chat-tab3" class="dropdown-item" data-toggle="tab">Dropdown tab</a>
							<a href="#chat-tab4" class="dropdown-item" data-toggle="tab">Another tab</a>
						</div>
					</li>
				</ul>
			</div>

			<div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-0 p-3">
				<div class="tab-pane fade show active" id="james">
					<ul class="media-list media-chat mb-3">
						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
								<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
								<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
								<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media content-divider justify-content-center text-muted mx-0">
							<span class="px-2">Уншаагүй мессэжүүд</span>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
								<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/c0.11.50.50a/p50x50/1601314_786685278037424_3859102602768585640_n.jpg?_nc_cat=101&_nc_oc=AQk4odfmxTcxcRfgDQ3zF87ijpJBpdyp-89M_QR1TSi26l1iazV6kX3JlsA4T-QP7JQ&_nc_ht=scontent.fuln1-1.fna&oh=d0518cf971c64ceb543214d12027e1b3&oe=5DD0B114" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
								<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item"><i class="icon-menu"></i></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/67751566_2293712740958896_7961803501930020864_n.jpg?_nc_cat=101&_nc_oc=AQkBLfQ5qihmz-jZSNrSMgkdYD06-T4vZGQxssYXs3GNKAr3r9aRNDgjOmhDY651Amw&_nc_ht=scontent.fuln1-1.fna&oh=0cfaca89cf3fd9d18ed6af07b6000c3e&oe=5DE79E23" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>
					</ul>

                	<textarea name="enter-message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>

                	<div class="d-flex align-items-center">
                		<div class="list-icons list-icons-extended">
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send photo"><i class="icon-file-picture"></i></a>
                        	<a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send video"><i class="icon-file-video"></i></a>
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send file"><i class="icon-file-plus"></i></a>
                		</div>

                		<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b>Илгээх</button>
                	</div>
				</div>

				<div class="tab-pane fade" id="william">
					<ul class="media-list media-chat media-chat-inverse mb-3">
						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
								<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
								<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
								<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media content-divider justify-content-center text-muted mx-0">
							<span class="px-2">New messages</span>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
								<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
								<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item"><i class="icon-menu"></i></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/11062056_1057419247623035_6768082597433473004_n.jpg?_nc_cat=107&_nc_oc=AQmqqF2M-KGPPEWEU4DhGnKPa-5CABWdyhx0D1_uHa86kQaaW4f8eNX4bCIYkygOn5o&_nc_ht=scontent.fuln1-2.fna&oh=e9ad7ed33365809bb823dd6960edb489&oe=5DE1528D" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>
					</ul>

                	<textarea name="enter-message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>

                	<div class="d-flex align-items-center">
                		<div class="list-icons list-icons-extended">
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send photo"><i class="icon-file-picture"></i></a>
                        	<a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send video"><i class="icon-file-video"></i></a>
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send file"><i class="icon-file-plus"></i></a>
                		</div>

                		<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b>Илгээх</button>
                	</div>
				</div>

				<div class="tab-pane fade" id="jared">
					<ul class="media-list media-chat mb-3">
						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
								<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
								<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
								<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media content-divider justify-content-center text-muted mx-0">
							<span class="px-2">New messages</span>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
								<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
								<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item"><i class="icon-menu"></i></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-1.fna.fbcdn.net/v/t1.0-1/p50x50/56506577_2169692490010283_1854935987902218240_n.jpg?_nc_cat=101&_nc_oc=AQmupCS5JIsUB-BF5AjqRkXtY5hd8dxQlglvixWJ2NedCj4RelCjNoBAzuEFcco3pa0&_nc_ht=scontent.fuln1-1.fna&oh=a1a5f4715ebd37e0dd1cf5cfc6c74052&oe=5DD78DF9" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>
					</ul>

                	<textarea name="enter-message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>

                	<div class="d-flex align-items-center">
                		<div class="list-icons list-icons-extended">
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send photo"><i class="icon-file-picture"></i></a>
                        	<a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send video"><i class="icon-file-video"></i></a>
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send file"><i class="icon-file-plus"></i></a>
                		</div>

                		<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b>Илгээх</button>
                	</div>
				</div>

				<div class="tab-pane fade" id="victoria">
					<ul class="media-list media-chat media-chat-inverse mb-3">
						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Crud reran and while much withdrew ardent much crab hugely met dizzily that more jeez gent equivalent unsafely far one hesitant so therefore.</div>
								<div class="font-size-sm text-muted mt-2">Tue, 10:28 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Far squid and that hello fidgeted and when. As this oh darn but slapped casually husky sheared that cardinal hugely one and some unnecessary factiously hedgehog a feeling one rudely much</div>
								<div class="font-size-sm text-muted mt-2">Mon, 10:24 am <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Tolerantly some understood this stubbornly after snarlingly frog far added insect into snorted more auspiciously heedless drunkenly jeez foolhardy oh.</div>
								<div class="font-size-sm text-muted mt-2">Wed, 4:20 pm <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media content-divider justify-content-center text-muted mx-0">
							<span class="px-2">New messages</span>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item">Satisfactorily strenuously while sleazily dear frustratingly insect menially some shook far sardonic badger telepathic much jeepers immature much hey.</div>
								<div class="font-size-sm text-muted mt-2">2 hours ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>

						<li class="media">
							<div class="mr-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>

							<div class="media-body">
								<div class="media-chat-item">Grunted smirked and grew less but rewound much despite and impressive via alongside out and gosh easy manatee dear ineffective yikes.</div>
								<div class="font-size-sm text-muted mt-2">13 minutes ago <a href="javascript:void(0);"><i class="icon-pin-alt ml-2 text-muted"></i></a></div>
							</div>
						</li>

						<li class="media media-chat-item-reverse">
							<div class="media-body">
								<div class="media-chat-item"><i class="icon-menu"></i></div>
							</div>

							<div class="ml-3">
								<a href="javascript:void(0);">
									<img src="https://scontent.fuln1-2.fna.fbcdn.net/v/t1.0-1/p50x50/36322696_10155835308737476_2352915678979162112_n.jpg?_nc_cat=107&_nc_oc=AQlmh0rjNOmQYxJ1-XznLFu_crLI1Q5ILfLk9PGrwWwyFynxraGmMEdezVDCg0yZnHA&_nc_ht=scontent.fuln1-2.fna&oh=43e91e0157dbc2a303e7b4b6506bc135&oe=5E14F58C" class="rounded-circle" width="40" height="40" alt="">
								</a>
							</div>
						</li>
					</ul>

                	<textarea name="enter-message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>

                	<div class="d-flex align-items-center">
                		<div class="list-icons list-icons-extended">
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send photo"><i class="icon-file-picture"></i></a>
                        	<a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send video"><i class="icon-file-video"></i></a>
                            <a href="javascript:void(0);" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send file"><i class="icon-file-plus"></i></a>
                		</div>

                		<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b>Илгээх</button>
                	</div>
				</div>
			</div>
		</div> -->
	</div>
</div>
<div class="sidebar sidebar-light sidebar-right sidebar-expand-md">
<div class="sidebar-mobile-toggler text-center">
    <a href="javascript:void(0);" class="sidebar-mobile-expand">
        <i class="icon-screen-full"></i>
        <i class="icon-screen-normal"></i>
    </a>
    <span class="font-weight-semibold">Right sidebar</span>
    <a href="javascript:void(0);" class="sidebar-mobile-right-toggle">
        <i class="icon-arrow-right8"></i>
    </a>
</div>
<div class="sidebar-content">



    <div class="card p-3">
        <div class="card-header bg-transparent header-elements-inline">
            <span class="text-uppercase font-size-sm font-weight-semibold">Статистик</span>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>


        <div class="card p-3">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="font-weight-semibold mb-0"><span class="small">Нийт баримт бичиг:</span> 1525</h3>
                    <!-- <span class="badge bg-success-400 badge-pill align-self-center ml-auto">+53,6%</span> -->
                </div>
            </div>

            <div class="container-fluid">
                <div id="chart_bar_basic"></div>
            </div>
        </div>



    </div>
    <div class="card p-3">
        <div class="card-header bg-transparent header-elements-inline">
            <span class="text-uppercase font-size-sm font-weight-semibold">Прогресс</span>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                    <div class="d-flex align-items-center mb-1">Шийдвэрлэх ёстой<span class="text-muted ml-auto">50%</span></div>
                    <div class="progress" style="height: 0.125rem;">
                        <div class="progress-bar bg-danger" style="width: 50%">
                            <span class="sr-only">50% Complete</span>
                        </div>
                    </div>
                </li>

                <li class="mb-3">
                    <div class="d-flex align-items-center mb-1">Хариулах ёстой <span class="text-muted ml-auto">70%</span></div>
                    <div class="progress" style="height: 0.125rem;">
                        <div class="progress-bar bg-warning-400" style="width: 70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                </li>

                <li class="mb-3">
                    <div class="d-flex align-items-center mb-1">Хянах ёстой <span class="text-muted ml-auto">80%</span></div>
                    <div class="progress" style="height: 0.125rem;">
                        <div class="progress-bar bg-success-400" style="width: 80%">
                            <span class="sr-only">80% Complete</span>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="d-flex align-items-center mb-1">Шилжүүлэх ёстой <span class="text-muted ml-auto">60%</span></div>
                    <div class="progress" style="height: 0.125rem;">
                        <div class="progress-bar bg-grey-400" style="width: 60%">
                            <span class="sr-only">60% Complete</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-header bg-transparent header-elements-inline">
            <span class="text-uppercase font-size-sm font-weight-semibold"><i class="icon-task mr-1 small"></i> Үйлдэл</span>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <li class="nav-item">
                    <a href="#?" class="nav-link p-2"><i class="icon-cog3"></i> Шилжүүлэх</a>
                </li>

                <li class="nav-item">
                    <a href="#?" class="nav-link p-2"><i class="icon-cog3"></i> Шийдвэрлэх</a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link p-2"><i class="icon-portfolio"></i> Явцын тэмдэглэл</a>
                </li>
            </ul>
        </div>

    </div>
    <div class="card p-3">
        <div class="card-header bg-transparent header-elements-inline">
            <span class="text-uppercase font-size-sm font-weight-semibold">Form example</span>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
    
        <div class="card-body">
            <form action="#">
                <div class="form-group">
                    <label>Your name:</label>
                    <input type="text" class="form-control" placeholder="Username">
                </div>
    
                <div class="form-group">
                    <label>Your password:</label>
                    <input type="password" class="form-control" placeholder="Password">
                </div>
    
                <div class="form-group">
                    <label>Your message:</label>
                    <textarea rows="3" cols="3" class="form-control" placeholder="Default textarea"></textarea>
                </div>
    
                <div class="row">
                    <div class="col-6">
                        <button type="reset" class="btn btn-danger btn-block">Reset</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-info btn-block">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
	</div>
	



<!-- Footer -->
<div class="navbar navbar-expand-md navbar-light alpha-slate p-1 ">

	<!-- Browser жижгэрэх үед гарах цонх -->
	<div class="text-center d-md-none w-100 ">
		<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
			<i class="icon-unfold mr-2"></i>
			Footer
		</button>
	</div>

	<div class="navbar-collapse collapse" id="navbar-footer">

		<div>
			<ul class="list-inline mb-0 pr-2">
				<li class="list-inline-item mr-2">
					Харж буй
				</li>
				<li class="list-inline-item mr-2">
					<img src="https://www.placecage.com/220/220" class="rounded-circle" width="32" height="32" alt="" data-popup="tooltip" title="Jargal Tumurbaatar">
				</li>
				<li class="list-inline-item mr-2">
					<img src="https://www.placecage.com/221/221" class="rounded-circle" width="32" height="32" alt="" data-popup="tooltip" title="Ganbat Gansukh">
					<span class="badge badge-mark badge-float border-danger mt-1 mr-1" data-popup="tooltip" title="Засах эрхтэй"></span>
				</li>
				

			</ul>
		</div>
	

		<div class="navbar-nav ml-lg-auto">
			<ul class="list-inline mb-0 pr-2">
				<li class="list-inline-item mr-2">
					Мэдээлэл
				</li>
				<!-- <li class="list-inline-item mr-2" data-popup="popover" data-placement="top" data-trigger="hover" title="Overdrew_scowled.doc" data-content="Size: 1.2Mb • 2019.03.28">
					<i class="icon-file-word icon-2x text-primary-300 top-0"></i>
				</li>
				
				<li class="list-inline-item mr-2" data-popup="popover" data-placement="top" data-trigger="hover" title="Overdrew_scowled.doc" data-content="Size: 1.2Mb • 2019.03.28">
					<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
				</li> -->


			</ul>
		</div>
	</div>
</div>
<?php

//Санамсаргүй текст буцаах функц
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



?>
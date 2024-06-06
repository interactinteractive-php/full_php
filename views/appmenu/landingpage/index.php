<?php
    echo '<style type="text/css">'.file_get_contents("views/appmenu/landingpage/tailwind.min.css").'</style>';
?>
<style type="text/css">
    .shadow-citizen {
        box-shadow: 0 0 #0000, 0 0 #0000, 0px 20px 27px 0px rgba(0, 0, 0, 0.05);
    }
    .p-4 {
        padding: 1rem !important;
    }    
    .p-3 {
        padding: .75rem !important;
    }   
    .mb-5 {
      margin-bottom: 1.25rem !important;
    }
    .page-content > .content-wrapper > .content {
        padding-bottom: 0 !important;
    }     
    .bg-ssoSecond {
        background-color: rgba(67, 56, 202, 1);
    }   
    .text-ssoSecond {
        color: rgba(67, 56, 202, 1);
    }     
    .hover\:bg-ssoSecond:hover {
        background-color: rgba(67, 56, 202, 1) !important;
    }    
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, #4338CA, #4338ca75, rgba(67, 56, 202, 0));
    }   
    .hover\:text-white:hover {
        color: rgba(255, 255, 255, 1);
    }
    .hover\:text-white:hover i {
        color: #fff;
    }
    .hover\:from-sso:hover {
        background-color: #B2E392 !important;
    }    
    .cloud-font-color-black {
        color: #333;
    }
    div.datepicker .table-sm {
        width:100%;
        height: 500px;
    }    
    div.datepicker .table-sm td, div.datepicker .table-sm th {
        font-size: 16px;
    }    
    div.datepicker {
        background-color: #F7F8FF;
        padding-top: 30px;
        margin-top: 20px;
        padding-bottom: 30px;
        border-radius: 1rem !important;
        margin-right: 20px;
    }   
    .cloud-grid-icon i {
        color:#B2E392
    } 
    .cloud-grid-icon {
        text-align: center;
        margin-top:10px;
    }
    .cloud-modulelist-tab {
        margin-top: 20px;
    } 
    .cloud-modulelist-tab li {
        display: inline-block;
        font-size: 14px;
        color: #585858;        
        margin-right: 25px;
        cursor: pointer;
        font-weight: bold;
    }
    .cloud-modulelist-tab li.active {
        color: #fff;        
        border-bottom: 2px solid #fff;
    }
    .cloud-badge {
        border-radius: 7px;
        font-size:14px;
        padding: 6px 13px 6px 13px;
        border-color:#D1D5DB !important;        
        color: #67748E !important;
        font-weight: normal;
        cursor: pointer;
    }
    .cloud-badge.active {
        border-color:#699BF7 !important;        
        color: #699BF7 !important
    }
</style>

<main class="h-full w-full" style="background-color: rgba(248, 249, 250, 1);margin-top: -8px;padding-top: 20px;">
  <section class="grid grid-cols-12 w-full h-full gap-x-6">
    <section class="mb-1 flex col-span-12">
      <section class="mb-6 flex-grow w-full h-full">
        <div class="bg-white p-4">
            <div style="font-size:20px;color:#585858;margin-bottom:20px">Кассын нярав</div>
            <section class="mb-1 flex col-span-12 items-center">
            <section data-sectioncode="3" class="mb-5 col-span-12 flex-shrink-0 mr-3 cursor-pointer">
                <div style="width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);" class="">
                <i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i>
                </div>
            </section>
            <section data-sectioncode="3" class="mb-5 col-span-12 w-full flex-grow">
                <div class="w-full h-full false" style="grid-gap:2%">
                <div class=" ">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 ">
                    <?php 
                    if ($this->cardList) {
                    foreach($this->cardList as $row) { ?>
                        <div class="rounded-xl" style="background-color:#496ABA;width: 310px;height: 200px;">
                            <div class="flex justify-between w-full h-full">
                                <div class="p-3">
                                    <div class="flex">
                                        <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#C0DCFF;background-image:url(https://randomuser.me/api/portraits/men/51.jpg);background-position: center;border: 2px solid #fff;">
                                        </div>
                                        <div class="ml-3">
                                            <span class="text-sm lg:text-base text-base text-gray-700 block font-bold" style="color:#fff;font-size:14px">Төгрөгийн касс</span>
                                            <span class="" style="color:#fff">
                                                <span class="line-clamp-0">Б.Батдорж</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-white" style="margin-top: 75px;">
                                        <div>
                                            Дансны мэдээлэл
                                        </div>
                                        <div>
                                            100101
                                        </div>
                                        <div>
                                            Төгрөгийн касс
                                        </div>
                                    </div>
                                </div>
                                <div style="background-color: #637FC2;width: 110px;border-top-left-radius: 0;border-bottom-left-radius: 0;" class="h-full p-3 rounded-xl">
                                    <div style="text-align: right;font-size: 10px;font-weight: bold;color: #fff;text-transform: uppercase;">mnt</div>
                                    <div class="font-bold p-1 mt-5" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                        Орлого
                                    </div>
                                    <div class="font-bold p-1 mt-2" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                        Зарлага
                                    </div>
                                    <div class="font-bold p-1 mt-2" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                        ...
                                    </div>
                                    <div class="absolute" style="
                                        text-align: center;
                                        margin-top: -95px;
                                        margin-left: -100px;
                                        opacity: .06;
                                        color: #fff;
                                        border: 10px solid #fff;
                                        padding: 10px;
                                        border-radius: 55px;
                                        height: 115px;
                                        width: 115px;">
                                        <i class="far fa-dollar-sign" style="font-size:80px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    } ?>
                    </div>
                </div>
                </div>
            </section>
            <section data-sectioncode="3" class="mb-5 col-span-12 flex-shrink-0 ml-3 cursor-pointer">
                <div style="width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);" class="">
                <i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i>
                </div>
            </section>
            </section>
        </div>
        <div class="p-4">
            <div style="font-size:20px;color:#585858;margin-bottom:20px">Нийтлэг процесс</div>
            <section class="mb-1 flex col-span-12 items-center">
            <section data-sectioncode="3" class="mb-5 col-span-12 flex-shrink-0 mr-3 cursor-pointer">
                <div style="width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center" class="">
                <i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i>
                </div>
            </section>
            <section data-sectioncode="3" class="mb-5 col-span-12 w-full flex-grow">
                <div class="w-full h-full false" style="grid-gap:2%">
                <div class=" ">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 ">

                    <?php 
                    if ($this->cardList) {
                    foreach($this->cardList as $row) { ?>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4">
                        <div class="flex items-center justify-between w-full sm:w-full">
                            <div class="flex items-center">
                            <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#C0DCFF">
                                <i style="color:#3975B5" class="<?php echo $row["icon"] ?> text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm lg:text-base text-base text-gray-700 block" style="color:#67748E;font-size:12px"><?php echo Str::formatMoney($row["mainnumber"], true) ?></span>
                                <span class="false  text-sm text-gray-600 font-bold block">
                                <span class="line-clamp-0"><?php echo $row["title"] ?></span>
                                </span>
                            </div>
                            </div>
                            <!-- <div>
                            <div class="flex items-center ml-2">
                                <i class="far <?php echo Str::upper($row["status"]) == "UP" ? "fa-angle-up" : "fa-angle-down" ?> text-xs tracking-wide font-bold leading-normal pl-1 false hover:text-sso "></i>
                                <span class="false  text-xs tracking-wide font-bold leading-normal pl-1">
                                <span class="line-clamp-0"><?php echo $row["subnumber"] ?></span>
                                </span>
                            </div>
                            </div> -->
                        </div>
                        </div>
                    <?php }
                    } ?>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 mt-3">

                    <?php 
                    if ($this->cardList) {
                    foreach($this->cardList as $row) { ?>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4">
                        <div class="flex items-center justify-between w-full sm:w-full">
                            <div class="flex items-center">
                            <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#C0DCFF">
                                <i style="color:#3975B5" class="<?php echo $row["icon"] ?> text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm lg:text-base text-base text-gray-700 block" style="color:#67748E;font-size:12px"><?php echo Str::formatMoney($row["mainnumber"], true) ?></span>
                                <span class="false  text-sm text-gray-600 font-bold block">
                                <span class="line-clamp-0"><?php echo $row["title"] ?></span>
                                </span>
                            </div>
                            </div>
                            <!-- <div>
                            <div class="flex items-center ml-2">
                                <i class="far <?php echo Str::upper($row["status"]) == "UP" ? "fa-angle-up" : "fa-angle-down" ?> text-xs tracking-wide font-bold leading-normal pl-1 false hover:text-sso "></i>
                                <span class="false  text-xs tracking-wide font-bold leading-normal pl-1">
                                <span class="line-clamp-0"><?php echo $row["subnumber"] ?></span>
                                </span>
                            </div>
                            </div> -->
                        </div>
                        </div>
                    <?php }
                    } ?>
                    </div>
                </div>
                </div>
            </section>
            <section data-sectioncode="3" class="mb-5 col-span-12 flex-shrink-0 ml-3 cursor-pointer">
                <div style="width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center" class="">
                <i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i>
                </div>
            </section>
            </section>
        </div>
        <section data-sectioncode="4" class="mb-5 col-span-12 hidden">
          <div class="w-full h-full grid grid-cols-12 gap-5" style="">
            <div class="w-full h-full bg-white p-4 shadow-citizen overflow-hidden rounded-xl col-span-8">
              <div class="grid grid-cols-1 gap-x-0 gap-y-4 md:grid-cols-2  md:gap-x-4 md:gap-y-0 w-full h-full">
                <div class="w-full h-full d-flex flex-col">
                  <div class="text-xl md:text-2xl font-bold leading-6 font-bold mt-6">
                    <span class="line-clamp-0">Хуримтлалыг зуршил болгоё</span>
                  </div>
                  <span class="false  text-sm xl:text-base text-gray-500 block mt-3">
                    <span class="line-clamp-0">Илүү сайхан итгэл дүүрэн ирээдүйн төлөө яг одооноос хуримтлуулцгаая!.</span>
                  </span>
                  <button class="flex items-center justify-center transition-colors duration-300 focus:shadow-outline text-white border-0 py-2 px-4 bg-ssoSecond hover:bg-ssoSecond-dark rounded-xl px-7" style="background-color:#585858;/* margin-bottom: 8px; *//* position: absolute; *//* bottom: 0px; *//* margin-bottom: auto; */align-self: flex-start;margin-top: auto;margin-bottom: 40px;">
                    <span>Данс нээх</span>
                  </button>
                </div>
                <div class="w-full h-full relative ">
                  <img class="mt-2 relative z-10 rounded w-full" src="https://res.cloudinary.com/dzih5nqhg/image/upload/v1631698637/cloud/item/Group_383_wu3dvn.png" alt="Glasses">
                </div>
              </div>
            </div>
            <div class=" col-span-4">
              <div class="relative w-full h-full rounded-xl">
                <img src="https://i.ibb.co/SBpL1cK/pexels-aleksandar-pasaric-325185-1.png" alt="city view" class="w-full h-full rounded-xl object-center object-fill absolute block ">
                <div class="relative bg-gradient-to-r from-ssoSecond to-transparent  w-full h-full z-40 top-0 p-4 rounded-xl d-flex flex-col">
                  <div>
                    <div class="false false text-xl md:text-2xl font-bold text-white mt-6">
                      <span class="line-clamp-0">Хуримтлалыг зуршил болгоё</span>
                    </div>
                    <span class="false  text-white block mt-3">
                      <span class="line-clamp-0">Илүү сайхан итгэл дүүрэн ирээдүйн төлөө яг одооноос хуримтлуулцгаая!</span>
                    </span>
                  </div>
                  <button class="flex items-center justify-center transition-colors duration-300 focus:shadow-outline text-ssoSecond border-0 py-2 px-4 bg-white hover:bg-ssoSecond hover:text-white rounded-xl px-7 shadow-lg flex" style="margin-top: 291px;position: absolute;">
                    <span>Илүү ихийг</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section data-sectioncode="5" class="mb-5 col-span-12 hidden">
          <?php
            $moduleGroup = Arr::groupByArray($this->moduleList, "categoryname");
          ?>
          <div class="w-full h-full false gap-5" style="">
            <div class="w-full h-full p-4 shadow-citizen overflow-hidden rounded-xl col-span-12 bg-cover bg-center" style="background-image: url(&quot;https://res.cloudinary.com/dzih5nqhg/image/upload/v1631868952/cloud/icons/989898_uyttkn.jpg&quot;);">
              <div class="w-full flex flex-col justify-center">
                <div class="false false text-lg leading-6 font-bold text-gray-700 undefined">
                  <span class="line-clamp-2">Миний модулиуд</span>
                </div>
                <div>
                    <ul class="cloud-modulelist-tab">
                      <?php 
                      $i = 0;
                      foreach ($moduleGroup as $key => $row) { ?>
                        <li class="<?php echo !$i ? "active" : "" ?>" data-class="<?php echo "class_".$key; ?>"><?php echo $key ?></li>
                      <?php 
                        $i++;
                      } ?>
                    </ul>
                </div>
                <div class="grid grid-cols-10 gap-5 py-3 cloud-modules">
                  <?php 
                  $i = 0;
                  foreach ($moduleGroup as $key => $row) { ?>
                    <?php foreach ($row["rows"] as $row2) { ?>
                    <div class="<?php echo $i ? "hidden" : ""; echo " class_".$key; ?> w-full h-full bg-white rounded-xl p-4 mr-3 text-xs hover:text-white group cursor-pointer bg-gradient-to-br from-white to-white hover:from-sso hover:to-sso-gradientfinish items-center cloud-font-color-black text-center">
                      <div class="mb-6 mt-3 cloud-grid-icon" style="background-color:#F0F9E9;display: inline-block;padding: 15px;border-radius: 50px;width: 60px;height: 60px;">
                        <i class="<?php echo $row2["icon"] ?> false hover:text-sso " style="font-size: 28px;"></i>
                      </div>
                      <div class="false false text-sm font-bold mb-2">
                        <span class="line-clamp-0"><?php echo $row2["title"] ?></span>
                      </div>
                    </div>
                  <?php } ?>
                  <?php 
                    $i++;
                  } ?>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section data-sectioncode="6" class="mb-5 col-span-12 hidden">
          <div class="w-full h-full grid grid-cols-12 shadow-citizen rounded-xl bg-white gap-5" style="">
            <div class="w-full col-span-12 lg:col-span-8">
                <div class="px-4 md:px-10 pt-4 bg-white rounded-xl">
                    <div class="sm:flex items-center justify-between">
                        <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-xl font-bold leading-normal text-gray-800">Миний ажил</p>
                    </div>
                </div>
                <div class="bg-white pb-2 overflow-y-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr tabindex="0" class="focus:outline-none h-16 w-full text-sm leading-none text-gray-800">
                                <th class="font-normal text-left pl-4" style="color:#9FA2B4;font-weight:700">Ажлын нэр</th>
                                <th class="font-normal text-left pl-12" style="color:#9FA2B4;font-weight:700">Явц</th>
                                <th class="font-normal text-left pl-12" style="color:#9FA2B4;font-weight:700">Ажлын тоо</th>
                                <th class="font-normal text-left pl-20" style="color:#9FA2B4;font-weight:700">Төсөв</th>
                                <th class="font-normal text-left pl-20" style="color:#9FA2B4;font-weight:700">Огноо</th>
                                <th class="font-normal text-left pl-16" style="color:#9FA2B4;font-weight:700">Гишүүд</th>
                            </tr>
                        </thead>
                        <tbody class="w-full">
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(3).png" alt="backend services" />
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Backend Services</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Hoeger - Hirthe</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">94%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-24 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(4).png" alt="UI design" />
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">UI Design</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Batz - Yundt</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">81%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-20 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(5).png" alt="UX stradegy"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">UX Strategy</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Erdman Group</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">37%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-14 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(6).png" alt="Website Development"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Website Development</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Dickens - Pacocha</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">58%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-16 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(7).png" alt="Mobile App Development"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Mobile App Development</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">O'Kon Inc</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">42%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-12 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>         
            <div class="w-full col-span-12 lg:col-span-4 pb-3">
                <div id="datepicker"></div>
            </div>             
          </div>
        </section>
        <section data-sectioncode="6" class="mb-5 col-span-12 mt-3">
          <div class="w-full h-full grid grid-cols-12 bg-white gap-5" style="">
            <div class="w-full col-span-12 lg:col-span-12">
                <div class="px-3 md:px-10 pt-3 bg-white">
                    <div class="sm:flex items-center justify-between">
                        <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-xl leading-normal text-gray-800" style="color:#585858;font-size: 20px;">Баримтын жагсаалт</p>
                    </div>
                </div>
                <div class="bg-white pb-2 overflow-y-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr tabindex="0" class="focus:outline-none h-16 w-full text-sm leading-none text-gray-800">
                                <th class="font-normal text-left pl-3" style="color:#9FA2B4;font-weight:700">Ажлын нэр</th>
                                <th class="font-normal text-left pl-12" style="color:#9FA2B4;font-weight:700">Явц</th>
                                <th class="font-normal text-left pl-12" style="color:#9FA2B4;font-weight:700">Ажлын тоо</th>
                                <th class="font-normal text-left pl-20" style="color:#9FA2B4;font-weight:700">Төсөв</th>
                                <th class="font-normal text-left pl-20" style="color:#9FA2B4;font-weight:700">Огноо</th>
                                <th class="font-normal text-left pl-16" style="color:#9FA2B4;font-weight:700">Гишүүд</th>
                            </tr>
                        </thead>
                        <tbody class="w-full">
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-3 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(3).png" alt="backend services" />
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Backend Services</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Hoeger - Hirthe</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">94%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-24 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-3 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(4).png" alt="UI design" />
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">UI Design</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Batz - Yundt</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">81%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-20 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-3 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(5).png" alt="UX stradegy"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">UX Strategy</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Erdman Group</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">37%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-14 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-3 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(6).png" alt="Website Development"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Website Development</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Dickens - Pacocha</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">58%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-16 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-3 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(7).png" alt="Mobile App Development"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Mobile App Development</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">O'Kon Inc</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">42%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-12 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>                 
          </div>
        </section>
        <section data-sectioncode="3" class="mb-5 col-span-12 px-3">
          <div style="font-size:20px;color:#585858;margin-bottom:20px">Хяналтын самбар</div>          
          <div class="w-full h-full false" style="grid-gap:2%">
            <div class=" ">
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 ">

              <?php 
                if ($this->cardList) {
                foreach($this->cardList as $row) { ?>
                  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-citizen p-4">
                    <div class="flex items-center justify-between w-full sm:w-full">
                      <div class="flex items-center">
                        <div class="p-3 rounded-xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:<?php echo $row["color"] ?>">
                          <i style="color:<?php echo $row["fontcolor"] ?>" class="<?php echo $row["icon"] ?> text-xl false hover:text-sso "></i>
                        </div>
                        <div class="ml-3">
                          <span class="text-sm lg:text-base text-base text-gray-700 font-bold block"><?php echo Str::formatMoney($row["mainnumber"], true) ?></span>
                          <span class="false  text-sm text-gray-600 font-semibold block" style="color:#67748E">
                            <span class="line-clamp-0"><?php echo $row["title"] ?></span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php }
                } ?>
              </div>
            </div>
          </div>
        </section>     
        <section data-sectioncode="6" class="mb-5 col-span-12 mt-3 px-3">
          <div class="w-full h-full grid grid-cols-12 gap-5" style="">
            <div class="w-full col-span-6 rounded-xl bg-white shadow-citizen">
              <div style="font-size:20px;color:#585858;margin-bottom:5px;margin-top: 19px;margin-left: 15px;">Борлуулалт</div>          
              <div class="p-2" style="height:470px">
                <canvas id="myChart"></canvas>
              </div>
            </div>
            <div class="w-full col-span-6 shadow-citizen bg-white rounded-xl">
                <div class="px-4 md:px-10 pt-3">
                    <div class="sm:flex items-center justify-between">
                        <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-xl leading-normal text-gray-800" style="color:#585858;font-size: 20px;">Баримтын жагсаалт</p>
                    </div>
                </div>
                <div class="bg-white pb-2 overflow-y-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr tabindex="0" class="focus:outline-none h-16 w-full text-sm leading-none text-gray-800">
                                <th class="font-normal text-left pl-4" style="color:#9FA2B4;font-weight:700">Ажлын нэр</th>
                                <th class="font-normal text-left pl-12" style="color:#9FA2B4;font-weight:700">Явц</th>
                                <th class="font-normal text-left pl-12" style="color:#9FA2B4;font-weight:700">Ажлын тоо</th>
                                <th class="font-normal text-left pl-20" style="color:#9FA2B4;font-weight:700">Төсөв</th>
                                <th class="font-normal text-left pl-20" style="color:#9FA2B4;font-weight:700">Огноо</th>
                                <th class="font-normal text-left pl-16" style="color:#9FA2B4;font-weight:700">Гишүүд</th>
                            </tr>
                        </thead>
                        <tbody class="w-full">
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(3).png" alt="backend services" />
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Backend Services</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Hoeger - Hirthe</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">94%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-24 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(4).png" alt="UI design" />
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">UI Design</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Batz - Yundt</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">81%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-20 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(5).png" alt="UX stradegy"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">UX Strategy</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Erdman Group</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">37%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-14 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(6).png" alt="Website Development"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Website Development</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">Dickens - Pacocha</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">58%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-16 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr tabindex="0" class="focus:outline-none h-20 text-sm leading-none text-gray-800 border-b border-t bg-white hover:bg-gray-100 border-gray-100">
                                <td class="pl-4 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10">
                                            <img class="w-full h-full rounded-xl" src="https://cdn.tuk.dev/assets/templates/olympus/projects(7).png" alt="Mobile App Development"/>
                                        </div>
                                        <div class="pl-4">
                                            <p class="font-medium">Mobile App Development</p>
                                            <p class="text-xs leading-3 text-gray-600 pt-2">O'Kon Inc</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="text-sm font-medium leading-none text-gray-800">42%</p>
                                    <div class="w-24 h-3 bg-gray-100 rounded-full mt-2">
                                        <div class="w-12 h-3 bg-green-progress rounded-full"></div>
                                    </div>
                                </td>
                                <td class="pl-12">
                                    <p class="font-medium">32/47</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">5 tasks pending</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">$13,000</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">$4,200 left</p>
                                </td>
                                <td class="pl-20">
                                    <p class="font-medium">22.12.21</p>
                                    <p class="text-xs leading-3 text-gray-600 mt-2">34 days</p>
                                </td>
                                <td class="pl-16">
                                    <div class="flex items-center">
                                        <img class="shadow-md w-8 h-8 rounded-full" src="https://cdn.tuk.dev/assets/templates/olympus/projects(8).png" alt="collaborator 1" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(9).png" alt="collaborator 2" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(10).png" alt="collaborator 3" />
                                        <img class="shadow-md w-8 h-8 rounded-full -ml-2" src="https://cdn.tuk.dev/assets/templates/olympus/projects(11).png" alt="collaborator 4" />
                                    </div>
                                </td>
                                <td class="px-7 2xl:px-0">
                                    <button onclick="dropdownFunction(this)" class="focus:ring-2 rounded-md focus:outline-none ml-7" role="button" aria-label="options">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.16667 10.8334C4.62691 10.8334 5 10.4603 5 10.0001C5 9.53984 4.62691 9.16675 4.16667 9.16675C3.70643 9.16675 3.33334 9.53984 3.33334 10.0001C3.33334 10.4603 3.70643 10.8334 4.16667 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 10.8334C10.4602 10.8334 10.8333 10.4603 10.8333 10.0001C10.8333 9.53984 10.4602 9.16675 10 9.16675C9.53976 9.16675 9.16666 9.53984 9.16666 10.0001C9.16666 10.4603 9.53976 10.8334 10 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M15.8333 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8333 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8333 10.8334Z" stroke="#A1A1AA" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-content bg-white shadow w-24 absolute z-30 right-0 mr-6 hidden">
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Edit</p>
                                        </div>
                                        <div tabindex="0" class="focus:outline-none focus:text-indigo-600 text-xs w-full hover:bg-indigo-700 py-4 px-4 cursor-pointer hover:text-white">
                                            <p>Delete</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>                 
          </div>
        </section>           
        <section data-sectioncode="7" class="mb-5 col-span-12 px-3">
          <div class="w-full h-full grid grid-cols-12 gap-5" style="">
            <div class="w-full h-full bg-white p-4 shadow-citizen overflow-hidden rounded-xl col-span-8">
              <div style="font-size:20px;color:#585858;">Шинэ мэдээ</div>
              <div class="mt-3">
                <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge active" style="">Борлуулалт</span>
                <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Маркетинг</span>
                <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Хүний нөөц</span>
                <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Санхүү</span>
              </div>
              <div class="grid grid-cols-3 gap-5 pt-3 pb-2">
                <?php foreach ($this->newsList as $row) { ?>
                <div>
                  <div class=" w-full relative bg-white dark:bg-gray-800">
                    <img src="<?php echo $row["photo"] ?>" class="w-full" alt="protest" style="border-top-left-radius: 1rem;border-top-right-radius: 1rem;">
                    <div class="text-gray-400 font-normal mt-2.5 text-sm px-2">
                      <?php echo Date::formatter($row["createddate"]) ?>
                    </div>
                    <div class="py-2 px-2">
                      <p class="text-lg font-bold text-gray-800 overflow-ellipsis overflow-hidden" style="height: 50px;line-height: 1.3;"><?php echo $row["name"] ?></p>
                      <p class="text-sm leading-5 text-gray-400 pt-2.5 overflow-ellipsis overflow-hidden"><?php echo $row["text"] ?></p>
                    </div>
                    <!-- <div class="border-t-2 mt-4 border-gray-200">
                      <div class="px-6 py-4 flex items-center justify-between">
                        <p class="text-sm leading-4 text-indigo-700">Learn more</p>
                        <div class="flex items-center"></div>
                      </div>
                    </div> -->
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="w-full h-full bg-white p-4 shadow-citizen overflow-hidden rounded-xl col-span-4">
              <div style="font-size:20px;color:#585858;">Санал асуулга</div>
              <div class="mt-3 mb-3">
                    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge active" style="">Борлуулалт</span>
                    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Маркетинг</span>
                    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Хүний нөөц</span>
                    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Санхүү</span>
                </div>                 
              <div>
                <span class="false  text-sm text-gray-400 block">
                  <span class="line-clamp-0">Борлуулалтийг нэмэгдүүлэх хамгийн үр дүнтэй арга юу вэ?</span>
                </span>
                <hr class="my-7">
                <div>
                  <div class="checkbox mb-4 items-center cursor-pointer flex">
                    <label>
                      <input class="mr-4" type="checkbox">
                      <span class="false  text-sm text-gray-800">
                        <span class="line-clamp-0">Маркетингийн зардлийг нэмэгдүүлэх</span>
                      </span>
                    </label>
                  </div>
                  <div class="checkbox mb-4 items-center cursor-pointer flex">
                    <label>
                      <input class="mr-4" type="checkbox">
                      <span class="false  text-sm text-gray-800">
                        <span class="line-clamp-0">Зорилтот зах зээлийг зөв тодорхойлох</span>
                      </span>
                    </label>
                  </div>
                  <div class="checkbox mb-4 items-center cursor-pointer flex">
                    <label>
                      <input class="mr-4" type="checkbox">
                      <span class="false  text-sm text-gray-800">
                        <span class="line-clamp-0">Борлуулагчдийн хэрэглэгчидтэй харилцах харилцааг сайжируулах</span>
                      </span>
                    </label>
                  </div>
                  <div class="checkbox mb-4 items-center cursor-pointer flex">
                    <label>
                      <input class="mr-4" type="checkbox">
                      <span class="false  text-sm text-gray-800">
                        <span class="line-clamp-0">Sarah Emily Jacob</span>
                      </span>
                    </label>
                  </div>
                  <div class="checkbox mb-4 items-center cursor-pointer flex">
                    <label>
                      <input class="mr-4" type="checkbox">
                      <span class="false  text-sm text-gray-800">
                        <span class="line-clamp-0">Sarah Emily Jacob</span>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="w-full flex flex-row items-between justify-center">
                <div>
                  <button style="background: #585858;" class="flex items-center justify-center transition-colors duration-300 focus:shadow-outline text-white border-0 py-2 px-4 bg-sso hover:bg-sso-dark rounded-xl mt-3 px-7">
                    <span>Санал өгөх</span>
                  </button>
                </div>             
                <div>
                  <button class="flex items-center justify-center transition-colors duration-300 focus:shadow-outline  border-0 py-2 px-4 bg-transparent hover:bg-gray-100 rounded-md mt-3 px-5">
                    <span>Үр дүн</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>
      </section>
      <section class="grid-cols-12 flex-shrink-0 pl-3" style="width:340px;background: #F6F6F6;box-shadow: 0px 2px 14px rgba(0, 0, 0, 0.1);">
        <section data-sectioncode="103" class="mb-1 col-span-12">
            <div class="w-full flex justify-start flex-col pl-1 pr-3 py-3">
                <div style="color:#585858;font-size:20px">Тайлан</div>
                <ul class="mt-3">
                    <li class="flex w-full justify-between text-gray-800  leading-none cursor-pointer items-center relative mb-3 text-sso">
                        <div class="flex">
                            <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 40px;width: 40px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#C0DCFF">
                                <i style="color:#3975B5" class="far fa-smile text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-2 self-center">
                                <div style="color:#585858;font-size:14px">Дэлгэрэнгүй тайлан</div>
                                <div style="color:#9FA2B4;font-size:12px;" class="mt-1">Хамгийн өндөр дүнтэй орлого</div>
                            </div>
                        </div>
                    </li>
                    <li class="flex w-full justify-between text-gray-800  leading-none cursor-pointer items-center relative mb-3 text-sso">
                        <div class="flex">
                            <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 40px;width: 40px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#C0DCFF">
                                <i style="color:#3975B5" class="far fa-smile text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-2 self-center">
                                <div style="color:#585858;font-size:14px">Насжилтын тайлан</div>
                                <div style="color:#9FA2B4;font-size:12px;" class="mt-1">Хамгийн өндөр дүнтэй орлогын харилцагч</div>
                            </div>
                        </div>
                    </li>
                    <li class="flex w-full justify-between text-gray-800  leading-none cursor-pointer items-center relative mb-3 text-sso">
                        <div class="flex">
                            <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 40px;width: 40px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#C0DCFF">
                                <i style="color:#3975B5" class="far fa-smile text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-2 self-center">
                                <div style="color:#585858;font-size:14px" class="">Татварын тайлан</div>
                                <div style="color:#9FA2B4;font-size:12px;" class="mt-1">Хамгийн өндөр дүнтэй зарлага</div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div style="color:#9FA2B4;font-size:14px;">Бүгдийг харах</div>
            </div>
        </section>
        <section class="pr-3">
            <hr/>
        </section>
        <section data-sectioncode="103" class="mb-6 col-span-12">
            <div class="w-full flex justify-start flex-col pl-1 pr-3 py-3">
                <div style="color:#585858;font-size:20px">Ханш</div>
                <div class="flex justify-between mt-2">
                    <div>
                        <div style="color:#9FA2B4;font-szie:14px;">Валют</div>
                        <div class="flex mt-2">
                            <span class="" style="background: #699BF7;opacity: 0.2;box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);width: 40px;height: 40px;border-radius: 40px;">
                            </span>
                            <img src="https://camo.githubusercontent.com/9deb349543d58a05948238d2cf6b66328b4d4759ab52c1370fc6bd51d8db9a57/68747470733a2f2f686174736372697074732e6769746875622e696f2f636972636c652d666c6167732f666c6167732f636e2e737667" 
                                style="width: 20px;height: 20px;position: absolute;margin-top: 10px;margin-left: 10px;">
                            <span class="self-center ml-2" style="font-szie:14px;color:#585858;font-weight: 700;">USD</span>
                        </div>
                        <div class="flex mt-2">
                            <span class="" style="background: #699BF7;opacity: 0.2;box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);width: 40px;height: 40px;border-radius: 40px;">
                            </span>
                            <img src="https://camo.githubusercontent.com/9deb349543d58a05948238d2cf6b66328b4d4759ab52c1370fc6bd51d8db9a57/68747470733a2f2f686174736372697074732e6769746875622e696f2f636972636c652d666c6167732f666c6167732f636e2e737667" 
                                style="width: 20px;height: 20px;position: absolute;margin-top: 10px;margin-left: 10px;">
                            <span class="self-center ml-2" style="font-szie:14px;color:#585858;font-weight: 700;">USD</span>
                        </div>
                    </div>
                    <div>
                        <div style="color:#9FA2B4;font-szie:14px;">Авах</div>
                        <div class="mt-3" style="color:#585858;font-szie:14px;">2,848.65</div>
                        <div class="mt-4" style="color:#585858;font-szie:14px;">2,848.65</div>
                    </div>
                    <div>
                        <div style="color:#9FA2B4;font-szie:14px;">Зарах</div>
                        <div class="mt-3" style="color:#585858;font-szie:14px;">2,848.65</div>
                        <div class="mt-4" style="color:#585858;font-szie:14px;">2,848.65</div>
                    </div>
                </div>
                <div style="color:#9FA2B4;font-size:14px;" class="mt-3">Дэлгэрэнгүй</div>
            </div>
        </section>
      </section>      
    </section>
  </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.0/Chart.bundle.min.js" type="text/javascript"></script>
<script>
  $( function() {
    $("#datepicker").datepicker();

    $(".cloud-modulelist-tab").on("click", "li", function(){
        var className = $(this).data("class");
        $(this).closest("ul").find("li").removeClass("active");
        $(".cloud-modules").children().addClass("hidden");
        $(".cloud-modules").find("."+className).removeClass("hidden");
        $(this).addClass("active");
    })
  });

var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["1 сар",	"2 сар",	"3 сар",	"4 сар",	"5 сар",	"6 сар",	"7 сар","8 сар",	"9 сар","10 сар"],
        datasets: [{
            label: 'Орлого', // Name the series
            data: [500,	50,	2424,	14040,	14141,	4111,	4544,	47,	5555, 6811], // Specify the data values array
            fill: false,
            borderColor: '#2196f3', // Add custom color border (Line)
            backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
            borderWidth: 1 // Specify bar border width
        },{
            label: 'Зарлага', // Name the series
            data: [1288,	88942,	44545,	7588,	99,	242,	1417,	5504,	75, 457], // Specify the data values array
            fill: false,
            borderColor: '#4CAF50', // Add custom color border (Line)
            backgroundColor: '#4CAF50', // Add custom color background (Points and Fill)
            borderWidth: 1 // Specify bar border width
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      legend: {
        labels: {
            position: 'right',
            boxWidth: 15
        }                  
      }      
    }
});  
</script>
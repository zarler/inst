<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../../_Common/Header.tpl'}}
    <style type="text/css">
        .radio-custom label{margin-right:30px;}
        .avatar img{border-radius:0px;}
        .page-account .widget-header{padding:5px;}
           .carolina_img{
            margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.18);border-radius: 5px; 

        }

       .carolina_img .example{margin-top:10px; }
       .carolina_img img{height: 100px;}
    </style>

</head>

<body>
{{include '../../_Common/Top.tpl'}}
<main class="site-page">
    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-account">
            <div class="page-header">
                <h1 class="page-title">基本信息</h1>
            </div>
            <div class="page-content">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading nav-tabs-animate">
                                <div class="panel-title">
                                {{include './Navigation.tpl'}}
                                  </div>
                            </div>

                            <div class="panel-body nav-tabs-animate" >
                              <iframe  class="col-md-12" frameborder="0"  height='1200' src="https://tenant.51datakey.com/fund/report_data?data=0l5c1mDOaur5Ss8WDeCOzMIthiGW0etGFxxntVuIlzZ5HvW2Q4vIzG63wY%2BC7tVpu5GIbAKPPPw3OwHyBlVGgWtqmgJuMRHiF4%2BMbVEUJ7g5usY76RDIew%3D%3D">       

                              </iframe> 

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</main>
  
{{include '../../_Common/Footer.tpl'}}
</body>
</html>



<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../_Common/Header.tpl'}}
</head>

<body>
{{include '../_Common/Top.tpl'}}
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
                                    {{include '../User/Navigation.tpl'}}
                                </div>
                            </div>

                            <div class="panel-body nav-tabs-animate">



                                <div class="tab-content ">



                                    <div class="col-md-12 ">
                                        <div class="example table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <tr>
                                                    <th>经度</th>
                                                    <th>纬度</th>
                                                    <th>地址</th>
                                                    <th>商圈</th>
                                                    <th>创建时间</th>
                                                </tr>
                                                {{foreach from=$location_info item=value }}
                                                <tr>
                                                    <td>{{$value.lng}}</td>
                                                    <td>{{$value.lat}}</td>
                                                    <td>{{$value.formatted_address}}</td>
                                                    <td>{{$value.business_circle}}</td>
                                                    <td>{{if !empty($value.create_time)}}{{date("Y-m-d H:i:s",$value.create_time)}}{{else}}{{/if}}</td>
                                                </tr>
                                                {{/foreach}}
                                            </table>
                                        </div>
                                    </div>


                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>
    <div class="page-loading vertical-align text-center">
        <div class="page-loader loader-default loader vertical-align-middle" data-type="default"></div>
    </div>
</main>
{{include '../_Common/Footer.tpl'}}
</body>
</html>
<script>
$(function(){
    $(".nav-tabs li a").each(function(){
        $(this).click(function(){
            $("#pthead").html($(this).text());
        });
    });
});
</script>

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
                <h1 class="page-title">借款记录</h1>
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
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>借款时间</th>
                                                    <th>借款金额</th>
                                                    <th>借款银行卡</th>
                                                    <th>到期还款日</th>
                                                    <th>订单状态</th>
                                                    <th>操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{foreach from=$list item=value key=key}}
                                                <tr>
                                                    <td>{{$value.id}}</td>
                                                    <td>{{if !empty($value.start_time)}}{{date("Y-m-d",$value.start_time)}}{{else}}空{{/if}}</td>
                                                    <td>{{$value.loan_amount}}</td>
                                                    <td>{{$value.bankcard_no}}</td>
                                                    <td>{{if !empty($value.expire_time)}}{{date("Y-m-d",($value.expire_time-86400))}}{{else}}空{{/if}}</td>
                                                    <td>{{if isset($_status[$value.status])}}{{$_status[$value.status]}}{{elseif $value.status==0 }}订单初始{{else}}未知{{/if}}</td>
                                                    <td>
                                                        <a href="/Order/Detail?id={{$value.id}}" class="btn btn-primary btn-sm">详情</a>
                                                    </td>
                                                </tr>
                                                {{/foreach}}
                                                </tbody>
                                            </table>

                                            {{$pagination}}
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


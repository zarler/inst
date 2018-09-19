<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../../_Common/Header.tpl'}}
</head>

<body>
{{include '../../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">

            <div class="page-content">

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>扣款队列</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_CoreListTab.tpl'}}

                        <div class="example-warp">
                            <div class="example">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User_id</th>
                                            <th>Order_id</th>
                                            <th>Tc_no</th>
                                            <th>deduct_no</th>
                                            <th>扣款金额</th>
                                            <th>扣款时间</th>
                                            <th>当前状态</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                    {{if $_action=='List'}}
                                    <!-- 处理中列表 -->
                                        {{foreach from=$list item=value key=key}}
                                        <tr>
                                            <td>{{$value.id}}</td>
                                            <td>{{$value.user_id}}</td>
                                            <td>{{$value.order_id}}</td>
                                            <td>{{$value.tc_no}}</td>
                                            <td>{{$value.payment_no}}</td>
                                            <td>{{$value.payment_amount}}</td>
                                            <td>{{date('Y-m-d H:i:s',$value.query_time)}}</td>
                                            <td>{{if $value.query_status == 6}}<p class="text-danger">失败</p>{{elseif $value.query_status == 3}}<p class="text-success">成功</p>{{elseif $value.query_status == 5}}<p class="text-danger">失败</p> {{elseif $value.query_status == 4}}<p class="text-danger">失败</p>{{else}}<p class="text-primary">处理中</p>{{/if}}</td>
                                        </tr>
                                        {{/foreach}}
                                    {{elseif $_action=='SuccessList'}}
                                    <!-- 扣款成功列表 -->
                                        {{foreach from=$list item=value key=key}}
                                        <tr>
                                            <td>{{$value.id}}</td>
                                            <td>{{$value.user_id}}</td>
                                            <td>{{$value.order_id}}</td>
                                            <td>{{$value.tc_no}}</td>
                                            <td>{{$value.payment_no}}</td>
                                            <td>{{$value.payment_amount}}</td>
                                            <td>{{date('Y-m-d H:i:s',$value.query_time)}}</td>
                                            <td>{{if $value.query_status == 6}}<p class="text-danger">失败</p>{{elseif $value.query_status == 3}}<p class="text-success">成功</p>{{elseif $value.query_status == 5}}<p class="text-danger">失败</p> {{elseif $value.query_status == 4}}<p class="text-danger">失败</p>{{else}}<p class="text-primary">处理中</p>{{/if}}</td>
                                        </tr>
                                        {{/foreach}}

                                    {{elseif $_action=='FailList'}}
                                        {{foreach from=$list item=value key=key}}
                                        <tr>
                                            <td>{{$value.id}}</td>
                                            <td>{{$value.user_id}}</td>
                                            <td>{{$value.order_id}}</td>
                                            <td>{{$value.tc_no}}</td>
                                            <td>{{$value.payment_no}}</td>
                                            <td>{{$value.payment_amount}}</td>
                                            <td>{{date('Y-m-d H:i:s',$value.query_time)}}</td>
                                            <td>{{if $value.query_status == 6}}<p class="text-danger">失败</p>{{elseif $value.query_status == 3}}<p class="text-success">成功</p>{{elseif $value.query_status == 5}}<p class="text-danger">失败</p> {{elseif $value.query_status == 4}}<p class="text-danger">失败</p>{{else}}<p class="text-primary">处理中</p>{{/if}}</td>
                                        </tr>
                                        {{/foreach}}
                                    {{/if}}
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

</main>
<!-- /wrapper -->
{{include '../../_Common/Footer.tpl'}}


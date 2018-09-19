<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../_Common/Header.tpl'}}
</head>

<body>
{{include '../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">

            <div class="page-content">

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>订单列表</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}


                        <div class="example-warp">
                            <div class="table-responsive">
                                <table class="table  table-striped  table-responsive table-hover">
                                    <thead>
                                    <tr>
                                        <th>订单ID</th>
                                        <th>订单号</th>
                                        <th>订单类型</th>
                                        <th>姓名</th>
                                        <th>手机号</th>
                                        <th>身份证号</th>
                                        <th>借款金额</th>
                                        <th>实付金额</th>
                                        <th>应还金额</th>
                                        <th>已还金额</th>
                                        <th>当前状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{foreach from=$list item=value key=key}}
                                    <tr>
                                        <td>{{$value.id}}</td>
                                        <td>{{$value.order_no}}</td>
                                        <td>{{$_type[$value.type]}}</td>
                                        <td>{{$value.name}}</td>
                                        <td>{{$value.mobile}}</td>
                                        <td>{{$value.identity_code}}</td>
                                        <td>{{$value.loan_amount}}</td>
                                        <td>{{$value.pay_amount}}</td>
                                        <td>{{$value.repay_amount}}</td>
                                        <td>{{$value.repaid_amount}}</td>


                                        <td>
                                          {{if in_array($value.status,Model_Order::STATUSGROUP_SUCCESSED)}}
                                           <span class="label label-success"> {{$_status[$value.status]}}</span>
                                         {{elseif in_array($value.status,Model_Order::STATUSGROUP_OVERDUE)}}
                                            <span class="label label-danger"> {{$_status[$value.status]}}</span>
                                         {{elseif in_array($value.status,Model_Order::STATUSGROUP_PAID)}}
                                            <span class="label label-primary"> {{$_status[$value.status]}}</span>
                                         {{elseif in_array($value.status,Model_Order::STATUSGROUP_PAY_SUCCESS__NOT_IN)}}
                                            <span class="label label-warning"> {{$_status[$value.status]}}</span>
                                         {{else}}
                                            <span class="label label-info"> {{$_status[$value.status]}}</span>
                                         {{/if}}


                                        </td>

                                        <td> 
                                            <a href="/{{$_controller}}/Detail?id={{$value.id}}" class="btn btn-primary btn-sm" >详情</a>
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

</main>
<!-- /wrapper -->
{{include '../_Common/Footer.tpl'}}


<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../_Common/Header.tpl'}}
    <style type="text/css">
        .radio-custom label{margin-right:30px;}
        .avatar img{border-radius:0px;}
        .page-account .widget-header{padding:5px;}
    </style>

</head>

<body>
{{include '../_Common/Top.tpl'}}
<main class="site-page">
    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-account">
            <div class="page-header">
                <h1 class="page-title">订单详情</h1>
            </div>
            <div class="page-content blue-grey-700 margin-left-10 ">
                <div class="row">

                    <div class="panel col-md-5">
                        <div class="example-wrap ">

                            <div class="ribbon ribbon-clip ribbon-danger">
                                <span class="ribbon-inner">订单信息</span>
                            </div>
                            <div class="example table-responsive padding-top-30">
                                <table class="table">
                                    <tbody>

                                    <tr>
                                        <td >订单号:</td>
                                        <td>{{$order.order_no}}</td>
                                    </tr>
                                    {{if $order.status==Model_Order::STATUS_OVERDUE}}
                                    <tr>
                                        <td>逾期天数</td>
                                        <td>{{Lib_Date::countDayDay($order.expire_time,strtotime(date('Y-m-d')))}}天</td>
                                    </tr>
                                    {{/if}}
                                    <tr>
                                        <td>借款类型:</td>
                                        <td>{{if isset($_type[$order.type])}}{{$_type[$order.type]}}{{/if}}</td>
                                    </tr>
                                    <tr>
                                        <td>借款金额:</td>
                                        <td>{{$order.loan_amount}}元</td>
                                    </tr>

                                    <tr>
                                        <td>实付金额:</td>
                                        <td>{{$order.pay_amount}}元</td>
                                    </tr>


                                    <tr>
                                        <td>应还金额:</td>
                                        <td>{{$order.repay_amount}}元</td>
                                    </tr>
                                    <tr>
                                        <td>已还金额:</td>
                                        <td>{{$order.repaid_amount}}元</td>
                                    </tr>


                                    <tr>
                                        <td>订单状态:</td>
                                        <td>
                                            {{if in_array($order.status,Model_Order::STATUSGROUP_SUCCESSED)}}
                                            <span class="label label-success"> {{$_status[$order.status]}}</span>
                                            {{elseif in_array($order.status,Model_Order::STATUSGROUP_OVERDUE)}}
                                            <span class="label label-danger"> {{$_status[$order.status]}}</span>
                                            {{elseif in_array($order.status,Model_Order::STATUSGROUP_PAID)}}
                                            <span class="label label-primary"> {{$_status[$order.status]}}</span>
                                            {{elseif in_array($order.status,Model_Order::STATUSGROUP_PAY_SUCCESS__NOT_IN)}}
                                            <span class="label label-warning"> {{$_status[$order.status]}}</span>
                                            {{else}}
                                            <span class="label label-info"> {{$_status[$order.status]}}</span>
                                            {{/if}}

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>借款日期:</td>
                                        <td>{{if $order.start_time>0}}{{date('Y-m-d',$order.start_time)}}{{/if}}</td>
                                    </tr>
                                    <tr>
                                        <td>到期日期:</td>
                                        <td>{{if $order.expire_time>0}}{{date('Y-m-d',$order.expire_time-86400)}}{{/if}}</td>
                                    </tr>

                                    {{if $order.status==Model_Order::STATUS_OVERDUE}}
                                    <tr>
                                        <td>利息计算</td>
                                        <td>{{if time() > $order.expire_time}}{{date('Y-m-d',$order.calc_time)}} <a href="/Order/Penalty?order_id={{$order.id}}" target="_blank">罚息记录</a>{{else}}未执行过{{/if}}</td>
                                    </tr>
                                    {{/if}}
                                    <tr>
                                        <td>借款合同:</td>
                                        {{if $order.seal}}
                                        <td><a target="_blank" href="/Seal/GetPdfFlow?order_id={{$order.id}}&&type=1&&behavior=1">预览合同</a>  &nbsp;&nbsp;&nbsp;&nbsp; <a href="/Seal/GetPdfFlow?order_id={{$order.id}}&&type=1&&behavior=2">下载合同</a></td>
                                        {{else}}
                                        <td ><a  href="javascript:;">预览合同</a>  &nbsp;&nbsp;&nbsp;&nbsp; <a  href="javascript:;">下载合同</a></td>
                                        {{/if}}
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <div class="panel col-md-6 bg-default  margin-left-20  ">
                        <div class="example-wrap">
                            <h4 class="example-title  ">借款人信息</h4>
                            <div class="example  margin-md-0 " >
                                <table class="table table-hover  table-responsive margin-md-0">
                                    <tr>
                                        <td>姓名:</td>
                                        <td>{{if isset($user.name)}}{{$user.name}}{{/if}}</td>
                                    </tr>
                                    <tr>
                                        <td>性别:</td>
                                        <td>{{if isset($user.sex)}}{{$user.sex}}{{/if}}</td>
                                    </tr>
                                    <tr>
                                        <td>身份证:</td>
                                        <td>{{if isset($user.identity_code)}}{{$user.identity_code}}{{/if}}</td>
                                    </tr>
                                    <tr>
                                        <td>手机号:</td>
                                        <td>{{if isset($user.mobile)}}{{$user.mobile}}{{/if}}</td>
                                    </tr>
                                    <tr>
                                        <td>授信额度:</td>
                                        <td>{{if isset($finance_profile)}}{{$finance_profile.inst_amount}}{{/if}}</td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel col-md-6 bg-default  margin-left-20 "  >
                        <div class="example-wrap bg-success-20 "  >
                            <h3 class="example-title ">费用记录</h3>
                            <div class="example table-responsive margin-md-0">
                                <table class="table margin-md-0">
                                    <thead>
                                    <tr>
                                        <th>项目名称</th>
                                        <th>金额</th>
                                        <th>项目名称</th>
                                        <th>金额</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>借款手续费</td>
                                        <td>{{if isset($charge)}}{{$finance_profile.charge}}{{/if}}</td>
                                        <td>罚息</td>
                                        <td>0</td>

                                    </tr>
                                    <tr>
                                        <td>借款管理费</td>
                                        <td>{{if isset($management)}}{{$finance_profile.management}}{{/if}}</td>
                                        <td>滞纳金</td>
                                        <td>0</td>

                                    </tr>
                                    <tr>

                                        <td>借款利息</td>
                                        <td>{{if isset($interest)}}{{$finance_profile.interest}}{{/if}}</td>
                                        <td> </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                     <div class="panel col-md-11 bg-default">
                        <div class="example-wrap margin-md-0"  >
                            <h1 class="example-title"><i class="icon fa-credit-card text-danger" aria-hidden="true"></i>分期记录</h1>
                            <div class="example table-responsive ">
                                  <table class="table">
                                    <thead>
                                    <tr>
                                        <th>期号</th>
                                        <th>开始时间</th>
                                        <th>到期时间</th>
                                        <th>当期当期本金</th>
                                        <th>当期应还利息</th>
                                        <th>当期应还管理费</th>
                                        <th>当期应还罚息</th>
                                        <th>当期应还滞纳金</th>
                                        <th>状态</th>
                                        <th>总计应还金额</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{foreach from=$bill item=value key=key}}
                                     
                                            <tr>
                                                <td>第<span class="text-danger">{{$value.number}}</span>期</td>
                                                <td>{{date('Y-m-d',$value.start_time)}}</td>
                                                <td><small>{{date('Y-m-d',$value.expire_time)}}</small></td>
                                                <td><small>{{$value.repay_principal}}</small></td>
                                                <td><small>{{$value.repay_interest}}</small></td>
                                                <td><small>{{$value.repay_management}}</small></td>
                                                <td><small>{{$value.repay_penalty}}</small></td>
                                                 <td><small>{{$value.repay_late_fee}}</small></td>
                                                 <td>
                                                    {{if in_array($value.status,Model_Bill::STATUSGROUP_SUCCESSED)}}
                                                       <span class="label label-success">  {{$_bill_status[$value.status]}}</span>
                                                     {{elseif in_array($value.status,Model_Bill::STATUSGROUP_OVERDUE)}}
                                                        <span class="label label-danger">  {{$_bill_status[$value.status]}}</span>
                                                     {{elseif in_array($value.status,Model_Bill::STATUSGROUP_PAID)}}
                                                        <span class="label label-primary">  {{$_bill_status[$value.status]}}</span>
                                                     {{elseif in_array($value.status,Model_Bill::STATUSGROUP_DEDUCTING)}}
                                                        <span class="label label-warning">  {{$_bill_status[$value.status]}}</span>
                                                     {{else}}
                                                        <span class="label label-info">  {{$_bill_status[$value.status]}}</span>
                                                     {{/if}} 
                                                
                                              </td>

                                                <td>{{$value.repay_amount}}</td>
                                                <td>
                                                    
                                                </td>
                                            </tr>
                                            {{/foreach}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="panel col-md-11 bg-default">
                        <div class="example-wrap margin-md-0"  >
                            <h1 class="example-title"><i class="icon fa-money text-danger" aria-hidden="true"></i>放款记录</h1>
                            <div class="example table-responsive ">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>卡号</th>
                                        <th>金额</th>
                                        <th>时间</th>
                                        <th>支付公司</th>
                                        <th>支付公司订单号</th>
                                        <th>状态</th>
                                        <th>备注</th>
                                        <th>查询</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{foreach from=$payment_list item=value key=key}}
                                            <tr {{if $value.status==Model_Finance_Payment::SUCCESS}}class=" alert alert-success"{{elseif $value.status==Model_Finance_Payment::FAILED}}class=" alert alert-danger"{{elseif $value.status==Model_Finance_Payment::WAIT}}class=" alert alert-warning"{{/if}}>
                                                <td><small>{{$value.card_no}}</small></td>
                                                <td>{{$value.amount}}</td>
                                                <td><small>{{date('Y-m-d H:i',$value.create_time)}}</small></td>
                                                <td><small>{{if $value.provider}}{{$_provider[$value.provider]}}{{/if}}</small></td>
                                                <td><small>{{$value.out_order_no}}</small></td>
                                                 
                                                 <td>
                                                 {{if isset($payment_status[$value.status])}}
                                                  {{$payment_status[$value.status]}}
                                                   {{else}}
                                                  {{$value.status}}
                                                {{/if}}
                                              </td>

                                                <td>{{if $value.msg}}{{$value.msg}}{{/if}}</td>
                                                <td>
                                                    
                                                </td>
                                            </tr>
                                            {{/foreach}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


</main>


{{include '../_Common/Footer.tpl'}}
</body>
</html>



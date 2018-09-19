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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>授信列表</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}


                        <div class="example-warp">
                                <div class="table-responsive">
                                    <table class="table  table-striped  table-hover">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>姓名</th>
                                            <th>身份证</th>
                                            <th>手机号</th>
                                            <th>授信额度</th>
                                            <th>审核人</th>
                                            <th>当前状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            {{foreach from=$list item=value key=key}}
                                            <tr>
                                                <td>{{$value.id}}</td>
                                                <td>{{$value.name}}</td>
                                                <td>{{$value.identity_code}}</td>
                                                <td>{{$value.mobile}}</td>
                                                <td>{{if isset($value.inst_amount)}}{{$value.inst_amount}}{{/if}}</td>
                                                
                                                <td> <span class="text-danger">{{if $value.admin_name}}{{$value.admin_name}} {{/if}}</span></td>
                                                <td> 
                                                     
                                                     {{if isset($_status[$value.credit_auth])}}{{$_status[$value.credit_auth]}}{{else}}{{$value.credit_auth}} {{/if}}
                                                </td>
                                                <td>  <a href="/{{$_controller}}/Detail?uid={{$value.id}}" class="btn btn-success  btn-sm " >审核</a>   </td>
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
{{include '../../_Common/Footer.tpl'}}
</body>
</html>


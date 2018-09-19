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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>用户列表</h3>
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
                                            <th>注册时间</th>
                                            <th>客户端</th>
                                            <th>用户状态</th>

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
                                                <td>{{date("Y-m-d",$value.create_time)}}</td>
                                                <td>{{$value.reg_app}}</td>


                                                <td>

                                                    {{if isset($_status[$value.status])}}

                                                      {{if $value.status==1}}
                                                         <span class="label label-success"> {{$_status[$value.status]}}</span>
                                                    {{elseif $value.status==2}}
                                                      <span class="label label-info"> {{$_status[$value.status]}}</span>
                                                       {{else}}
                                                      <span class="label label-danger"> {{$_status[$value.status]}}</span>
                                                      {{/if}}

                                                    {{else}}
                                                    未知{{/if}}


                                                </td>

                                                <td>
                                                    <a href="/{{$_controller}}/Edit?id={{$value.id}}" class="btn btn-primary btn-danger btn-sm" >修改</a>
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


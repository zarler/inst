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
            <div class="page-header">
                <h1 class="page-title">管理员</h1>
            </div>
            <div class="page-content">

                <div class="panel">
                    <div class="panel-body">


                        <div class="example-wrap">
                            {{include './_Tab.tpl'}}
                            <div class="example">
                                <div class="table-responsive">
                                    <table class="table table-striped  table-hover ">
                                    <thead>
                                    <tr>
                                        <th>用户名</th>
                                        <th>邮箱</th>
                                        <th>姓名</th>
                                        <th>工号</th>
                                        <th>手机</th>
                                        <th>组</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{foreach from=$list item=value key=key}}
                                    <tr>
                                        <td>{{$value.username}}</td>
                                        <td>{{$value.email}}</td>
                                        <td>{{$value.name}}</td>
                                        <td>{{$value.number}}</td>
                                        <td>{{$value.mobile}}</td>
                                        <td>{{if isset($value.user_group)}}{{foreach from=$value.user_group item=v key=k}}<span>{{$v.name}}</span>{{/foreach}}{{/if}}</td>
                                        <td>
                                        {{if $value.status==1}}
                                            <span class="glyphicon glyphicon-ok-sign text-success" >正常</span>
                                        {{elseif $value.status==2}}
                                            <span class="glyphicon glyphicon-minus-sign text-warning" >已禁止</span>
                                        {{elseif $value.status==3}}
                                            <span class="glyphicon glyphicon-remove-sign text-danger" >已删除</span>
                                        {{else}}
                                            <span class="glyphicon glyphicon-question-sign">未知</span>
                                        {{/if}}
                                        </td>
                                        <td>
                                            <a href="/{{$_controller}}/Edit?id={{$value.id}}" class="btn btn-primary btn-sm">更改</a>
                                            {{if $value.id!=$sessionid}}
                                            <a href="javascript:void(0);" data-action="/{{$_controller}}/Delete?id={{$value.id}}"  class="btn bg-danger btn-sm delete_btn">删</a>
                                            {{/if}}
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
    <div class="page-loading vertical-align text-center">
        <div class="page-loader loader-default loader vertical-align-middle" data-type="default"></div>
    </div>
</main>
<!-- /wrapper -->
{{include '../../_Common/Footer.tpl'}}
</body>
</html>

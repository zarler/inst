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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>短信模板</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}


                        <div class="example-warp">
                            <div class="example">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>模板ID</th>
                                            <th>服务名</th>
                                            <th>标题</th>
                                            <th>服务提供商</th>
                                            <th>类型</th>
                                            <th>创建时间</th>
                                            <th>状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{foreach from=$list item=value key=key}}

                                        <tr>
                                            <td>{{$value.id}}</td>
                                            <td>{{$value.code}}</td>
                                            <td>{{$value.title}}</td>
                                            {{foreach from=$providers item=data key=key}}
                                                {{if $value.provider_id==$data.id}}
                                                <td>{{$data.name}}</td>
                                                {{/if}}
                                            {{/foreach}}
                                            <td>{{if isset($_types[$value.type])}}{{$_types[$value.type]}}{{else}}未知{{/if}}</td>
                                            <td>{{date('Y-m-d',$value.create_time)}}</td>
                                            <td>{{if isset($_status[$value.status])}}{{$_status[$value.status]}}{{else}}未知{{/if}}</td>
                                            <td>
                                                <a href="/{{$_controller}}/Edit?id={{$value.id}}" class="btn btn-primary btn-sm" target="_blank">更改</a>
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
</body>
</html>

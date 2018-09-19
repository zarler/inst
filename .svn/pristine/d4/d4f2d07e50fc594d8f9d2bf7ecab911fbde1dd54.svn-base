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
                            <div class="example">
                                <div class="table-responsive">
                                    <table class="table table-striped  table-hover ">
                                    <thead>
                                    <tr>
                                        <th>任务</th>
                                        <th>功能</th>
                                        <th>执行时间格式</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>上次执行时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{foreach from=$cron_list item=value key=key}}
                                    <tr>
                                        <td>{{$value.task}}</td>
                                        <td>{{$value.comment}}</td>
                                        <td>{{$value.time_format}}</td>
                                        <td>{{$value.status_explanation}}</td>
                                        <td>{{$value.create_time_format}}</td>
                                        <td>{{$value.last_execute_time_format}}</td>
                                        <td>
                                            <input type="button" value="详细信息" name="" onclick="window.location='/Admin_Crontab/Detail?id={{$value.id}}'" />
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
    </div>
    <div class="page-loading vertical-align text-center">
        <div class="page-loader loader-default loader vertical-align-middle" data-type="default"></div>
    </div>
</main>
<!-- /wrapper -->
{{include '../../_Common/Footer.tpl'}}
</body>
</html>

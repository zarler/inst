<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../../../_Common/Header.tpl'}}
</head>

<body>
{{include '../../../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">
            <div class="page-header">
                <h1 class="page-title">权限组</h1>
            </div>
            <div class="page-content">

                <div class="panel">

                    <div class="panel-body">

                        {{include './_Tab.tpl'}}
                        <div class="example-wrap">

                            <div class="example">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">

                                        <thead>
                                        <tr>
                                            <th>用户名</th>
                                            <th>操作描述</th>
                                            <th>url</th>
                                            <th>ip</th>
                                            <th>日期时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{foreach from=$list item=value key=key}}
                                        <tr>
                                            <td>{{$value.username}}</td>
                                            <td>{{$value.summary}}</td>
                                            <td>{{$value.url}}</td>
                                            <td>{{$value.ip}}</td>
                                            <td>{{date('Y-m-d H:i:s',$value.create_time)}}</td>
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
        <script src="/public/vendor/peity/jquery.peity.min.js"></script>
        <script src="/public/themes/classic/global/js/plugins/selectable.js"></script>
    </div>
    <div class="page-loading vertical-align text-center">
        <div class="page-loader loader-default loader vertical-align-middle" data-type="default"></div>
    </div>
</main>
<!-- /wrapper -->
{{include '../../../_Common/Footer.tpl'}}

</body>
</html>


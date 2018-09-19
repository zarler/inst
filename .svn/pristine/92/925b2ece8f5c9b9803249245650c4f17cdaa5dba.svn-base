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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>银行列表</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}

                        <div class="example-warp">
                            <div class="example">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                        <tr>
                                            <th>银行名称</th>
                                            <th>银行编号</th>
                                            <th>银联编号</th>
                                            <th>状态</th>

                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            {{foreach from=$list item=value key=key}}
                                            <tr>
                                                <td>{{$value.name}}</td>
                                                <td>{{$value.code}}</td>
                                                <td>{{$value.unionpay_code}}</td>
                                                <td>{{if isset($_status[$value.status])}}{{$_status[$value.status]}}{{/if}}</td>
                                                <td>
                                                    <a href="/{{$_controller}}/Edit?id={{$value.id}}" class="btn btn-primary btn-sm">更改</a>
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
    <script src="/public/vendor/peity/jquery.peity.min.js"></script>
    <script src="/public/themes/classic/global/js/plugins/selectable.js"></script>

</main>
<!-- /wrapper -->
{{include '../_Common/Footer.tpl'}}
</body>
</html>
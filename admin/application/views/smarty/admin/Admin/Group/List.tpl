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
                    <h1 class="page-title">管理员组</h1>
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
                                                <th>名称</th>
                                                <th>备注</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{foreach from=$list item=value key=key}}
                                            <tr>
                                                <td>{{$value.name}}</td>
                                                <td>{{$value.description}}</td>
                                                <td>
                                                    <a href="/{{$_controller}}/Edit?id={{$value.id}}" class="btn btn-primary btn-sm">更改</a>

                                                    <a href="javascript:void(0);" data-action="/{{$_controller}}/Delete?id={{$value.id}}"   class="btn bg-danger btn-sm delete_btn">删</a>
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
    {{include '../../_Common/Footer.tpl'}}

</body>
</html>

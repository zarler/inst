<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../_Common/Header.tpl'}}
    <style type="text/css">
        .radio-custom label{margin-right:30px;}
    </style>
</head>

<body>
{{include '../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">
            <div class="page-content">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>系统设置</h3>
                    </div>
                    <div class="panel-body">
                        {{include './_Tab.tpl'}}
                        {{include '../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th width="20%">类别</th>
                                                <th width="10%">变量名</th>
                                                <th width="10%">类型</th>
                                                <th width="50%">说明 </th>
                                                <th width="10%">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{foreach from=$hideres item=value }}
                                            <tr>
                                                <td>{{$value.typename}}</td>
                                                <td>{{$value.name}}</td>
                                                <td>{{$value.val_type}}</td>
                                                <td>{{$value.description}}</td>
                                                <td><button type="button" class="btn btn-primary" onclick="if (confirm('NOTICE:您确定显示？！！！')){location.href='/{{$_controller}}/Recovery?id={{$value.id}}&is_show=1';}" name="valshow" value="显示">显示</button></td>
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
    </div>

</main>
{{include '../_Common/Footer.tpl'}}
</body>
</html>



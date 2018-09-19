<!DOCTYPE html>
<html lang="zh-CN">
<head>
    {{include '../../_Common/Header.tpl'}}
</head>
<body>

<!-- #wrapper -->
<div id="wrapper">
    {{include '../../_Common/Top.tpl'}}
    <!-- #page-wrapper -->
    <div id="page-wrapper">

        <!-- #.row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">上传过的文件</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" id="pthead">列表</div>
                    <!-- /.panel-heading -->

                    <div class="panel-body">

                        {{include './_Tab.tpl'}}


                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>用户名</th>
                                    <th>文件</th>
                                    <th>扩展名</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{foreach from=$list item=value key=key}}
                                <tr>
                                    <td>{{$value.username}}</td>
                                    <td>{{$value.file}}</td>
                                    <td>{{$value.ext}}</td>
                                    <td>{{date('Y-m-d H:i:s',$value.create_time)}}</td>
                                    <td>
                                        <a target="_blank" href="/{{$_controller}}/File?id={{$value.id}}" class="btn btn-default">查看</a>
                                    </td>
                                </tr>
                                {{/foreach}}
                                </tbody>
                            </table>

                            {{$pagination}}


                        </div>
                        <!-- /.panel-body -->

                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>


        </div>
        <!-- /page-wrapper -->
    </div>
    <!-- /wrapper -->
    {{include '../../_Common/Footer.tpl'}}
</body>
</html>
<script>
    $(function(){
        $(".nav-tabs li a").each(function(){
            $(this).click(function(){
                $("#pthead").html($(this).text());
            });
        });
    });
</script>
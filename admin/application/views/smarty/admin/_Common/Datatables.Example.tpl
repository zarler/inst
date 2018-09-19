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
                <h1 class="page-header">菜单管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        管理列表
                    </div>
                    <!-- /.panel-heading -->

                    <div class="panel-body">


                        <!-- tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="/Admin_Menu/List" data-toggle="tab">列表</a>
                            </li>
                            <li><a href="/Admin_Menu/Add">添加</a>
                            </li>
                            <li><a href="#edit_form" data-toggle="tab">更改</a>
                            </li>
                            <li><a href="#search_form" data-toggle="tab">搜索</a>
                            </li>
                        </ul>
                        <!-- tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade" id="edit_form">
                                <h4>提示</h4>
                                <p>请选择相应项目,点击编辑按钮.</p>
                            </div>
                            <div class="tab-pane fade" id="search_form">
                                <h4>这里是搜索框</h4>
                                <p>具体还没有做</p>
                            </div>
                        </div>
                        <!-- /tabs -->



                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="datalist">
                                <thead>
                                <tr>
                                    <th>菜单名</th>
                                    <th>Url</th>
                                    <th>Controller</th>
                                    <th>Action</th>
                                    <th>上级菜单</th>
                                    <th>显示</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>


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
<script>
    $(document).ready(function() {
        $('#datalist').DataTable({
            language: {
                url: '{{$ui_url}}/bower_components/datatables/media/zh-CN.json'
            },
            "paging": true,
            "pagingType":   'full_numbers',
            "searching": false,
            "processing": true,
            "serverSide": true,
            "ajax": '/Admin_Menu/ListAjax',
            "columnDefs": [
                    { "visible": false,
                        "targets": 1 },
            ],
            "dom": '<"top"i>rt<"bottom"flp><"clear">',
        });
        $('#datalist tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
        } );

    });
</script>

</body>
</html>

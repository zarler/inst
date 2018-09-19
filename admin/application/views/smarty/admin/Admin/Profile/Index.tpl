<!DOCTYPE html>
<html lang="zh-CN">
<head>
    {{include '../../_Common/Header.tpl'}}
</head>
<body>

<!-- #wrapper -->
<div id="wrapper">
    {{include '../../_Common/Top.tpl'}}
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">您的个人信息</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->



            <!-- #.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <!--
                        <div class="panel-heading">
                            Form Elements
                        </div>
                        -->
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-lg-6">
                                    {{include '../../_Common/Form_Message.tpl'}}
                                    <!-- form -->
                                    <form role="form" method="post">

                                        <div class="form-group">
                                            <label for="disabled_name">用户名</label>
                                            <input class="form-control" id="disabled_name" type="text"  value="{{$data.username}}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="disabled_email">邮箱</label>
                                            <input class="form-control" id="disabled_email" name="disabled_email"  value="{{$data.email}}" disabled>
                                        </div>


                                        <div class="form-group">
                                            <label for="name">姓名</label>
                                            <input class="form-control" id="name" name="name" placeholder="{{$data.name}}" value="{{$data.name}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="mobile">手机</label>
                                            <input class="form-control" id="mobile" name="mobile" placeholder="{{$data.mobile}}" value="{{$data.mobile}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">工作电话</label>
                                            <input class="form-control" id="phone" name="phone" placeholder="{{$data.phone}}" value="{{$data.phone}}">
                                        </div>


                                        <div class="form-group">
                                            <label for="department">部门</label>
                                            <input class="form-control" id="department" name="department" placeholder="{{$data.department}}" value="{{$data.department}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="job">公职</label>
                                            <input class="form-control" id="job" name="job" placeholder="{{$data.job}}" value="{{$data.job}}">
                                        </div>


                                        <button type="submit" class="btn btn-primary" name="submit" value="保存">保存</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                    </form>
                                </div>



                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->






        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /wrapper -->
{{include '../../_Common/Footer.tpl'}}
</body>
</html>

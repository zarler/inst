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
                    <h1 class="page-header">更改密码</h1>
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
                                            <label for="old_papssword">原密码</label>
                                            <input class="form-control" id="old_papssword" name="old_password" type="password" placeholder="输入原密码" value="">
                                        </div>

                                        <div class="form-group">
                                            <label for="new_papssword">新密码</label>
                                            <input class="form-control" id="new_papssword" name="new_password" type="password" placeholder="输入新密码" value="">
                                        </div>

                                        <div class="form-group">
                                            <label for="new_papssword_confirm">确认新密码</label>
                                            <input class="form-control" id="new_papssword_confirm" name="new_password_confirm" type="password" placeholder="再输入一次新密码" value="">
                                        </div>


                                        <button type="submit" class="btn btn-primary" name="submit" value="更改">更改</button>
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

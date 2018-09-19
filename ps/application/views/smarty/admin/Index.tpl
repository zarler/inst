<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>文件上传demo</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{$ui_url}}/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{$ui_url}}/dist/css/sb-admin-2.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">文件上传</h3>
                </div>
                <div class="panel-body">
                    <form action="/{{$_controller}}/Save" method="post" enctype="multipart/form-data">
                        <fieldset>
                           <!--<div ><span style="width: 150px;margin-left: 5px" ></span><input type="text" id="client_id" name="client_id"/></div>-->
                            <!-- Change this to a button or input when using this as a form -->
                            <div style="width: 100%;height:20px;clear: both">

                                <input style="display: block" type="file" name="upfile" id="file" />
                                <input style="float: right;" type="submit" name="submit" value="上传文件" />
                            </div>

                        </fieldset>
                    </form>
                    <br />
                    <br />
                        <fieldset>
                            <div><span style="width: 150px;margin-bottom: 5px;margin-left: 45px">id值:</span><input type="text" id="id" name="id"/></div>
                            <br>
                            <div><span style="width: 150px;margin-bottom: 5px;margin-left: 25px">hash值:</span><input type="text" id="hash" name="hash"/></div>
                            <br>
                            <Button style="float: right;" id="buttonJson">Json获取文件</Button>
                            <Button style="float: right;margin-right: 5px" id="buttonGet">get获取文件</Button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">文件列表</h3>
                </div>
                <div class="panel-body">
                    {{foreach from=$_data item=value key=key}}
                        <div style="width: 100%;height:20px;clear: both"> <span>{{$value.file}}</span><a href="/{{$_controller}}/Delete?hash={{$value.hash}}" style="float: right">删除</a></div>
                        <br>
                    {{/foreach}}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{$ui_url}}/bower_components/jquery/dist/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#buttonJson').click(function () {
            var hash = $('#hash').val();
            var id = $('#id').val();
            if(!hash && !id){
                alert('id和hash不能都为空！');
            }else{
                window.location.href = "/{{$_controller}}/Json?hash="+hash+"&id="+id;
            }
        });
        $('#buttonGet').click(function () {
            var hash = $('#hash').val();
            var id = $('#id').val();
            if(!hash && !id){
                alert('id和hash不能都为空！');
            }else{
                window.location.href = "/{{$_controller}}/Get?hash="+hash+"&id="+id;
            }
        });
    });

</script>
</body>
</html>

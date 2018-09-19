<!DOCTYPE html>
<html  class="no-js css-menubar height-full" lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>分期</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui" />
    <meta name="author" content="" />
    <!-- 360浏览器默认使用Webkit内核 -->
    <meta name="renderer" content="webkit" />
    <!-- 禁止百度SiteAPP转码 -->
    <!-- 禁止百度SiteAPP转码 -->
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="stylesheet" href="{{$ui_url}}/themes/css/site.css">
    <link rel="stylesheet" href="{{$ui_url}}/themes/css/login.css" />
    <!-- 插件 -->
    <!-- 图标 -->
    <link rel="stylesheet" href="{{$ui_url}}/fonts/web-icons/web-icons.css" />

</head>

<body class="page-locked layout-full page-dark">


<div class=" page animation-fade vertical-align text-center animsition-fade height-full">
    <div class="page-content vertical-align-middle">
        <div class="avatar avatar-lg">
            <img src="{{$ui_url}}/themes/images/avatar.svg"  />
        </div>
        <h2 class="locked-user">分期管理平台</h2>
        <form  id="login_form" role="form" name="login_form"  method="post">

            <div class="input-group" >
                <span class="input-group-btn"> <button type="button" class="btn btn-primary"> <i class="icon wb-user" aria-hidden="true"></i> <span class="sr-only">账号</span> </button> </span>

                <input type="text" class="form-control last" name="account"  placeholder="请输入平台账号" data-fv-field="account" />

            </div>
           <br/>
            <div class="input-group">
                <span class="input-group-btn"> <button type="button" class="btn btn-primary"> <i class="icon wb-unlock" aria-hidden="true"></i> <span class="sr-only">密码</span> </button> </span>

                <input type="password" class="form-control last " name="password" id="password" placeholder="请输入您的密码继续登录" />
            </div>
            <br/>
            <div class="input-group">
                <div class="checkbox-custom checkbox-primary">
                    <input name="remember" type="checkbox" value="Remember Me">
                    <label for="inputChecked">记住密码</label>
                </div>
            </div>
            <div class="input-group">

                <button type="submit" class="btn btn-primary sub" name="submit"   value="Sign up">登录</button>

            </div>

        </form>

    </div>
</div>

<script type="text/javascript" src="{{$ui_url}}/themes/js/jquery.min.js"></script>
<script type="text/javascript" src="{{$ui_url}}/themes/js/jquery.validate.js"></script>
<script type="text/javascript" src="{{$ui_url}}/themes/js/login.js"></script>

</body>
</html>

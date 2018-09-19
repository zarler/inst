<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

{{include './_Common/Header.tpl'}}
</head>

<body>
{{include './_Common/Top.tpl'}}

<main class="site-page">
    <div class="page-container" id="admui-pageContent">
        <div class="page animation-fade page-index">
            <div class="page-content">
                <div class="media account-info">
                    <div class="media-left">
                        <div class="avatar avatar-online">
                            <img src="{{$ui_url}}/themes/images/avatar.svg" alt="xiaxuan@admui_demo">
                            <i class="avatar avatar-busy"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            欢迎您，{{$_user['name']}}
                            <small>

                                {{$_user['department']}}  {{$_user['job']}}

                            </small>
                        </h4>
                        <p>
                            <i class="icon icon-color wb-bell" aria-hidden="true"></i> 当前时间：{{date("Y-m-d H:i:s",time())}}，详细信息请查看
                            <a data-pjax="" href="/account/log" target="_blank">日志</a>
                            ，如果不是您本人登录，请及时
                            <a data-pjax="" href="/account/password" target="_blank">修改密码</a>
                            。
                        </p>
                    </div>
                    <div class="media-right">
                        <a href="javascript:void(0);" data-pjax="" target="_blank" class="btn btn-outline btn-success btn-outline btn-sm">账户管理</a>
                    </div>
                </div>


            </div>
        </div>


    </div>

</main>
 <!-- /wrapper -->
{{include './_Common/Footer.tpl'}}
</body>
</html>

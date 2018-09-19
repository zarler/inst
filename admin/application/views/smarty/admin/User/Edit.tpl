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
            <div class="page-header">
                <h1 class="page-title">修改用户</h1>
            </div>
            <div class="page-content">

                <div class="panel">
                    <div class="panel-body">
                        {{include './_Tab.tpl'}}
                        {{include '../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form role="form" method="post">

                                            <div class="form-group ">
                                                <label for="user_id">用户ID</label>
                                                <input class="form-control" id="user_id" name="user_id"  value="{{$data.id}}" disabled="disabled">
                                            </div>


                                            <div class="form-group form-material row">

                                                <div class="col-sm-3">
                                                    <label for="mobile">手机号码以及验证状态</label>
                                                    <input class="form-control" id="mobile" name="mobile" value="{{$data.mobile}}">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="mobile"></label>
                                                    <span class="radio radio-custom  radio-success">
                                                    {{foreach from=$verify_status item=value key=key}}


                                                        <input type="radio" value="{{$key}}" id="validated_mobile{{$key}}" name="validated_mobile" {{if $data.validated_mobile==$key}}checked="checked"{{/if}}>
                                                             <label for="validated_mobile{{$key}}" > {{$value}}</label>

                                                        {{/foreach}}
                                                    </span>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="password">重置密码</label>
                                                <input class="form-control" id="password" name="password" type="password" placeholder="如不修改,请不要填写"  value="">
                                            </div>


                                            <div class="form-group">
                                                <label for="name">姓名</label>
                                                <input class="form-control" id="name" name="name" value="{{$data.name}}">
                                            </div>
                                            <div class="form-group">
                                                <label>身份证号</label>
                                                <input class="form-control" id="identity_code" name="identity_code" placeholder="身份证号码18位,不满18位的例如17位数字在最后一位补X"  value="{{$data.identity_code}}">
                                                {{foreach from=$verify_status item=value key=key}}
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" value="{{$key}}" id="validated_identity{{$key}}" name="validated_identity" {{if $data.validated_mobile==$key}}checked="checked"{{/if}}> {{$value}}
                                                    </label>
                                                </div>
                                                {{/foreach}}
                                            </div>

                                            <hr>

                                            <div class="form-group form-material">

                                                <h4 class="example-title">状态</h4>

                                                <div class="radio-custom radio-custom radio-primary">

                                                    {{if $data.status==Model_User::STATUS_DELETED}}

                                                    <input type="radio" id="status1" name="status"   value="{{Model_User::STATUS_DELETED}}" checked="checked">
                                                    <label for="status1" >已删除</label>

                                                    {{else}}
                                                    {{foreach from=$_status item=value key=key}}
                                                    {{if !in_array($key,[Model_User::STATUS_DELETED,Model_User::STATUS_DENY])}}

                                                    <input type="radio"  id="status{{$key}}" name="status" {{if $data.status==$key}}checked="checked"{{/if}} value="{{$key}}">
                                                    <label for="status{{$key}}" > {{$value}}</label>

                                                    {{/if}}
                                                    {{/foreach}}
                                                    {{/if}}

                                                </div>

                                            </div>
                                            <hr>

                                            <div class="form-group">
                                                <label>登录状态</label>

                                                <div class="radio radio-custom radio-danger">

                                                        <input type="radio"   id="alllow_login1" name="allow_login" value="{{Model_User::ALLOW_LOGIN__ALLOWED}}" {{if $data.allow_login==Model_User::ALLOW_LOGIN__ALLOWED}}checked="checked"{{/if}}>
                                                        <label for="alllow_login1" >允许</label>


                                                        <input type="radio"   id="alllow_login2" name="allow_login" value="{{Model_User::ALLOW_LOGIN__DISALLOW}}" {{if $data.allow_login==Model_User::ALLOW_LOGIN__DISALLOW}}checked="checked"{{/if}}>
                                                        <label for="alllow_login2" >禁止</label>

                                                </div>

                                            </div>
                                            <hr>

                                            <div class="form-group">
                                                <label for="admin_log_message">更改理由 (管理员修改请填写本项后保存)</label>
                                                <textarea id="admin_log_message" name="admin_log_message" rows="5" class="form-control"></textarea>
                                            </div>


                                            <button type="submit" class="btn btn-primary" name="submit" value="保存"> 保存更改 </button>
                                            <button type="reset" class="btn btn-default">重置</button>
                                            <button type="submit" name="submit_delete" id="submit_delete" class="btn btn-danger" value="取消">取消(删除)</button>
                                        </form>
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



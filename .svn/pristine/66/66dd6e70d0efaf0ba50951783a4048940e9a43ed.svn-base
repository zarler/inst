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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>短信模板</h3>
                    </div>
                    <div class="panel-body">
                        {{include './_Tab.tpl'}}
                        {{include '../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-lg-12">
                                            <div class="col-lg-6">
                                            <form role="form" method="post" id="v_form">

                                                <div class="form-group">
                                                    <label for="title">标题</label>
                                                    <input class="form-control" id="title" name="title" placeholder="必填项" maxlength="25"  value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="code">服务名</label>
                                                    <input class="form-control" id="code" name="code" placeholder="必填项" maxlength="25" value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="body">模板内容</label>
                                                    <textarea id="body" name="body" rows="5" class="form-control"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_id">服务提供商</label>
                                                    <select class="form-control" id="provider_id" name="provider_id">
                                                        {{foreach from=$providers item=value key=key}}
                                                        <option value="{{$value.id}}" >{{$value.name}}</option>
                                                        {{/foreach}}
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>类型</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"  value="{{Model_SMS::TYPE_NORMAL}}" name="type" checked="checked">普通
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="{{Model_SMS::TYPE_OVERDUE}}" name="type">催收
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="{{Model_SMS::TYPE_AD}}" name="type">营销
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>状态</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"  value="{{Model_SMS::VALID}}" name="status">有效
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="{{Model_SMS::INVALID}}" name="status" checked="checked">无效
                                                        </label>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary" name="submit" value="保存">添加</button>
                                                <button type="reset" class="btn btn-default">重置</button>
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
    </div>
</main>
{{include '../_Common/Footer.tpl'}}
<script>
    $(document).ready(function () {
        $(".nav-tabs li a").each(function(){
            $(this).click(function(){
                $("#pthead").html($(this).text());
            });
        });
        $('#v_form').validate({
            rules: {
                title: {
                    maxlength: 25,
                    required: true
                },
                code: {
                    required: true,
                    maxlength: 25
                }
            },
            messages: {
                title: "标题必填,不超过25个字符",
                code: "服务名填,不超过25个字符",
            },
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (element) {
                element.closest('.form-group').removeClass('has-error').removeClass('has-success');
            }
        });

    });
</script>
</body>
</html>



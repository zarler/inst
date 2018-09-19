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
                                                    <input class="form-control" id="title" name="title" placeholder="模板标题"  value="{{$data.title}}">
                                                </div>


                                                <div class="form-group">
                                                    <label for="code">服务名</label>
                                                    <input class="form-control" id="code" name="code" placeholder="服务名"  value="{{$data.code}}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="body">模板内容</label>
                                                    <textarea id="body" name="body" rows="5" class="form-control">{{$data.body}}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bank_id">服务提供商</label>
                                                    <select class="form-control" id="provider_id" name="provider_id">
                                                        {{foreach from=$providers item=value key=key}}
                                                        <option value="{{$value.id}}"  {{if $value.id==$data.provider_id}}selected{{/if}}>{{$value.name}}</option>
                                                        {{/foreach}}
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>类型</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"  value="{{Model_SMS::TYPE_NORMAL}}" name="type" {{if $data.type==Model_SMS::TYPE_NORMAL}}checked="checked"{{/if}}>普通
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="{{Model_SMS::TYPE_OVERDUE}}" name="type" {{if $data.type==Model_SMS::TYPE_OVERDUE}}checked="checked"{{/if}}>催收
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="{{Model_SMS::TYPE_AD}}" name="type" {{if $data.type==Model_SMS::TYPE_AD}}checked="checked"{{/if}}>营销
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>状态</label>
                                                    {{if $data.status==Model_SMS::DELETED}}
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"  value="{{Model_SMS::DELETED}}" name="status" checked="checked">已取消
                                                        </label>
                                                    </div>
                                                    {{else}}
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"  value="{{Model_SMS::VALID}}" name="status" {{if $data.status==Model_SMS::VALID}}checked="checked"{{/if}}>有效
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="{{Model_SMS::INVALID}}" name="status" {{if $data.status==Model_SMS::INVALID}}checked="checked"{{/if}}>无效
                                                        </label>
                                                    </div>
                                                    {{/if}}
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
    </div>

</main>
{{include '../_Common/Footer.tpl'}}
<script>
    $(document).ready(function() {
        $(".nav-tabs li a").each(function(){
            $(this).click(function(){
                $("#pthead").html($(this).text());
            });
        });
        $("button[name='submit_delete']").click(function(){
            return confirm("确定要删除吗");
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



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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>银行列表</h3>
                    </div>
                    <div class="panel-body">
                        {{include './_Tab.tpl'}}
                        {{include '../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form role="form" method="post">

                                            <div class="form-group">
                                                <label for="name">银行名称</label>
                                                <input class="form-control" id="name" name="name" placeholder="必填项"  value="">
                                            </div>

                                            <div class="form-group">
                                                <label for="code">银行编号</label>
                                                <input class="form-control" id="code" name="code" maxlength="20" placeholder="必填项"  value="">
                                            </div>

                                            <div class="form-group">
                                                <label for="unionpay_code">银联编号</label>
                                                <input class="form-control" id="unionpay_code" name="unionpay_code" maxlength="20" placeholder=""  value="">
                                            </div>

                                            <div class="form-group">
                                                <label>状态</label>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio"  value="{{Model_Bank::STATUS_VALID}}" id="status1" name="status">有效
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" value="{{Model_Bank::STATUS_INVALID}}" id="status2" name="status" checked="checked">无效
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="rank">排序(越大越排前)</label>
                                                <input class="form-control" id="rank" name="rank" placeholder=""  value="0">
                                            </div>

                                            <button type="submit" class="btn btn-primary" name="submit" value="保存">添加</button>
                                            <button type="reset" class="btn btn-default">重置</button>
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
    $(function(){
        $(".nav-tabs li a").each(function(){
            $(this).click(function(){
                $("#pthead").html($(this).text());
            });
        });
    });
</script>
</body>
</html>



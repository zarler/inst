<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../../_Common/Header.tpl'}}
    <style type="text/css">
        .radio-custom label{margin-right:30px;}
    </style>
</head>

<body>
{{include '../../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">
            <div class="page-content">

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>短信服务商</h3>
                    </div>
                    <div class="panel-body">
                        {{include './_Tab.tpl'}}
                        {{include '../../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-lg-12">
                                            <div class="col-lg-6">
                                            <form role="form" method="post" id="v_form">

                                                <div class="form-group">
                                                    <label>提供商名</label>
                                                    <input class="form-control" id="name" name="name" placeholder="必填项" maxlength="25"  value="">
                                                </div>

                                                <div class="form-group">
                                                    <label>服务商编码</label>
                                                    <input class="form-control" id="provider" name="desc" placeholder="必填项" maxlength="25" value="">
                                                </div>

                                                <div class="form-group">
                                                    <label>描述</label>
                                                    <textarea id="desc" name="desc" rows="5" class="form-control"></textarea>
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
{{include '../../_Common/Footer.tpl'}}
</body>
</html>



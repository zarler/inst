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
            <div class="page-header">
                <h1 class="page-title">权限组</h1>
            </div>
            <div class="page-content">

                <div class="panel">
                    <div class="panel-body">

                        {{include './_GroupTab.tpl'}}
                        {{include '../../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-md-12">

                                        <form role="form" method="post">

                                            <div class="form-group  form-material">
                                                <label for="name">名称</label>
                                                <input class="form-control" id="name" name="name"  placeholder="必填项"  value="">
                                            </div>

                                            <div class="form-group  form-material">
                                                <label for="sort">排序(越大越排前)</label>
                                                <input class="form-control" id="sort" name="sort" value="0">
                                            </div>

                                            <div class="form-group  form-material">
                                                <label for="description">备注</label>
                                                <textarea id="description" name="description" rows="5" class="form-control"></textarea>
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
    <div class="page-loading vertical-align text-center">
        <div class="page-loader loader-default loader vertical-align-middle" data-type="default"></div>
    </div>
</main>
<!-- /wrapper -->
{{include '../../_Common/Footer.tpl'}}

</body>
</html>
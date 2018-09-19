<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../../../_Common/Header.tpl'}}
    <style type="text/css">
        .radio-custom label{margin-right:30px;}
    </style>
</head>

<body>
{{include '../../../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">

            <div class="page-content">

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>先锋支付</h3>
                    </div>
                    <div class="panel-body">
                        {{include '../../../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <form action="/{{$_controller}}/{{$_action}}" method="post">

                                            <div class="form-group">
                                                <label for="balance">余额</label>
                                                <input class="form-control" id="balance" name="balance" value="{{if isset($data['balance'])}}{{$data['balance']}}{{/if}}" autocomplete="on">
                                            </div>

                                            <div class="form-group">
                                                <label for="usableBalance">可用余额</label>
                                                <input class="form-control" id="bailBalance" name="bailBalance" value="{{if isset($data['bailBalance'])}}{{$data['bailBalance']}}{{/if}}" autocomplete="on">
                                            </div>

                                            <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                                        </form>
                                    </div>
                                    <div class="col-lg-6">
                                        {{if $message!==NULL}}
                                        <div class="form-group">
                                            <label for="result">返回:</label>
                                            <textarea id="result" name="result" rows="8" class="form-control">{{$result}}</textarea>
                                        </div>
                                        {{if $result_array!==NULL}}
                                        <div class="form-group">
                                            <label for="result_json">json decode:</label>
                                            <textarea id="result_json" name="result_json" rows="8" class="form-control">{{print_r($result_array)}}</textarea>
                                        </div>
                                        {{/if}}
                                        {{/if}}
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
{{include '../../../_Common/Footer.tpl'}}
</body>
</html>
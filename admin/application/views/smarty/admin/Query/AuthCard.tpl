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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>银行卡验卡</h3>
                    </div>
                    <div class="panel-body">
                        {{include '../_Common/Form_Message.tpl'}}
                        <div class="example-wrap">
                            <div class="example">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="panel-body">
                                            <form action="/{{$_controller}}/{{$_action}}" method="post">
                                                <div class="form-group">
                                                    <label for="holder">姓名</label>
                                                    <input class="form-control" id="holder" name="holder" value="{{$postarr['holder']}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="identity_code">身份证</label>
                                                    <input class="form-control" id="identity_code" name="identity_code" value="{{$postarr['identity_code']}}" autocomplete="on">
                                                </div>
                                                <div class="form-group">
                                                    <label for="mobile">手机号</label>
                                                    <input class="form-control" id="mobile" name="mobile" value="{{$postarr['mobile']}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="bank_no">借记卡卡号</label>
                                                    <input class="form-control" id="bank_no" name="bank_no" value="{{$postarr['bank_no']}}">
                                                </div>
                                                <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        {{if $result!==NULL}}
                                        <div class="form-group">
                                            <label for="result">返回:</label>
                                            <textarea id="result" name="result" rows="8" class="form-control">{{$result}}</textarea>
                                        </div>
                                        {{if $result_array!==NULL}}
                                        <div class="form-group">
                                            {{if $bank_name!==NULL}}
                                            <label for="bank_name">{{$bank_name}}</label>
                                            {{/if}}
                                            {{if $card_type!==NULL}}
                                            <label for="card_type">{{$card_type}}</label>
                                            {{/if}}
                                        </div>
                                        <div class="form-group">
                                            <label for="result_json">json decode:</label>
                                            <textarea id="result" name="result" rows="8" class="form-control">{{print_r($result_array)}}</textarea>
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
{{include '../_Common/Footer.tpl'}}
</body>
</html>
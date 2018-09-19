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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>系统设置</h3>
                    </div>
                    <div class="panel-body">
                        {{include './_Tab.tpl'}}
                        {{include '../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form name="add_form" id="add_form" method="post">
                                            <div class="form-group">
                                                <label for="name">变量名</label>
                                                <input class="form-control" id="name" name="name" placeholder="必填项" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="val_type">变量类型</label>
                                                <select class="form-control" id="val_type" name="val_type">
                                                    <option value="bool" selected>布尔</option>
                                                    <option value="int">整数</option>
                                                    <option value="float">浮点数</option>
                                                    <option value="string">字符串</option>
                                                    <option value="text">长文本</option>
                                                    <option value="json">JSON字符串</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="value">变量值 </label>
                                                <div id="radioitem">
                                                    <input type="radio" name="radiovalue" value="1" checked> 开启
                                                    <input type="radio" name="radiovalue" value="0"> 关闭
                                                </div>
                                                <div id="txtitem" style="display: none">
                                                    <input class="form-control" id="txtvalue" name="txtvalue" placeholder="必填项" value="">
                                                </div>
                                                <div id="textareaitem" style="display: none">
                                                    <textarea style="width: 100%" rows="5" name="textareavalue" id="textareavalue" placeholder="必填项"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="typename">类别</label>
                                                <select class="form-control" id="typename" name="typename">
                                                    {{foreach from=$navres item=val }}
                                                    <option value="{{$val.typeid}}|{{$val.typename}}">{{$val.typename}}</option>
                                                    {{/foreach}}
                                                    <option value="0">新增</option>
                                                </select>
                                                <div id="moretypeitem" style="display: none"><input class="form-control" id="moretype" name="moretype" placeholder="必填项"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">说明</label>
                                                <input class="form-control" id="description" name="description">
                                            </div>
                                            <input type="hidden" class="form-control" name="maxtid" value="{{count($navres)}}">
                                            <button class="btn btn-primary" id="add_btn" name="submit" value="添加">添加</button>
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
<script>
    $(function(){
        $("#val_type").bind('change',function(){
            if($(this).val()=='bool'){
                $("#txtitem").hide();
                $("#textareaitem").hide();
                $("#radioitem").show();
            }else if($(this).val()=='text'||$(this).val()=='json'){
                $("#txtitem").hide();
                $("#radioitem").hide();
                $("#textareaitem").show();
            }else{
                $("#textareaitem").hide();
                $("#radioitem").hide();
                $("#txtitem").show();
            }
        });
        $("#typename").bind('change',function(){
            console.log($(this).val());
            if($(this).val()=='0'){
                $("#moretypeitem").show();
            }else{
                $("#moretypeitem").hide();
            }
        });
    });
</script>
</body>
</html>



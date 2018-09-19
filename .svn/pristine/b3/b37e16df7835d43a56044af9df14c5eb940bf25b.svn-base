<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

{{include '../_Common/Header.tpl'}}
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

                        <div class="example-warp">
                            <div class="example">
                                <div class="table-responsive">
                                    <form id="Configfrom" name="Configfrom" method="post">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <tr>
                                                    <th width="10%">变量名</th>
                                                    <th width="5%">类型</th>
                                                    <th width="40%">说明 </th>
                                                    <th width="35%">变量值 <small style="font-weight: normal; color:#999; float: right;">点击修改，修改变量设置，保存设置</small></th>
                                                    <th width="10%">操作</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{foreach from=$tablist item=value }}
                                                <tr>
                                                    <td><input type="text" class="inputxt notxt" name="name_{{$value.id}}" value="{{$value.name}}" disabled></td>
                                                    <td><select class="selectitem noselect" name="val_type_{{$value.id}}" disabled>
                                                            <option value="bool" {{if $value.val_type=='bool'}}selected{{/if}}>布尔</option>
                                                            <option value="int" {{if $value.val_type=='int'}}selected{{/if}}>整数</option>
                                                            <option value="float" {{if $value.val_type=='float'}}selected{{/if}}>浮点数</option>
                                                            <option value="string" {{if $value.val_type=='string'}}selected{{/if}}>字符串</option>
                                                            <option value="text" {{if $value.val_type=='text'}}selected{{/if}}>长文本</option>
                                                            <option value="json" {{if $value.val_type=='json'}}selected{{/if}}>JSON</option>
                                                        </select></td>
                                                    <td><input type="text" class="inputxt notxt" size="50" name="des_{{$value.id}}" value="{{$value.description}}" disabled></td>
                                                    <td class="valbox">{{if $value.val_type=='bool'}}
                                                        <input type="radio" name="val_{{$value.id}}" value="1"
                                                               {{if $value.value}}checked{{/if}} disabled><small>开启</small>
                                                        <input type="radio" name="val_{{$value.id}}" value="0"
                                                               {{if (!$value.value)}}checked{{/if}} disabled><small>关闭</small>
                                                        {{elseif ($value.val_type=='json'||$value.val_type=='text')}}
                                                        <textarea style="width: 100%;resize: none;" class="notextarea" rows="5" name="val_{{$value.id}}" disabled>{{$value.value}}</textarea>
                                                        {{else}}
                                                        <input class="inputxt notxt" size="30" name="val_{{$value.id}}" value="{{$value.value}}" disabled>
                                                        {{/if}} </td>
                                                    <td><button type="button" class="btn btn-primary modbt" data-id="{{$value.id}}" name="mod" value="修改">修改</button>
                                                        <button style="display: none" type="button" class="btn btn-reddit backbt" data-id="{{$value.id}}" name="back" value="返回">返回</button>
                                                        <button type="button" class="btn btn-default" onclick="if (confirm('NOTICE:您确定隐藏？！！！')){location.href='/{{$_controller}}/Recovery?id={{$value.id}}&is_show=0';}" name="valhide" value="隐藏">隐藏</button>
                                                    </td>
                                                </tr>
                                                {{/foreach}}
                                                <input type="hidden" name="typeid" value="{{$typeid}}">
                                                <input type="hidden" id="modidstr" name="modidstr" value="">
                                            </tbody>
                                        </table>
                                    <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">保存</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<!-- /wrapper -->
{{include '../_Common/Footer.tpl'}}
<script>
    $(function(){
        $(".modbt").each(function(){
            $(this).click(function(){
                $(this).hide();
                $(this).siblings('.backbt').show();
                $(this).parent().siblings('td').find('.inputxt').attr('disabled',false).removeClass('notxt');
                $(this).parent().siblings('td').find('input[type="radio"]').attr('disabled',false).removeClass('notxt');
                $(this).parent().siblings('td').find('select').attr('disabled',false).removeClass('noselect');
                $(this).parent().siblings('td').find('textarea').attr('disabled',false).removeClass('notextarea');
                $("#modidstr").val($("#modidstr").val()+'|'+$(this).attr('data-id'));
            });
        });
        $(".backbt").each(function(){
            $(this).click(function(){
                $(this).hide();
                $(this).siblings('.modbt').show();
                $(this).parent().siblings('td').find('.inputxt').attr('disabled',true).addClass('notxt');
                $(this).parent().siblings('td').find('input[type="radio"]').attr('disabled',true).addClass('notxt');
                $(this).parent().siblings('td').find('select').attr('disabled',true).addClass('noselect');
                $(this).parent().siblings('td').find('textarea').attr('disabled',true).addClass('notextarea');
                $("#modidstr").val($("#modidstr").val().replace('|'+$(this).attr('data-id'),''));
            });
        });
    });
</script>
</body>
</html>


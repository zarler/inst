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
                <h1 class="page-title">权限项</h1>
            </div>
            <div class="page-content">

                <div class="panel">
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}
                        {{include '../../_Common/Form_Message.tpl'}}

                        <div class="example-wrap">

                            <div class="example">

                                <div class="row">
                                    <div class="col-md-12">


                                        <form role="form" method="post">

                                            <div class="form-group">
                                                <label for="fid">父菜单</label>
                                                <select class="form-control" id="fid" name="fid">
                                                    <option value="0" {{if $data.fid==0}}selected{{/if}}> 顶级菜单 </option>
                                                    {{foreach from=$menu_tree[0] item=value key=key}}
                                                    <option value="{{$value.id}}" {{if $data.fid==$value.id}}selected{{/if}}>{{str_repeat('  ｜  ',$value.level)}}{{$value.name}}</option>

                                                    {{if isset($menu_tree[$value.id]) && is_array($menu_tree[$value.id])}}
                                                    {{foreach from=$menu_tree[$value.id] item=value2 key=key2}}
                                                    <option value="{{$value2.id}}" {{if $data.fid==$value2.id}}selected{{/if}}>{{str_repeat('  ｜  ',$value2.level)}}{{$value2.name}}</option>　

                                                    {{if isset($menu_tree[$value2.id]) && is_array($menu_tree[$value2.id])}}
                                                    {{foreach from=$menu_tree[$value2.id]  item=value3 key=key3}}
                                                    <option value="{{$value3.id}}" {{if $data.fid==$value3.id}}selected{{/if}}>{{str_repeat('  ｜  ',$value3.level)}}{{$value3.name}}</option>　
                                                    {{/foreach}}
                                                    {{/if}}
                                                    {{/foreach}}
                                                    {{/if}}

                                                    {{/foreach}}
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <label for="name">菜单名称</label>
                                                <input class="form-control" id="name" name="name" placeholder="必填项"  value="{{$data.name}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="url">URL</label>
                                                <input class="form-control" id="url" name="url" placeholder="必填项"  value="{{$data.url}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="controller">Controller</label>
                                                <input class="form-control" id="controller" name="controller" value="{{$data.controller}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="action">Action</label>
                                                <input class="form-control" id="action" name="action" value="{{$data.action}}">
                                            </div>

                                            <div class="form-group">
                                                <label>公共显示</label>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio"  value="1" id="show_yes" name="pub_show" {{if $data.pub_show==1}}checked="checked"{{/if}}>显示
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" value="0" id="show_no" name="pub_show" {{if $data.pub_show==0}}checked="checked"{{/if}}>不显示
                                                    </label>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="group_show">组显示</label>
                                                <select class="form-control" name="group_show" id="group_show">
                                                    <option value="0" {{if $data.group_show==0}}selected{{/if}}> 不指定 </option>
                                                    {{if is_array($user_group)}}
                                                    {{foreach from=$user_group  item=value key=key}}
                                                    <option value="{{$value.id}}" {{if $data.group_show==$value.id}}selected{{/if}}>{{$value.name}}</option>　
                                                    {{/foreach}}
                                                    {{/if}}
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="sort">排序(越大越排前)</label>
                                                <input class="form-control" id="sort" name="sort" value="{{$data.sort}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="description">备注</label>
                                                <textarea id="description" name="description" rows="5" class="form-control">{{$data.description}}</textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary" name="submit" value="保存">保存</button>
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

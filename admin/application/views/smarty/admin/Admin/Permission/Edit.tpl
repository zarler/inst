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

                                            <div class="form-group   ">
                                                <label for="group_id">权限组</label>
                                                <select class="form-control" id="group_id" name="group_id">
                                                    <option value="0" {{if $data.group_id==0}}selected{{/if}}> 未指定 </option>
                                                    {{foreach from=$permission_group item=value key=key}}
                                                    <option value="{{$value.id}}" {{if $data.group_id==$value.id}}selected{{/if}}>{{$value.name}}</option>
                                                    {{/foreach}}
                                                </select>
                                            </div>


                                            <div class="form-group ">
                                                <label for="name">名称</label>
                                                <input class="form-control" id="name" name="name" placeholder="必填项"  value="{{$data.name}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="controller">Controller</label>
                                                <input class="form-control" id="controller" name="controller"  placeholder="必填项" value="{{$data.controller}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="action">Action</label>
                                                <input class="form-control" id="action" name="action" placeholder="必填项"  value="{{$data.action}}">
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
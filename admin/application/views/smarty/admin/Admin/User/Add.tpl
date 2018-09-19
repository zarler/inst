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
                <h1 class="page-title">添加管理员</h1>
            </div>
            <div class="page-content">

                <div class="panel">
                    <div class="panel-body">


                        <div class="example-wrap">
                            {{include './_Tab.tpl'}}
                            <div class="example">

                                    <div class="row">
                                        <div class="col-md-12">
                                            {{include '../../_Common/Form_Message.tpl'}}
                                            <form role="form" method="post">



                                                <div class="form-group form-material row  ">
                                                    <div class="col-sm-6">
                                                        <label for="username" class="text-danger">用户名<small>(*)</small></label>
                                                        <input class="form-control" id="username" name="username" placeholder="用户名必填,最少3位"  value="">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="email" class="text-danger">邮箱<small>(*)</small></label>
                                                        <input class="form-control" id="email" name="email" placeholder="邮箱必填"  value="">
                                                    </div>
                                                </div>
                                                <div class="form-group form-material row">
                                                    <div class="col-sm-6">
                                                        <label for="password" class="text-danger">初始密码<small>(*)</small></label>
                                                        <input class="form-control" id="password" name="password" type="password" placeholder="密码必填,最少6位"  value="">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="number">工号</label>
                                                        <input class="form-control" id="number" name="number" value="" maxlength="20">
                                                    </div>
                                                </div>

                                                <div class="form-group form-material row">
                                                    <div class="col-sm-6">
                                                        <label for="name">姓名</label>
                                                        <input class="form-control" id="name" name="name" value="">
                                                    </div>

                                                        <div class="col-sm-6">
                                                            <label for="mobile">手机</label>
                                                            <input class="form-control" id="mobile" name="mobile" value="">
                                                        </div>


                                                </div>


                                                <div class="form-group form-material row">

                                                    <div class="col-sm-6">
                                                        <label for="department">部门</label>
                                                        <input class="form-control" id="department" name="department" placeholder="例如:财务部"  value="">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="phone">座机</label>
                                                        <input class="form-control" id="phone" name="phone" value="">
                                                    </div>

                                                </div>
                                                <div class="form-group form-material">
                                                    <label for="job">公职</label>
                                                    <input class="form-control" id="job" name="job" placeholder="例如:工程师"  value="">
                                                </div>



                                                <div class="form-group form-material">

                                                        <h4 class="example-title">状态</h4>

                                                        <div class="radio-custom radio-success">
                                                            <input type="radio" id="status1" name="status" checked="" value="1">
                                                            <label for="status1" >正常</label>

                                                            <input type="radio" id="status2" name="status"  value="2" >
                                                            <label for="status2" >选中（禁用）</label>

                                                            <input type="radio" id="status3" name="status"  value="3">
                                                            <label for="status3"  >选中（删除）</label>
                                                        </div>

                                                </div>



                                                <div class="form-group ">
                                                    <label  class="control-label" for="group_id[]">管理员组<small>(*)</small></label>
                                                    <select class="form-control" id="group_id[]" name="group_id[]" multiple="multiple" size="5">
                                                        <option value="0"  class="text-danger" selected> 此项必选 </option>
                                                        {{foreach from=$user_group item=value key=key}}
                                                          <option value="{{$value.id}}">{{$value.name}}</option>
                                                        {{/foreach}}
                                                    </select>
                                                </div>

                                                <div class="form-group ">
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
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
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>人脸识别列表</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}


                        <div class="example-warp">
                                <div class="table-responsive">
                                    <table class="table  table-striped  table-hover">
                                          <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>用户ID</th>
                                                <th>姓名</th>
                                                <th>手机号</th>
                                                <th>身份证</th>
                                                <th>性别</th>
                                                <th>民族</th>
                                                <th>户籍地址</th>
                                                <th>活体分数</th>
                                                <th>活体状态</th>
                                                <th>网纹分数</th>
                                                <th>网纹状态</th>
                                                <th>验证时间</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{foreach from=$list item=value key=key}}

                                            <tr>
                                                <td>{{$value.id}}</td>
                                                <td>{{$value.user_id}}</td>
                                                <td>{{$value.name}}</td>
                                                <td>{{$value.mobile}}</td>
                                                <td>{{$value.identity_code}}</td>
                                                <td>{{$value.gender}}</td>
                                                <td>{{$value.race}}</td>
                                                <td>{{$value.address}}</td>
                                                <td>{{$value.score}}</td>
                                                <td>{{if isset($_status[$value.status])}}{{$_status[$value.status]}}{{else}}未知{{/if}}</td>
                                                <td>{{$value.identity_score}}</td>
                                                <td>{{if isset($_identity_status[$value.identity_status])}}{{$_identity_status[$value.identity_status]}}{{else}}未知{{/if}}</td>
                                                <td>{{date('Y-m-d H:i:s',$value.create_time)}}</td>
                                                <td>
                                                    <a href="/{{$_controller}}/Detail?id={{$value.id}}" class="btn btn-primary btn-sm">详情</a>
                                                </td>
                                            </tr>
                                            {{/foreach}}
                                        </tbody>
                                        

                                    </table>

                                    {{$pagination}}
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
</body>
</html>


<!-- /tabs -->
<div class="example" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs" role="tablist">

            <li {{if $_action=='List' && $option==''}}class="active"{{/if}}role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 列表 </a>
            </li>

         

            <li {{if $_action=='List' && $option }}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List?option=my" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 我的审核</a>
            </li>

            <li role="presentation">
                <a data-toggle="tab" href="#search" aria-controls="search_tab" role="tab" aria-expanded="false"> 搜索 </a>
            </li>


        </ul>

        <div class="tab-content padding-top-20">
            <div class="tab-pane " id="edit_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>ch
            </div>
            <div class="tab-pane" id="delete_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
            <div class="tab-pane" id="search_tab" role="tabpanel">
                <div class="col-sm-10">
                    <div class="example-wrap  example-well container-fluid page-header">
                         <form role="form" name="search_form1" id="search_form1" method="get" action="/{{$_controller}}/List">

                    <div class="form-group col-sm-2">
                        <label for="mobile">用户ID</label>
                        <input class="form-control" id="id" name="id" value="">
                    </div>

                    <div class="form-group col-sm-2">
                        <label for="mobile">手机号</label>
                        <input class="form-control" id="mobile" name="mobile" value="">
                    </div>

                    <div class="form-group col-sm-2">
                        <label for="name">姓名</label>
                        <input class="form-control" id="name" name="name" value="">
                    </div>

                    <div class="form-group col-sm-2">
                        <label for="identity_code">身份证</label>
                        <input class="form-control" id="identity_code" name="identity_code" value="">
                    </div>


                    <div class="form-group col-sm-2">
                        <label for="status">状态</label>
                        <select class="form-control" id="credit_auth" name="credit_auth">
                            <option value="">--请选择授信状态--</option>
                            <option value="3">已提交</option>
                            <option value="4">待进一步审核</option>

                        </select>
                    </div>

                    <div class="form-group col-sm-2 padding-top-20">
                            <label class="control-label" for="inputBasicNickName"></label>
                            <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                    </div>

                </form>

                    </div>
                </div>
            </div>



        </div>
    </div>

</div>


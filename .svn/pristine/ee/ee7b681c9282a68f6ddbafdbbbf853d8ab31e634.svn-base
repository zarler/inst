<div class="example" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs" role="tablist">

            <li {{if $_action=='List'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 列表 </a>
            </li>

            <li {{if $_action=='Add'}}class="active"{{/if}} role="presentation">

                <a data-toggle="tab" href="/{{$_controller}}/Add" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 添加 </a>

            </li>

            <li {{if $_action=='Edit'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="#edit" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 更改 </a>
            </li>

            <li role="presentation">
                <a data-toggle="tab" href="#search" aria-controls="search_tab" role="tab" aria-expanded="false"> 搜索 </a>
            </li>


        </ul>

        <div class="tab-content padding-top-20">
            <div class="tab-pane " id="edit_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
            <div class="tab-pane" id="edit_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
            <div class="tab-pane" id="search_tab" role="tabpanel">
                <div class="col-sm-12">
                    <div class="example-wrap  example-well container-fluid page-header">


                        <form role="form" name="search_form1" id="search_form1" method="get" action="/{{$_controller}}/List">
                            <div class="form-group col-sm-2">
                                <label for="group_id">管理员组</label>
                                <select class="form-control" id="group_id" name="group_id">
                                    <option value="0" selected> 不限 </option>
                                    {{foreach from=$user_group item=value key=key}}
                                    <option value="{{$value.id}}">{{$value.name}}</option>
                                    {{/foreach}}
                                </select>
                            </div>


                            <div class="form-group col-sm-2">
                                    <label for="username">用户名</label>
                                    <input class="form-control"  name="username" value="">
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="email">邮箱</label>
                                    <input class="form-control"   name="email" value="">
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="name">姓名</label>
                                    <input class="form-control" name="name" value="">
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="status">状态</label>
                                    <select class="form-control" name="status">
                                        <option value="" selected> 不限 </option>
                                        <option value="1">正常</option>
                                        <option value="2">禁止</option>
                                        <option value="3">已删除</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <button type="sublimit" class="btn btn-primary" style="margin-top: 20px;" id="search_btn1">查询</button>
                                </div>

                            </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>




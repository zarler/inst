<!-- /tabs -->
<div class="example" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs" role="tablist">

            <li {{if $_action=='List'}}class="active"{{/if}}role="presentation">
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
                <p>请选择相应项目,点击更改按钮.</p>ch
            </div>
            <div class="tab-pane" id="delete_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
            <div class="tab-pane" id="search_tab" role="tabpanel">
                <div class="col-sm-10">
                    <div class="example-wrap  example-well container-fluid page-header">
                        <form role="form" name="search_form1" id="search_form1" method="get" action="/{{$_controller}}/List">
                            <div class="form-group">
                                <label for="group_id">权限组</label>
                                <select class="form-control"  name="group_id">
                                    <option value="0" selected> 不限制</option>
                                    {{foreach from=$permission_group item=value key=key}}
                                    <option value="{{$value.id}}">{{$value.name}}</option>
                                    {{/foreach}}
                                </select>
                            </div>

                            <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                        </form>

                    </div>
                </div>
            </div>



        </div>
    </div>

</div>




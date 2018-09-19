
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
                                <label for="fid">父菜单</label>
                                <select class="form-control" id="fid" name="fid">
                                    <option value="0" selected> 顶级菜单 </option>
                                    {{foreach from=$menu_tree[0] item=value key=key}}
                                    <option value="{{$value.id}}">{{str_repeat('  ｜  ',$value.level)}}{{$value.name}}</option>

                                    {{if isset($menu_tree[$value.id]) && is_array($menu_tree[$value.id])}}
                                    {{foreach from=$menu_tree[$value.id] item=value2 key=key2}}
                                    <option value="{{$value2.id}}">{{str_repeat('  ｜  ',$value2.level)}}{{$value2.name}}</option>　

                                    {{if isset($menu_tree[$value2.id]) && is_array($menu_tree[$value2.id])}}
                                    {{foreach from=$menu_tree[$value2.id]  item=value3 key=key3}}
                                    <option value="{{$value3.id}}">{{str_repeat('  ｜  ',$value3.level)}}{{$value3.name}}</option>　
                                    {{/foreach}}
                                    {{/if}}

                                    {{/foreach}}
                                    {{/if}}

                                    {{/foreach}}
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="name">菜单名称</label>
                                <input class="form-control" id="name" name="name" value="">
                            </div>

                            <div class="form-group">
                                <label for="url">URL</label>
                                <input class="form-control" id="url" name="url" value="">
                            </div>

                            <div class="form-group">
                                <label for="controller">Controller</label>
                                <input class="form-control" id="controller" name="controller" value="">
                            </div>

                            <div class="form-group">
                                <label for="action">Action</label>
                                <input class="form-control" id="action" name="action" value="">
                            </div>
                            <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                        </form>

                    </div>
                </div>
            </div>



        </div>
    </div>

</div>



<div class="example-wrap" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">

            <li {{if $_action=='List'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 列表 </a>
            </li>

            <li {{if $_action=='Add'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/Add" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 添加 </a>
            </li>

            <li {{if $_action=='Edit'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="#edit" aria-controls="edit_tab" role="tab" aria-expanded="false"> 更改 </a>
            </li>

            <li role="presentation">
                <a data-toggle="tab" href="#search" aria-controls="search_tab" role="tab" aria-expanded="false"> 搜索 </a>
            </li>

        </ul>

        <div class="tab-content padding-top-20">

            <div class="tab-pane" id="edit_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
            <div class="tab-pane  " id="search_tab" role="tabpanel">
                <form role="form" name="search_form1" id="search_form1" method="get" action="/{{$_controller}}/List">
                    <div class="form-group">
                        <label for="status">状态</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0" selected> 不限 </option>
                            {{foreach from=$_status item=value key=key}}
                            <option value="{{$key}}">{{$value}}</option>
                            {{/foreach}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">类型</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0" selected> 不限 </option>
                            {{foreach from=$_types item=value key=key}}
                            <option value="{{$key}}">{{$value}}</option>
                            {{/foreach}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">标题</label>
                        <input class="form-control" id="title" name="title" value="">
                    </div>

                    <div class="form-group">
                        <label for="code">服务名</label>
                        <input class="form-control" id="code" name="code" value="">
                    </div>
                    <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                </form>
            </div>
        </div>
    </div>

</div>


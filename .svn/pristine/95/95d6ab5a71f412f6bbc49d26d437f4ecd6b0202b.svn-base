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

                  <div class="row  container-fluid">
                        <div class="form-group col-sm-2">
                            <input type="text" class="form-control col-sm-3" id="name" name="name" placeholder="银行名称" autocomplete="off">
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label" for="inputBasicNickName"></label>
                          <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>

</div>


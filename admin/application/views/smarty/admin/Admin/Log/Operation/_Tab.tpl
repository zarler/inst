<!-- /tabs -->
<div class="example" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs" role="tablist">

            <li {{if $_action=='List'}}class="active"{{/if}}role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 列表 </a>
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
                                <label for="username">用户名</label>
                                <input class="form-control" id="username" name="username" value="">
                            </div>
                            <div class="form-group">
                                <label for="time_start">时间范围</label>
                                <p>
                                    从<input class="form-control" id="time_start" name="time_start" value="" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
                                    至<input class="form-control" id="time_end" name="time_end" value="" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
                                </p>
                            </div>

                            <button class="btn btn-primary" id="search_btn1" name="submit" value="查询">查询</button>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>




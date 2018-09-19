<div class="example-wrap margin-bottom-0" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">

            <li {{if $_action=='List'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 列表 </a>
            </li>


            <li role="presentation">
                <a data-toggle="tab" href="#search" aria-controls="search_tab" role="tab" aria-expanded="false"> 搜索 </a>
            </li>

        </ul>

        <div class="tab-content padding-top-20 ">

            <div class="tab-pane" id="edit_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
            <div class="tab-pane bg-primary-100  padding-20" id="search_tab" role="tabpanel">

                <form role="form" name="search_form1" id="search_form1" method="get" action="/{{$_controller}}/List">

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="order_no">订单号</label>
                            <input class="form-control"   name="order_no" value="">
                        </div>
                        <div class="col-sm-3">
                            <label for="mobile">手机号</label>
                            <input class="form-control"  name="mobile" value="">
                        </div>
                        <div class="col-sm-3">
                            <label for="name">姓名</label>
                            <input class="form-control"   name="name" value="">
                        </div>
                        <div class="col-sm-3">
                            <label for="identity_code">身份证</label>
                            <input class="form-control"   name="identity_code" value="">
                        </div>
                    </div>




                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="status">状态</label>
                            <select class="form-control" id="status" name="status">
                                <option value=""> 不限 </option>
                                {{foreach from=$_status item=value key=key}}
                                <option value="{{$key}}">{{$value}}</option>
                                {{/foreach}}
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="type">类型</label>
                            <select class="form-control"  name="type">
                                <option value=""> 全部 </option>
                                {{foreach from=$_type item=value key=key}}
                                <option value="{{$key}}">{{$value}}</option>
                                {{/foreach}}
                            </select>
                        </div>
                        <div class="col-sm-3 padding-top-20">

                            <button class="btn btn-primary " id="search_btn1" name="submit" value="查询">查询</button>
                        </div>
                    </div>


                </form>
            </div>

        </div>
    </div>

</div>


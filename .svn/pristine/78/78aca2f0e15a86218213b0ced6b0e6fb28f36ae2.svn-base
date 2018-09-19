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

                 
                        <div class="form-group col-sm-2">
                            <label for="name">姓名</label>
                            <input type="text" class="form-control col-sm-3" id="name" name="name" placeholder="姓名" autocomplete="off">
                        </div>
                        <div class="form-group  col-sm-2">
                            <label for="name">手机号</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="手机号" autocomplete="off">
                        </div>
                        <div class="form-group  col-sm-2">
                            <label for="name">身份证号码</label>
                            <input type="text" class="form-control" id="identity_code" name="identity_code" placeholder="身份证号码" autocomplete="off">
                        </div>


                      <div class="form-group  col-sm-2">
                          <label for="card_no">借记卡卡号</label>
                          <input class="form-control" id="bank_card_no" name="bank_card_no" value="">
                      </div>

                      <div class="form-group  col-sm-2">
                          <label for="card_no">信用卡卡号</label>
                          <input class="form-control" id="credit_card_no" name="credit_card_no" value="">
                      </div>


                      <div class="form-group col-sm-2 padding-top-20">
                            <label class="control-label" for="inputBasicNickName"></label>
                            <button type="button" class="btn btn-primary" id="search_btn1">查询</button>
                        </div>
                   

                </form>

                    </div>
                </div>
            </div>



        </div>
    </div>

</div>



<!-- tabs -->
<ul class="nav nav-tabs">
    <li {{if $_action=='List'}}class="active"{{/if}}><a href="/{{$_controller}}/List">列表</a>
    </li>
    <li><a href="#search_tab" data-toggle="tab">搜索</a>
    </li>
</ul>
<!-- tab panes -->
<div class="tab-content">
    <div class="tab-pane fade" id="search_tab">
        <div class="col-lg-6"><div class="panel-body">
        <form role="form" name="search_form1" id="search_form1" method="get" action="/{{$_controller}}/List">
            <div class="form-group">
                <label for="ext">扩展名</label>
                <input class="form-control" id="ext" name="ext" value="">
            </div>
            <div class="form-group">
                <label for="user_id">用户id</label>
                <input class="form-control" id="user_id" name="user_id" value="">
            </div>
            <div class="form-group">
                <label for="username">用户名</label>
                <input class="form-control" id="username" name="username" value="">
            </div>
            <div class="form-group">
                <label for="email">邮箱</label>
                <input class="form-control" id="email" name="email" value="">
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
            </div></div>
    </div>
</div>
<!-- /tabs -->

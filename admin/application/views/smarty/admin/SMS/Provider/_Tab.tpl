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
        </ul>

        <div class="tab-content padding-top-20">
            <div class="tab-pane" id="edit_tab" role="tabpanel">
                <p>请选择相应项目,点击更改按钮.</p>
            </div>
    </div>

</div>


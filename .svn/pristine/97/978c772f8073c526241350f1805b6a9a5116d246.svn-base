<div class="example-wrap" id="contabs_wrap">
    <div class="nav-tabs-horizontal" data-approve="nav-tabs">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">

            <li {{if $_action=='List'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/List" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 列表 </a>
            </li>

            {{foreach from=$navres item=val }}
            <li {{if $typeid==$val.typeid}}class="active"{{/if}} role="presentation">
                <a href="/{{$_controller}}/List?typeid={{$val.typeid}}">{{$val.typename}}</a>
            </li>
            {{/foreach}}

            <li {{if $_action=='Add'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/Add" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 添加变量 </a>
            </li>

            <li {{if $_action=='Recovery'}}class="active"{{/if}} role="presentation">
                <a data-toggle="tab" href="/{{$_controller}}/Recovery" aria-controls="exampleTabsOne" role="tab" aria-expanded="false"> 变量回收 </a>
            </li>

        </ul>
    </div>
</div>


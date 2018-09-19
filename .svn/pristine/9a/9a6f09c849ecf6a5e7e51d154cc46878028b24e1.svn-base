<nav class="site-contabs" >
    <div class="btn-group pull-left">
        <ol class="breadcrumb breadcrumb-arrow" >
            <li> <a class="icon wb-home" href="javascript:;">首页</a> </li>
           {{if $parent_menu}}
            {{foreach from=array_reverse($parent_menu) item=value key=key}}
            {{if count($parent_menu)==$key+1}}
            <li class="active"> {{$value.name}} </li>
            {{else}}
             <li> <a href="{{$value.url}}">{{$value.name}}</a> </li>
            {{/if}}
             {{/foreach}}
            {{/if}}

        </ol>
    </div>

    <div class="btn-group pull-right">
        <button type="button" class="btn btn-icon btn-default hide">
            <i class="icon fa-angle-double-right"></i>
        </button>
        {{if $_action=='List'}}
        <button type="button" class="btn btn-default dropdown-toggle btn-outline" data-toggle="dropdown" aria-expanded="false" onclick="window.location.reload();">
            <span class="icon wb-reload  text-success"></span>  <span class=" text-danger">刷新当前页面</span>
        </button>

        {{/if}}

    </div>
</nav>
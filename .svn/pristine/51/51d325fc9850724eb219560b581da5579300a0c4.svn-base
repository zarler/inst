<ul class="nav nav-tabs nav-tabs-line tabList font-size-14" role="tablist">


    <li  class="news {{if (!empty($_action) && $_action=='Detail')}}active{{/if}}">
        <a href="/User/Detail?id={{if !empty($user_id)}}{{$user_id}}{{else}}{{$user.id}}{{/if}}" >
            <i class="icon wb-user" aria-hidden="true"></i>基本信息

        </a>
    </li>
    <li  {{if (!empty($_action) && $_action=='CreditAuth')}}class="active" {{/if}}>
        <a href="/User_Audit/Report" >
            <i class="icon fa-book" aria-hidden="true"></i> 授信信息
        </a>
    </li>
    <li {{if (!empty($_action) && $_action=='OrderList')}}class="active" {{/if}}>
        <a href="/User/OrderList?user_id={{if !empty($user_id)}}{{$user_id}}{{else}}{{$user.id}}{{/if}}" >
            <i class="site-menu-icon fa-file-text-o" aria-hidden="true"></i>
            借款记录
        </a>
    </li>

    <li {{if (!empty($_action) && $_action=='Location')}}class="active" {{/if}}>
        <a href="/User/Location?user_id={{if !empty($user_id)}}{{$user_id}}{{else}}{{$user.id}}{{/if}}" >地理位置</a>
    </li>
</ul>

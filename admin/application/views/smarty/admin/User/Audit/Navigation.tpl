<ul class="nav nav-tabs nav-tabs-line tabList font-size-14" role="tablist">


    <li  class="news {{if (!empty($_action) && $_action=='Detail')}}active{{/if}}">
        <a href="" >
            <i class="icon wb-user" aria-hidden="true"></i>基本信息

        </a>
    </li>
    <li  {{if (!empty($_action) && $_action=='CreditAuth')}}class="active" {{/if}}>
        <a href="" >
            <i class="icon fa-book" aria-hidden="true"></i> 运营商报告
        </a>
    </li>
    <li {{if (!empty($_action) && $_action=='OrderList')}}class="active" {{/if}}>
        <a href="" >
            <i class="site-menu-icon fa-file-text-o" aria-hidden="true"></i>
            京东报告
        </a>
    </li>

    <li {{if (!empty($_action) && $_action=='Location')}}class="active" {{/if}}>
        <a href="" >淘宝报告</a>
    </li>

     <li {{if (!empty($_action) && $_action=='Location')}}class="active" {{/if}}>
        <a href="" >公积金报告</a>
    </li>

    <li {{if (!empty($_action) && $_action=='Location')}}class="active" {{/if}}>
        <a href="" >社保报告</a>
    </li>

    <li {{if (!empty($_action) && $_action=='Location')}}class="active" {{/if}}>
        <a href="" >同盾报告</a>
    </li>

    <li {{if (!empty($_action) && $_action=='Location')}}class="active" {{/if}}>
        <a href="" >历史订单</a>
    </li>
</ul>

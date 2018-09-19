<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-inverse " role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided unfolded" data-toggle="menubar">
            <span class="sr-only">切换菜单</span> <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggle collapsed" data-target="#booroot-navbarCollapse" data-toggle="collapse">
            <i class="icon wb-more-horizontal" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            <img class="navbar-brand-logo visible-lg visible-xs navbar-logo" src="{{$ui_url}}/themes/images/logo-white.svg" title="booroot">
            <img class="navbar-brand-logo hidden-xs hidden-lg navbar-logo-mini" src="{{$ui_url}}/themes/images/logo-white-min.svg" title="booroot">
        </div>
    </div>
    <div class="navbar-container container-fluid">
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="booroot-navbarCollapse">

            <ul class="nav navbar-toolbar navbar-left">

                <li class="hidden-float">
                    <a data-toggle="menubar" class="hidden-float hided unfolded" href="javascript:;" role="button" id="booroot-toggleMenubar">
                        <i class="icon hamburger hamburger-arrow-left">
                            <span class="sr-only">切换目录</span>
                            <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>

                <li class="navbar-menu nav-tabs-horizontal nav-tabs-animate" id="booroot-navMenu">
                    <ul class="nav navbar-toolbar nav-tabs" role="tablist">
                        <!-- 顶部菜单 -->
                        {{if isset($_common.menu.tree) && isset($_common.menu.data) }}
                        {{foreach from=$_common.menu.tree.0 item=value key=key}}
                         {{if $value && ( $value.pub_show==1 || in_array($value.group_show,$_group_id) ) }}
                            <li role="presentation" {{if  isset($parent_menu.1.id) &&$parent_menu.1.id==$value.id}} class="active" {{/if}}>
                                <a data-toggle="tab" href="{{$value.url}}" aria-controls="{{$value.url}}" role="tab" aria-expanded="false">
                                    <i class=""></i> <span>{{$value.name}}</span>
                                </a>
                            </li>
                         {{/if}}
                        {{/foreach}}
                        {{/if}}

                        <li class="dropdown " id="booroot-navbarSubMenu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"  data-animation="slide-bottom" aria-expanded="false" role="button">
                                <i class="icon wb-more-vertical"></i> </a>
                            <ul class="dropdown-menu" role="menu">
                            </ul>
                        </li>

                    </ul>
                </li>
            </ul>


            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">


                <li class="dropdown">
                    <div class="owText text-center">
                        <span><img src="{{$ui_url}}/themes/images/avatar.svg" style="width: 30px;"></span>
                        <span>{{$_user['name']}}&nbsp;</span>
                    </div>

                </li>
                <li class="dropdown" id="booroot-navbarMessage">
                    <a data-toggle="dropdown" href="javascript:;" class="msg-btn" aria-expanded="false" data-animation="scale-up" role="button">
                        <i class="icon wb-bell" aria-hidden="true"></i> <span class="badge badge-danger up msg-num"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
                        <li class="dropdown-menu-header" role="presentation">
                            <h5>最新消息</h5>
                            <span class="label label-round label-danger"></span>
                        </li>
                        <li class="list-group" role="presentation">
                            <div id="booroot-messageContent" data-height="270px" data-plugin="slimScroll">
                                <p class="text-center height-150 vertical-align">
                                    <small class="vertical-align-middle opacity-four">没有新消息</small>
                                </p>

                            </div>
                        </li>
                        <li class="dropdown-menu-footer" role="presentation">
                            <a href="/system/account/message" target="_blank" data-pjax>
                                <i class="icon fa-navicon"></i> 所有消息
                            </a>
                        </li>
                    </ul>
                </li>







                <li class="hidden-xs" id="booroot-navbarFullscreen" data-toggle="tooltip" data-placement="bottom" title="全屏">
                    <a class="icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                        <span class="sr-only">全屏</span></a>
                </li>

                <li>
                    <a class="icon fa-sign-out" id="booroot-signOut" data-ctx="" data-user="9" href="/Login/Out" role="button">
                        <span class="sr-only">退出</span></a>
                </li>

            </ul>
        </div>
    </div>
</nav>
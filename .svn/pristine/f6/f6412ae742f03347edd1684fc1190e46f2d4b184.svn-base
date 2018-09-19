
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <title>提示页面</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        body, button, input, select, textarea {
            font:14px/1.5 tahoma, arial, "隶书";
            color:#666
        }
        .center {
            text-align:center
        }
        .clear:after, .clearfix:after {
            content:".";
            display:block;
            clear:both;
            visibility:hidden;
            font-size:0;
            height:0;
            line-height:0
        }
        .clear, .clearfix {
            zoom:1
        }
        .b-panel {
            position:absolute
        }
        .b-fr {
            float:right
        }
        .b-fl {
            float:left
        }
        .error-404 {
            background-color:#EDEDF0
        }
        .module-error {
            margin-top:182px
        }
        .module-error .error-main {
            margin:0 auto;
            width:820px
        }
        .module-error .label {
            float:left;

        }
        .module-error .info {
            margin-left:120px;
            line-height:1.8;
            float:left;
        }
        .module-error .title {
            color:#666;
            font-size:14px
        }
        .module-error .reason {
            margin:8px 0 18px 0;
            color:#666
        }
        </style>
</head>
<body class="error-404">
<div id="doc_main">
    <!-- 内容区 -->
    {{if isset($redirect) && !empty($redirect)}}
    <script language="JavaScript" type="text/javascript">
        function tourl() {
            window.location.href="{{$redirect}}";
        }
        setTimeout("tourl()", {{if $redirect_time>0}}{{$redirect_time}}{{else}}3{{/if}}*1000);
    </script>
    {{/if}}
    <section class="bd clearfix">
        <div class="module-error">
            <div class="error-main clearfix">
                <div class="label">
                    <img src="/static/public/themes/images/x-404.png" style=" width:260px;"/>
                </div>
                <div class="info">
                    <h2 class="title" style="font-size: 18px;"> {{if isset($title)}}{{$title}}{{else}}信息{{/if}}</h2>
                    <div class="reason">

                        <p>{{$message}}</p>
                            {{if isset($links) && isset($data.links) }}
                        <p>
                            {{foreach from=$data.links key=k item=v}}
                            <a href="{{$v.href}}" class="alert-link" {{if isset($v.style)}} style="{{$v.style}}"{{/if}} {{if isset($v.title)}} title="{{$v.title}}"{{/if}} >{{$v.text}}</a>
                            {{/foreach}}
                        </p>
                        {{/if}}
                    </div>
                    <div class="oper">
                        <p><a href="/">回到网站首页&gt;</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="{{$ui_url}}/vendor/jquery/jquery.min.js"></script>
</body></html>

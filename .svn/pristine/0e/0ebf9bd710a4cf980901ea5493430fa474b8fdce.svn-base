{{*
工具条
在Controller中定制样式
例如:
$tool_style = array('export'=>1);
*}}
<div class="pull-right">
{{if $tool_style.export == 1}}
    <button class="btn btn-default" id="export">导出数据</button>
{{/if}}
</div>
<script type="text/javascript">

    $('#export').click(function(){
        window.location.href = UrlUpdateParams(window.location.href, "_export", 1);
    });

    function UrlUpdateParams(url, name, value) {
        var r = url;
        if (r != null && r != 'undefined' && r != "") {
            value = encodeURIComponent(value);
            var reg = new RegExp("(^|)" + name + "=([^&]*)(|$)");
            var tmp = name + "=" + value;
            if (url.match(reg) != null) {
                r = url.replace(eval(reg), tmp);
            }
            else {
                if (url.match("[\?]")) {
                    r = url + "&" + tmp;
                } else {
                    r = url + "?" + tmp;
                }
            }
        }
        return r;
    }
</script>
{{*
窗体提示信息部件调用方法
在Controller制作message数组
例如:
$message = array('type' => 'success', 'message' => '更新成功.');
$message = array('type' => 'error', 'message' => array('错误信息1','错误信息2','错误信息3'));
type: success | error | notice | info
*}}
{{if isset($message)&&is_array($message)}}
<div class="alert {{if $message.type=='success'}}alert-success{{elseif $message.type=='error'}}alert alert-danger{{elseif $message.type=='notice'}}alert-warning{{else}}alert-info{{/if}} alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{if !is_array($message.message)}}
    {{$message.message}}
    {{else}}
    {{foreach from=$message.message item=value key=key}}
    <li>{{$value}}</li>
    {{/foreach}}
    {{/if}}
</div>

<script type="text/javascript">
    setTimeout(function () {
        $(".alert").remove();
    }, 3600);
</script>
{{/if}}
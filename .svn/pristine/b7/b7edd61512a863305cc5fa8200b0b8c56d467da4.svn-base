{{*
日历控件
在Controller中定制样式
例如:
$datepk_style = array('todayBtn' => 0, 'weekStart' => 0 ,'date' => '2015-1-4');
todayBtn: 0 | 1
weekStart: 0 ~ 6

2016-1-9 by majin
增加IF判断
*}}

<link href="{{$ui_url}}/bower_components/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="{{$ui_url}}/bower_components/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="{{$ui_url}}/bower_components/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<div class="input-group date form_date col-md-5" data-date="{{if isset($datepk_style.date)}}{{$datepk_style.date}}{{else}}{{date('Y-m-d')}}{{/if}}" data-date-format="" data-link-field="dtp_input" data-link-format="yyyy-mm-dd">
</div>
<input type="hidden" id="dtp_input" value="" />

<script type="text/javascript">
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        weekStart: {{if isset($datepk_style.weekStart)}}{{$datepk_style.weekStart}}{{else}}0{{/if}},
        todayBtn:  {{if isset($datepk_style.todayBtn)}}{{$datepk_style.todayBtn}}{{else}}0{{/if}},
        autoclose: 0,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    }).on('changeDate', function(ev){
        var dateline = $('#dtp_input').val();
        window.location.href='?_dateline='+dateline;
    });
</script>
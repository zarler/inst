    <?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({
        vars: {
            reqUrl: '<?php echo isset($_VArray['reqUrl']) ? $_VArray['reqUrl'] : "";?>',
            seajsVer: '<?php echo isset($_VArray['seajsVer']) ? $_VArray['seajsVer'] : "";?>',
            jumpUrl: '<?php echo isset($_VArray['jumpUrl']) ? $_VArray['jumpUrl'] : "";?>',
        }
    });
    seajs.use('js/seajs/emergencycontact');
    var $AppInst = new $.AppInst();
    var abc = '';
    function appCallback(msg) {
        // alert(11111111);
        var json = eval('(' + msg + ')');
        if(json.type == 'getPhone'){

            abc.text('重新选择');
            $("input[name='conname"+abc.data('code')+"']").val((json.data.name).replace(/\s+/g,""));
            $("input[name='ccomtell"+abc.data('code')+"']").val((json.data.tell).replace(/\s+/g,""));
        }
    }

</script>

<style type="text/css">

</style>



<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="?#jump=no"></a><?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>
<p class="tip-p">请输入两位紧急联系人联系方式，至少输入一位直系亲属</p>

<section class="t-credit-w contact1">
    <p class="t-credit-input border-bottom">
        <span>请在通讯录中选择联系人</span>
        <button class="buttonsmall" data-code="1" onclick='abc= $(this);$AppInst.WebJump({"type":"getPhone","par":""})'>选择联系人</button>
    </p>
    <p class="t-credit-input border-bottom">
        <?php echo Form::input('conname1','',array('class'=>'form-control text_width_70','placeholder'=>'姓名'));?>
        <span class="t-icon-close"></span>
    </p>
    <p class="t-credit-input border-bottom">
        <?php echo Form::input('ccomtell1','',array('class'=>'form-control text_width_70','placeholder'=>'手机号码')); ?>
        <span class="t-icon-close"></span></p>
    <p class="t-credit-input">
        <span>关系</span>
        <?php echo Form::select('contact1',array('0'=>'请选择关系','parent'=>'父母','brother'=>'兄弟姐妹','spouse'=>'配偶','children'=>'子女'),'0',array('id'=>'contact-select1','data-type'=>'1','class'=>'b-form-select'));?>
        <span class="b-icon-select"></span></p>
</section>
<div class="t-mi-vacancy"></div>
<section class="t-credit-w contact2">
    <p class="t-credit-input border-bottom">
        <span>请在通讯录中选择联系人</span>
        <button class="buttonsmall" data-code="2" onclick='abc= $(this);$AppInst.WebJump({"type":"getPhone","par":""})'>选择联系人</button>
    </p>
    <p class="t-credit-input border-bottom">
        <?php echo Form::input('conname2','',array('class'=>'form-control text_width_70','placeholder'=>'姓名'));?>
        <span class="t-icon-close"></span> </p>
    <p class="t-credit-input border-bottom">
        <?php echo Form::input('ccomtell2','',array('class'=>'form-control text_width_70','placeholder'=>'手机号码')); ?>
        <span class="t-icon-close"></span></p>
    <p class="t-credit-input">
        <span>关系</span>
        <?php echo Form::select('contact2',array('0'=>'请选择关系','colleague'=>'同事','classmate'=>'同学','friend'=>'朋友'),'0',array('id'=>'contact-select1','data-type'=>'1','class'=>'b-form-select'));?>
        <span class="b-icon-select"></span></p>
</section>

<div class="test"></div>


<div style="height: 1.2rem"></div>
<button class="submitButton t-blue-btn submit">提交</button>

</body>
</html>
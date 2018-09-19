<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../../_Common/Header.tpl'}}
    <style type="text/css">
        .radio-custom label{margin-right:30px;}
        .avatar img{border-radius:0px;}
        .page-account .widget-header{padding:5px;}
        .carolina_img{
            margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.18);border-radius: 5px;
        }

       .carolina_img .example{margin-top:10px; }
       .carolina_img img{height: 100px;}
    </style>

</head>

<body>
{{include '../../_Common/Top.tpl'}}
<main class="site-page">
    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-account">
            <div class="page-header">
                <h1 class="page-title">基本信息</h1>
            </div>
            <div class="page-content">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading nav-tabs-animate">
                                <div class="panel-title">
                                {{include './Navigation.tpl'}}
                                  </div>
                            </div>

                            <div class="panel-body nav-tabs-animate">



                                <div class="tab-content ">

                                    <div class="col-sm-3">
                                        <div class=" ">
                                            <div class="thumbnail text-center">
                                                <a class="thumbnail" href="javascript:;">
                                                {{if !empty($user.identity_face)}}
                                                {{if $user.identity_face.status==false }}
                                                图片调取失败
                                                {{else}}
                                                <img src="data:image/{{$user.identity_face.extension}};base64,{{$user.identity_face.binary}}" width="178"/>
                                                {{/if}}
                                                {{else}}
                                                    <i class="icon fa-picture-o" style="font-size: 200px;color:#76838f "  aria-hidden="true"></i>
                                                {{/if}}
                                                </a>
                                                <div class="text-center label-outline text-danger">
                                                    <p>网纹照片</p>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                        <div class="col-md-9 ">
                                                <div class="example table-responsive">
                                                    <table class="table  table-hover table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <td>姓名:</td>
                                                            <td>{{if isset($user.name)}}{{$user.name}}{{/if}}</td>
                                                          
                                                            <td>性别:</td>
                                                            <td>{{if isset($user.gender)}}{{$user.gender}}{{/if}}</td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td>年龄:</td>
                                                            <td>{{if isset($user.age)}}{{$user.age}}{{/if}}</td>
                                                            <td>注册时地理位置:</td>
                                                            <td >{{if isset($user.baiduMap)}}{{$user.baiduMap}}{{/if}}</td>

                                                        </tr>
                                                        <tr>
                                                            <td>身份证:</td>
                                                            <td>{{if isset($user.identity_code)}}{{$user.identity_code}}{{/if}}</td>

                                                            <td>手机号:</td>
                                                            <td>{{if isset($user.mobile)}}{{$user.mobile}}{{/if}}</td>
                                                        </tr>

                                                        <tr>
                                                            <td>状态:</td>
                                                            <td>

                                                                {{if isset($_status[$user.status])}}

                                                                <span class="label {{if $user.status==1}} label-success{{else}}label-Warning {{/if}}">  {{$_status[$user.status]}}</span>




                                                                {{else}}未知{{/if}}</td>

                                                            <td>手机实名状态:</td>
                                                            <td >
                                                                {{if $user.validated_mobile eq 1}}
                                                                <span class="label label-outline label-success">验证通过</span>

                                                                {{elseif $user.validated_mobile eq 2}}
                                                                  <span class="label label-outline label-danger"> 验证失败</span>
                                                                {{elseif $user.validated_mobile eq 3}}
                                                                <span class="label label-outline label-warning"> 等待验证</span> {{/if}}
                                                            </td>

                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                        </div>


                                </div>

                                <div class="tab-content">

                                    <div class="col-lg-12">
                                        <div class="example-wrap margin-bottom-0">
                                            <h4 class="example-title font-size-16 blue-grey-400">
                                                <i class="icon wb-gallery text-danger" aria-hidden="true"></i>身份证以及自拍照片
                                            </h4>

                                            <div class="row  text-center margin-top-20">
                                                <div class="carolina_img col-xs-6 col-sm-2  ">
                                                    <div class="example">

                                                          {{if !empty($user.ocr_pic)}}
                                                            {{if $user.ocr_pic.status==false }}
                                                            {{$user.ocr_pic['msg']}}
                                                            {{else}}
                                                              <a class="thumbnail example-image-link"  href="data:image/{{$user.ocr_pic.extension}};base64,{{$user.ocr_pic.binary}}"   rel="lightbox-tour" title="身份证以及自拍照片" class="lightbox-enabled" >
                                                                <img  class="img-rounded" src="data:image/{{$user.ocr_pic.extension}};base64,{{$user.ocr_pic.binary}}" />
                                                               </a>
                                                            {{/if}}
                                                            {{else}}

                                                               <i class="icon fa-picture-o" style="font-size: 100px;color:#76838f "  aria-hidden="true"></i>

                                                            {{/if}}

                                                        <div class="caption text-center label-outline">
                                                            <p>身份证正面照片</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                  <div class="carolina_img col-xs-6 col-sm-2 ">
                                                    <div class="example ">

                                                          {{if !empty($user.ocr_back_pic)}}
                                                            {{if $user.ocr_back_pic.status==false }}
                                                            {{$user.ocr_back_pic['msg']}}
                                                            {{else}}
                                                              <a class="thumbnail example-image-link"  href="data:image/{{$user.ocr_back_pic.extension}};base64,{{$user.ocr_back_pic.binary}}"   rel="lightbox-tour" title="身份证以及自拍照片" class="lightbox-enabled" >
                                                                <img  class="img-rounded" src="data:image/{{$user.ocr_back_pic.extension}};base64,{{$user.ocr_back_pic.binary}}"   height="128"/>
                                                               </a>
                                                            {{/if}}
                                                            {{else}}

                                                               <i class="icon fa-picture-o" style="font-size: 100px;color:#76838f "  aria-hidden="true"></i>

                                                            {{/if}}

                                                        <div class="caption text-center label-outline">
                                                            <p>身份证反面照片</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="carolina_img col-xs-6 col-sm-2">
                                                    <div class="example">

                                                            {{if !empty($user.pic1)}}
                                                            {{if $user.pic1.status==false }}
                                                            {{$user.pic1.msg}}<br>
                                                            {{else}}
                                                        <a class="thumbnail text-center example-image-link"  href="data:image/{{$user.pic1.extension}};base64,{{$user.pic1.binary}}"   rel="lightbox-tour" title="照片1" class="lightbox-enabled" >
                                                            <img src="data:image/{{$user.pic1.extension}};base64,{{$user.pic1.binary}}" />
                                                        </a>

                                                            {{/if}}
                                                            {{else}}
                                                            <i class="icon fa-picture-o" style="font-size: 100px;color:#76838f "  aria-hidden="true"></i>
                                                            {{/if}}

                                                        <div class="caption text-center">
                                                            <p>自拍照1</p>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carolina_img col-xs-6 col-sm-2">
                                                    <div class="example">

                                                            {{if !empty($user.pic2)}}
                                                            {{if $user.pic2.status==false }}
                                                            {{$user.pic2.msg}}<br>
                                                            {{else}}
                                                            <a class="thumbnail text-center example-image-link"  href="data:image/{{$user.pic2.extension}};base64,{{$user.pic2.binary}}"   rel="lightbox-tour" title="照片2" class="lightbox-enabled" >
                                                                <img src="data:image/{{$user.pic2.extension}};base64,{{$user.pic2.binary}}"  />
                                                            </a>
                                                            {{/if}}
                                                            {{else}}
                                                            <i class="icon fa-picture-o" style="font-size: 100px;color:#76838f "  aria-hidden="true"></i>
                                                            {{/if}}

                                                        <div class="caption text-center">

                                                            <p>自拍照2</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carolina_img col-xs-6 col-sm-2">
                                                    <div class="example">

                                                            {{if !empty($user.pic3)}}
                                                            {{if $user.pic3.status==false }}
                                                            {{$user.pic3.msg}}<br>
                                                            {{else}}
                                                        <a class="thumbnail text-center example-image-link"  href="data:image/{{$user.pic3.extension}};base64,{{$user.pic3.binary}}"   rel="lightbox-tour" title="照片3" class="lightbox-enabled" >
                                                            <img src="data:image/{{$user.pic3.extension}};base64,{{$user.pic3.binary}}" />
                                                        </a>
                                                            {{/if}}
                                                            {{else}}
                                                            <i class="icon fa-picture-o" style="font-size: 100px;color:#76838f "  aria-hidden="true"></i>
                                                            {{/if}}

                                                        <div class="caption text-center">

                                                            <p>自拍照3</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carolina_img col-xs-6 col-sm-2">
                                                    <div class="example">

                                                            {{if !empty($user.pic4)}}
                                                            {{if $user.pic4.status==false }}
                                                            {{$user.pic4.msg}}<br>
                                                            {{else}}
                                                        <a class="thumbnail text-center example-image-link"  href="data:image/{{$user.pic4.extension}};base64,{{$user.pic4.binary}}"   rel="lightbox-tour" title="照片3" class="lightbox-enabled" >
                                                            <img src="data:image/{{$user.pic4.extension}};base64,{{$user.pic4.binary}}"  />
                                                        </a>
                                                            {{/if}}
                                                            {{else}}
                                                            <i class="icon fa-picture-o" style="font-size: 100px;color:#76838f "  aria-hidden="true"></i>
                                                            {{/if}}

                                                        <div class="caption text-center">
                                                            <p>自拍照4</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                             <div class="panel-body "> 
                                 <div class="row row-lg">
                                    <div class="col-md-12">
                                        <h4 class="example-title font-size-16 blue-grey-400">
                                                <i class="icon fa-jpy text-danger" aria-hidden="true"></i>额度审批
                                         </h4>
                                         <div class="form-group" style="width: 185px;">
                                            <label for="source">[<span style="color: #1ab394 "> 选择审批金额 </span>]</label>
                                             <select class="form-control inst_amount"  >
                                                <option value="">-请选择-</option> 
                                                <option value="1000">1000</option>
                                                <option value="2000">2000</option>
                                                <option value="3000">3000</option>
                                                <option value="4000">4000</option>
                                                <option value="5000">5000</option>
                                               
                                             
                                              </select>
                                            </div>
                                     </div>
                                  </div>
                            </div>


                           <div class="panel-body ">
                                <div class="row row-lg">
                                    <div class="col-md-12">
                                        <h4 class="example-title font-size-16 blue-grey-400">
                                                <i class="icon wb-order text-danger" aria-hidden="true"></i>备注
                                         </h4>
                                            
                                        <div class="example-wrap">
                                            
                                            <div class="example table-responsive">
                                                <table class="table" id="table">
                                                    <thead>
                                                    <tr>
                                                        <th>备注内容</th>
                                                        <th>添加时间</th>
                                                        <th>操作人</th>
                                                         
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {{foreach from=$remarkinfo item=value key=key}}
                                                    <tr>
                                                        <td>{{$value.remarkcontent}}</td>
                                                        <td>{{date('Y-m-d H:i:s',$value.remarkcreate_time)}}</td>
                                                        <td>{{$value.remarkadminname}}</td>
                                                    </tr>
                                                    {{/foreach}}

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                     <div class="col-sm-12">
                                         <form method="post" >
                                            <div class="example-wrap margin-bottom-10">
                                                <h4 class="example-title">添加备注 <span class="text-danger">（*点击右下侧保存按钮）</span></h4>
                                                <textarea class="form-control" name="remarkinfo" id="textareaRemarks" rows="3" ></textarea>
                                            </div>
                                             <div class="example-wrap text-right margin-top-0 margin-right-20">
                                                <label class="floating-label ">
                                                    <button type="button" name="submit" class="btn btn-outline btn-success" id="remark">保存备注</button>
                                                </label>
                                            </div>
                                         </form>

                                     </div>
                                </div>
                            </div>


                          
                            <div class="panel-body "> 
                                 <div class="example-wrap ">
                                     
                                    <div class="example example-buttons text-center">
                                        <button type="button" class="btn btn-animate   btn-primary font-size-16 usr-audit"  data-mark='pass'  > 
                                            <span>通过</span> 
                                        </button>
                                       
                                       <button type="button" class="btn btn-animate   btn-danger font-size-16 usr-audit" data-mark='refuse'> 
                                            <span>拒绝</span> 
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</main>

<link rel="stylesheet" href="{{$ui_url}}/vendor/lightbox2/src/css/lightbox.css">
<script src="{{$ui_url}}/vendor/lightbox2/src/js/lightbox.js?version=1.0.1"></script>

<script type="text/javascript">
    $(function() {
        $("#remark").click(function(){
             
             var content = $("#textareaRemarks").val();

             if(!content){

               layer.msg('请输入备注内容');
               return  false;
             }
            var theurl="/User_Audit/SaveMark";
            var user_id={{$user.id}};
            $.post(theurl,{remarkinfo:content,user_id:user_id},function(data){
                var jsondata = $.parseJSON(data);
                if(jsondata.type=='success'){
                    $("#table tbody").append('<tr><td>'+jsondata.data.remarkcontent+'</td><td>'+jsondata.data.remarkcreate_time+'</td><td>'+jsondata.data.remarkadminname+'</td></tr>');

                }else{
                    layer.msg(jsondata.message);
                    return  false;
                }

            });

        });

     $(".usr-audit").click(function(){
       
           var mark = $(this).attr('data-mark'); 
           var amount = $(".inst_amount").val();
           var confirmContent;
         
           if(mark=='pass'){

             confirmContent ='通过';  
             if(amount<1000){
                layer.msg("请选择审批额度");
                return false;
             }

           }else{
             confirmContent ='拒绝';
           }
   

            //询问框
        layer.confirm('确定授信审核 <span class="text-danger font-size-16">'+confirmContent+'</span>', {
            skin: 'layui-layer-lan', //样式类名
            title:'用户授信审核',
            icon:3,
            shade: 0.8, 
            btn: ['确定','取消'] //按钮
        }, function(){
        
            layer.load(0,{ shade: [0.3,'#fff']});
            $.post('/User_Audit/Change',{user_id:{{$user.id}},operation:mark,amount:amount},function(result){
                    layer.closeAll();
                    layer.alert(result.msg, {
                        skin: 'layui-layer-lan',
                        shade: 0.8,
                        title: '返回提示' ,
                        closeBtn: 0
                    }, function(){
                        if(result.code==1000){
                            window.location.href='/User_Audit/List';
                        }

                    });

                   

            },'JSON');
        }, function(){
            layer.closeAll('diaog');
        });




     });


    })
</script>

{{include '../../_Common/Footer.tpl'}}
</body>
</html>



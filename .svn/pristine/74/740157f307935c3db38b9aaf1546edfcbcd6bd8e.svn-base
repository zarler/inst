<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

    {{include '../_Common/Header.tpl'}}
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
{{include '../_Common/Top.tpl'}}
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
                                {{include '../User/Navigation.tpl'}}
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
                                                            <td >授信额度:</td>
                                                            <td > <span class="label label-danger">{{if isset($user.inst_amount)}}{{$user.inst_amount}}{{/if}}</span></td>

                                                        </tr>
                                                        <tr>
                                                            <td>性别:</td>
                                                            <td>{{if isset($user.gender)}}{{$user.gender}}{{/if}}</td>
                                                            <td >银行卡号:</td>
                                                            <td >{{if isset($user.bank_card_no)}}{{$user.bank_card_no}}{{/if}}</td>

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
                                        <div class="example-wrap">
                                            <h4 class="example-title">
                                                <i class="icon wb-gallery text-danger" aria-hidden="true"></i>身份证以及自拍照片
                                            </h4>

                                            <div class="row  text-center">
                                                 <div class="carolina_img col-xs-6 col-sm-2 ">
                                                    <div class="example margin-bottom-0">

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
                                                    <div class="example  margin-bottom-0">

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
                                                    <div class="example margin-bottom-0">

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
                                                    <div class="example margin-bottom-0">

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
                                                    <div class="example margin-bottom-0">

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
                                                    <div class="example margin-bottom-0">

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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</main>

<link rel="stylesheet" href="{{$ui_url}}/vendor/lightbox2/src/css/lightbox.css">
<script src="{{$ui_url}}/vendor/lightbox2/src/js/lightbox.js?version=1.0.1"></script>

{{include '../_Common/Footer.tpl'}}
</body>
</html>



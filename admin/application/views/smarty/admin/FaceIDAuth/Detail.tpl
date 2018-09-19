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
                <h1 class="page-title">活体识别&网纹对比详情</h1>
            </div>
            <div class="page-content">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel"> 
                            <div class="panel-body nav-tabs-animate">



                                <div class="tab-content ">

                                   

                                        <div class="col-md-12 ">
                                                <div class="example table-responsive">
                                                    <table class="table  table-hover table-striped">
                                                        <tbody>
                                                        
                                                            <tr>
                                                                <td>用户ID:</td>
                                                                <td>{{$user.id}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>姓名:</td>
                                                                <td>{{$user.name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>身份证号:</td>
                                                                <td>{{$user.identity_code}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>性别:</td>
                                                                <td>{{$user.gender}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>民族:</td>
                                                                <td>{{$faceidauth.race}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>户籍地址:</td>
                                                                <td>{{$faceidauth.address}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>活体识别分数:</td>
                                                                <td>{{$faceidauth.score}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>活体识别状态:</td>
                                                                <td>{{if isset($_status[$faceidauth.status])}}{{$_status[$faceidauth.status]}}{{else}}未知{{/if}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>网纹对比分数:</td>
                                                                <td>{{$faceidauth.identity_score}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>网纹对比状态:</td>
                                                                <td>{{if isset($_identity_status[$faceidauth.identity_status])}}{{$_identity_status[$faceidauth.identity_status]}}{{else}}未知{{/if}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>验证时间:</td>
                                                                <td>{{if $faceidauth.create_time>0}}{{date('Y-m-d',$faceidauth.create_time)}}{{/if}}</td>
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



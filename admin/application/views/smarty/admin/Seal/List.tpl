<!DOCTYPE html>
<html class="no-js css-menubar" lang="zh-cn">
<head>

{{include '../_Common/Header.tpl'}}
</head>

<body>
{{include '../_Common/Top.tpl'}}

<main class="site-page">

    <div class="page-container" id="admui-pageContent">

        <div class="page animation-fade page-tables">

            <div class="page-content">

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon wb-grid-9" aria-hidden="true"></i>合同签章</h3>
                    </div>
                    <div class="panel-body">

                        {{include './_Tab.tpl'}}

                         <div class="example-warp dropdown margin-bottom-20">
                                <button type="button" class="btn btn-default dropdown-toggle" id="exampleColorDropdown6" data-toggle="dropdown" aria-expanded="false">
                                 --请选择要盖的签章--<span class="caret"></span> </button>
                                <ul class="dropdown-menu dropdown-menu-primary  animate" aria-labelledby="exampleColorDropdown6" role="menu">

                                     {{foreach from=$_seal_array item=value key=key }}
                                       <li role="presentation"> <a href="javascript:;" role="select"    >{{$value}}</a> </li>
                                      {{/foreach}} 
                                  </ul>

                                 <a type="button" class="btn btn-danger signature">批量盖章</a>
                        </div>   

                        <div class="example-warp">
                                <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>
                                              <div  class="checkbox-custom checkbox-primary">
                                                        <input type="checkbox" name="all" onclick="check_all()">
                                                        <label for="inputChecked"> 全选/反选</label>
                                            </div>
                                            
                                        </th>
                                        <th>订单号</th>
                                        <th>姓名</th>
                                        <th>文件名</th>
                                        <th>当前签章</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{foreach from=$list item=value key=key}}
                                    <tr>
                                        <td>

                                            <div class="checkbox-custom checkbox-primary">
                                                        <input type="checkbox" id="inputChecked" name= "ids[]" class="lineCheck" value= "{{$value.docid}}">
                                                        <label for="inputChecked"> </label>
                                             </div>

                                        </td>
                                        <td>{{$value.order_id}}</td>
                                        <td>{{$value.name}}</td>
                                        <td>{{$value.filename}}</td>
                                        <td>{{$value.seal_name}}</td>
                                        <td>
                                        {{if $value.status ==Model_Seal::SEAL}}
                                          <span class="label label-danger">{{$_status[$value.status]}}</span>  
                                         {{elseif $value.status ==Model_Seal::SEALING }}
                                          <span class="label label-warning">{{$_status[$value.status]}}</span>  
                                         {{elseif $value.status ==Model_Seal::SEALED }}
                                          <span class="label label-success">{{$_status[$value.status]}}</span>  
                                         {{else}}
                                         {{$value.status}}
                                         {{/if}}
    
                                        </td>
                                        <td>{{$value.create_time}}</td>
                                        <td>

                                        {{if $value.docid}}

                                            <a href="/{{$_controller}}/Show?id={{$value.id}}" target=”_blank" class="btn btn-primary btn-sm">下载合同</a>
                                            {{if $value.issign==1 }}
                                            <a href="/{{$_controller}}/Show?id={{$value.id}}&&type=2" target=”_blank" class="btn btn-primary btn-sm">下载盖章合同</a>
                                            {{/if}}
                                            {{if $value.status==2 ||  $value.issign==0}}
                                            <a href="javascript:alert('准备盖章中~~');" class="btn btn-primary btn-sm">准备盖章中</a>
                                            {{/if}}
                                        {{else}}
                                            <a href="javascript:makeContract({{$value.id}});" target=”_blank" class="btn btn-primary btn-sm">生成合同</a>
                                        {{/if}}
                                         
                                             
                                        </td>
                                    </tr>
                                    {{/foreach}}
                                     </tbody>
                                  </table>
                                  
                                    {{$pagination}}
                                </div>

                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>

</main>
<script type="text/javascript">
   
     function check_all(){
        if($("input[name=all]").prop("checked")){
            $(".lineCheck").prop("checked",true);
        }else{
            $(".lineCheck").attr("checked",false);
        }
    }

    $(function(){
        $(".signature").click(function(){
            alert(111);
        });
    })

</script>>
<!-- /wrapper -->
{{include '../_Common/Footer.tpl'}}


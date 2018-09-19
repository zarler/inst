   $(".selectable-all").click(function () {
        if(this.checked){
            $("input[name=items]:checkbox").attr("checked",this.checked);
            $(".site-action").addClass("active");
            $("#user_tables table tbody tr").each(function () {
                $(this).addClass("active");
            });
        }else{
            $("[name=items]:checkbox").attr("checked",false);
            $(".site-action").removeClass("active");
        }

    });

    $("#user_tables table tbody .selectable-item").click(function () {
        $(".selectable-all").prop("checked", false);
        $(this).parents("tr").parent().find("tr").removeClass("active");
        if(this.checked){
            $(".site-action").addClass("active");
        }else{
            $(".site-action").removeClass("active");
        }

    });


    $(".site-action .btn-floating i").click(function () {

        if($(".site-action").is(".active")){
            $(".site-action").removeClass("active");
            $(".selectable-all :checkbox").attr("checked",false);
            $("[name=items]:checkbox").attr("checked",false);
        }else{
            $(".modal-fill-in").addClass("in");
            $(".modal-fill-in").css("display","block")
        }
    });

  


    $('.site-action-buttons button').mousemove(function () {
         var top= $(this).position().top-120;
         $(".tips-ed").css({"top":top+"px","left":"-105px"});
         $(".tips-ed").removeClass("hide");
         $(".tips-ed .tooltip-inner").html($(this).attr("data-original-title"));

    }).mouseout(function () {
        $(".tips-ed").addClass("hide");
    });

   $(".site-action .btn-floating i").mousemove(function () {

       $(".tips-ed").css({"top":"15px","left":"-65px"});
       $(".tips-ed").removeClass("hide");
       if($(".site-action").is(".active")){
           $(".tips-ed .tooltip-inner").html("取消");
       }else{
           $(".tips-ed .tooltip-inner").html("添加");
       }


   }).mouseout(function () {
      $(".tips-ed").addClass("hide");
   });



$('#add-user button[data-dismiss="modal"]').click(function () {

    $(".modal-fill-in").removeClass("in");
    $(".modal-fill-in").css("display","none")
});


    
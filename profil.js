$(".submenu").hover(
    function(){
        $(this).find(".dropdown").slideDown(200);
    },function(){$(this).find(".dropdown").slideUp(200);}
);
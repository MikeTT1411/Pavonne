$(window).on('scroll', function(){
    if($(window).scrollTop()>=220) {
        $('#header').addClass('bg-1', 500)
    } else {
        $('#header').removeClass('bg-1');
    }
})
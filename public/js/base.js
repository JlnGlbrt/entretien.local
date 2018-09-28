/* Fonction qui affiche les éléments à animer lorsqu'ils apparaissent */

(function(){
    if(screen.width > 992){
        $('.animated').appear(function() {
            var elem = $(this);
            var animation = elem.data('animation');
            if ( !elem.hasClass('visible') ) {
                var animationDelay = elem.data('animation-delay');
                if ( animationDelay ) {
                    setTimeout(function(){
                        elem.addClass( animation + " visible" );
                    }, animationDelay);
                } else {
                    elem.addClass( animation + " visible" );
                }
            }
        });
    }else{
        $('.animated').addClass("visible");
    }
})();
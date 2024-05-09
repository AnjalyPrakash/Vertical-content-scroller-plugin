jQuery(document).ready(function($) {
    $('.custom-content-scroller').each(function() {
        var $scroller = $(this);
        var speed = parseInt($scroller.data('speed'), 10) || 3000; 

        var $content = $scroller.find('.scroller-content');
        var originalContent = $content.html();
        
       
        function adjustContent() {
            var requiredHeight = $scroller.height() * 2; 
            var contentHeight = $content.outerHeight(true); 

            
            var timesToReplicate = Math.ceil(requiredHeight / contentHeight);
            var newContent = '';
            for (var i = 0; i < timesToReplicate; i++) {
                newContent += originalContent;
            }

            $content.html(newContent);
        }

       
        adjustContent();

        function scrollContent() {
            var totalHeight = $content.outerHeight(true) / 2;
            var duration = totalHeight / $scroller.height() * speed;

            $scroller.animate({ scrollTop: totalHeight }, duration, 'linear', function() {
              
                $scroller.scrollTop(0);
                setTimeout(scrollContent, 10); 
            });
        }

       
        $scroller.hover(
            function() {
                $(this).stop(true, false); 
                console.log('Animation paused on hover'); 
            },
            function() {
                console.log('Hover ended, resuming animation'); 
                scrollContent(); 
            }
        );

       
        $(window).resize(adjustContent);

      
        scrollContent();
    });
});

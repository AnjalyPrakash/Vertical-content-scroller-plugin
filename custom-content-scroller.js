jQuery(document).ready(function($) {
    $('.custom-content-scroller').each(function() {
        const $scroller = $(this);
        const $scrollerContent = $scroller.find('.scroller-content');
        const speed = parseInt($scroller.data('speed'), 10) || 1; // Default speed to 1 if undefined

        // Clone the content to create a continuous effect
        const $contentClone = $scrollerContent.clone();
        $scrollerContent.append($contentClone.children());

        const scrollHeight = $scrollerContent.outerHeight() / 2; // Explanation for dividing by 2

        let animationId;

        // Continuous scrolling function using requestAnimationFrame
        function scrollContent() {
            const currentScrollTop = $scroller.scrollTop();
            if (currentScrollTop >= scrollHeight) {
                $scroller.scrollTop(1); // Reset to avoid visual jump
            } else {
                $scroller.scrollTop(currentScrollTop + 1);
            }
            animationId = requestAnimationFrame(scrollContent);
        }

        // Pause and resume on hover
        $scroller.hover(
            function() {
                cancelAnimationFrame(animationId);
            },
            function() {
                animationId = requestAnimationFrame(scrollContent);
            }
        );

        // Starting the animation
        animationId = requestAnimationFrame(scrollContent);
    });
});

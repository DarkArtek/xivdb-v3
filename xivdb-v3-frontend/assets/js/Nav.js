export default class Nav
{
    static init()
    {
        // detect main nav menu buttons
        $('[data-menu]').on('click', event => {
            const $button = $(event.target);
            const menuClassName = $button.attr('data-menu');

            $(`[data-menu]:not([data-menu="${menuClassName}"])`).removeClass('on');
            $button.toggleClass('on');

            $(`.h-box:not(.${menuClassName})`).removeClass('on');
            $(`.${menuClassName}`).toggleClass('on');
        });

        // Detect clicking outside to close it
        $(document).on('click', event => {
            const container = $('[data-menu], .h-box.on');

            if (!container.is(event.target) && container.has(event.target).length === 0) {
                $('[data-menu].on').removeClass('on');
                $('.h-box.on').removeClass('on');
            }
        });
    }
}

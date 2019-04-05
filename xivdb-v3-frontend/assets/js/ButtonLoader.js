const ButtonLoaderSettings =
{
    svg: '<img src="/img/ui/loaders/dual_ring_{theme}.svg" height="{height}" class="btn-loader-icon">',
    attr: 'button-loader-id',
    data: {},
};

export default class ButtonLoader
{
    /**
     * Turn on loading
     * @param $element
     * @param theme
     */
    static on($element, theme)
    {
        // get the text and id
        const text = $element.text();
        const width = $element.outerWidth(true) + 'px';
        const height = $element.outerHeight(true) + 'px';
        const id = Math.random().toString(36);

        let icon = ButtonLoaderSettings.svg.replace('{theme}', theme);
        icon = icon.replace('{height}', $element.hasClass('btn-sm') ? 18 : 28);

        // fix width
        $element.css({
            width:    width,
            height:   height,
            position: 'relative'
        });

        // store the current text
        $element.attr(ButtonLoaderSettings.attr, id);
        ButtonLoaderSettings.data[id] = text;

        // set state
        $element
            .html(icon)
            .prop('disabled', true);
    }

    /**
     * Turn off loading
     * @param $element
     */
    static off($element)
    {
        // renable button
        const id = $element.attr(ButtonLoaderSettings.attr);
        const text = ButtonLoaderSettings.data[id];

        $element
            .html(text)
            .prop('disabled', false);
    }
}

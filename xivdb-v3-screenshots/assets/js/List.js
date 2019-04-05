import Settings from './Settings';
import Render from './Render';
import Events from './Events';

class List
{
    constructor(id, dom) {
        this.id = id;
        this.$dom = $(dom);

        // dependencies
        this.Render = new Render();
        this.Events = new Events();

        // render pictures
        this.render();

        $('html').on('click', '.xivdb-screenshots-delete', event => {
            const id = $(event.currentTarget).attr('id');
            $.ajax({
                url: Settings.ENDPOINT + '/' + id,
                method: 'DELETE',
                success: response => {
                    this.Events.push('deleteSuccess', { response });
                    this.render();
                },
                error: response => {
                    this.Events.push('deleteError', { response, code, message });
                },
                complete: () => {
                    this.Events.push('deleteComplete');
                }
            });
        });
    }

    render()
    {
        // get images
        $.ajax({
            url: Settings.ENDPOINT + '/list',
            data: {
                'idUnique': this.id
            },
            success: response => {
                this.Events.push('listSuccess', { response });

                if (typeof response.error === 'undefined') {
                    this.Events.push('listRendering');
                    this.renderImages(response);
                } else {
                    this.Events.push('listEmpty');
                    this.renderEmpty(response.error);
                }
            },
            error: response => {
                this.Events.push('listError', { response, code, message });
            },
            complete: () => {
                this.Events.push('listComplete');
            }
        });
    }

    renderImages(images)
    {
        this.$dom.html(
            this.Render.getScreenshotListHtml()
        );

        for(let i in images) {
            let img = images[i],
                html = this.Render.getScreenshotEmbedHtml(img);

            this.$dom.find('.xivdb-screenshots-display').append(html);
        }
    }

    renderEmpty(code)
    {
        this.$dom.html(
            this.Render.getScreenshotEmptyHtml(code)
        );
    }
}

export { List as default }

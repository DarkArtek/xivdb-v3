import Settings from './Settings';
import Render from './Render';
import Events from './Events';
import Uploader from './Uploader';

class Instance
{
    constructor(id, dom) {
        this.id = id;
        this.$dom = $(dom);

        // dependencies
        this.Render = new Render();
        this.Events = new Events();
        this.Uploader = new Uploader();

        // render
        this.$dom.html(
            this.Render.getFileSelectHtml()
        );

        // watch for events
        this.Events.watch(
            this.Uploader,
            this.$dom
        );

        // set uploader callbacks
        this.Uploader
            .form({
                idUnique: this.id,
                userId: 'hello-world'
            })
            .on({
                start: () => {
                    this.Events.push('uploadStart');
                },
                error: (response, code, message) => {
                    this.Events.push('uploadError', { response, code, message });
                },
                success: response => {
                    this.Events.push('uploadSuccess', { response });

                    console.log(Settings.MS_SS_LISTS);

                    // render images again
                    Settings.MS_SS_LISTS[this.id].render();
                },
                complete: () => {
                    this.Uploader.uploading = false;
                    this.$dom.find('input').val('');
                    this.Events.push('uploadComplete');
                },
                progress: response => {
                    this.Events.push('uploadProgress', { response });
                },
                load: response => {
                    this.Events.push('uploadLoad', { response });
                },
            });
    }
}

export { Instance as default }

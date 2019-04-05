import Settings from './Settings';

class Uploader
{
    constructor()
    {
        this.uploading = false;
        this.events = {};
        this.formdata = {};
    }

    form(form)
    {
        this.formdata = form;
        return this;
    }

    on(callbacks)
    {
        this.events = callbacks;
        return this;
    }

    post(event)
    {
        if (this.uploading) {
            return;
        }

        // grab the correct files instance
        let dt = event.dataTransfer || (event.originalEvent && event.originalEvent.dataTransfer);
        let files = event.target.files || (dt && dt.files);

        console.log(event);

        // Convert files to array
        files = $.grep(files, (a, b) => {
            return a != b;
        });

        // get first file (not supporting multi at this time)
        let file = files[0];

        console.log(file);

        // check for errors
        let error = false;
        if (error = this.invalid(file)) {
            this.uploading = false;
            return this.events.error(error);
        }

        // upload
        this.upload(file);
    }

    upload(file)
    {
        // Open a reader
        let reader = new FileReader();

        // On reader load
        reader.onload = (temp => {
            this.events.start();

            return event => {
                // new image
                let img = new Image;
                img.src = event.target.result;

                // On image load
                img.onload = () => {
                    this.uploading = true;

                    // create form data
                    let formData = new FormData();
                    formData.append('media', file);

                    // append form data
                    for(let i in this.formdata) {
                        let value = this.formdata[i];
                        formData.append(i, value);
                    }

                    // upload
                    $.ajax({
                        xhr: () => {
                            let xhr = new window.XMLHttpRequest();

                            xhr.upload.addEventListener('progress', event => {
                                let percent = (event.loaded / event.total * 100).toFixed(2);
                                this.events.progress(percent);
                            }, false);

                            xhr.upload.addEventListener('load', event => {
                                this.events.load(file)
                            }, false);

                            return xhr;
                        },
                        url: Settings.ENDPOINT,
                        data: formData,
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: this.events.success,
                        error: this.events.error,
                        complete: this.events.complete
                    });
                }
            }
        })(file);

        // Read in the image file as data.
        reader.readAsDataURL(file);
    }

    /**
     * Check if a file is invalid or not
     *
     * @param file
     * @returns {*}
     */
    invalid(file)
    {
        if (typeof file === 'undefined') {
            return 'File entity invalid';
        }

        if (file.size > Settings.MAX_SIZE) {
            return 'File size is too big, please keep it below 15mb';
        }

        if (Settings.ALLOWED_TYPES.indexOf(file.type) === -1) {
            return `${file.type} is an invalid file type`;
        }

        return false;
    }
}

export { Uploader as default }

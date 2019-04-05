class Events
{
    watch(Uploader, $dom)
    {
        let dragCounter = 0;

        // form submit
        $dom.on('submit', event => {
            event.preventDefault();
            event.stopPropagation();

            Uploader.post(event);
        });

        // on file change
        $dom.on('change', 'form input[type="file"]', event => {
            event.preventDefault();
            event.stopPropagation();

            Uploader.post(event);
        });

        // drag enter
        $dom.on('dragenter', event => {
            event.preventDefault();
            event.stopPropagation();

            dragCounter++;
            if (dragCounter === 1) {
                console.log('drag enter');
            }
        });

        // drag leave
        $dom.on('dragleave', event => {
            event.preventDefault();
            event.stopPropagation();

            dragCounter--;
            if (dragCounter === 0) {
                console.log('drag leave');
            }
        });

        // drag over (this is important)
        $dom.on('dragover', event => {
            event.preventDefault();
            event.stopPropagation();
        });

        // drop
        $dom.on('drop', event => {
            console.log('dropped');
            event.preventDefault();
            event.stopPropagation();

            Uploader.post(event);
        });

        console.log('Watching', $dom);

    }

    push(name, data)
    {
        if (typeof MS_SCREENSHOTS === 'undefined') {
            return;
        }

        if (typeof MS_SCREENSHOTS[name] === 'undefined') {
            return;
        }

        MS_SCREENSHOTS[name](data);
    }
}

export { Events as default }

import Settings from './Settings';

class Render
{
    getFileSelectHtml()
    {
        return `
            <form class="xivdb-screenshots-container">
                <div class="xivdb-screenshots-fields active">
                    <div class="xivdb-screenshots-input">
                        <label for="file" class="btn">Choose a file</label>
                        <input type="file" id="file">
                    </div>
                    <div class="xivdb-screenshots-droptext">
                        Or drop your file here
                    </div>
                </div>
                <div class="xivdb-screenshots-state">
                    <div class="xivdb-screenshots-title">Uploading...</div>
                    <div class="xivdb-screenshots-progress"><span style="width:0%;"></span></div>
                </div>
            </form>
        `;
    }

    getScreenshotListHtml()
    {
        return `
            <div class="xivdb-screenshots-display"></div>
        `;
    }

    getScreenshotEmbedHtml(image)
    {
        let imageUrl = Settings.ENDPOINT + '/' + image.id;

        return `
            <span class="xivdb-screenshots-embed">
                <a href="${imageUrl}" target="_blank">
                    <img src="${imageUrl}" class="xivdb-screenshots-img" height="80">
                </a>
                <button class="xivdb-screenshots-delete" id="${image.id}">Delete</button>
            </span>
        `;
    }

    getScreenshotEmptyHtml(code)
    {
        return `
            <div class="xivdb-screenshots-empty">[${code}]</div>
        `;
    }
}

export { Render as default }

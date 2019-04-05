const Settings = {
    ENDPOINT: (typeof MS_SCREENSHOTS_ENDPOINT !== 'undefined')
                ? MS_SCREENSHOTS_ENDPOINT
                : 'http://127.0.0.1:8000',

    INSTANCE_CLASS_NAME: 'xivdb-screenshots',
    LIST_CLASS_NAME: 'xivdb-screenshots-list',

    MAX_SIZE: (1024 * 1024 * 15),
    ALLOWED_TYPES: [
        'image/png',
        'image/gif',
        'image/bmp',
        'image/jpg',
        'image/jpeg'
    ],

    MS_SS_INSTANCES: {},
    MS_SS_LISTS: {}
};

export { Settings as default }

<?php

namespace XIV;

class Config
{
    public static function init()
    {
        // Endpoints
        if (getenv('APP_ENV') === 'dev') {
            putenv('MS_XIVSYNC=http://xivsync.local');
            putenv('MS_API=http://api.xivdb.local');
            putenv('MS_SYNC=http://ms.sync.xivdb.local');
            putenv('MS_MOGNET=http://ms.mognet.xivdb.local');
            putenv('MS_DATA=http://ms.data.xivdb.local');
            putenv('MS_SEARCH=http://ms.search.xivdb.local');
            putenv('MS_PAGE=http://ms.pages.xivdb.local');
            putenv('MS_EMAIL=http://ms.email.xivdb.local');
            putenv('MS_DEVAPPS=http://ms.devapps.xivdb.local');
            putenv('MS_COMMENTS=http://ms.comments.xivdb.local');
            putenv('MS_SCREENSHOTS=http://ms.screenshots.xivdb.local');
            putenv('MS_TRANSLATIONS=http://ms.translations.xivdb.local');
            putenv('MS_FEEDBACK=http://ms.feedback.xivdb.local');
        } else {
            putenv('MS_XIVSYNC=http://xivsync.xivdb-staging.com');
            putenv('MS_API=http://api.xivdb-staging.com');
            putenv('MS_SYNC=http://ms.sync.xivdb-staging.com');
            putenv('MS_MOGNET=http://ms.mognet.xivdb-staging.com');
            putenv('MS_DATA=http://ms.data.xivdb-staging.com');
            putenv('MS_SEARCH=http://ms.search.xivdb-staging.com');
            putenv('MS_PAGE=http://ms.pages.xivdb-staging.com');
            putenv('MS_EMAIL=http://ms.email.xivdb-staging.com');
            putenv('MS_DEVAPPS=http://ms.devapps.xivdb-staging.com');
            putenv('MS_COMMENTS=http://ms.comments.xivdb-staging.com');
            putenv('MS_SCREENSHOTS=http://ms.screenshots.xivdb-staging.com');
            putenv('MS_TRANSLATIONS=http://ms.translations.xivdb-staging.com');
            putenv('MS_FEEDBACK=http://ms.feedback.xivdb-staging.com');
        }
    
        // Keys
        putenv('MS_API_KEY=');
        putenv('MS_MOGNET_KEY=');
        putenv('MS_DATA_KEY=');
        putenv('MS_SYNC_KEY=');
        putenv('MS_SEARCH_KEY=');
        putenv('MS_PAGE_KEY=');
        putenv('MS_EMAIL_KEY=');
        putenv('MS_DEVAPPS_KEY=');
        putenv('MS_COMMENTS_KEY=');
        putenv('MS_SCREENSHOTS_KEY=');
        putenv('MS_TRANSLATIONS_KEY=');
        putenv('MS_FEEDBACK_KEY=');
    }
}

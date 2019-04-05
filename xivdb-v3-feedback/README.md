# xivdb-v3-feedback

Microservice for feedback and support

### Local setup

- `touch storage/data.db`
- `php bin/console server:start`

### API:

> GET /{uuid}

Get some feedback, requires a json body, view models below.

> PUT /{uuid}

Update some feedback, requires a json body, view models below.

The following fields can be updated:

- title
- message
- data
- category
- status_code
- email_subscriptions
- screenshots

> DELETE /{uuid}

Delete some feedback, options:

- `?fully=true` Performs a full delete, rather than a flag.

> POST / 

Create new feedback, requires a json body, view models below.

### Models:

Creating a new feedback post:

```json
{
	"user_id": "123",
	"title": "Some Support Ticket",
	"message": "Updated the feedback message",
	"data": {
		"browser": "Chrome",
		"version": 62
	}
}
```

Updating a feedback post: (you should have the existing model at this point)

```json
{
    "id": "f98274b6-f457-4877-bb61-e31e43f774d8",
    "user_id": "123",
    "added": 1521648600,
    "updated": 1521649234,
    "title": "Some Support Ticket",
    "message": "Updated the feedback message",
    "data": {
        "browser": "Chrome",
        "version": 62
    },
    "category": "new",
    "status_code": 0,
    "email_subscriptions": [],
    "deleted": false,
    "screenshots": []
}
```

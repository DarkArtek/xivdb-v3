# xivdb-v3-email

Microservice for sending emails

### Local setup

- `touch storage/data.db`
- `php bin/console server:start`

### API

> POST /

Post a new email, requires a json body, view models below.

### Models

Posting a new email

```json
{
	"email": "send-to@email.com",
	"subject": "Welcome to XIVDB!",
	"template": "user_welcome",
	"data": {
		"username": "Vekien",
		"foo": "bar"
	}
}
```

# XIVDB v3 Comments
Comments Service

### API

> [GET] /search

Search for some comments.

Parameters:
- **Filters:** `idUnique`
- **Order:** `updated,desc`
- `limit`
- `page`


-----


> [GET] /{uuid}

Get a single comment.


-----


> [PUT] /{uuid}

Update a comment

JSON Payload:

```
{
    "message": "Hello World"
}
```


-----


> [DELETE] /{uuid}

Delete a comment, this is a soft delete.


-----


> [POST] /

Create a new comment.

JSON Payload:

```
{
    "idUnique": "item:1675",
    "idReply": "{optional: uuid of reply comment}",
    "idUser": "ID of the user who submitted the comment",
    "message": "Lorem Ipsum"
}
```
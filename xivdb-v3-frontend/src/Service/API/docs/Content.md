
# Content  

Obtain game content data of Final Fantasy XIV

## `/content`

View a list of available content that is accessible in the API. Content is added rapidly when discovered and mapped to the SaintCoincach Schema, with huge effort from the community there is a lot of information availble. Have fun hunting through!

---
  
## `/<ContentName>`

> **Note:** ContentName is case sensitive, for example: `Item` will work, `item` will not.

Returns a paginated list of content for the specified Content Name, to get a list of items you can query:
- http://api.xivdb-staging.com/Item

The list will return a `pagination` object and an array of `results`

#### Pagination

```
"pagination": {
    "page": 1,
    "page_next": 2,
    "page_prev": false,
    "page_total": 94,
    "results": 250,
    "results_per_page": 250,
    "results_total": 23500
}
```
| Field | Details |
| -- | -- |
| `page` | The current page you have queried |
| `page_next` | The next page you can query, if `null` there is no next page and you're at the end |
| `page_prev` | The previous page you can query, if `null` there is no previous page and you're at the start |
| `results` | The total number of results in the current page |
| `results_per_page` | Your current maximum results a page can have |
| `results_total` | The total amount of results for the specified content |

## Queries

### `schema=1`

View the current column and schema information of the content. This will provide 2 objects: `columns` and `schema`.
- `columns` - This is list of all columns in dot notation. For example an achievement would have: `AchievementCategory.AchievementKind.ID`
- `schema` - This is a nested representation of the schema.

### `columns=x,y,a.b.c`
Pull specific columns from the data. By default the list just provides the `ID`. You can request specific pieces of information from a list by plugging in the column names. For extended content you use dot notation to access it, for example:
- `columns=ID,Icon,Name,ClassJobCategory.Name`
	- `ID`
	- `Icon`
	- `Name`
	- `ClassJobCategory.Name`

**Response**
```
{
    "ClassJobCategory.Name": "CNJ WHM",
    "ID": 2000,
    "Icon": "\/img\/ui\/game\/icon4\/2\/2000.png",
    "Name": "Elm Cane"
},
{
    "ClassJobCategory.Name": "CNJ WHM",
    "ID": 2001,
    "Icon": "\/img\/ui\/game\/icon4\/2\/2001.png",
    "Name": "Aetherial Elm Cane"
},
{
    "ClassJobCategory.Name": "CNJ WHM",
    "ID": 2002,
    "Icon": "\/img\/ui\/game\/icon4\/2\/2002.png",
    "Name": "Elm Crook"
}
```

Sometimes a piece of data will have an array of sub data, for example:

```json
{
    "ID": 1,
    "Name": "Example",
    "Items": [
        {
            "Name": "foo"
        },
        {
            "Name": "bar"
        }
    ]
}
```

To access the data in `Items` these you would request the columns:

- `columns=Items.0.Name,Items.1.Name`

If you imagine an array having 50 items, this could be tedious and will eat into your maximum column count. You can therefore use a count format, eg:

- `columns=Items.*50.Name`

This will return 50 rows from the column `Items` using the index `Name`



There are restrictions on the number of columns you can query, the column character length and the maximum items. By default these are pretty strict so it is recommended to create a **Developer App** on XIVDB to be provided a `key` to increase your limits.

> **Note:** If you use someone elses key that means the key owner has control over your application and could break you app, either by changing the keys limit or deleting the key completely. Developer Keys are free, so get your own :)

### `max_items=X`

Limit the number of items returned by the API. The maximum items you can set depends on your Developer App Key, by default this is 250 objects.

### `ids=13,60,45`

Filter the ids down if you want data for a specific series of items.

---

## `/<ContentName>/<ID>`

> **Note:** ContentName is case sensitive, for example: `Item` will work, `item` will not.

Returns information about a specific object including extended information, for example:
- https://api.xivdb-staging.com/Item/1675

## Queries

### `minified=1`

Provides a minified version of the content, usually down to 1 depth. This is useful if a piece of content has a lot of extended information that you do not care about but may want it to provide application features.

### `columns=x,y,a.b.c`

Obtain specific columns of information from the content. View the above `/<ContentName>` documentation for information on how this works.

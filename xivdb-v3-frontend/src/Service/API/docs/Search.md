
> (9th June 2018) Work-In-Progress. This route may be unstable or provide no data at this time.   

# Search  
  
## `/Search`

Search for something. The XIVDB Search is multi-content and combined. This means your search covers a vast amount of selected content (which you can further extend via filters) and results are combined based on best-case matching.

A typical example would be searching for `ifrit`, this could potentionally return:

- Items (eg: Ifrit's Blade)
- Recipes (eg: Wind-up Ifrit)
- Quests (eg: Ifrit Ain't Broke)
- NPCs (eg: Ifrit-Egi)
- Enemies (eg: Ifrit himself!)
- Minions: (eg: Wind-p Ifrit)

## Queries

### `indexes=item,achievement,action,...`

Search a specific series of indexes, the current valid list is:

- `achievement`
- `action`
- `bnpcname` - Enemies
- `companion` - Minions
- `enpcresident` - NPCs
- `emote`
- `fate`
- `instancecontent`
- `item`
- `leve`
- `mount`
- `placename`
- `quest`
- `recipe`
- `status`
- `title`
- `weather`

Search a specific piece of content rather than all pieces of content, eg: `one=item` will only search Items.

### `string=hello-world`

Search Names for a specific string. Using additional filters this can be extended to search descriptions and lore material.

### `string_algo`

Change the string search algorithm, there are currently 6 to choose from based on ElasticSearch, these are useful to narrow down different ways of searching via strings

| Option | Details |
| -- | -- |
| wildcard | A very basic wild card, for example: `ard` would match: `b-ard-ing` or `h-ard` etc. |
| multi_match | .. |
| query_string | .. |
| term | .. |
| match_phrase_prefix | .. |
| fuzzy | .. |

### `string_column=X`

Adjust which column the string search is performed on, by default this is the `Name` column. This can be changed to things like Descriptions or even Lore Columns.

### `filters=???`

> Heavily work-in-progress, I have not decided on the format for filters.

### `page=X`

Pull content from a specific page in the search results

### `sort_field=X`

The column to sort the results by.

### `sort_order=asc|desc`

The order the sort should be, this will either be Ascending or Descending order

### `limit=X`

- Min: 1
- Max: 500

Limit the number of results, this cannot be higher than the maximum allowed

# Welcome

The API provides a massive amount of FFXIV Game data in JSON format as a REST API. You can fetch information on all sorts of content that has been discovered and mapped in the SaintCoinach Schema. In addition it provides Character, Free Company, Linkshell and Lodestone information.

---

## Endpoint

The staging API is used for testing new features, if you have an idea or want some specific information it will be tested on here first before moving to Live.

| Live | Staging |
|--|--|
| (v2) https://api.xivdb.com/ | (v3) http://api.xivdb-staging.com/ |

---

## Global Queries

### `language=XX`

This will tell the API to handle the request and the response in the specified language.

All string fields will be provided in multiple languages, so you will see this:

```
{
    "Name_en": "English",
    "Name_ja": "Japanese",
    "Name_de": "German",
    "Name_fr": "French",
    "Name_cn": "Chinese",
    "Name_kr": "Korean"
}
```

To help template usage, you may want to just use `Name`, so you can provide the query `language=fr` and now `Name` will be the French name. This is also extended to other string fields such as Descriptions.

The search will use this to decide which field to query the `string` against, for example: `language=fr&string=LeAwsome` will search for `LeAwesome` on the field `Name_fr`.

### `pretty=1`

This will provide a nice pretty JSON response, this is intended for debugging purposes. Don't use this in your production urls as it adds weight to the response. 

**Example**

```
{"ClassJobCategory.Name":"PLD","ID":1675,"Icon":"\/img\/ui\/game\/icon4\/1\/1675.png","Name":"Curtana"}
```

Becomes

```
{
    "ClassJobCategory.Name": "PLD",
    "ID": 1675,
    "Icon": "\/img\/ui\/game\/icon4\/1\/1675.png",
    "Name": "Curtana"
}
```

---

## API Keys

The API is very public and can be used without any keys but this will have some restrictions, for example `list` routes will have a reduced amount of items per page. This is to reduce the overheard of spam and prototyping. 

To get higher limits on any of the routes you can create a **Developer App** on XIVDB by going to: [###](#)

This will provide you with a key to put on your queries as `key=123`.

## Rate Limiting

The API has a rate limit of 15/second and a cool-down of 10 seconds. If you're hitting this limit, you're doing something wrong. Please do not hesitate to ask for help in the discord for best practices when querying the API. If you're just looking for a ton of data, caching is your friend :).

---

## SaintCoinach Schema
The schema can be found here: https://github.com/ufx/SaintCoinach/blob/master/SaintCoinach/ex.json

The schema is a huge JSON file that describes the EXD files found in the FFXIV game files. Many community members take time to datamining and understand the way the EXD files are mapped and this file helps describe it in a universal format.

**Special fields and schema differences**

Some fields in the API are not part of the SaintCoinach Schema and have been implemented for ease of use. For example: `NPC.Quests` provides all quests related to the NPC. 

Other files are XIVDB Specific, for example: GamePatch is XIVDB's patch tracking information, `GameContentLinks` are reverse links from one content to another. Make sure to use the schema endpoint on the API to see what you can obtain :)

In addition, to make things more simpler in the templates, some fields have been globally simplified for example a contents "`Singular`" field is known as "`Name`", a "`Masculinity`"  would also be converted to "`Name`" with "`Feminine`" converted to "`NameFemale`" 

---

### Open Source Libraries

- [PHP Lodestone Parser](https://github.com/viion/lodestone-php) - Obtain data from The Lodestone
- [C# SaintCoinach](https://github.com/ufx/SaintCoinach) - FFXIV Game Data extraction tool
- [FFXIV Datamining](https://github.com/viion/ffxiv-datamining) - FFXIV Datmaining information and resources
- [Javascript FFXIV Custom Launcher](https://github.com/viion/ffxiv-launcher) - A custom launcher for FFXIV
- [C# MemoryReader](https://github.com/Icehunter/sharlayan) - Read FFXIV memory while in-game
- [WPF TextTools](https://github.com/liinko/FFXIV_TexTools2) - Texture and file modifications


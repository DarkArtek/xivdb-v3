# XIVSYNC  
  
The XIVSYNC API sits on its own domain and provides direct access to The Lodestone. It has some benefits but also some drawbacks to the main XIVDB API. 

The main benefit is that it is real-time and parses The Lodestone directly, you do not need to wait for Characters, FC or LS's to be on XIVDB in order to get content. This is useful if you need to check if a character exists, is delete or for verification codes. 

The drawbacks are: It is not cached and the server is in Japan, this can make it feel very slow. The API is heavily rate-limited, it is not intended to be used as a crawler and thus has a 1/second rate limit. This will not change and may become more strict based on abuse.

| Live | Staging |  
|--|--|  
| (v2) https://api.xivsync.com/ | (v3) https://xivsync.xivdb-staging.com/ |

--- 

# Characters
  
## `/character/search`  
  
Search lodestone for a character.  
  
| Field | Details |  
| -- | -- |  
| `name` | The name to search |  
| `server` | The server to search |  
| `page` | Page to parse |  
  
## `/character/parse/{id}`  
  
Parse a character via their `{id}`  
  
## `/character/parse/{id}/following`  
  
Parse a characters following via their `{id}`  
  
## `/character/parse/{id}/achievements`  
  
Parse a characters achievements via their `{id}`  
  
## `/character/parse/{id}/achievements?category={category_id}`  
  
Parse a specific category of character achievements via their `{id}`  
  
- eg: `/character/parse/730968/achievements?category=3`  
  
---  

# Free Company
  
## `/freecompany/search`  
  
Search lodestone for free companies.  
  
| Field | Details |  
| -- | -- |  
| `name` | The name to search |  
| `server` | The server to search |  
| `page` | Page to parse |  
  
## `/freecompany/parse/{id}`  
  
Parse a free company via their {id}  
  
## `/freecompany/parse/{id}/members`  
  
Parse a free company member list via their `{id}`  
  
| Field | Details |  
| -- | -- |  
| `page` | Page to parse |  
  
---  

# Linkshell
  
## `/linkshell/search`  
  
Search lodestone for linkshells.  
       
| Field | Details |  
| -- | -- |  
| `name` | The name to search |  
| `server` | The server to search |  
| `page` | Page to parse |       
       
  
## `/linkshell/parse/{id}`  
  
Parse a linkshell via their `{id}`  
  
| Field | Details |  
| -- | -- |  
| `page` | Page to parse |  
  
---  
  
# Lodestone  
  
## `/lodestone/banners`  
  
Get lodestone banners.  
  
## `/lodestone/news`  
  
Get lodestone news.  
  
## `/lodestone/topics`  
  
Get lodestone topics.  
  
## `/lodestone/notices`  
  
Get lodestone notices.  
  
## `/lodestone/maintenance`  
  
Get lodestone maintenance.  
  
## `/lodestone/updates`  
  
Get lodestone updates.  
  
## `/lodestone/status`  
  
Get lodestone status.  
  
## `/lodestone/world-status`  
  
Get lodestone world-status.  
  
## `/lodestone/dev-blog`  
  
Get lodestone Dev-Blogs.  
  
## `/lodestone/leaderboards/feast`  
  
Get feast loaderboards.  
  
## `/lodestone/leaderboards/deep-dungeon`  
  
Get deep-dungeon loeaderboards.  
  
## `/lodestone/forums/dev-posts`  
  
Get forums dev-posts.

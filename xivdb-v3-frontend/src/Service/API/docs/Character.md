> (9th June 2018) Work-In-Progress. This route may be unstable or provide no data at this time.

# Character  

## `/Character/<lodestone_id>`  
  
Get information for a character. Characters must be added to the service using XIVSYNC.
  
## Queries  
  
### `files=x,y,z`  
  
| Parameters| Details |
|--|--|
| `data` | Get basic profile information (including Minions and Mounts) |
| `friends` | Get a list of the characters friends |
| `achievements` | Get the characters achievements (if they're public) |
| `sync` | Get XIVSync information of the character |
| `events` | Get EXP/Level Events |
| `gear` | Get Gearsets |
| `tracking` | Get tracking information (name, server, race changes, etc) |
      
You can query multiple files at one time  
  
- `files=data,friends,sync`  
  
### `extend=1/0`
  
| Parameters | Details
| -- | -- |
| `1/0` | *bool* - Extends profile and gear data |

- For profile, this will include information about Grand Companies, full Minion/Mount information etc.
- For gear, this will include equipment, dye, materia and glamour item details.

### `fc=1/0`

| Parameters | Details
| -- | -- |
| `1/0` | *bool* - Include characters Free Company information|

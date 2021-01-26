# Comment API DOCS

# Base URL
http://localhost

# Other resources 

 
# Headers

Authorization: key your token

Accept : application/json

# API 

| Route                        | Request Method | Parameters | Response  |
| -----------                  | -----------    |----------- |---------- |
| /api/admin/comments            | POST           |  [Create Parmaters](#Create)|[Response](#Response)|
| /api/admin/comments | GET           |-|  [Response](#Response)         |
|/api/admin/comments/{id}         | GET           |  - |  [Response](#Response)         |
|/api/admin/comments/{id}        |PUT           |  [Update Parmaters](#Update)|[Response](#Response)     |
|/api/admin/comments/{id}        |DELETE           |  -|[Response](#Response)| 
|/api/comments        |GET           |-| [Response](#Response)|
|/api/comments/{id}        |GET           |-|[Response](#Response)|


# <a name="Create"> </a> Create new Comment 

```json
{
"comment" : "String"
} 
```

# <a name="Update"> </a> Update Comment

```json
{
"comment" : "String"
} 
```
# <a name="Response"> </a> Responses 

## Unauthorized error

__*Response code : 401*__
```json 
{
    "message" : "Unauthenticated"
}
```

## Validation error 
__*Response code : 422*__

```json 
{
    "errors" {
        "Key" : "Error message"
    }
}
```
## Success  
__*Response code : 200*__
```json 
{
    "records" [
        {

        },
    ]
}
```

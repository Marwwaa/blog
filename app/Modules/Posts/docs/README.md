# Post API DOCS

# Base URL
http://localhost

# Other resources
[Comment](./comment.md) 

 
# Headers

Authorization: key your token

Accept : application/json

# API 

| Route                        | Request Method | Parameters | Response  |
| -----------                  | -----------    |----------- |---------- |
| /api/admin/posts            | POST           |  [Create Parmaters](#Create)|[Response](#Response)|
| /api/admin/posts | GET           |-|  [Response](#Response)         |
|/api/admin/posts/{id}         | GET           |  - |  [Response](#Response)         |
|/api/admin/posts/{id}        |PUT           |  [Update Parmaters](#Update)|[Response](#Response)     |
|/api/admin/posts/{id}        |DELETE           |  -|[Response](#Response)| 
|/api/posts        |GET           |-| [Response](#Response)|
|/api/posts/{id}        |GET           |-|[Response](#Response)|


# <a name="Create"> </a> Create new Post 

```json
{
"title" : "String"
"description" : "String"
"iamge" : "File"
} 
```

# <a name="Update"> </a> Update Post

```json
{
"title" : "String"
"description" : "String"
"iamge" : "File"
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

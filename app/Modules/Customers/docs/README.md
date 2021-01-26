# Customer API DOCS

# Base URL
http://localhost

# Other resources 

 
# Headers

Authorization: key your token

Accept : application/json

# API 

| Route                        | Request Method | Parameters | Response  |
| -----------                  | -----------    |----------- |---------- |
| /api/admin/customers            | POST           |  [Create Parmaters](#Create)|[Response](#Response)|
| /api/admin/customers | GET           |-|  [Response](#Response)         |
|/api/admin/customers/{id}         | GET           |  - |  [Response](#Response)         |
|/api/admin/customers/{id}        |PUT           |  [Update Parmaters](#Update)|[Response](#Response)     |
|/api/admin/customers/{id}        |DELETE           |  -|[Response](#Response)| 
|/api/customers        |GET           |-| [Response](#Response)|
|/api/customers/{id}        |GET           |-|[Response](#Response)|


# <a name="Create"> </a> Create new Customer 

```json
{
"name" : "String"
"email" : "String"
"phoneNumber" : "String"
"password" : "String"
} 
```

# <a name="Update"> </a> Update Customer

```json
{
"name" : "String"
"email" : "String"
"phoneNumber" : "String"
"password" : "String"
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

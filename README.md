## Laravel project Taken-away-items-info

Documentation to Taken-away-items-info APIs.

-   [How to run project](#how)
-   [Dummy data](#dummy)
-   [API methods](#api)
    -   [Authentication](#auth)
        -   [Login](#auth-login)
        -   [Register](#auth-register)
        -   [Logout](#auth-logout)
        -   [Refresh](#auth-refresh)
        -   [User Profile](#auth-profile)
        -   [Update password](#auth-update)
        -   [Reset password](#auth-reset)
        -   [Delete student account](#auth-delete)
    -   [Inventory](#inventory)
        -   [Get all (User:Admin)](#inventory-all-admin)
        -   [Get all (User:Admin) with search params](#inventory-search)
        -   [Get by id (User:Admin)](#inventory-by-id)
        -   [Get all (User:Student)](#inventory-all-student)
        -   [Create Inventory (User:Admin)](#inventory-post)
        -   [Update Inventory (User:Admin)](#inventory-put)
        -   [Delete Inventory (User:Admin)](#inventory-delete)
        -   [Assign Inventory (User:Admin)](#inventory-assign-admin)
        -   [Assign Inventory (User:Student)](#inventory-assign-student)
        -   [Unassign Inventory (User:Admin)](#inventory-unassign-admin))
        -   [Unassign Inventory (User:Student)](#inventory-unassign-student)
        -   [Which student responsible for inventory (User:Student)](#inventory-responsible)
    -   [Student](#student)
        -   [Get all students (User:Admin)](#get-all-students)
-   [HTTP Response Codes](#responses)

## <a name="how"></a>How to run project:

-   Create a database locally
-   Rename `.env.example` file to `.env`inside your project root and fill the database information and mail information.
-   Open the console and cd your project root directory
-   Run `composer install`
-   Run `php artisan key:generate`
-   Run `php artisan jwt:secret`
-   Run `php artisan migrate`
-   Run `php artisan db:seed` to run seeders, if any.
-   Run `php artisan serve`

#### You can now access your project at http://127.0.0.1:8000 :)

### If for some reason your project stop working do these:

-   `composer install`
-   `php artisan migrate`

# <a name="dummy">Dummy data

Run `php artisan db:seed`

Creates 1 admin user (`email = info@teltonika.lt, password = teltonika`), 2 student users(`email = n.antanavicius2000@gmail.com, password = kretinga; email = jubartas@gmail.com, password = lexus;`) and 3 invetory items.


# <a name="api"></a>API methods

## <a name="auth"></a>Authentication

### <a name="auth-login">Login

Access to the API is granted by providing your email and password using HTTP basic authentication.

```no-highlight
Post http://127.0.0.1:8000/api/auth/login
```

#### Body parameters

| Name     | Type   |
| -------- | ------ |
| email    | string |
| password | string |

#### Response

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODUxNzY4MCwiZXhwIjoxNjQ4NTIxMjgwLCJuYmYiOjE2NDg1MTc2ODAsImp0aSI6InpmQlJ5aHhBNzBWbm5ZeVYiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.h23C3EzLAzunaLfSkYnaU5KH6gOLKM2dgpCHXgSN4E4",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 2,
        "name": "Neilas",
        "surname": "Antana",
        "email": "n.antan@gmail.com",
        "isAdmin": 0,
        "created_at": null,
        "updated_at": null
    }
}
```

### <a name="auth-register">Register

```no-highlight
Post http://127.0.0.1:8000/api/auth/register
```

#### Body parameters

| Name      | Type   |
| --------- | ------ |
| name      | string |
| surname   | string |
| email     | string |

#### Response

```json
{
    "status": "ok",
    "message": "User successfully registered"
}
```

### <a name="auth-logout">Logout

```no-highlight
Post http://127.0.0.1:8000/api/auth/logout
```
#### Response

```json
{
    "status": "ok",
    "message": "User successfully signed out"
}
```

### <a name="auth-refresh">Refresh

```no-highlight
Post http://127.0.0.1:8000/api/auth/refresh
```
#### Response
```json
{
    {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9yZWZyZXNoIiwiaWF0IjoxNjQ4NTEzMjY5LCJleHAiOjE2NDg1MTY4ODUsIm5iZiI6MTY0ODUxMzI4NSwianRpIjoiNmsxZnpoYnY4RVRWZXd0ciIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.F6KfJDR647nB241qn6DmBZNlLtJc946UxnotnQJoGlQ",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "name": "Lukas",
        "surname": "Asd",
        "email": "asd@asd.lt",
        "isAdmin": 1,
        "created_at": null,
        "updated_at": null
            }
    }
}
```

### <a name="auth-profile">User profile

```no-highlight
Get http://127.0.0.1:8000/api/auth/user-profile
```
#### Response
```json
    {
        "id": 1,
        "name": "Lukas",
        "surname": "Asd",
        "email": "asd@asd.lt",
        "isAdmin": 1,
        "created_at": null,
        "updated_at": "2022-03-29T00:32:54.000000Z"
    }
```

### <a name="auth-update">Update password

```no-highlight
Post http://127.0.0.1:8000/api/auth/update-password
```

#### Body parameters

| Name      | Type   |
| --------- | ------ |
| password  | string |


#### Response

```json
{
    "status": "ok",
    "message": "Password successfully updated"
}
```

## <a name="auth-reset">Reset password

```no-highlight
Post http://127.0.0.1:8000/api/auth/reset-password
```

#### Body parameters

| Name      | Type   |
| --------- | ------ |
| email     | string |


#### Response

```json
{
    "status": "ok",
    "message": "Password successfully updated"
}
```

### <a name="auth-delete">Delete student account (User:Admin)

```no-highlight
Delete http://127.0.0.1:8000/api/auth/{id}
```

#### Response

```json
{
    "status": "ok",
    "message": "Student successfully deleted"
}
```

## <a name="inventory">Invetory

### <a name="inventory-all-admin">Get all (User:Admin)

```no-highlight
GET http://127.0.0.1:8000/api/inventory/index
```

#### Response

```json
[
    {
    "id": 4,
    "user_id": null,
    "name": "Modemas",
    "model": "RUTX9111",
    "serial_number": 14785211,
    "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
    "created_at": "2022-03-29T00:42:50.000000Z",
    "updated_at": "2022-03-29T00:42:50.000000Z",
    "user": null
    },
    {
    "id": 1,
    "user_id": null,
    "name": "Modem",
    "model": "RUTX11",
    "serial_number": 1114915382,
    "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
    "created_at": null,
    "updated_at": null,
    }   
]
```

### <a name="inventory-search">Get all (User:Admin) with search params

```no-highlight
GET http://127.0.0.1:8000/api/inventory/index?search={value}&status={assign/unassign}
```

#### Response

```json
[
    {
        "id": 1,
        "user_id": 2,
        "name": "Modem",
        "model": "RUTX11",
        "serial_number": 1114915382,
        "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
        "created_at": null,
        "updated_at": null,
        "user": {
            "id": 2,
            "name": "Neilas",
            "surname": "Antana",
            "email": "n.antana@gmail.com",
            "isAdmin": 0,
            "created_at": null,
            "updated_at": null
        }
    }
]

```

### <a name="inventory-get-by-id">Get by id (User:Admin)

```no-highlight
GET http://127.0.0.1:8000/api/inventory/{id}
```

#### Response

```json
{
    "id": 4,
    "user_id": null,
    "name": "Modemas",
    "model": "RUTX9111",
    "serial_number": 14785211,
    "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
    "created_at": "2022-03-29T00:42:50.000000Z",
    "updated_at": "2022-03-29T00:42:50.000000Z",
    "user": null
}

```

### <a name="inventory-all-student">Get all (User:Student)

```no-highlight
GET http://127.0.0.1:8000/api/inventory/index
```

#### Response

```json
[
    {
        "id": 2,
        "user_id": null,
        "name": "Modem",
        "model": "RUTX911",
        "serial_number": 1117915382,
        "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
        "created_at": null,
        "updated_at": "2022-03-29T01:18:42.000000Z",
        "user": null
    },
    {
        "id": 4,
        "user_id": null,
        "name": "Modemas",
        "model": "RUTX9111",
        "serial_number": 14785211,
        "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
        "created_at": "2022-03-29T00:42:50.000000Z",
        "updated_at": "2022-03-29T00:42:50.000000Z",
        "user": null
    }
]
```

### <a name="inventory-post">Create Inventory (User:Admin)

```no-highlight
Post http://127.0.0.1:8000/api/inventory
```

#### Body parameters

| Name           | Type   |
| ---------------| ------ |
| name           | string |
| model          | string |
| serial_number  | integer|
| comment        | string |


#### Response

```json

   {
    "status": "ok",
    "message": "Product successfully stored"
   }

```

### <a name="inventory-put">Update Inventory (User:Admin)

```no-highlight
Put http://127.0.0.1:8000/api/inventory/{id}
```

#### x-www-form-urlencoded parameters

| Name           | Type   |
| ---------------| ------ |
| name           | string |
| model          | string |
| serial_number  | integer|
| comment        | string |


#### Response

```json
   {
    "status": "ok",
    "message": "Product successfully updated"
   }
```

### <a name="inventory-delete">Delete Inventory (User:Admin)

```no-highlight
Delete http://127.0.0.1:8000/api/inventory/{id}
```

#### Response

```json
   {
    "status": "ok",
    "message": "Product successfully deleted"
   }
```

### <a name="inventory-assign-admin">Assign Inventory (User:Admin)

```no-highlight
Post http://127.0.0.1:8000/api/inventory/{user_id}/assign
```

#### Body parameters

| Name           | Type   |
| ---------------| ------ |
| inventory_id   | int    |


#### Response

```json

   {
    "status": "ok",
    "message": "Successfully assigned inventory"
   }

```
### <a name="inventory-assign-student">Assign Inventory (User:Student)

```no-highlight
Post http://127.0.0.1:8000/api/inventory/assign
```

#### Body parameters

| Name           | Type   |
| ---------------| ------ |
| inventory_id   | int    |


#### Response

```json

   {
    "status": "ok",
    "message": "Successfully assigned inventory"
   }

```

### <a name="inventory-unassign-admin">Unassign Inventory (User:Admin)

```no-highlight
Put http://127.0.0.1:8000/api/inventory/unassign
```

#### X-WWW-FORM-URLECODED

| Name           | Type   |
| ---------------| ------ |
| inventory_id   | int    |



#### Response

```json

   {
    "status": "ok",
    "message": "Successfully unassigned inventory"
   }

```

### <a name="inventory-unassign-student">Unassign Inventory (User:Student)

```no-highlight
Put http://127.0.0.1:8000/api/inventory/unassign
```

#### X-WWW-FORM-URLECODED

| Name           | Type   |
| ---------------| ------ |
| inventory_id   | int    |


#### Response

```json

   {
    "status": "ok",
    "message": "Successfully unassigned inventory"
   }

```

### <a name="inventory-responsible">Which student responsible for inventory (User:Student)

```no-highlight
Get http://127.0.0.1:8000/api/inventory/responsible
```

#### Response

```json
[
    {
        "id": 1,
        "user_id": 2,
        "name": "Modem",
        "model": "RUTX11",
        "serial_number": 1114915382,
        "comment": "Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.",
        "created_at": null,
        "updated_at": null,
        "user": {
            "id": 2,
            "name": "Neilas",
            "surname": "Antana",
            "email": "n.antanagmail.com",
            "isAdmin": 0,
            "created_at": null,
            "updated_at": null
        }
    }
]

```

## <a name="student"></a>Student

### <a name="get-all-students">Get all students (User:Admin)
```no-highlight
Get http://127.0.0.1:8000/api/auth/show-students
```


#### Response

```json

[
{
    "id": 2,
    "name": "Neilas",
    "surname": "Antana",
    "email": "n.antana@gmail.com",
    "isAdmin": 0,
    "created_at": null,
    "updated_at": null
}
]

```


## <a name="responses"></a>HTTP Response Codes

Each response will be returned with one of the following HTTP status codes:

-   `200` `OK` The request was successful
-   `400` `Bad Request` There was a problem with the request (security, malformed, data validation, etc.)
-   `404` `Not found` An attempt was made to access a resource that does not exist in the API

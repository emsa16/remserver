REM Server - a REST Mockup API
===========================================

This is a mockup server for development of applications working with a REST API. The server responds to any API endpoint, stores data and changes to the data in the user session.

The server has a default dataset for the endpoint `api/users`.

You can add your own datasets and work with them through `api/[datasets]`.



Try it {#try}
-------------------------------------------

You can try out the pre-populated dataset `users`.

* [Get all users](api/users)
* [Get the user with `id=1`](api/users/1)



API {#api}
-------------------------------------------

###Get the dataset {#all}

Get the full dataset, or a part of it.

```text
GET /api/[dataset]
GET /api/users
```

Results.

```json
{
    "data": [],
    "offset": 0,
    "limit": 25,
    "total": 0
}

{
    "data": [
        {
            "id": "1",
            "firstName": "Phuong",
            "lastName": "Allison"
        },
        ...
    ],
    "offset": 0,
    "limit": 25,
    "total": 12
}
```

Optional query string parameters.

* `offset` defaults to 0.
* `limit` defaults to 25.

```text
GET /api/users?offset=0&limit=25
```



###Get one entry {#one}

Get one entry based on its id.

```text
GET /api/users/7
```

Results.

```json
{
    "id": "7",
    "firstName": "Etha",
    "lastName": "Nolley"
}
```



###Create a new entry {#create}

Add a new entry to a dataset, create the dataset if it does not exists and will add a id to the entry.

```text
POST /api/[dataset]
{"some": "thing"}

POST /api/users
{"firstName": "Mikael", "lastName": "Roos"}
```

Results.

```json
{
    "some": "thing",
    "id": 1
}

{
    "firstName": "Mikael",
    "lastName": "Roos",
    "id": 13
}
```



###Upsert/replace a entry {#upsert}

Upsert (insert/update) or replace a entry, create the dataset if it does not exists.

```text
PUT /api/[dataset]/1
{"id": 1, "other": "thing"}

PUT /api/users/13
{"id": 13, "firstName": "MegaMic", "lastName": "Roos"}
```

The value in the id-field is updated to match the one from the PUT request value.

Results.

```json
{
    "other": "thing",
    "id": 1
}

{
    "id": 13,
    "firstName": "MegaMic",
    "lastName": "Roos"
}
```



###Delete a entry {#delete}

Delete a entry.

```text
DELETE /api/[dataset]/1

DELETE /api/users/13
```

The result will always be `null`.



Other REM servers {#other}
-------------------------------------------

There are more servers doing the same thing.

* [REM REST API](http://rem-rest-api.herokuapp.com/)



Source {#source}
-------------------------------------------

The source is on GitHub in [dbwebb-se/rem-server](https://github.com/dbwebb-se/rem-server).

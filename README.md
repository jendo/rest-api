**Own REST API implementaion usign SLIM framework**

My own rest api server using SLIM framework and nette DIC with REDIS as primary storage.

API routes are configured via api.neon file with registered services in config.neon.

**API endpoints**

**POST: /user/create**
- create new user
- payload request looks like:
```
{
  "userId":<int>,
  "name": <string>,
  "surname": <string>
}
```

**POST: /game/save**
- save game result 
- payload request looks like:
```
{
  "userId":<int>,
  "gameId":<int>,
  "score":<int>
}
```

**GET: /game-result/{id}**
- return first 10 users ordered by their game scores

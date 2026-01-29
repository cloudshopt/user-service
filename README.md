## user-service
- **Purpose:** User registration/login and JWT issuance.
- **Base path:** `/api/users`

### Create database
```
kubectl -n cloudshopt exec -it cloudshopt-mysql-0 -- bash

# mysql -u root -prootpass

CREATE DATABASE cloudshopt_users CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'users'@'%' IDENTIFIED BY 'CHANGE_ME_PASSWORD';
GRANT ALL PRIVILEGES ON cloudshopt_users.* TO 'users'@'%';
FLUSH PRIVILEGES;
```

### Migrations

```
kubectl exec -n cloudshopt -it deploy/user-service -c app -- sh
# php artisan migrate
```


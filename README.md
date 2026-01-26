# User service

## Create users database + user on mysql server
```
kubectl -n cloudshopt exec -it cloudshopt-mysql-0 -- bash
```

```
CREATE DATABASE cloudshopt_users CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'users'@'%' IDENTIFIED BY 'userspass';
GRANT ALL PRIVILEGES ON cloudshopt_users.* TO 'users'@'%';
FLUSH PRIVILEGES;
```

Ustvari še bazo za *dev* okolje
```
CREATE DATABASE cloudshopt_users_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'users_dev'@'%' IDENTIFIED BY 'userspass';
GRANT ALL PRIVILEGES ON cloudshopt_users_dev.* TO 'users_dev'@'%';
FLUSH PRIVILEGES;
```

## Crete external secrets for prod and dev
prod:
```
kubectl -n cloudshopt create secret generic user-service-secrets \
  --from-literal=DB_PASSWORD="userspass" \
  --from-literal=REDIS_PASSWORD="redispass" \
  --dry-run=client -o yaml | kubectl apply -f -
```

dev:
```
kubectl -n cloudshopt-dev create secret generic user-service-secrets \
  --from-literal=DB_PASSWORD="userspass" \
  --from-literal=REDIS_PASSWORD="redispass" \
  --dry-run=client -o yaml | kubectl apply -f -
```

check for secrets:
```
kubectl get secret -n cloudshopt user-service-secrets
kubectl get secret -n cloudshopt-dev user-service-secrets
```

## Install user-service for prod and dev
prod:
```
helm upgrade --install user-service ./helm/user-service \
-n cloudshopt \ 
-f helm/user-service/values.yaml
```

dev:
```
helm upgrade --install user-service-dev ./helm/user-service \
-n cloudshopt-dev \ 
-f helm/user-service/values-dev.yaml
```



## Migrations

run migrations:
```
kubectl exec -n cloudshopt-dev -it deploy/user-service-dev -c app -- sh

# php artisan migrate
```

asd
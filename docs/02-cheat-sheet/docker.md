## Docker Cheat Sheet

1. Log into Container
```
docker exec -it <container-name> bash
```

or

```
docker exec -it <container-name> sh
```

2. View logs from Container
```
docker logs <container-name> -f
```

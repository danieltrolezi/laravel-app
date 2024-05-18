## Docker Setup on Ubuntu

### Docker Engine

1. Setup Docker's apt repository
```
# Add Docker's official GPG key:
sudo apt-get update
sudo apt-get install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
```

2. Install the Docker packages
```
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

3. Verify that the Docker Engine installation is successful by running the hello-world image
```
sudo docker run hello-world
```

4. List Networks
```
docker network list
```

5. Create Network if there is no network with `DRIVER=bridge` and `SCOPE=local`
```
docker network create bridge
```

### Credential Store (docker-login)

1. Create new GPG Key
```
gpg --full-generate-key
```

2. Get the GPG Key ID
```
gpg --list-secret-keys --keyid-format=long
```

It should return something like:
```
/Users/hubot/.gnupg/secring.gpg
------------------------------------
sec   4096R/3AA5C34371567BD2 2016-03-10 [expires: 2017-03-10]
uid                          Hubot <hubot@example.com>
ssb   4096R/4BB6D45482678BE3 2016-03-10
```

In that case, **3AA5C34371567BD2** is the ID.

3. Set up Password Store
```
pass init 3AA5C34371567BD2
```

4. Store temp password, necessary to proper initialize the service
```
pass insert docker-credential-helpers/tmp
```

5. Check passwords
```
pass show
```

6. Download `docker-credential-pass` binary from [docker-crential-helpers release page](https://github.com/docker/docker-credential-helpers/releases)

7. Rename it from `docker-credential-pass-v0.8.1.linux-amd64` to `docker-credential-pass`
```
$ cp ./docker-credential-pass-v0.8.1.linux-amd64 ./docker-credential-pass
$ rm docker-credential-pass-v0.8.1.linux-amd64
```

8. Add binary to $PATH
```
$ cp ./docker-credential-pass /usr/local/bin/
$ rm docker-credential-pass
```

10. Verify by running
```
docker-credential-pass
```

It should return something like this:
```
Usage: docker-credential-pass <store|get|erase|list|version>
```

11. Set `docker-credential-pass` as the `credsStore` for Docker.
Open your `~/.docker/config.json` file and change the value of `credsStore` to `pass`:
```
{
   "credsStore":"pass"
}
```

12. Login into Docker Hub
```
docker login
```

13. Verify
```
docker-credential-pass list
```

And also:
```
pass show
```

14. Remove tmp password created on step 4:
```
pass remove docker-credential-helpers/tmp
```

### Docker Desktop

1. Download latest [DEB package](https://desktop.docker.com/linux/main/amd64/149282/docker-desktop-4.30.0-amd64.deb?utm_source=docker&utm_medium=webreferral&utm_campaign=docs-driven-download-linux-amd64) and install it
```
sudo apt-get update
sudo apt-get install ./docker-desktop-<version>-<arch>.deb
```

2. The latest Ubuntu 24.04 LTS is not yet supported. Docker Desktop will fail to start. Due to a change in how the latest Ubuntu release restricts the unprivileged namespaces, run the code bellow at least once
```
sudo sysctl -w kernel.apparmor_restrict_unprivileged_userns=0
```

3. To start Docker Desktop for Linux, search Docker Desktop on the Applications menu and open it.

4. ?

## References

* [Docker - Install Docker Desktop on Ubuntu](https://docs.docker.com/desktop/install/ubuntu/)
* [Docker - Install Docker Engine on Ubuntu](https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository)
* [Docker - Credential Store](https://docs.docker.com/reference/cli/docker/login/#credential-stores)
* [Setting up Password Store](https://www.passwordstore.org/)
* [Github - Gerar uma chave GPG](https://docs.github.com/pt/authentication/managing-commit-signature-verification/generating-a-new-gpg-key#generating-a-gpg-key)
* [AskUbuntu - Add binary to my path](https://askubuntu.com/questions/440691/add-a-binary-to-my-path)
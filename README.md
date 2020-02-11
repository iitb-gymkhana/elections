# Elections

Secure portal to conduct elections in a Symfony-like stack.

## Configuration
Put database and other information in `bootstrap.php`. Install `mod-auth-openidc` to provide `REMOTE_USER`. The configuration of apache will look something like the following (note that `/safe/` needs a valid user).
```
<VirtualHost *:80>
    DocumentRoot /var/www/html

    OIDCProviderMetadataURL https://sso.iitb.ac.in/.well-known/openid-configuration
    OIDCScope "openid"
    OIDCClientID election
    OIDCClientSecret secret
    OIDCRedirectURI http://deployment/safe/redir
    OIDCCryptoPassphrase openstack

    <Location /safe>
       AuthType openid-connect
       Require valid-user
    </Location>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    <Directory /var/www/html>
        Options -Indexes
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Migrations
Perform migrations with PHP CLI
```bash
vendor/bin/doctrine orm:schema-tool:update --dump-sql
```

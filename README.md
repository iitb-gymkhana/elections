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

## Proxy cache generation
Use the following command to generate proxy cache
```
vendor/bin/doctrine orm:generate-proxies
```

## Electron Integration
Pack the website with nativefier and add the following lines to the indicated files. After this is done, electron should clear cookies of all sites after logout from elections.
```js
// resources/app/lib/main.js
// Find this line -- mainWindowState.manage(mainWindow);

_electron.ipcMain.on('logout', () => clearCache(mainWindow, options.targetUrl));
```

```js
// resources/app/lib/static/preload.js
// Add just after imports

window.ipc = _electron.ipcRenderer;
```

Set internal urls in JSON config to
```js
"https:\\/\\/(gymkhana\\.iitb\\.ac\\.in\\/election|sso.*\\.iitb\\.ac\\.in).*"
```

Generate the native app with
```bash
nativefier --honest --fast-quit --full-screen --hide-window-frame --disable-context-menu --disable-dev-tools --single-instance --clear-cache --always-on-top https://gymkhana.iitb.ac.in/election/
```

This is a varnish interface.

Requirements:

1. working varnishadm binary. You can copy the varnishadm binary from the varnish servers along with the required so files
2. sqlite3 installed

![alt tag](https://raw.githubusercontent.com/rghose/varnish-interface/master/vat_1.png)

The config file is needed to be configured in config.php.

We also need varnishadm to be installed on the server, and the secret files of the servers to be queried if they use authentication.

Whats new?

* Logs server set activity to syslog
* Servers can be edited from management interface
* Cluster management
* Uses active directory authentication
* Varnish interface manager used to add varnish servers' secrets

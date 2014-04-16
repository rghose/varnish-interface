This is a varnish interface.

.. figure:: https://raw.githubusercontent.com/rghose/varnish-interface/master/vat_1.png
    :align: center

The config file is needed to be configured in config.php.

We also need varnishadm to be installed on the server, and the secret files of the servers to be queried if they use authentication.

Whats new?

* Logs server set activity to syslog
* Servers can be edited from management interface
* Cluster management
* Uses active directory authentication
* Varnish interface manager used to add varnish servers' secrets
BFOSSettingsManagementBundle
============================

Symfony2 bundle to easily manage your application settings.

Installing
----------

**Install from composer for symfony 2.1**

Add BFOSSettingsManagementBundle in your composer.json:

```js
{
    "require": {
        "brazilianfriendsofsymfony/settings-management-bundle": "dev-master"
    }
}
```

**Register BFOSSettingsManagementBundle into your application kernel**

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...,
           new BFOS\SettingsManagementBundle\BFOSSettingsManagementBundle()
            // ...,
        );

        //..
        return $bundles;
    }

** Import the routes in app/config/routing.yml **

    bfos_settings_management:
        resource: "@BFOSSettingsManagementBundle/Resources/config/routing.yml"

PREPARE YOUR APPLICATION SETTINGS
=================================

Manage application settings
---------------------------

First you need to decide which setting the admin can change. You do that using a command like the following example.

In these example we create an email setting that you allow the admin to customize the application's checkout email.

    php app/console bfos:setting:create checkout_email email_template --roles="ROLE_ADMIN,ROLE_MANAGER"


LET YOUR USERS TO CUSTOMIZE THE SETTINGS
========================================

Creating your administration's settings page
--------------------------------------------

You need to create a page where you want your users to go to change the settings. In that template page you
use the Twig funciont *bfos_settings_management* that will output a whole admin interface. See the
example below.


{% extends 'AppAdminBundle::layout.html.twig' %}

{% block content %}
{{ bfos_settings_management() }}
{% endblock content %}


USING THE SETTINGS
==================

Rendering Twig template from strings
------------------------------------

When storing email templates in database, you'll need a way to render Twig templates from strings. The
following examples shows you how.

$this->get('bfos_setting_management.twigstring')->render($this->get('bfos_setting_management.setting_manager')->getValue('checkout_email'), array('name' => 'Fabien'));

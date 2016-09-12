TYPO3 Extension: Flux forms for EXT:news
========================================

Provides Flux forms integrations for EXT:news - allows defining `<flux:form>` in an EXT:news controller
template to add/modify the FlexForm used by EXT:news.

What does it do?
----------------

In essence: puts the definitions of form fields your editors use when inserting EXT:news plugin instances,
into the template that is rendered by the controller. Ideal when your overridden EXT:news templates need
additional user-configurable options which are not part of the default EXT:news FlexForm - lets you define
such form fields directly in the template that requires such values.

The integration is built using standard Flux integrations with minimal adaptations in key places.
It consists of a single class file (around 25 lines of code) )and one line of code to register this class,
which is all that is necessary to connect Flux to EXT:news.

Installation
------------

This extension is only available through composer/Packagist:

```
composer require namelesscoder/newsflux
```

Afterwards, either execute:

```
./typo3/cli_dispatch.phpsh extbase extension:install newsflux
```

Or, activate the extension in the Extension Manager.

There are no other officially supported installation methods.

Configuration
-------------

There is no configuration for this extension. The integrations only trigger if you actually define a form
in the templates you override from EXT:news and this form will contain all your configuration.

To consume TypoScript variables in your form such variables *must be addded to the `plugin.tx_news.settings`
scope - which is already where you would define variables you would use as `{settings}` in your EXT:news
templates, including additional variables beyond those EXT:news defines.

How do use the feature
----------------------

The integration works by making a connection between the template file that would be rendered by the plugin
instance and the form that is displayed in the "Plugin options" FlexForm field - which then allows Flux to
read a `flux:form` if one is defined in the template.

This means that your point of integration is *your overridden templates* which you added to the template
paths of EXT:news just like you normally would. This **also** means that you *must override the controller
template if for example you need the variable in a Partial template rendered via that controller action*.
In other words: you cannot define a `flux:form` in a Partial template or a Layout - it must be in one of
the `List.html`, `Detail.html` etc. templates.

Once the `flux:form` is defined this integration will automatically add the sheets/fields you define inside
that `flux:form` to the data structure provided by EXT:news. The merging is done by *recursively replacing*
any existing fields, e.g. if you define a sheet or field that exists in the default data structure then that
sheet or field is overridden with the one you define.

Example
-------

An example `flux:form` integrated into the `List.html` template to allow selecting a CSS class name:

```html
{namespace n=GeorgRinger\News\ViewHelpers}
{namespace flux=FluidTYPO3\Flux\ViewHelpers}
<f:layout name="General" />
<!--
	=====================
    Templates/News/List.html
-->

<f:section name="Configuration">
	<flux:form id="extended">
	    <flux:form.sheet name="extended" label="Extended fields">
		    <flux:field.select name="settings.myCssClass" label="Special CSS class"
		                       items="normal,special,awesome" />
        </flux:form.sheet>
	</flux:form>
</f:section>

<f:section name="content">
    <div class="{settings.myCssClass}">
        ...
    </div>
</f:section>
```

To briefly explain this template:

* The `Configuration` section is added to an overridden template
* `Configuration` section has a `flux:form` is added which has a single sheet named/labeled "Extended fields"
* The variable editors will enter in this field can then be used in the standard output section

All features known from the Flux ViewHelper API can be used and will behave in the same way you are used to
from extensions such as `fluidcontent` and `fluidpages`.
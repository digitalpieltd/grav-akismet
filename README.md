# Akismet Plugin

The **Akismet** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). Adds very basic Akismet spam detection to Grav forms.

## Installation

Installing the Akismet plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `akismet`. You can find these files on [GitHub](https://github.com/digitalpieltd/grav-plugin-akismet) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/akismet
	
> NOTE: This plugin is a modular component for Grav which requires the Form plugin.

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/akismet/akismet.yaml` to `user/config/plugins/akismet.yaml` and only edit that copy.

Here is the default configuration:

```yaml
enabled: true
akismet_key: null
is_test: false
```

Note that if you use the Admin Plugin, a file with your configuration named akismet.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

Add Akismet as a process in your form processes with three values, name, email and comment. These must be the name of the fields that will be sent to Akismet. Currently these are not set by default and must be defined.
```yaml
process:
    -
      akismet:
        name: name
        email: email
        comment: comment
```

If Akismet flags the comment as spam, no more processing will occur. As such it is important to put akismet before any processes you want to stop.

## To Do

- [ ] Include more environment details to Akismet
- [ ] Ability to mark as ham / spam.
- [ ] Add error checking / handling
- [ ] Update admin form


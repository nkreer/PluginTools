# PluginTools

This is a plugin for the [Fish IRC-Bot](https://github.com/nkreer/fish) that helps with plugin creation.

## Installation

Drop the PluginTools.phar into your _plugins_ directory and restart Fish

## Permissions

You need to be an operator or have these permissions to use the makeplugin and extractplugin commands:

| Permission                  | Description                     |
|-----------------------------|---------------------------------|
| plugintools.plugins.make    | Allows the use of makeplugin    |
| plugintools.plugins.extract | Allows the use of extractplugin |

## Usage

To pack your plugin, run the **makeplugin** command on your Fish-Bot and pass the name of your plugin.
Your plugin must be in a folder in Fish's _plugins_ directory.

## License

This code is released to the public domain
# PluginTools

This is a plugin for the [Fish IRC-Bot](https://github.com/nkreer/fish) which helps with plugin creation.

## Installation

Drop the PluginTools.phar into your _plugins_ directory and reload Fish using the `reload` command.

## Permissions

You need to be an admin or have these permissions to use the makeplugin, extractplugin and loadfolderplugin commands:

| Permission                  | Description                     |
|-----------------------------|---------------------------------|
| plugintools.plugins.make    | Allows the use of makeplugin    |
| plugintools.plugins.extract | Allows the use of extractplugin |
| plugintools.load.folder | Allows the user to load folder plugins |

## Usage

To pack your plugin, run the **makeplugin** command on your Fish-Bot and pass the name of your plugin.
Your plugin must be in a folder in Fish's _plugins_ directory.

## License

This code is released to the public domain
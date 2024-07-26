## How to extend PHP code sniffer standards?

This implementation was tested and working perfectly on VsCode.

# Before implementation
PHP coding standards need to be installed and working.

# Change the settings in VsCode
Go to Code -> Preferences -> Settings -> [find: phpcs] -> Phpcs: Standard -> Edit in settings.json
Click on that and change the line

```"phpcs.standard": "PSR12"```
into 
```"phpcs.standard": "~/phpcs-custom/DmytrosCustomCodeStyleStandard/ruleset.xml"```

# Create ruleset
Create a folder
```
touch ~/phpcs-custom/DmytrosCustomCodeStyleStandard/ruleset.xml
```

Inside the content of ruleset.xml copy ruleset.xml from root of this repo

# Create sniffs

Ruleset already extends default PSR12 standards and has 2 additional sniffs
- RequireReturnType
- RequireArgumentType

These sniffs are inside sniffs folder.

Happy Codding.

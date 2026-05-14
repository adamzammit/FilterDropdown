# FilterDropdown
LimeSurvey Plugin that filters dropdown responses by their code or label


## Why??
Currently expression manager doesn't apply to answer options. This plugin allows you to filter out answer options in dropdown responses based on their code on label starting with a certain value. You can use an expression for the filtering.

For example, you may have a dropdown containing all possible regions in all countries. If you know the respondent's country, you could filter out to only show the applicable regions.

You would set up a dropdown with all countries and regions for example:

Code / Label
AU1 / Australia - Victoria
AU2 / Australia - New South Wales
NZ1 / New Zealand
US1 / United States of America - California
US2 / United States of America - New York

You could then set a filter by code being: AU   which would restrict to Australia only, or a filter by label being: Australia which would also filter by Australia given the labels above.

The filter uses expressions so could refer to a previous question (As long as on a previous page) or could refer to a token value

## Installation

Download the zip from the [releases](https://github.com/adamzammit/FilterDropdown/releases) page and extract to your plugins folder. You can also clone directly from git: go to your plugins directory and type 
```
git clone https://github.com/adamzammit/FilterDropdown.git FilterDropdown
```

### Usage

Enable the plugin, then visit your dropdown style questions. Two new attributes appear under the "Display" section which are used for filtering.
 
## Security

If you discover any security related issues, please email adam@acspri.org.au instead of using the issue tracker.

## Contributing

PR's are welcome!

## Users

You are free to use/change/fork this code for your own products (licence is GPLv2), and I would be happy to hear how and what you are using it for!

### Acknowledgements

This plugin was based on: SelectFilterByDropdown: https://gitlab.com/SondagesPro/QuestionSettingsType/SelectFilterByDropdown

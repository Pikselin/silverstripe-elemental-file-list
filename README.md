# Pikselin File List elemental

An elemental block with a list of files.

Features:
- easily sortable via the admin
- option to display a download icon or icons based on the file extension
- includes CSS that can be easily overridden
- includes an extension for the controller that does the requirements call for the CSS.

## Installation
This module only works with SilverStripe 6.x.

`composer require pikselin/silverstripe-elemental-file-list`

Run `dev/build` afterwards.

## Requirements
These can be found in the composer.json file.

## Templates
You can override the default template by copying `templates/Pikselin/FileList/Elemental/FileList.ss` to your own theme.

## CSS
Base styles can be found in:
`client/css/file-list.css`

## Notes
- This module already activates the CSS files in the PageController via the `FileListControllerExtension.php` extension.
- To add more file extension icons, make sure to update the mapping in `FileList.php` (or via the yml file) and add
the svg to the sprite in the images folder.

## test

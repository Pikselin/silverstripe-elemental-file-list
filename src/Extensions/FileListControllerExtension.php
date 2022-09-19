<?php

namespace Pikselin\FileList\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

/**
 * Adds css requirement directive
 */
class FileListControllerExtension extends Extension
{
    public function onAfterInit() {
        Requirements::css('pikselin/silverstripe-elemental-file-list:client/css/file-list.css');
    }
}

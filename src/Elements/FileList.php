<?php

namespace Pikselin\FileList\Elemental;

use Bummzack\SortableFile\Forms\SortableUploadField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\File;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Elemental block that allows for a list of files that can be easily sortable via the admin.
 */
class FileList extends BaseElement
{
    use Configurable;

    private static $singular_name = 'File list';
    private static $plural_name = 'File list';
    private static $description = 'list of internal files';
    private static $icon = 'font-icon-install';
    private static $table_name = 'ElementFileList';
    private static $inline_editable = FALSE;

    private static $many_many = [
        'Files' => File::class,
    ];

    private static $owns = [
        'Files',
    ];

    private static $many_many_extraFields = [
        'Files' => [
            'SortOrder' => 'Int'
        ],
    ];

    /**
     * Maps out what icon each file extension should display
     *
     * @var string[]
     */
    private static $icon_extension_mapping = [
        'xlsx' => 'csv',
        'xls' => 'csv',
        'csv' => 'csv',
        'doc' => 'word',
        'docx' => 'word',
        'txt' => 'word',
        'ppt' => 'powerpoint',
        'pptx' => 'powerpoint',
        'pdf' => 'pdf',
        'video' => 'video',
        'webpage' => 'webpage',
        'external' => 'external',
    ];

    public function getCMSFields()
    {
        // SortableUploadField has to be in beforeUpdateCMSFields
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            // removes the Files tab
            $fields->removeByName('Files');

            // Sortable upload field
            $fields->addFieldToTab('Root.Main', $filesField = SortableUploadField::create(
                'Files', $this->fieldLabel('Files')
            ));
            $filesField->setAllowedFileCategories('document')
                ->setFolderName($this->config()->get('folder_name'));

            // check config to see if specific file types have been set
            if ($this->getAllowedFileTypes()) {
                $filesField->setAllowedExtensions($this->getAllowedFileTypes());
            }
        });

        return parent::getCMSFields();
    }

    public function getType()
    {
        return 'File list';
    }

    /**
     * Retrieve sorted files.
     *
     * @return mixed
     */
    public function FileList()
    {
        return $this->Files()
            ->sort('SortOrder');
    }

    /**
     * Generate the summary by listing the number of files in this block.
     *
     * @return DBHTMLText
     */
    public function getSummary()
    {
        if ($this->Files()->count() == 1) {
            $label = ' file';
        } else {
            $label = ' files';
        }

        return DBField::create_field('HTMLText', $this->Files()->count() . $label)->Summary(20);
    }

    /**
     * Provides a summary to the gridfield.
     *
     * @return array
     * @throws \SilverStripe\ORM\ValidationException
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    /**
     * Format our file size for the template.
     *
     * @param $size
     *
     * @return string|string[]
     */
    public function TidyFileSize($size)
    {
        $tidySize = str_replace(' ', '', $size);

        // The proper notation for kilobytes is "kB".
        $tidySize = str_ireplace('kb', 'kB', $tidySize);

        return $tidySize;
    }

    /**
     * Grab the allowed file types from config.
     * No fallback needed because setAllowedFileCategories('document') is already set.
     *
     * @return mixed|string[]
     */
    public function getAllowedFileTypes()
    {
        if (!empty($this->config()->get("allowed_extensions"))) {
            $types = $this->config()->get("allowed_extensions");
            $types = array_unique($types);
            return $types;
        }

        return false;
    }

    /**
     * Determine the icons display from config.
     *
     * @return mixed
     */
    public function SimpleIconDisplay()
    {
        return $this->config()->get('simple_icon_display');
    }

    /**
     * Grab the icon name to display for the given file extension, via the icon_extension_mapping array.
     *
     * @param $extension
     * @return string
     */
    public function DownloadIcon($extension)
    {
        // lowercase it, for easier string matching
        $extension = trim(strtolower($extension));

        // we've got various doctypes that relate to icons with different names, so...
        $mapping = $this->config()->get('icon_extension_mapping');

        // are we one of those mapping types?
        if (isset($mapping[$extension])) {
            return strtolower($mapping[$extension]);
        } else {
            // otherwise send back the default icon
            return 'webpage';
        }
    }
}

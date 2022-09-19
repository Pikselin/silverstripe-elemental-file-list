<?php

namespace Pikselin\FileList\Elemental;

use Bummzack\SortableFile\Forms\SortableUploadField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\File;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Elemental block that allows for a list of files that can be easily sortable via the admin.
 */
class FileList extends BaseElement
{
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
                ->setFolderName('ElementalFiles');
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
}

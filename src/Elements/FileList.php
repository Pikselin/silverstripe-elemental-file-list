<?php

namespace Pikselin\FileList\Elemental;

use Bummzack\SortableFile\Forms\SortableUploadField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

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
//        $fields = parent::getCMSFields();

//        $fields->removeByName('Files');
//
//        $fields->addFieldToTab(
//            'Root.Main',
//            UploadField::create('Files', 'Files')
////                ->setAllowedExtensions(['doc', 'docx','xls', 'xlsx', ''pdf'])
//                ->setAllowedFileCategories('document')
//                ->setIsMultiUpload(TRUE)
//                ->setFolderName('ElementalFiles')
//        );

        $this->beforeUpdateCMSFields(function (FieldList $fields) {
        $fields->addFieldToTab('Root.Main', $filesField = SortableUploadField::create(
            'Files', $this->fieldLabel('Files')
        ));
        $filesField->setAllowedFileCategories('document')
            ->setFolderName('ElementalFiles');
        });

//        $linksConfig = GridFieldConfig_RelationEditor::create();
//        // allow for drag and drop
//        $linksConfig->addComponent(new GridFieldOrderableRows('SortOrder'));
//        $linksGridField = GridField::create('Files', 'Files', $this->Files(), $linksConfig);
//        $fields->addFieldToTab('Root.Main', $linksGridField);

        return parent::getCMSFields();
        return $fields;
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
            ->sort('Sort');
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
     * Format our file size for the template
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

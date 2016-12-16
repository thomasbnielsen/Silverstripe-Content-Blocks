<?php

/**
 * Request handler for creating new blocks in the CMS
 *
 * @package silverstripe-block-page
 * @license MIT License https://github.com/cyber-duck/silverstripe-block-page/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class CreateBlock_ItemRequest extends GridFieldDetailForm_ItemRequest
{
    /**
     * Allowe CMS block request actions 
     *
     * @since version 1.0.0
     *
     * @var array
     **/
    private static $allowed_actions = ['ItemEditForm', 'doCreateBlock'];

    /**
     * CMS record form method
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function ItemEditForm()
    {
        $form = parent::ItemEditForm();
        $actions = $form->Actions();

        $fields = $this->record->getCMSFields();
        foreach($fields as $field) {
            $field->setForm($form);
        }
        $form->fields()->removeByName('Name');
        $form->fields()->removeByName('BlockType');
        $form->fields()->removeByName('BlockStage');
        $form->fields()->removeByName('PageID');
        $form->fields()->addFieldsToTab('Root.Main', $fields);

        $name = TextField::create('Name')
            ->setDescription('Reference name not displayed in the page.');

        $form->fields()->insertAfter($name, 'BlockHeader');
        $form->loadDataFrom($this->record);

        if($this->getAction() == 'new') {
            $actions->removeByName('action_doSave');
            $button = FormAction::create('doCreateBlock');
            $button->setTitle('Create')
                ->setAttribute('data-icon', 'accept')
                ->setAttribute('data-icon-alternate', 'addpage')
                ->setAttribute('data-text-alternate', 'Clean-up now');
            $actions->unshift($button);

            $form->addExtraClass('cms-content-fields');
        } else {
            $button = FormAction::create('doAddBlock');
            $button->setTitle('New Block')
                ->setAttribute('data-icon', 'accept')
                ->setAttribute('data-icon-alternate', 'addpage')
                ->setAttribute('data-text-alternate', 'Clean-up now');
            $actions->unshift($button);
        }
        return $form;
    }

    /**
     * Hanldles the block create request and redirects to the new record
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function doCreateBlock($data, Form $form)
    {
        $request = Controller::curr()->getRequest();

        $class = $request->postVar('BlockType');

        $block = new $class();
        $block->PageID = $request->postVar('PageID');
        $block->write();

        return Controller::curr()->redirect(sprintf('/admin/pages/edit/EditForm/field/Blocks/item/%s/edit', $block->ID));
    }

    /**
     * Shortcut to add another block quickly
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function doAddBlock($data, Form $form)
    {
        return Controller::curr()->redirect('/admin/pages/edit/EditForm/field/Blocks/item/new');
    }

    /**
     * Get the current record action
     *
     * @since version 1.0.0
     *
     * @return string
     **/
    private function getAction()
    {
        $path = explode('/', Controller::curr()->getRequest()->getURL());

        return array_pop($path);
    }
}

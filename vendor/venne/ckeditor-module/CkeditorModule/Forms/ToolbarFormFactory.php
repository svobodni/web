<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CkeditorModule\Forms;

use FormsModule\ControlExtensions\ControlExtension;
use FormsModule\Controls\TagsInput;
use Venne\Forms\Container;
use Venne\Forms\Form;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ToolbarFormFactory extends FormFactory
{

	/** @var string */
	private $ckeditorDir;

	private $toolbarItems = array(
		'-', 'Source', 'Save', 'NewPage', 'Preview', 'Print', 'Templates',
		'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo',
		'Find', 'Replace', 'SelectAll', 'Scayt',
		'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField',
		'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'RemoveFormat',
		'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'BidiLtr', 'BidiRtl',
		'Link', 'Unlink', 'Anchor',
		'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe',
		'Styles', 'Format', 'Font', 'FontSize',
		'TextColor', 'BGColor',
		'Maximize', 'ShowBlocks',
		'About',
	);


	/**
	 * @param $ckeditorDir
	 */
	public function __construct($ckeditorDir)
	{
		$this->ckeditorDir = $ckeditorDir;
	}


	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new ControlExtension,
		));
	}


	public function getToolbarItems($filters)
	{


		$ret = array();

		foreach ($this->toolbarItems as $item) {
			if (!$filters || stripos($item, $filters) !== FALSE) {
				$ret[$item] = $item;
			}
		}

		return $ret;
	}


	/**
	 * @param Form $form
	 */
	protected function configure(Form $form)
	{
		$toolbar = $form->addDynamic('toolbar', function (Container $container) use ($form) {

			$group = $form->addGroup('Toolbar line');
			$container->setCurrentGroup($group);

			$line = $container->addDynamic('line', function (Container $container) use ($form, $group) {
				$container->setCurrentGroup($group);

				/** @var TagsInput $tags */
				$tags = $container->addTags('items', 'Group');

				$_this = $this;
				$tags->setSuggestCallback(function ($filters) use ($_this) {
					return $_this->getToolbarItems($filters);
				});

				$container->addSubmit('remove', 'Remove group')
					->addRemoveOnClick();
			});


			$line->addSubmit('add', 'Add group')
				->addCreateOnClick();
			$container->addSubmit('remove', 'Remove line')
				->addRemoveOnClick();
		});
		$toolbar->addSubmit('add', 'Add line')
			->addCreateOnClick();

		$form->setCurrentGroup();
		$form->addSaveButton('Save');
	}


	public function handleSave(Form $form)
	{
		if ($form->isSubmitted() !== $form->getSaveButton()) {
			return;
		}

		$data = array('toolbar' => array());

		$i = TRUE;
		foreach ($form['toolbar']->values as $items) {
			if ($i) {
				$i = FALSE;
			} else {
				$data['toolbar'][] = '/';
			}

			$r = array();
			$i = TRUE;
			foreach ($items['line'] as $value) {

				if ($i) {
					$i = FALSE;
				} else {
					$data['toolbar'][] = array('items' => $r);
					$r = array();
				}

				foreach ($value['items'] as $item) {
					$r[] = $item;
				}
			}
			$data['toolbar'][] = array('items' => $r);
		}

		file_put_contents($this->ckeditorDir . '/backend.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
	}


	public function handleLoad(Form $form)
	{
		$data = json_decode(file_get_contents($this->ckeditorDir . '/backend.json'), TRUE);

		$y = TRUE;
		foreach ($data['toolbar'] as $items) {
			if ($y) {
				$y = FALSE;
				$toolbar = $form['toolbar']->createOne();
			}

			if ($items === '/') {
				$toolbar = $form['toolbar']->createOne();
				continue;
			}

			$i = TRUE;
			foreach ($items as $item) {
				if ($i) {
					$i = FALSE;
					$line = $toolbar['line']->createOne();
				}
				if ($item === '/') {
					$line = $toolbar['line']->createOne();
					continue;
				}
				$line['items']->setValue($item);
			}
		}
	}
}

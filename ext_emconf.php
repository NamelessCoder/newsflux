<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "newsflux".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Flux integration for EXT:news',
	'description' => 'Provides methods of extending EXT:news plugin options through the Flux API',
	'category' => 'misc',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Claus Due',
	'author_email' => 'claus@namelesscoder.net',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '7.6.0-8.3.99',
			'news' => '3.0.0-5.1.99'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	),
);

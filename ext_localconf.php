<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\FluidTYPO3\Flux\Core::registerConfigurationProvider(\NamelessCoder\Newsflux\Provider\NewsPluginConfigurationProvider::class);

<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$extkey = 'sypets_example_findbyrelation_cli';
$extensionName = 'SypetsExampleFindbyrelationCli';
$pluginName = 'Files';
$pluginSignature = strtolower($extensionName . '_' . $pluginName);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extensionName,
    $pluginName,
    'File relations'
);

// add flexform
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $extkey . '/Configuration/FlexForms/FileRelations.xml');

// do not show "Record storage page" etc. in the form
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature]
    = 'recursive,select_key,pages';

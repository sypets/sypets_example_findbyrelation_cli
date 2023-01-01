<?php

use Sypets\SypetsExampleFindbyrelatationCli\Controller\FilesController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(function () {
    ExtensionUtility::configurePlugin(
        // Extension Name
        'SypetsExampleFindbyrelationCli',
        // plugin
        'Files',
        [
            FilesController::class => 'show',
        ]
    );
})();

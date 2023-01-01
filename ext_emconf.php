<?php

/** @var $_EXTKEY string */
$EM_CONF[$_EXTKEY] = [
    'title'            => 'sypets_example_findbyrelation_cli',
    'description'      => 'TYPO3 example for reproducing a TYPO3 issue: FileRepository::findByRelation() does not work in CLI mode.',
    'version' => '0.0.1',
    'state'            => 'beta',
    'clearcacheonload' => 0,
    'author'           => 'Sybille Peters',
    'author_email'     => 'sypets@gmx.de',
    'constraints'      => [
        'depends' => [
            'typo3' => '11.5.1-12.9.99',
        ],
    ],
];

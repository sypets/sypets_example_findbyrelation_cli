Example for reproducing TYPO3 Issue:

* https://forge.typo3.org/issues/99442

FileRepository::findByRelation() does not work in CLI mode.

## Install

Without Composer:

```shell
git clone https://github.com/sypets/sypets_example_findbyrelation_cli.git
```

With Composer:

Add to composer.json:

```json
"repositories": {
		"sypets_example_findbyrelation_cli": {
			"type": "git",
			"url": "https://github.com/sypets/sypets_example_findbyrelation_cli.git"
		}
},
```

Run:

```shell
composer require sypets/sypets-example-findbyrelation-cli:dev-main
```

## Reproduce

To reproduce the issue

1. Install the extension
2. Insert a plugin `"File relations" [sypetsexamplefindbyrelationcli_files]` on a page (select "General plugin" in TAB "Plugins" of "New content element wizard")
3. Select a file in the Flexform of the plugin
4. Run the command from command line:

With Composer:

```shell
php vendor/bin/typo3 sypets_example:showFileRelation
```

Without Comoposer:

```shell
php typo3/sysext/core/bin/typo3 sypets_example:showFileRelation
```


# Riot Assetic Filter
An Assetic filter for precompiling Riot JS tags.

## Installation

1. Open your `composer.json` file and add the following to the repositories section:
```json
"repositories": [
		{
		    "type": "package",
		    "package": {
    		    "name": "PaulHughes01/assetic-riot",
				"version": "dev-master",
				"source": {
			    	"url": "git@github.com:PaulHughes01/assetic-riot.git",
			    	"type": "git",
			    	"reference": "master"
				},
				"autoload": {
					"psr-4": {
				    	"PaulHughes01\\Riot\\Assetic\\": "/src/PaulHughes01/Riot/Assetic"
					}
			    }
		    }
		}
	]
```
2. Then, add to the requirements section:
```json
"require": {
        "PaulHughes01/assetic-riot": "dev-master"
}
```
3. Run `composer update` in your project.
4. `cd` to your `vendor/PaulHughes01/assetic-riot/` directory and run `npm install`
```bash
cd vendor/PaulHughes01/assetic-riot/
npm install
```
5. You can now use the RiotFilter in your app.
```php
<?php
use PaulHughes01\Riot\Assetic\RiotFilter;
...
$filter = new RiotFilter( '/usr/bin/node' );
```

### Symfony Use

Follow steps 1 through 4 in the Installation section. Then, follow the steps below:

1. Add to your app's config/services.yml file:
```yml
services:
    assetic.filter.riot:
        class: %assetic.filter.riot.class%
        tags:
            - { name: assetic.filter, alias: riot }
        arguments: ['%assetic.filter.riot.node%']
        calls:
            - [setNodePaths, ['%assetic.filter.riot.node_paths%']]
```
2. Add to your assetic filters in your config/config.yml file:
```yml
assetic:
    filters:
        riot:
            resource: "%kernel.root_dir%/../vendor/PaulHughes01/assetic-riot/src/PaulHughes01/Riot/Assetic/Resources/config/services.xml"
            node: /usr/bin/node
```

3. Use the filter in your twig templates! 
```twig
{% javascripts 
    filter='riot'
    '@MyBundle/Resources/riot/mytag.tag'
    '@MyBundle/Resources/riot/mytag2.tag'
     %}
    <script src="{{ asset_url }}"></script>
{% endjavascripts %}
```

If running in prod mode, make sure to use
```bash
php app/console assetic:dump
php app/console cache:clear --env=prod
```
to see the updates. In dev mode (using app_dev.php), tags will automatically recompile on page load. 

Enjoy! Tips, questions, and pull requests are welcome. :-
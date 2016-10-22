# Virtualmin Control Panel SDK

[![Author](http://img.shields.io/badge/author-@mikebarlow-red.svg?style=flat-square)](https://twitter.com/mikebarlow)
[![Source Code](http://img.shields.io/badge/source-snscripts/virtualmin--sdk-brightgreen.svg?style=flat-square)](https://github.com/mikebarlow/virtualmin-sdk)
[![Latest Version](https://img.shields.io/github/release/mikebarlow/virtualmin-sdk.svg?style=flat-square)](https://github.com/mikebarlow/virtualmin-sdk/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/mikebarlow/virtualmin-sdk/blob/master/LICENSE)

This is a PSR-2 Compliant Unofficial PHP SDK for the Virtualmin control panel which should hopefully make integrating your own systems into Virtualmin a piece of cake!

## Requirements

### Composer

Virtualmin SDK requires the following:

* "php": ">=5.5.0
* "guzzlehttp/guzzle": "6.2.*",
* "cartalyst/collections": "1.1.*"

## Installation

### Composer

Simplest installation is via composer.

    composer require snscripts/virtualmin-sdk 0.*

or adding to your projects `composer.json` file.

    {
        "require": {
            "snscripts/virtualmin-sdk": "1.*"
        }
    }

## Usage

### Setup

To get started you first need to define the Virtualmin class and pass in the Guzzle dependency.

    use \Snscripts\Virtualmin\Virtualmin;

    $Virtualmin = new Virtualmin(
        new GuzzleHttp\Client
    );

Next you'll need to setup the connection to your Virtualmin Server. This requires the main account login details.

    $Virtualmin->setConnection(
        'host:port',
        'user',
        'pass'
    );

If you're server is running in http mode you can pass in the `Virtualmin::NOSECURE` constant as the fourth parameter of setConnection to disable the use of https. (Default behaviour is `Virtualmin::SECURE`).

    $Virtualmin->setConnection(
        'host',
        'user',
        'pass',
        Virtualmin::NOSECURE
    );

If you are running https on but it's in a development environment and doesn't actually have a valid SSL certificate, you can disable the checking of the certificate with:

    $Virtualmin->setVerify(Virtualmin::NOVERIFY);

**THIS SHOULD NOT BE DONE IN PRODUCTION AND IS TO ONLY BE USED DURING DEVELOPMENT IF NEEDED**

### Using

When using the SDK you may find [this page](https://www.virtualmin.com/documentation/developer/cli) from the Virtualmin Documentation useful. It details what actions and parameters are available on the Command Line Tool for managing your Virtualmin Server. All these commands and parameters are transferrable to this SDK (Not all commands supported yet). 

Each supported section of the API comes with a "Manager" class, this class defines all the available actions for. It then also accepts any parameters needed for that request and then runs it.

Each Manager class should accept the Virtualmin object defined from the Virtualmin class, used in the examples above.

### PlanManager

The plan manager is responsible for handling the Plans Related actions.

	$PlanManager = new \Snscripts\Virtualmin\Plans\PlanManager($Virtualmin);
	$plans = $PlanManager->ListPlans()->run();
	$Plan = $plans->first(); // get the first plan returned
	
The ListPlans action will return a Collection of results (or an empty collection if no plans created). Each item in the Collection should be an instance of \Snscripts\Virtualmin\Plans\Plan containing the required details for that plan.

### HostingManager

The Hosting Manager is responsible for handling the "Virtual Servers" or Shared Hosting accounts.

	$HostingManager = new \Snscripts\Virtualmin\Hosting\HostingManager($Virtualmin);
	
	$createResult = $HostingManager->CreateService()
		->setDomain('example.com')
		->setPlan($Plan->id)
		->setPass('password')
		->setLimitsFromPlan() // this makes it use the disk quota and broadband limits from the set plan otherwise define your own
		->setFeaturesFromPlan() // this makes it use the defined features on your plan otherwise define your own.
		->run();
		
`$createResult` will contain an instance of `\Snscripts\Virtualmin\Results\Result`. With that object you can do `$createResult->getStatus()` to retrieve a boolean true / false representation of whether the call was successful or not and `$createResult->getMessage()` to retrieve a verbose reason for the call failing or a success message.

	$deleteResult = $HostingManager->DeleteService()
		->setDomain('example.com')
		->run();
		
	$disableResult = $HostingManager->DisableService()
		->setDomain('example.com')
		->run();
		
	$enableResult = $HostingManager->EnableService()
		->setDomain('example.com')
		->run();

## Contributing

Please see [CONTRIBUTING](https://github.com/mikebarlow/virtualmin-sdk/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/mikebarlow/virtualmin-sdk/blob/master/LICENSE) for more information.

GoogleanalyticsModule module for Venne:CMS
==========================================

This module is official extension for Venne:CMS. Thank you for your interest.

Installation
------------

- Copy this folder to /vendor/venne
- Active this module in administration

Usage
-----

1. In templates as widget:
```
...
{control googleAnalytics $accountId}
...
```

2. Globally as listener you must set in `config.neon`:
```
	googleanalytics:
		account:
			activated: true
			accountId: '<accountId>'
```
Or in administration: `Analytics -> Account settings`
.. highlight:: bash
.. _installation:

Installation
============

Composer
--------

The extension can be installed through composer::

    composer require "codappix/search_core_twitter" "~0.0.2"

Download
--------

You can also `download`_ the extension and place it inside the :file:`typo3conf/ext`-Folder of
your installation.  In that case you need to install all dependencies yourself. Dependencies are:

.. literalinclude:: ../../composer.json
   :caption: Dependencies from composer.json
   :lines: 20-22
   :dedent: 8

Setup
-----

To be able to index anything from twitter, you need to create an app to get the necessary
credentials. New and existing apps can be managed at https://apps.twitter.com/ .

Afterwards you need to enable the extension through the extension manager and need to configure the
indexing through TypoScript.

.. _download: https://github.com/codappix/search_core_twitter/archive/develop.zip

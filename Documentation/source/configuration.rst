.. highlight:: typoscript

.. _configuration:

Configuration
=============

Check out :ref:`search_core:configuration` of *search_core* as everything will be configured through
this extension.

The following additional options are introduced for this extension:

A small example for a full setup::

   plugin.tx_searchcore.settings {
       connections {
           twitter {
               pressAccount {
                   // Credentials
               }
           }
       }

       indexing {
           tweetsFromPress {
               indexer = Codappix\SearchCoreTwitter\Domain\Index\TweetIndexer
               twitterAccount = pressAccount
               parameters {
                   screen_name = company-press
               }
           }
       }
   }

.. _configuration_connections:

Connections
-----------

Multiple twitter connections can be setup, to allow indexing of multiple accounts.
Each connection is configured inside of ``plugin.tx_searchcore.settings.connections.twitter``. Use a
unique identifier for each connection, e.g. ``pressAccount``.

The credentials to provide can be obtained from https://packagist.org/packages/j7mbo/twitter-api-php
as this library is used to establish the connection.

.. code-block:: typoscript

   plugin.tx_searchcore.settings.connections {
       twitter {
           <identifier> {
               // Credentials
           }
       }
   }

.. _configuration_indexer:

Indexer
-------

New indexer are introduced, to enable indexing of content from twitter.
The following indexers are added:

.. toctree::

   Indexer/TweetIndexer

.. _configuration_twitterAccount:

Twitter Account
---------------

As multiple twitter connections are possible, you have to configure the connection to use for each
index definition. This is done using the ``twitterAccount`` option, which has to match the
``<identifier>`` of a twitter connection.

.. code-block:: typoscript

   plugin.tx_searchcore.settings.indexing {
       tweetsFromPress {
           twitterAccount = pressAccount
       }
   }

.. _configuration_parameters:

Parameters
----------

As all information are fetched from twitter via REST API, it's possible to define all parameters to
use via TypoScript, the available parameters are documented at https://developer.twitter.com/en/docs/api-reference-index

Which parameters are available, depends on the used indexer.

.. code-block:: typoscript

   plugin.tx_searchcore.settings.indexing {
       tweetsFromPress {
           parameters {
               screen_name = company-press
               include_rts = 1
           }
       }
   }

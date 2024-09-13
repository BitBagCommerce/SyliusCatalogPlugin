# [![](https://bitbag.io/wp-content/uploads/2021/06/SyliusCatalogPlugin.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog)

# BitBag SyliusCatalogPlugin

----

[![](https://img.shields.io/packagist/l/bitbag/catalog-plugin.svg)](https://packagist.org/packages/bitbag/catalog-plugin "License")
[![](https://img.shields.io/packagist/v/bitbag/catalog-plugin.svg)](https://packagist.org/packages/bitbag/catalog-plugin "Version")
[![](https://img.shields.io/travis/BitBagCommerce/SyliusCatalogPlugin/master.svg)](http://travis-ci.org/BitBagCommerce/SyliusCatalogPlugin "Build status")
[![](https://poser.pugx.org/bitbag/catalog-plugin/downloads)](https://packagist.org/packages/bitbag/catalog-plugin "Total Downloads")
[![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com)
[![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog)

We want to impact many unique eCommerce projects and build our brand recognition worldwide, so we are heavily involved in creating open-source solutions, especially for Sylius. We have already created over **35 extensions, which have been downloaded almost 2 million times.**

You can find more information about our eCommerce services and technologies on our website: https://bitbag.io/. We have also created a unique service dedicated to creating plugins: https://bitbag.io/services/sylius-plugin-development. 

Do you like our work? Would you like to join us? Check out the **“Career” tab:** https://bitbag.io/pl/kariera. 


# About Us 
---

BitBag is a software house that implements tailor-made eCommerce platforms with the entire infrastructure—from creating eCommerce platforms to implementing PIM and CMS systems to developing custom eCommerce applications, specialist B2B solutions, and migrations from other platforms.

We actively participate in Sylius's development. We have already completed **over 150 projects**, cooperating with clients worldwide, including smaller enterprises and large international companies. We have completed projects for such important brands as **Mytheresa, Foodspring, Planeta Huerto (Carrefour Group), Albeco, Mollie, and ArtNight.**

We have a 70-person team of experts: business analysts and consultants, eCommerce developers, project managers, and QA testers.

**Our services:**
* B2B and B2C eCommerce platform implementations
* Multi-vendor marketplace platform implementations
* eCommerce migrations
* Sylius plugin development
* Sylius consulting
* Project maintenance and long-term support
* PIM and CMS implementations

**Some numbers from BitBag regarding Sylius:**
* 70 experts on board 
* +150 projects delivered on top of Sylius
* 30 countries of BitBag’s customers
* 7 years in the Sylius ecosystem
* +35 plugins created for Sylius

---
[![](https://bitbag.io/wp-content/uploads/2024/09/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog) 

---


## Table of Content

***

* [Overview](#overview)
* [Installation](#installation)
* [Testing](#testing-and-running-the-plugin)
* [Usage](#usage)
* [Functionalities](#functionalities)
* [Demo](#demo)
* [Additional Sylius resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact and support](#contact-and-support)
* [Community](#community)

# Overview
---

BitBag SyliusCatalogPlugin Allows for displaying catalog with products - calculated dynamically with rules.

**For catalog You can configure:**

* code
* names, when it should be shown
* when it should be shown - this is useful for time restricted special offers or promotions
* there is set of rules that restrict which products will be shown inside, they can be combined using AND or OR.
* there is another set of rules - used to restrict products associated with given catalog - it can be shown on product details page
* templates for each catalog

# Installation
---
The SyliusCatalogPlugin **installation process** can be found [here](doc/installation.md).

# Testing and running the plugin
---
The SyliusCatalogPlugin **testing process** can be found [here](doc/installation.md#testing--running-the-plugin).

# Usage
---
Plugin provides 2 new twig functions which can be used inside templates:
 * for rendering catalogs by their code:
```html
    {{ bitbag_render_product_catalog("test_catalog") }}
```
 * for rendering all catalogs active for given product
```html
    {{ bitbag_render_product_catalogs(product) }}
```
# Functionalities
---

All main functionalities of the plugin are described [here.](https://github.com/BitBagCommerce/SyliusCatalogPlugin/blob/master/doc/functionalities.md)

---

**If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog) or send us an e-mail at hello@bitbag.io!**

---
# Demo
---
We created a demo app with some useful use-cases of plugins! Visit http://demo.sylius.com/ to take a look at it.

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog)

# Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)
* [Sylius Catalog Plugin Blog](https://bitbag.io/blog/customize-sales-in-sylius-based-ecommerce-sylius-catalog-plugin/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog)

# License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

# Contact and support 
---
This open-source plugin was developed to help the Sylius community. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know! **Contact us** or send us an **e-mail to hello@bitbag.io** with your question(s).

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog)

# Community

---- 

For online communication, we invite you to chat with us & other users on **[Sylius Slack](https://sylius-devs.slack.com/).**

[![](https://bitbag.io/wp-content/uploads/2024/09/badges-partners.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_catalog)

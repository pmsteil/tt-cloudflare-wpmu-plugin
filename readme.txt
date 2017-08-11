=== TT CloudFlare WPMU Plugin ===
Contributors: stiofansisland ,paoltaia
Donate link: 
Tags: CloudFlare, wpmu, wordpress multisite 
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Keeps your CloudFlare DNS updated, creating records for all your new subdomains.

== Description ==

<p>Every time a new blog is created, a CNAME record will be added via the CloudFlare API.<br>

This allows you to accelerate and protect individual CNAMEs since CloudFlare <a href="http://support.cloudflare.com/kb/adding-sites-cloudflare/does-cloudflare-support&lt;br /&gt;
wildcard-dns-entries"> does not proxy wildcard entries.</a></p>

<p>If you need to sync your existing blogs, please upgrade to our <a href="http://stiofan.themetailors.com/store/products/tt-cloudflare-wpmu-plugin-pro/">PRO version</a>.<br>
You do not know what CloudFlare is? Check the </a><a href="http://www.cloudflare.com/overview">Overview</a>.</p>
<p>This plugin can be run in conjunction with the official CloudFlare plugin which can be found here: <a target="_blank" href="http://wordpress.org/extend/plugins/cloudflare/">http://wordpress.org/extend/plugins/cloudflare/</a></p>
<p>This plugin was not written by CloudFlare, Inc.</p>

Tailored by <a href="http://www.themetailors.com">theme tailors</a>

== Installation ==

1. Create a CloudFlare account: (if you don't have one yet) and add your main domain to your account!
2. Upload the plugin and activate only on main site (no network). You will find the plugin options page on main site dashboard under settings menu.
3. Email Address: (the email address you use to login to www.cloudflare.com)
4. Your API Key: (you can find your CloudFlare API key here https://www.cloudflare.com/myaccount)
5. Zone URL: (this is simply the domain of your main site with no prefix e.g.: example.com)
6. Automatically add new blogs: (If yes this will add the required settings to CloudFlare automatically when a new blog is created)
7. This plugin will not be able to sync all pre-existing sites, please upgrade to PRO if you wish to do so.

== Frequently Asked Questions ==

= Does it work with mapped domain? =

With mapped domains every new domain must be manually created on CloudFlare, as there is no way to do that through API at the moment.

We have asked if adding this feature was something they would consider, and they said : "It's under discussion, but not immediate."

If it will happen, we'll definetly work on it... 

= Does it sync existing blogs? =

No it doesn't, you need the PRO version for that. <a href="http://stiofan.themetailors.com/store/products/tt-cloudflare-wpmu-plugin-pro/">CloudFlare WPMU Plugin – PRO</a>

== Screenshots ==

1. TT CloudFlare WPMU Settings.

== Changelog ==

= 1.1 - 12/03/2013 =
* plugin updated to work with newest CloudFlare API additions.


= 1.0 =
* plugin released.

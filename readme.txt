=== Bridge SEO - Keyword Tag Wrapper ===
Contributors: bridgeseo
Tags: seo, search engine optimization,posts,page,keywords,google, bold keywords
Requires at least: 2.3.2
Tested up to: 2.9.1
Stable tag: 1.1

HTML Tag or Bold keywords to Optimize your Wordpress blog for SEO.

== Description ==


The Bridge SEO Keyword Tag Wrapper is an easy to use plugin that allows you to maximize your SEO efforts within WordPress. By simply adding your keywords and selecting to bold them or wrap them with any HTML tag, you will be able to have the search engines more accurately identify your website keywords for indexing. The plugin allows you to select any HTML tag to wrap any keyword you choose. As an admin, you can fully customize the features and add and remove the HTML tags to choose from when adding a new keyword. Many SEO experts recommeded that you bold keywords for maximum impact.

Features:

* Bold Keywords or Add any html tag around a keyword 
* Functionalized for use in WordPress pages other than Posts and Pages
* Limit how many times your keyword is tag wrapped - recommend no more than 3 per page/post
* Add a class to the tag for a specific keyword





For further information on this plugin got to the [SEO Keyword Tag Wrapper](http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper/)

Follow us on Twitter to keep up with the latest updates [BridgeSEO@twitter](http://twitter.com/bridgeseo/)



== Installation ==
You can use the built in installer and upgrader, or you can install the plugin manually.



1. Upload the folder "keyword-tagger-bseo" and all its contents into your "wp-content/plugins/" directory

2. From within the WordPress Admin, go to "Plugins", find the plugin titled 'Bridge SEO Keyword Tag Wrapper' and click Activate

3. Within the settings menu, click on SEO Tag Keywords

4. You should now be in the Bridge SEO Keyword Tag Wrapper admin page, add the keywords you would like tagged; we recommend utilizing the 'strong' tag. Once your new keywords are added, scroll to the bottom and check the box next to Enable Plugin and then 'Update Plugin'

5. Once activated, all of the keywords you added will be HTML tag wrapped within your pages and posts with the html tag you specified




== Frequently Asked Questions ==

= 1.  After activating the Keyword Tag Wrapper plugin, I am receiving 'nested' warning messages when validating my html code. =

If you have already wrapped a tag around a specific keyword utilizing the default WordPress content editor, there is a possibility that this will occur. We are working on a fix for this issue for future versions. To solve this problem, in your WordPress Admin CMS, go to Pages : Edit : Your Page Name and remove the pre-existing tag that is causing the conflict.


= 2. After activating the Keyword Tag Wrapper plugin, where do I edit the settings or add new keywords to be bolded? = 

In your WordPress Admin CMS, go to Settings : SEO Tag Keywords. Sorry, "Keyword Tag Wrapper" was too long and took up 2 lines on the menu.
   

= 3. By default, I have the choice of surrounding a keyword with the following tags: strong,b,i,em; which one is best? = 

We currently recommend using strong as the first choice. Keep in mind to also include your keywords in your page titles, meta keywords, and meta descriptions to maximize your SEO results. It is also important not to have too many words that are wrapped in the strong tag on a single page, keep them limited and focused.

= Also view at the http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-faq = 




== Screenshots ==

1. This screen shot is of the administration panel for the plugin




== Changelog ==
= 1.1 =
* Updated code to deal with 3 plus word keyword phrases causing double nesting when the center word was also in the keyword list

= 1.0 =
* This is the first released version




== Upgrade Notice == 
There is an issue with 3 plus word keyword phrases causing double nesting when the center word was also in the keyword list, upgrading to version 1.1 will correct this issue


== Version 1.0 notes ==

Be aware that if you have manually added tags to posts or pages prior to installing this plugin, there is a chance of non-compliant nested 
tags being created. For example, if in a post you have created the following html code - `<b>We are SEO professionals.</b>` and you create 
"SEO" as a keyword within this plugin and select it to have a 'b' tag, the following output will result - `<b>We are <b>SEO</b> professionals.</b>`  
We hope to have this issue corrected in future versions.  



[For Support of this SEO Keyword Plugin](http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper) 

[To Report Bugs in this Plugin](http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-feedback/)

[Plugin Change Log](http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-change-log) 

[Keyword Tag Wrapper FAQ](http://www.bridgeseo.com/wordpress-plugins/keyword-tag-wrapper-faq)






If you have to upgrade manually simply repeat the installation steps and re-enable the plugin.



**If installing or upgrading, ALWAYS back up your database first!**








== Usage outside of Pages and Posts ==

`bseo_tag_keyword($Text,$ReturnOrEcho); //return as var $ReturnOrEcho = 0; to echo results $ReturnOrEcho = 1`
`$taggedText = bseo_tag_keyword($Text,0);`
`bseo_tag_keyword($Text,1);`


To use this plugin to evaluate other text portions.  For this example, we will use the Snippets plugin:

`ob_start();`
`snippets_value('Bottom_Left_Text');`
`$Bottom_Left_Text = ob_get_contents();`
`ob_end_clean();`
`bseo_tag_keyword($Bottom_Left_Text,1);`

=== RSS Feeds News Blocks ===
Contributors: onigetoc
Donate link: http://www.scriptsmashup.com/donation
Tags: RSS feed, rss, rss plugin, wp rss retriever, rss aggregator, rss widget, rss aggregator widget, rss aggregator shortcode, rss multiple feeds, rss reader, rss youtube, podcast, rss news feeds, youtube player, podcast player, Popurls, Alltop ,Youtube channel, Youtube user, Youtube playlist, simplepie
Requires at least: 2.8
Tested up to: 4.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show RSS Feed on your posts and pages with shortcode to show and display single and multiple RSS feeds blocks like Popurls, Alltop, Netvibes.

== Description ==

This plugin fetchs RSS feed, or multiple feeds, and displays them in columns blocks list using shortcode.

> ### Features: ###
> *   Fetch as many RSS feeds as you want
> *   Display the RSS feed wherever you want using shortcode, including text widgets
> *   Control whether to display the entire RSS feeds content or just an excerpt
> *   Control how many words display in the excerpt
> *   Control whether it has a Read more link or not
> *   Control whether links open in a new window or not
> *   Simple, lightweight, and fast
> *   Easy to setup
> *   Set cache time (in seconds)
> *   Control order of items
> *   Responsive for mobiles and tablets. Choose between 1 to 6 columns width
> *   Translation ready (Anglish, Français). Please share your own translation here
> *   Show NEW items of the day with a cool orange dot at the right of each daily NEW items

> ###PRO version ###
> *   Show more or show less items link Popurl 
> *   Fetch thumbnail or first image
> *   Control thumbnail's size
> *   Show/Hide descriptions with the +/- Minimize and Maximize button
> *   Find and show RSS Favicons and Icons
> *   Huffington Post, Pinterest or Drudge Repport Newspaper style
> *   Google Adsense integration in RSS items
> *   (Read more PRO version infos below)

### Live Demo:
<p>Altnews.top was created with the RSS Feeds News Blocks pro version plugin <strong>(RFNB) </strong>: <a href="http://altnews.top/wordpress-rss-feeds-news-blocks-pro" target="_blank">AltNews.top</a></p>
<p>Pro version Newspaper Style 1 (Huffington post style): <a href="http://altnews.top/" target="_blank">AltNews.top (Huffington)</a> <strong>shortcode newspaper="1"</strong></p>
<p>Pro version Newspaper Style 4 (Pinterest or Cards): <a href="http://altnews.top/rss-news-feeds-blocks-style-4-pinterest-cards" target="_blank">Demo Pinterest AltNews.top</a> <strong>shortcode newspaper="4"</strong></p>
<p>Pro version Newspaper Style 5 images top (Pinterest or Cards): <a href="http://altnews.top/rss-news-feeds-blocks-style-5-pinterest-cards-img-top" target="_blank">Demo 2 Pinterest AltNews.top</a> <strong>shortcode newspaper="5"</strong></p>

### Demo Video Pro version
[youtube http://www.youtube.com/watch?v=ZVPKjagbgoo]

### Example:
`[newsblocks url="https://fr.sputniknews.com/export/rss2/archive/index.xml,http://feeds.feedburner.com/elise/simplyrecipes,http://feeds.feedburner.com/boingboing/ibag,feeds.bbci.co.uk/sport/0/football/rss.xml,http://feeds.feedburner.com/readwriteweb,http://feeds.reuters.com/reuters/topNews" items="10" excerpt="50" new_window="true" cache="10800" source="true" columns="3"]`

> ### Properties: ###
> *   <strong>url</strong> - The url of the feed you wish to fetch from. For multiple urls simply use a comma between them.
> *   <strong>items</strong> - Number of items from the feed you wish to fetch *(Default is 10)*
> *   <strong>orderby</strong> - Order the items by date or reverse date *(date or date_reverse)*
> *   <strong>title</strong> - Whether to display the title or not *(true or false, defaults is true)*
> *   <strong>excerpt</strong> - How many words you want to display for each item	*(Default is 0 or infinite, use 'none' to remove the excerpt)*
> *   <strong>read_more</strong> - Whether to display a read more link or not	*(true or false, defaults is true)*
> *   <strong>new_window</strong> - Whether to open the title link and read more link in a new window	*(true or false, defaults is true)*
> *   <strong>source</strong> - Whether to display the source or not *(true or false, defaults is true)*
> *   <strong>date</strong> - Whether to display the publish date or not *(true or false, defaults i true)*
> *   <strong>cache</strong> - How long you want the feed to cache the results in seconds *(Default is 43200, (12 hours))*. The first loading will be slow but it will be cached for the time you setup
> *   <strong>merge</strong> - merge all RSS Feeds in show feed by date by default *(true or false, defaults is false)*
> *   <strong>blocktitle</strong> - In merge mode, use this to enter your own title at the beginning of the feed like: News Press (Leave empty for no title at all)

> ###PRO version ###
> *   <strong>tooltip</strong> - Show description in tooltip like Popurls, Alltop, Netvibes *(true or false, defaults is false)*
> *   <strong>max_items</strong> - Use this options for the show more items link. EX: <strong>items="10" max_items="15"</strong> It will show 10 items and will reveal 5 more items when the user will click <strong>↓ Show more</strong>.
> *   <strong>thumbnail</strong> - Whether or not you want to display a thumbnail, and if so, what size you want it to be*(true or false, defaults is true. Inserting a number will change the size, default is 150)*
> *  <strong>newspaper</strong> - Choose between News papers styles <em>(1 - 2 - 3 - 4 - 5)</em> <em><a href="http://altnews.top/huffington-post" target="_blank">Huffington style (1) Demo</a> | <a href="http://altnews.top/merge-report" target="_blank">Huffington Merged style (1) Demo</a> | <a href="http://altnews.top/rss-news-feeds-blocks-newspaper-style-2-small-image" target="_blank">Huffington style (2) Demo</a> | <a href="http://altnews.top/drudge-classic" target="_blank">Drudge Repport Demo Style (3)</a>  | <a href="http://altnews.top/rss-news-feeds-blocks-style-4-pinterest-cards" target="_blank">Pinterest style (4) Demo</a> | <a href="http://altnews.top/rss-news-feeds-blocks-style-5-pinterest-cards-img-top" target="_blank">Pinterest style (5) Demo</a> | </em> ex: newspaper="3"
> *   <strong>splash</strong> - Splash news on top. Give a Unique name / ID in this options to show the right Splash News on top of all Newsfeeds block. First news appear on top with a big title and big image like the Huffington Post or Drudge Repport *(true or false, defaults is false)* In merge mode option, You should add the splash option only to the first feeds inside the [newsblocks-merge] shortcode (see below)
> *   <strong>imgtop</strong> - Show image before or after title, if true, the image will be before the feed item title *(true or false, defaults is false)*
> *   <strong>favicons</strong> - Show RSS Feed icons / favicons *(true, false, top, bottom, defaults is true)* <strong>True</strong> and <strong>top</strong> do the same thing, the favicon will appear before the RSS item Title. <strong>bottom</strong> will make the favicon appear in replacement of <strong>source</strong>, it will create a link to the RSS feed source main page, it will automatically show the RSS item date next to it.
> *   <strong>lazyload</strong> - Use <strong>RSS Feeds News blocks</strong> with a lazy load plugin like <a href="https://tah.wordpress.org/plugins/lazy-load-xt/" target="_blank">Lazy Load XT (the best one to me)</a>   *(true or false, defaults is false)*  Or use any lazy load plugin who use the <strong>data-src</strong> tag to work with <strong>RFNB</strong> like <strong>data-src="img/image.jpg"</strong>
> *   <strong>adsense</strong> - Insert Google Adsense ads between RSS Feeds items (Only available in Newspaper style). *(ca-pub-XXXXXXXXXXXXXXXX, defaults is false)*  Your Google Adsense ads client data-ad-client. Ex: adsense="ca-pub-XXXXXXXXXXXXXXXX"

###PRO version NewsPapers styles ###
*   Use <strong>[newsblocks-merge][/newsblocks-merge]</strong> To wrap multiple newsblocks in the NewsPapers styles.  

### PRO version NewsPapers Example:
`[newsblocks-merge]
[newsblocks url="http://feeds.feedburner.com/breitbart,http://tempsreel.nouvelobs.com/rss.xml,http://www.veteranstoday.com/feed/,https://sputniknews.com/export/rss2/archive/index.xml,https://www.rt.com/rss/,http://www.lifezette.com/feed/,http://rss.radio-canada.ca/fils/nouvelles/nouvelles.xml,https://conservativedailypost.com/feed/" items="10" max_items="15" excerpt="22" new_window="true" thumbnail="true" cache="10800" source="false" columns="3" newspaper="1" merge="true" blocktitle="Alternative news" splash="latest"]
[newsblocks url="https://feeds.feedburner.com/zerohedge/feed,http://www.independent.co.uk,http://russia-insider.com/en/taxonomy/term/191/all/feed/,http://wearechange.org/feed/,http://theantimedia.org/feed/" items="10" max_items="15" excerpt="22" new_window="true" thumbnail="true" cache="10800" source="false" columns="3" newspaper="1" merge="true" blocktitle="politic"]
[newsblocks url="http://feeds.feedburner.com/wmexperts,https://www.cnet.com/rss/news/,http://feeds.gawker.com/lifehacker/full,https://www.smashingmagazine.com/feed/,http://www.androidpolice.com/feed/" items="10" max_items="15" excerpt="22" new_window="true" thumbnail="true" cache="10800" source="false" columns="3" newspaper="1" merge="true" blocktitle="technology"]
[/newsblocks-merge]`

*   You can choose between newspaper="1" and newspaper="2" styles
*   In merge mode, since all RSS are mixed/merged, it's preferable to add the blocktitle option to give this block/column a title like: blocktitle="technology"
*   Give the right numbers of columns according to the numbers of newsblocks inside the shortcode columns option.  If you have 3 newsblocks shortcode inside the [newsblocks-merge] shortcode, write: columns="3". It help News RSS Feeds Blocks to put the right numbers of columns for the CSS and a good looking NewsPapers style.
*   You do not need to use the [newsblocks-merge] shortcode around the [newsblock] shortcode if you are using the merge options with only one columns and just one block: columns="1" OR if you do not use the newspaper option.


Please post any issues under the support tab. If you use and like this plugin, please don't forget to <strong>rate</strong> it! Additionally, if you would like to see more features for the plugin, please let me know.

### RSS Feeds News block

*   Show feed description
*   Aggregate multiple feeds into one list
*   Show full feed if data content exist in the RSS Feed
*   With the text widget you can add RSS Feeds to widgets
*   More info and help here: : <a href="http://scriptsmashup.com" target="_blank">ScriptsMashup</a>

###Pro version: <a href="http://scriptsmashup.com/product/rss-feeds-news-blocks" target="_blank">Go to RSS Feeds News Block pro:</a>

*   Show Youtube channel, Youtube user video, find and play Vimeo video
*   Work with Podcast XML / RSS feeds and play audio mp3, mp4 files with the mp3 player
*   Show images
*   Hide RSS broken image
*   Display the RSS feed title and show description and image on hover like Popurls, Alltop, Netvibes ,Theweblist
*   PLUS buttons to show or hide the descriptions and images with a beautifull animation
    Find and play Youtube video, mp4, .ogg and many others HTML5 videos in enclosure or in the description
    Find and play Audio files, mps, .ogg and many others audio files in enclosure or in the description    
*   Use the HTML5 Video player PLYR
*   Find medias in enclosure and feed description and play Youtube Videos,  MP4 Videos, MP3 podcast with the MP3 player and Video Player.
*   Find and show RSS Favicons and Icons. Cache the Favicons url for faster loading
*   Insert Google adsense Ads between RSS Feeds items (Only available in Newspaper style)

`[newsblocks url="https://fr.sputniknews.com/export/rss2/archive/index.xml,http://feeds.feedburner.com/elise/simplyrecipes,http://feeds.feedburner.com/boingboing/ibag,feeds.bbci.co.uk/sport/0/football/rss.xml,http://feeds.feedburner.com/readwriteweb,http://feeds.reuters.com/reuters/topNews" items="5" max_items="10" excerpt="50" new_window="true" thumbnail="70" cache="10800" source="true" columns="3" tooltip="true"]`

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `Feed-News-Blocks` to the `/wp-content/plugins/` directory
2. Unzip the file
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Use the shortcode `[newsblocks]` anywhere in your content
5. Use a widget who accept shortcode to put RSS Feeds News Blocks in your sidebar or footer

== Screenshots ==

1. RSS Feed News block who show: 3 columns, 6 feeds, 5 items
2. <strong>Pro version</strong> with Favicons and Videos icons. Show more/Show less feeds, Show descriptions and images on hover. see more and the Minimize and Maximize +/- buttons to reveal the description. Find and play Youtube Videos,  MP4 Videos, MP3 podcast with the MP3 and Video Player.
3. <strong>Pro version</strong> with the shortcode option <strong>newspaper="1"</strong> and merged (Multiple RSS mixed order by date) style like The Huffington Post
4. <strong>Pro version</strong> with the shortcode option <strong>newspaper="3"</strong> style like The Drudge Repport
5. In sidebar with shortcode in Text Widget or other Widgets plugins who accept shortcode in widget
6. <strong>Pro version</strong> Newspaper style 4. ( Cards | Pinterest ) : <strong>newspaper="4"</strong>
7. <strong>Pro version</strong> Newspaper style 5. ( Cards | Pinterest ) : <strong>newspaper="5"</strong>

== Changelog ==

= 1.0 =
* Initial release

= 1.0.1 =
* Image in CSS background for faster loading

= 1.2.1 =
* Lazy load

= 1.4 =
* Google Adsense

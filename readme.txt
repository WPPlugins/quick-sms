=== Quick SMS ===
Contributors: mutube
Donate link: http://www.mutube.com/about/be-nice/
Tags: sms, comments, widget, plugin
Requires at least: 2.0.2
Tested up to: 2.1
Stable tag: 2.11

Quick SMS allows visitors to your blog pages to send you SMS messages direct to your mobile phone, wherever you are.

== Description ==

If you spend a lot of time moblogging it can be a little bit lonely out there - especially if you don’t get back to see your comments very often. Maybe you’re publishing a live event & want instant feedback as it happens. Maybe you just like to hear from complete strangers. Either way, this plugin may be the answer.

Quick SMS allows visitors to your blog pages to send you SMS messages direct to your mobile phone, wherever you are. All you need is a mobile phone registered with one of the supported networks.

Networks in the Austria, Australia, Canada, Germany, Iceland, India, Italy, Japan, Netherlands, Singapore, South Africa, Spain, Sweden, United States, UK. Check directory for all supported networks.

== Installation ==

= Installation =

1. Unzip the downloaded package and drop the Quick-SMS folder in your Wordpress plugins folder
1. Log into your WordPress admin panel
1. Go to Plugins and “Activate” the plugin

= Basic Setup =

1. Go to Options, and select the Quick SMS tab
1. Select your network, or use Teleflip for US/Canda
1. Enter your 10-digit mobile number

= Add to Sidebar using Widget =

1. Go to Presentation, Sidebar Widgets
1. Drag the Quick SMS widget to the right
1. Check your blog to see the Widget in place
1. Send yourself a message to make sure it’s working!

= Add to Sidebar using Code =

1. Edit your theme (sidebar.php recommended) to include <?php quicksms(); ?> where you want the SMS box to appear
1. Check your blog to see the Quick SMS form is in place
1. Send yourself a message to make sure it’s working!

== Frequently Asked Questions ==

= Do I need to do anything else to active my mobile? =

Most networks require you to activate “Email to SMS” capability in order for this to work. 
The activation information is displayed within the plugin when you select the network. Just follow the instructions. Alternatively, you should be able to find instructions on your providers website or by contacting them directly. 

If you find any changes to the activation processes let me know.

= Why isn't it working? =

Try experimenting with number formats, for example entering your number with or without country codes. Normally all numbers should be entered without a country code or leading 0, but there may be errors in the network list. If you find any that need fixing let me know and I'll update the list.

= How much does this cost? =

Depends on your network. Many offer the service for free although some charge the standard network SMS rate. Ask your network provider or send a test message.

= Why isn't my network listed? = 

The networks are created by public submissions. I like in the UK and use Vodafone and as such I am unable to confirm/check any other networks work.  Actually, Vodafone don't support the service so I can't even test my own phone. But it works, trust me.

If you want to add your own network simply call them and ask for the address of their "Email to SMS Gateway". If they give you one that isn't listed, let me know!

= How do I add my own network? =

The format for doing this is fairly simple. Read the mobile-networks.ini file and you should be able to work it out. Send any modifications to me and I'll include them in the next release.

= What do you plan for future releases? =

* Confirmation page before/after SMS send
* Limit max/min size of message (stop blanks, multiple texts)

== Screenshots ==

1. Send SMS from your blog
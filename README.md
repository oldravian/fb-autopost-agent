# Facebook AutoPost Agent
Automatically generate post descriptions and tags using Openai API (by getting the latest news from RSS feeds) then publish them on the Facebook page using Facebook Graph API.

# Some of the posts that are auto-generated & posted by this script on a Facebook page:

![screenshot-1](screenshot/screenshot-1)

![screenshot-2](screenshot/screenshot-2.png)

# Working of this script
- Get a random feed URL from one of the URLs provided in the .env file
- Get the top item title and link. Then use Openai API to convert that title into an engaging Facebook post description and also generate tags
- Use Facebook Graph API to post that description and link on the page.

# Script Installation
- After downloading or cloning the repo, run
```
composer install
```
- copy .env.example into .env
- put the credentials in .env. Here are some comments about how to get that env variable

```
#create a Facebook App with the type "Business" and add these permissions to the app "pages_manage_posts, pages_read_engagement"
#Also, you don't need the app for App review if you are the manager of the page
#Cope the app id and secret and paste it below
fcebook_app_id=""
facebook_app_secret=""

#generate from https://developers.facebook.com/tools/explorer
short_lived_user_access_token=""

#go to your Facebook page, then click the "About" tab then click "Page Transparency". Finally, copy the page id
facebook_page_id=""

#generate the following token from generate-token.php
long_lived_page_access_token=""

#comma separated rss feed urls

rss_feeds="https://machinethink.net/blog/index.xml, https://www.marketingaiinstitute.com/blog/rss.xml, https://learn.microsoft.com/en-us/archive/blogs/machinelearning/feed.xml"

#get your api key from your openai account
openai_api_key=""
```

- Finally run cron.php as a cron job to auto-run the script after the specific intervals (hourly, daily, etc) 

# Do let me know in case you need any help. My email is chaudryhabib2@gmail.com

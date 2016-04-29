# silverstripe-instagram-moderation
SilverStripe CMS module that pulls public images from Instagram based on a hashtag and allows moderation.
##Installation
composer require one20/silverstripe-instagram-moderation
##Setup
1. Once you have the package installed, run /dev/build
2. In the CMS settings, click on the "Instagram" tab and enter in your access token and a single hashtag to use in the search

##CMS Usage
1. Go to the "Instagram" admin (button on the left nav of CMS) and click on "Add Instagram Post"
2. Public Instagram posts using the hashtag you specified in the settings will be pulled and you can select "Approve" or "Reject" for each photo
3. Once you've gone through the photos, hit the create button, and the page should refresh with a new set of photos

##Template Usage
Adding `$InstagramPosts` to any SS file will display approved images in an unordered list

=== Plugin Name ===
Contributors: Province of British Columbia
Donate link: https://www2.gov.bc.ca/
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Welcome to the WordPress technical test for the IS24R Web Technical Analyst position, within the Government Digital Experience Division, Government Communications & Public Engagement, with the Province of British Columbia.  The test is looking to not only assess your technical skill set, but to provide you with the opportunity to showcase your creativity, resourcefulness and solution way finding.

You will be marked on the functionality of the overall solution, showcasing your understanding of the WordPress environment and development framework, coding style, commenting, and ability to work within the WordPress plugin boilerplate provided.  All of your code must be added to the existing boilerplate plugin framework in your completed submission.    

!PLEASE NOTE! 
It is acceptable to refer to resources online and get assistance there.  Yes!  We all use the web from time to time to make it work, and we are okay with this.  However, the work you submit must be your own.  A library here, and code inclusion there with a reference to where it came from, is totally acceptable.  But the work you submit must be your own.

We are running WordPress 4.9.4.  Your submission will need to run on this version.  If a later version of WordPress is released during the testing phase, based upon our asks below, your submission should be able to handle a minor upgrade.

!IMPORTANT!
Please compress your final solution, and rename the extension to ‘.piz’, and email it as an attachment to govwordpress@gov.bc.ca, Subject Heading: WordPress Technical Test Submission.

There are security measures in place that prevent us from accepting a ‘.zip’. We’ll rename it, extract it, and get to work on marking it!

Varying Vagrant Vagrants
For our local development environments, we use Varying Vagrant Vagrants, which you can find here https://varyingvagrantvagrants.org/docs/en-US/installation/software-requirements/
We find it easy to get up and running quickly, and very practical in our day to day.  You do not have to use this environment, we just recommend it. 


== Test Instructions ==
A business area is looking to leverage WordPress to inventory a repository of their technical documentation and resources.  We will call these ‘books’.  There are two main tasks we are looking to complete.  First leveraging WordPress’s custom post type, we need a new custom post type created for books to be added to WordPress by site administrators.  Don’t worry about the multiple user roles accessing the backend, only the functionality of those authenticated.

Story
As a site administrator, I need to be able to enter new books into the inventory so that my audiences can find them in the site.  

Acceptance Criteria
	- A site administrator can add a new book with title, description, and thumbnail - DONE
	- A site administrator can revert back to a previous version of the book - DONE
	- Each book has an author name, release year and publisher - Done

—Requirements: Administrative View (WordPress Backend) Instructions-

Create a custom post type - “Books"
	The Books custom post type will need to include post type support for the following features:
	- Title
	- Editor
	- Thumbnails
	- Revisions

	Books must support the following custom post meta fields.  Please place them below the main editor used for descriptions.
	- author name (text)
	- release year (date)
	- publisher (text)

The “Books” custom post type needs a taxonomy too.  
   Add a custom taxonomy for this Custom Post Type called “Book Genre”, (Code would be ‘book-genre’).  Pre-populate with the following three separate entries when you are testing it out -> 'Technical Documentation', 'Coding Standards' and 'Easy Reading.'

-Public Rendering View (WordPress Front End) Instructions-

We are looking to leverage two approaches WordPress has to render content.  First a custom page template, and second, the famous Shortcode.

Story
As a site user, I need to be able to view the books of the inventory so that I can use these resources to do my job better.

Acceptance Criteria
	- A site user can view all of the books on a single page, post or widget
	- A site user can view a single book page including all details about the book

Custom Template Page
The “books” custom post type will require a custom template for rendering the book items. The custom template page should render the book

A table with the following for each entry:
	- Book title, author, release year, genres
	- Book title clickable link to the custom template page about the book

ShortCode
Using Bootstrap’s responsive table layout options, please create ShortCode that will allow a user to render the following format within a page/post/widget.  The syntax to call the shortcode will be '[books]'.
		- the title
		- the featured image of the Book, if one has been set
		- a list with the author name, release year, publisher
		- a list with the Book Genre
		- the description content from within the main editor
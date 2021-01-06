# newspush_api
Create blog posts from incoming API calls on concrete5 CMS

This package puts the work of deek87 (https://documentation.concrete5.org/tutorials/creating-a-simple-api-for-posting-blogs) into a an easily applicable package for concrete5 version 8.x.
What you need to do is:
1. Install the Package
2. Activate and connect to the REST API (https://documentation.concrete5.org/developers/rest-api/connecting-to-the-concrete5-rest-api)
3. Generate your API call with the these parameters:
blogTitle = Page title, title of your entry, blogDesc = Short description, blogContent = Content of your Blog, supports html markup.
This setup will post the new entry as a child of the /blog directory, using the blog_entry pagetype and the right_sidebar template. Your "blog_entry" page needs to have a content block present to show the output.
This setup will work with the standard concrete5 sample content.
 
Props to deek87 and mesuva whose both work helped in putting this together.

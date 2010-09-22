create folders for each template you want to create

these folder should contain the following files:
init.php : included before initializing module (optional)
core.php : included before sending HTML (optional) - usually contain headers modifiers
page.php : included within the HTML body (mandatory) - should call the module content at some point


you can also create some special templates to change the default pages :
_error : error pages (see readme in this template)
_login : login page
_logout : logout page
_logister : register page
_logminder : user password reminder page

This special templates will not be in the list of available templates for pages
# module-meetTheTeacher
The Meet The Teacher module provides an API which allows for the information contained in Gibbon to be exposed in a JSON form, readable to the Meet The Teacher servers. In addition the module provides easy access for parents (via Gibbon's Parent Dashboard) to the online Meet The Teacher service.

### Prerequisites ###

* You must be able to facilitate a HTTPS connection.

### How do I get set up? ###

1. Clone the repo to your /modules/ folder in the Gibbon web directory. You should take note of the location of the jsonEndpoint.php file inside the module's directory.
2. Log in as an administrative user and go to Admin > System Admin > Manage Modules. Click Install. This will perform the database setup.
3. Go to Other > Meet The Teacher and set up what you need.

### Securing the API ###

You should make sure the API key is not guessable and that you only add IPs to the Allowed IPs list that you trust.

### What will connecting third parties need? ###

Any third party connecting to the API will need to know:

* Your jsonEndpoint.php address from step 1 of the setup section. Make sure the link is HTTPS otherwise a 401 HTTP error will be received.
* Your API key.

You'll also need to add their connecting IP to the list of Allowed IPs otherwise they will receive a 401 HTTP error.

### What data is output? ###

We output the following information in JSON format:

JSON Property Name 		| Description
------------------------|--------------------
Info					| Contains information on the setup of the Meet The Teacher. For example, version information and if an LS Support Role has been chosen.
Staff					| All staff members at the school who have the "Full" or "Expected" status.
Contacts				| All contacts for students at the school. Both students and their contacts must have a "Full" or "Expected" status.
Students				| All students who are marked reportable and have a status "Full" or "Expected".
CustomGroupLinks		| **Not Implemented**
ActivtyGroupLinks		| A list of links between students and teachers within activity groups.
ContactLinks			| A list of links between parents and students. The priority and relationship of the students is shown.
RollGroupLinks			| A list of roll group links for registration tutors.
ClassLinks				| A list of academic class links for timetabled classes.
IndividualNeedsGroups	| **Only available in Gibbon v15 and up.** A list of links between learning support teachers and children with additional needs. Read the Individual Needs section for more info.

### Individual Needs ###
There's a few options available for the individual needs section on the system simply because there's a few custom requests of that page. Individual needs can be

### Contribution guidelines ###

I'm using 4 space tabulation in VIM. I'd prefer this is uniform to help make the code look nicer :)

### Contributors ###
Jim Speir - School Cloud Systems
Sandra Kuipers - Gibbon Developer
Ross Parker - Gibbon Developer

### Support ###

In the first instance, you should get in touch with Jim Speir on jim@schoolcloudsystems.co.uk.

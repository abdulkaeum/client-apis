## Backend multiple API consumption

### Summary

Integrating with 3rd party API's to pull in user data. 
This project uses the https://reqres.in/ API.

Build a console command that pulls data from an API and stores it 
against the User model, this command should not have any user 
interaction at all but should have a way to call the next page in 
the pagination on the API, so if a there are 12 total pages this 
command should be developed in a way to call these other pages so we 
can potentially retrieve all the data from the API.

Focus: To be maintainable and flexible to add other API calls in 
the future. 

Main tasks:
- Retrieve a fresh set of the data from the API's - to be used anywhere else in the application
- Retrieve user data and store against the User Model

### Solution

My solution uses the database to store API end points to a Client
model, uses an associated class to call the API end points and 
performs any data normalisation. The users model also includes a client_id column to associate it with its source of origin

### Other solutions to consider
#### What testing can we perform using PHPUnit
- given we have a client model = Reqres
- run the artisan command = client:api
- check client class Reqres exists - must throw an exception if not found and gracefully exit
- fake the api with a custom closure based response
- use the response returned to assert a json structure is matched
- use an assertion to check the database if a record has/contains the above json structure
- use an assertion to check the artisan command's exit code = 0 for success

#### How would we respond if the API we are calling goes down
- We would need to record/log which client class API failed to make its call
- At this point maybe use a sms service or email/notify an administrator to switch the status of the API to in-active 

#### How would we use the command in a schedule to repeatedly update the users from the API
- The command uses the Laravel 7 upsert. This takes an array of arrays (in this case our response data) to insert, identifies if the record already exists and if so an array of what should be updated
- The solution stores a client_id column on the users table to link it to the API's users ID

### Further solutions I've considered
- Depending on the amount of records we have in the Client model we may want to use chunking
- Many API's would have stored their first name and last name in separate columns and so should we 
- Add a status column on the Client model so we can activate and de-activate any API 
- Add a schedule time column on the Client model as we may only want to call the API's at a specific time
- Create own exception classes to target more specific error handling 
- Move the logic for retrieving and storing the api data into a trait and away from model 
- Add a history model to log api calls, when was it called, how many records were retrieved, new and updated
- We could examine model attribute using the isDirty and getChanges to create a log of changes, although this could accumulate too additional database queries potentially slowing our process down

#### Features
- Uses the HTTP client introduced in Laravel 7
- Uses the new Eloquent upsert method introduced in Laravel 8

### Screenshots

<img alt="" src="public/screenshots/Reqres.PNG">

<img alt="" src="public/screenshots/command.PNG">

<img alt="" src="public/screenshots/the-last-record.PNG">

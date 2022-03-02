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
model and uses an associated class to call the api end point and 
perform any data normalisation.

### Further solutions to consider
#### What testing can we perform using PHPUnit
- given I have we a client model = Reqres
- run the artisan command = client:api
- check client class Reqres exists - must throw an exception if not found and gracfully exit
- fake the api with a custom closure based reponse
- check if that matches
- check if status is ok, and structure
- store data

#### How would we respond if the API we are calling goes down

#### How would we use the command in a schedule to repeatedly update the users from the API

### Screenshots

<img alt="" src="public/screenshots/command.PNG">

<img alt="" src="public/screenshots/the-last%20-record.PNG">


This is a basic exercise in integrating with different endpoints from Clarabridge https://www.clarabridge.com/

Specifically it is currently creating a new API Endpoint which:
 1. Authenticates with the Clarabridge API.
 2. Makes a request to grab all accounts currently accessible to the authenticated user.
 3. Grabs the Canned Responses from the accounts.
 4. Briefly processes the Canned Responses and sends their messages to the Sentiment API endpoint which analyzes whether the message is positive, negative or neutral.
 
 ``` "I would appreciate it if you'd be quiet." is positive
 "We value your opinion" is neutral
 "Taco Tuesday or Pizza Friday?" is neutral
 "Your opinion matters" is neutral
 "I HATE it when a demon incursion ruins my holiday." is negative
 "Potato" is neutral
```
 **This is a super rough version and a number of improvements need to be made**
 1. Done - Responses need their own class and objects
 2. Error handling everywhere, especially around authentication.
 3. There is no expiry or real handling for the tokens. This needs to be addressed.
 4. Done - Everything is driven by the constructor of the ApiEndpoint - this needs to change, functions exist for a reason.
 5. This currently has no real purpose. No front-end to display the data nicely, and it's not actually an API endpoint currently.
 6. Oauth2 and curl libraries have been included in composer but not utilized.
 7. In Progress - A major refactor needs to take place as a lot of function calls are redundant.
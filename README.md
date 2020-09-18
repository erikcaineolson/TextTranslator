# Text Translator

Upload a file and translate it into the language of your choice (from any available language via the Google Cloud Translation API). Online at https://urzul.com

### Requirements
1. PHP 7+
1. Laravel 7.x
1. Composer
1. npm 
1. Google Cloud Services API Key and JSON Credentials, with the Translation Service enabled

### Assumptions 
1. You are familiar with setting up and hosting Laravel applications, including Composer and npm
1. You are familiar with Google Cloud Services IAM 
1. Google Cloud Translation Service is live and accessible
1. Google Cloud Translation Service can determine the source language of the document
1. Google Cloud Translation Service can translate the entire document
1. Google Cloud Translation Service uses the same ISO 639-1 Codes everywhere

### Setup
1. Clone the repository or extract the zip locally 
1. Retrieve a Google Cloud Service API Key and JSON Credentials for the Google Cloud Services Translation API
1. Add the API Key to the `GOOGLE_CLOUD_API_KEY` parameter in `.env`
1. Add the location of the Credentials JSON to the `GOOGLE_APPLICATION_CREDENTIALS` parameter in `.env`
1. You're ready to go...we're not using a database

### Possible Edge Cases
1. A binary file submitted as a .txt with a forged MIME-type
1. Google Cloud Translation Service AI unable to determine the source language or translate the entire document
1. The user's system does not support the "translated-to" language
1. Text that is too short (so Google can't determine the source language)
1. Text that is too long (Google places character limits per 100 seconds)

### How it Works
1. The user lands on the page
    1. Vue populates the select box with the most recent list of available languages
        1. Vue makes an HTTP GET API call via Axios to the Laravel API `/api/translations`
        1. The Translation Controller calls the Translation API (`available languages` endpoint) using the Google Cloud Services PHP SDK
        1. Google Cloud Services returns JSON listing the localized name and ISO 639-1 code
        1. The `languages()` method on the Translation Controller returns that JSON
        1. Vue parses the JSON and loads the select box with the completed list of languages
1. The user selects a language to translate to
1. The user uploads a file through the web interface 
1. Vue sends the form data to the Laravel API
    1. Vue makes an HTTP POST API call via Axios to the API `/api/translations` (`translate()` method on the Translation Controller)
    1. `translate()` pulls the content out of the out of the text file and sends the text and desired language to Google Cloud Translation services (`translate` endoint)
    1. Google Cloud Services Translation API translates the text and returns the translated text as JSON
    1. `translate()` returns either a `200` and the text for display, or a `422` or `500` along with the related error message
1. If there was an error somewhere, Vue displays an error screen with the option to try again
1. If everything processes correctly, Vue displays the edited text, along with the associated information describing what happened to the file 
1. Any server error, hangup, etc. is represented as a 500 to the end-user

### Potential Improvements
1. Cache language list to decrease load time and make fewer (billed) API calls to Google Cloud Services
1. Authentication requirements to avoid DDOS and decrease the risk of Google Cloud Services API abuse
1. Better logging throughout
1. Don't handle any "otherwise indeterminate error" as a blanket 500
1. Notifications via SNS if one or more services goes down
1. Abstract the use of the client in the Translation Controller into its own Laravel Service

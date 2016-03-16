# Reporting

The application displays transaction statistics on a monthly and daily basis.

## The workflow can be broken down as the following:
  - The user has to be logged in to view the reports.
  - Once logged in, users can view the following data:
    - Total revenue.
    - Total commission.
    - Average order value.
  - A second report displays the top 5 affiliates ranked by sale amount.

The above requirements and their sub divisions (i.e what's supposed to happen was supposed to be written in BeHat, but due to time constraints, this was not possible).

## Application design breakdown

The application is build using the Larave framework (5.2). MySQL was used as the backend database. As Laravel provides it's own server (started using the 'php artisan serve' command in the terminal, rather than the 'sudo apachectl start' command for apache). It is quite similar to a MAMP stack, but not identical. The application tries to follow various software object oriented and design principles such as SOLID, Inheritance, Encapsulation and Polymorphism. All the code has been documented and a documentaion folder has been created. The application follows the Zend coding standard. 

There are three parts to the application.
  - Authentication.
  - Data aggregation.
  - Parsing data.
  - Displaying the aggregated data to the user.

### Authentication

Laravel has a built in authentication service which is ready to use and only requries very little modification. The initial changes have to be made during the database migration. By default Laravels 'User' table set-up includes an email field. As we will be using usernames, this has to change. All passwords are hashed using bcrypt. As we only want logged in users to view the reports page, sessions have to be put in place. Thanks to Laravel's middleware mechanism we can simply mark a specific route as only visible to logged in users. This causes all users who attempt to visit the route to be redirected to a specific page. This page is set within the Authenticate.php Middleware file. Laravel also has built in CSRF, SQLi and XSS protection. XSS and SQLi has not been thoroughly tested with tools such as SQLMap. From the research avaialable online, it is not an area of worry.

### The Aggregation Process 

Once the transaction table is populated with transactions, a cron job acquires new data every minute and populates a transaction_aggregation table. Before the data is inserted into the aggregation table, it is processed. Firstly, the transactions are combined sale amount, commission and number of transactions. The next is step is to acquire the averages, these averages are created using the aggregation table itself as we can use the sum of sale amount of commission/ the number of transactions. This has now given us all the averages and sale amounts. The transaction id of the last processed transaction is also stored to be able to pick up where we left off from (as the transaction id is indexed, it is a lot quicker rather than doing this by date or time). The last three columns contain the month, day and id. This is to be able to look back in history for a particular day, when the user requests this via the interface.

### Parsing Data

Data parsing happens between the time a user makes a request for the report and the report data being returned. Once the request is made, some data is returned, it is acquired from the aggregation table (as it is a smaller table and very little computation is needed). Before displaying it on the page, it is provided to the ReportAggregator, which parses the data so as to make it as easy as possible to acquire the data once on the view. 

### Displaying the aggregated data to the user.

Once the data is acquired and parsed, it is displayed to the user. Laravel uses the Blade templating engine which makes it very easy to loop through arrays and various objects. The data is already parsed, so accessing the relevant data point is made as easy as possible.

### Testing

Although there was not much time to write any tests, a few PHPUnit acceptance tests have been written to confirm that the authenticaion process is running as expected. If more time were available, both unit and integration tests would have been applied.

### Notes

As the data is not in real time, it is difficult to display the application's complete capabilities (without it running for days on end). However, to imitate this process a transactionGenerator has been created. This script has to be run manually, it grabs data from a transaction_spool table and feeds it into the transactions table (This imitates real transactions being passed to the database), combining this with the cron job that pulls new transactions every minute, we are able to test the application's capabilities in a near Production environment.

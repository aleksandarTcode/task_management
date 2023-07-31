# Task Management System API
This is an example of a Task Management System API created using Laravel 10
## Prerequisites
Before you start, make sure you have the following prerequisites installed on your system:
1. PHP (Recommended version: 7.4 or higher)
2. Composer
3. Laravel 10 (You can install it using Composer)
## Step 1: Clone the Repository
First, clone the Task Management API repository from your preferred version control platform (e.g., GitHub) to your local machine.
```
git clone https://github.com/aleksandarTcode/task_management
```
## Step 2: Install Dependencies
Once you have cloned the repository, navigate to the project directory and install the required dependencies using Composer.
```
composer install
``` 
## Step 3: Create .env File
Make a copy of the .env.example file and rename it to .env and update the necessary configuration options.
## Step 4: Configure the Database
In the .env file, configure the database connection settings according to your local database setup. For example, you might need to set the following:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
## Step 5: Run Database Migrations and Seeders
Now, run the database migrations to create the necessary tables in the database.
```
php artisan migrate
``` 
Additionally, you can seed the database with some sample data using the seeder:
```
php artisan db:seed
``` 
or 
```
php artisan migrate:fresh --seed
```
## Step 6: Set Up Task Due Date Reminder Notifications (Optional)
If you want to enable the task due date reminder notifications, make sure your mail configuration is set up correctly in the .env file.
```
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=your_smtp_port
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=your_smtp_encryption
MAIL_FROM_ADDRESS=your_email_address
MAIL_FROM_NAME=your_email_name
```
## Step 7: Start the Development Server
You can now start the development server and run the API:
```
php artisan serve
```
## Step 8: Test the API Endpoints
You can use tools like Postman or curl to test the API endpoints. Here are some example endpoints you can test:
* Register a new user: POST /api/register
* Login as a user: POST /api/login
* Logout a user: POST /api/logout
* Create a new task: POST /api/tasks
* Update a task: PUT /api/tasks/{task_id}
* Delete a task: DELETE /api/tasks/{task_id}
* Get all tasks: GET /api/tasks
* Get a specific task: GET /api/tasks/{task_id}
* Search tasks by title: GET /api/tasks/search/{title}
* Get all users: GET /api/users
* Get a specific user: GET /api/tasks/{user_id}

### Authentication
The API uses token-based authentication with Laravel Sanctum. To access protected routes, you need to include a Bearer token in the request headers.
#### Register a New User
- URL: POST /register
- Description: Register a new user.
- Parameters:
  - name (required): Name of the user.
  - email (required): Email address of the user.
  - role (required): Role of the user (Admin, Developer, Project Manager).
  - password (required): User password.
  - password_confirmation (required): Confirm the user password.
- Response: Returns the registered user and a Bearer token.
#### Login as a User
- URL: POST /login
- Description: Log in as an existing user.
- Parameters:
  - email (required): Email address of the user.
  - password (required): User password.
- Response: Returns the user and a Bearer token.
#### Logout a User
- URL: POST /logout
- Description: Log out the currently authenticated user.
- Headers:
  - Authorization: Bearer <your_bearer_token> (required): Include the Bearer token obtained during login.
- Response: Returns a message indicating successful logout.
### Tasks
#### Create a New Task
- URL: POST /tasks
- Description: Create a new task.
- Headers:
  - Authorization: Bearer <your_bearer_token> (required): Include the Bearer token for authentication.
- Parameters:
  - title (required): Title of the task.
  - description (optional): Description of the task.
  - status (required): Status of the task (New, In Progress, Completed).
  - priority (required): Priority of the task (Low, Medium, High).
  - due_date (required): Due date and time of the task (format: Y-m-d H:i:s).
  - assigned_user_id (required): ID of the user to whom the task is assigned.
- Response: Returns the created task.
#### Update a Task
- URL: PUT /tasks/{task_id}
- Description: Update an existing task.
- Headers:
  - Authorization: Bearer <your_bearer_token> (required): Include the Bearer token for authentication.
- Parameters:
  - title (required): Updated title of the task.
  - status (required): Updated status of the task (New, In Progress, Completed).
  - priority (required): Updated priority of the task (Low, Medium, High).
  - due_date (required): Updated due date and time of the task (format: Y-m-d H:i:s).
  - assigned_user_id (optional): Updated ID of the user to whom the task is assigned (Only the creator can update this field).
- Response: Returns a message indicating successful update.
#### Delete a Task
- URL: DELETE /tasks/{task_id}
- Description: Delete an existing task.
- Headers:
  - Authorization: Bearer <your_bearer_token> (required): Include the Bearer token for authentication.
- Response: Returns a message indicating successful deletion.
#### Get All Tasks
- URL: GET /tasks
- Description: Get a list of all tasks.
- Response: Returns an array of tasks with pagination.
#### Get a Specific Task
- URL: GET /tasks/{task_id}
- Description: Get details of a specific task.
- Response: Returns the task information.
#### Search Tasks by Title
- URL: GET /tasks/search/{title}
- Description: Search tasks by their title.
- Parameters:
  - {title} (required): Title to search for.
- Response: Returns an array of tasks matching the search title.
### Users
#### Get All Users
- URL: GET /users
- Description: Get a list of all users.
- Response: Returns an array of users with pagination.
#### Get a Specific User
- URL: GET /users/{user_id}
- Description: Get details of a specific user.
- Response: Returns the user information.

## Step 9: Run Tests (Optional)
To run the test suite, execute the following command:
php artisan test
This will run the PHPUnit tests and check the functionality of various API endpoints.

## Task Due Date Reminder (Notifications)
Task due date reminder notifications are sent daily to the assigned users for tasks that are due within the next 24 hours or are overdue.
## Error Handling
The API handles various error scenarios and returns appropriate error responses with status codes and error messages.

Congratulations! You have successfully set up and run the Task Management API. You can now use the API to manage tasks and perform various operations like registering users, creating, updating, and deleting tasks, and more.

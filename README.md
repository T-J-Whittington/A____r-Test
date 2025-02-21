# Symfony API in Docker

This project is a Symfony-based API running inside a Docker environment using Apache and MySQL.

## 🚀 Features
- Symfony backend API
- Dockerized environment (Apache, PHP, MySQL)
- API endpoints for handling data

---

## 🛠️ Prerequisites

Before you begin, ensure you have the following installed:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)

---

## 📦 Installation & Setup

### 1️⃣ Clone the Repository
git clone https://github.com/T-J-Whittington/Arbor-Test.git
cd Arbor-Test

### 2️⃣ Build & Start Docker Containers
Run the following command to build and start the containers:

docker-compose up --build -d

### 3️⃣ Install Symfony Dependencies
Enter the PHP container and install dependencies:

docker exec -it php-symfony bash
composer install

### 4️⃣ Database Setup
Run the following commands to set up the database schema:

docker exec -it php-symfony bash
php bin/console doctrine:migrations:migrate

---

## 🔧 Usage
### Access the API

Once the containers are running, access the API at:

    API Base URL: http://localhost:8000
    Secure HTTPS (if configured): https://localhost:8443

#### There are four endpoints to test:

- /list [GET] - Returns the top ten scores in an array format.
- /new [GET] - Returns a new random string of characters.
- /compare [POST] - Assesses the given attempt, returning a current score and whatever letters are left. Returns no letters if there are no more anagrams.
- - attemptWord - string, required.
- - remainingLetters - string, required.
- - currentScore - number, optional.
- /end [POST] - Ends the current word, returning the final score and any potential anagrams.
- - remainingLetters - string, required.
- - currentScore - number, required.


---------

# My Solution
I chose to use a Symfony framework as both myself and Arbor use it, and I know that it can accomplish the given task.
I chose Docker as the containerised nature of it allows for easier reproduction and testing.

I went with an API style as the test document specified a backend. I originally planned to do a very basic React frontend but opted to create a purely backend system which I developed using Postman.
As far as confirming the anagrams goes, I tried different attempts before settling on using a text file of known words. I originally planned to use a dictionary API such as Datamuse, but ultimately opted against them as they didn't quite fit the use-case for this project. While many of them have anagram solving functionality, they accomplish it in different ways, with varying levels of leeway, and to cover the task I'd have needed to use multiple. I don't necessarily agree with the contents of the text file (such as single letters counting as words), but it functions as a proof of concept.

With the current approach, different data sources can easily be plumbed into the anagram logic, not merely the text file provided.

I did not save the currently active word to the database as I felt that, when combined with a front-end, it would be unneccesary weight to the system. With the constraints of the test, it's reasonable to infer that a front-end is the ideal accompanyment, though it was not part of the requirements. As such, some of the data handling is assumed to be left to the front end, such as keeping track of the current active word and the current user's score. Were I to make a front end for this, I would handle updating the user based on the data I'd sent to the front-end, such as a /compare returning no letters if there are no more anagrams. This would be followed up with the end screen as if the user had clicked the end button.

However, as the top scores needed to be persisted, they are saved to a local MySQL database. With the theoretical front-end, I'd probe the /list endpoint on launch to display a table of top scoring words to the user.

If I had more time, I would have added unit tests, and would improve the setup to make it easier by better leveraging Docker. Validation needs adding to any incoming data, though this would also be mitigated with a front-end.
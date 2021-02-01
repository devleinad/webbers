<h2>About Webbers</h2>
Webbers is a personal project. It is a platform that allows users to register, login and ask/post questions, answer questions. It is similar to <a href="https://stack overflow.com">Stack Overflow</a>, except this is local hosted.

Its features include:
<ul>
 <li>Registering users either via the sites form or via ggogle, github</li>
 <li>Email verification</li>
 <li>User has the option of selecting the type of content he/she would like to see based on category of posts</li>
 <li>User can ask questions</li>
 <li>User can edit question and update questions</li>
 <li>User can delete questions that only belongs to him/her</li>
 <li>Just like stack overflow, if a user wants quick answer to his question, he can set a bounty on that question. The bounty will only be valid if the user has enough points to give out
 </li>
 <li>When a user's answer is picked as the best by the person who asked that question, if there was a bounty on the question, the user will be rewarded with that bounty</li>
 <li>User can filter posts based on categories</li>
 
</ul>

<h2>User Registration and Login</h2>
To join the platform, one would have to firts register. Registration can be done via a registration form or via other platforms such as google,and github.

<h2>CONFIGURATION</h2>
First and foremost, open the .env file and make changes to the following environment variables to suit yours:
<ul>
 <li>DB_CONNECTION=your_database_source(eg.mysql)</li>
 <li>DB_HOST=127.0.0.1</li>
 <li>DB_PORT=your_database_server_port</li>
 <li>DB_DATABASE=your_database_name</li>
 <li>DB_USERNAME=your_database_server_username</li>
 <li>DB_PASSWORD=your_database_server_password</li>
</ul>

Because of email verification, you'd have to edit the following variables.
<ul>
 <li>MAIL_MAILER=your_mail_mailer</li>
 <li>MAIL_HOST=your_mail_host</li>
 <li>MAIL_PORT=your_mail_port</li>
 <li>MAIL_USERNAME=your_mail_username</li>
 <li>MAIL_PASSWORD=your_mail_password</li>
 <li>MAIL_ENCRYPTION=your_mail_encryption</li>
</ul>

Also, because we'll be utilizing <a href="https://github.com/laravel/socialite">Socialite</a> package, so its important you register your app on any platform you'd want users to be able to log into your site from. I decided to use google and github, so i registered my app, and i was given the required credentials. Visit <a href="https://github.com/laravel/socialite">https://github.com/laravel/socialite</a> to learn how to use socialite. Follow the instructions given and you will be just fine.

After getting the neccessary credentials, and storing them inside your <b>app/config/services</b>, the next thing is to migrate your database.

After migrating, you can visit <b>127.0.0.1:8000/register</b> for registration and login.









 

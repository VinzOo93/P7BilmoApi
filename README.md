
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/c3f9542c3d8942d5adeb554cf29eeee6)](https://www.codacy.com/gh/VinzOo93/P7BilmoApi/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=VinzOo93/P7BilmoApi&amp;utm_campaign=Badge_Grade)
# ⚙️ Installation
____________________
#### _Requirement 
(you can use this command "composer check" for verify):

php 7.4+
composer > 2.0.0

#### _For install dependencies :

<code> $ composer install </code>

#### _You need to create your .env.local with .env and modify with your parameters.

#### _To create a database and install migrations :

<code> $ php bin/console doctrine:database:create </code>
<code> $ php bin/console doctrine:migrations:migrate </code>.

#### _To generate SSL keys for JWT Token :

<code> $ mkdir config/jwt </code>
<code> $ php bin/console lexik:jwt:generate-keypair </code>

#### After that, you can use this command to run this Api :

$ symfony serve

##### You can access to Swagger Api from this link : http://localhost:8000/api.
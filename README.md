# Orabota.ru – employee evaluation platform
Orabota was an early 2013 startup attempt by Maxim Neaga, targeted primarily for the Eastern European countries with the idea to help businesses relying on non-professional employees hire only the best candidates and avoid the troublesome ones. It allowed employers, partners and co-workers leave reviews about their peers. The project is no longer active, and thus its sources were made public.

## Setup and configuration
### Requirements
* PHP 5.1.6+
* CodeIgniter 2.2.x
* MySQL 4.1+

### Setup
1.	Install and configure requirements
2.	Clone this repository into the CodeIngiter’s root directory
3.	Create a database and import the schema from the _DBSchema.sql_ file
4.	Rename and configure the files in _application/config/\<filename\>.template.php_ (when renaming, remove the ".template" from the file name).

## Project overview
Orabota was a multilingual site and supported Google oAuth, as well as local accounts for authentication.
![orabota.ru home page](https://user-images.githubusercontent.com/3027370/37864943-69aa6778-2f43-11e8-9688-082631eab065.png)


All reviews left on the website were public, and searcheable by person's name.
![Sample review](https://user-images.githubusercontent.com/3027370/37870616-23bc866c-2fa0-11e8-8443-b840c81303fe.png)


A visitor could contact the review author via a contact form.
![Contact widget](https://user-images.githubusercontent.com/3027370/37864990-1ee6b39e-2f44-11e8-9fdf-ee8bd106880a.png)


Whether or not a user could receive direct messages, as controlled via a setting.
![Settings pane](https://user-images.githubusercontent.com/3027370/37865055-05e2f172-2f45-11e8-9f13-da1b907828ef.png)


Once authenticated, a user could add a review or request their peers to review them.
![Logged in user actions](https://user-images.githubusercontent.com/3027370/37865013-851241d8-2f44-11e8-87c4-f945ed324c32.png)


Employee review form:
![Employee review form](https://user-images.githubusercontent.com/3027370/37865033-c1f5cfca-2f44-11e8-8fca-0b87b6ca11cf.png)


Recommendation request form:
![Recommendation request form](https://user-images.githubusercontent.com/3027370/37865042-da0141f8-2f44-11e8-8b4d-46b572ca6633.png)


The site also had a protected administrative portal, where an admin could review latest user actions, view and manage all registered employers and people with reviews.
![Admin interface](https://user-images.githubusercontent.com/3027370/37865166-9f5bffe6-2f46-11e8-9964-c0aaa0f2ffd4.png)

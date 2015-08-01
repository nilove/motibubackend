<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Motibu Email Testing</title>
</head>
<body>
WELCOME!

You've been invited as an agent in Motibu.com

Click the link below to confirm your email address and fill in your user details:

<p>{{ URL::to( $client_base_url.'invitation/claim/' . $confirmation ) }}.<br/></p>

Sent on behalf of Ben Fatola
-------
You received this email as a member of project Connections Revolution Beta on cloudcustomsolutions.mybalsamiq.com.
Visit your project settings page if you want to leave this project.
</body>
</html>
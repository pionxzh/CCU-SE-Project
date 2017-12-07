<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Thanks for creating an account with the CCU iTalents.
            Please follow the link below to verify your email address
            {{ URL::to('verify/' . $emailtok)  }}.<br/>

        </div>

    </body>
</html>

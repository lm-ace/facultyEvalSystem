<!DOCTYPE html>
<html>
<head>
    <title>Faculty Registration Successful</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>Hello, Prof. {{ $faculty->last_name }}!</h2>
    
    <p>You have been added as a faculty member in <strong>EDURATE PUP</strong>.</p>
    
    <p>Here are your login credentials:</p>
    
    <div style="background-color: #f4f4f4; padding: 15px; border-radius: 5px; border-left: 5px solid #800000; width: fit-content;">
        <p style="margin: 5px 0;"><strong>Faculty ID:</strong> {{ $faculty->faculty_code }}</p>
        <p style="margin: 5px 0;"><strong>Password:</strong> {{ $password }}</p>
    </div>

    <p>Please log in immediately to set up your classes.</p>
    
    <p>Thank you!</p>
    
</body>
</html>